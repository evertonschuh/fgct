<?php
defined('_JEXEC') or die('Restricted access');


jimport( 'joomla.application.component.model' );

class EASistemasModelRequest extends JModel {

	var $_id = null;
	var $_data = null;
	var $_db = null;
	var $_app = null;	
	var $_isRoot = null;
	var $_user = null;
	var $_userAdmin = null;
	var $_pagination = null;
	
	function __construct()
	{
		parent::__construct();
		
		$this->_app 	= JFactory::getApplication(); 
		$this->_db		= JFactory::getDBO();
		$this->_user	= JFactory::getUser();
		
		$this->_isRoot		= $this->_user->get('isRoot');	
		$this->_userAdmin	= $this->_user->get('id');
		
		//echo JPATH_ADMINISTRATOR;
		if( JRequest::getVar( 'task' )  != 'add')
		{
			$array  	= JRequest::getVar( 'cid', array(0), '', 'array');
			JRequest::setVar( 'cid', $array[0] );
			$this->setId( (int) $array[0] );
		
			if (!$this->isCheckedOut() )
			{
				$this->checkout();		
			}
			else
			{
				$tipo = 'alert-warning';
				$msg = JText::_( 'JGLOBAL_CONTROLLER_CHECKIN_ITEM' );
				$link = 'index.php?view=armas';
				$this->_app->redirect($link, $msg, $tipo);
			}
		}
		
	}

	function setId( $id ) 
	{
		$this->_id		= $id;
		$this->_data	= null;
	}

	function getItem()
	{
		if (empty($this->_data))
		{			
			$query = $this->_db->getQuery(true);			
			$query->select('*');
			$query->from('#__intranet_service');	
			//$query->leftJoin($this->_db->quoteName('#__users').'  ON ('.$this->_db->quoteName('id').'='.$this->_db->quoteName('id_user').')');
			$query->where( $this->_db->quoteName('id_service') . '=' . $this->_db->escape( $this->_id ) );
			$this->_db->setQuery($query);
			if(!(boolean) $this->_data = $this->_db->loadObject()) {
				$this->addTablePath(JPATH_SITE.'/tables');
	    		$this->_data = $this->getTable('request');
				//$this->_data->name='';
				//$this->_data->email='';
			}
		}
		return $this->_data;
	}


	function getServices() 
	{
		$query = $this->_db->getQuery(true);
		$query->select('id_service_type as value, CONCAT(codigo_service_type, \' - \', name_service_type) as text, message_service_type as script');
		$query->from('#__intranet_service_type');
		$query->where('status_service_type = 1');
		$query->order($this->_db->quoteName('name_service_type'));
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();	
	}


	function getServiceMaps() 
	{
		$query = $this->_db->getQuery(true);
		$query->select($this->_db->quoteName(array( 'name_service_stage',
													'color_service_stage',
													'title_service',
													'message_service',
													'update_service',
													'name',
													'image_pf'

													)));
		$query->from($this->_db->quoteName('#__intranet_service_map'));
		$query->innerJoin($this->_db->quoteName('#__intranet_service_stage').' USING ('.$this->_db->quoteName('id_service_stage').')');
		$query->innerJoin($this->_db->quoteName('#__intranet_pf').' USING ('.$this->_db->quoteName('id_user').')');
		$query->leftJoin($this->_db->quoteName('#__users').'  ON ('.$this->_db->quoteName('id').'='.$this->_db->quoteName('id_user').')');

		$query->order($this->_db->quoteName('update_service') . ' DESC');
		$this->_db->setQuery($query);

		return $this->_db->loadObjectList();	
	}
	


	
	function store() 
	{
		$config   = JFactory::getConfig();
		$siteOffset = $config->getValue('offset');

		$data = JRequest::get( 'post' );

		$this->addTablePath(JPATH_SITE.'/tables');
		$row = $this->getTable('request');
		
		$data['id_user'] = $this->_user->get('id');
		$data['status_service'] = '1';

		$options = array();
		$options['id_user'] = $data['id_user'];
		$options['update_service'] = JFactory::getDate('now', $siteOffset)->toISO8601(true);
		$data['lastupdate_service'] = JFactory::getDate('now', $siteOffset)->toISO8601(true);
		if($this->_id):
			$row->load($this->_id);	
			$options['message_service'] = $data['message_service'];
			$options['id_service_stage'] = '2';
		else:
			$data['create_service'] = $data['lastupdate_service'];
			$options['id_service_stage'] = '1';
			$options['title_service'] = '';
			$options['message_service'] = '';

		endif;


		if ( !$row->bind($data)) 
		{
			$this->setError($this->_db->getErrorMsg());
			return false;	
		}

		if ( !$row->check($data)) 
		{
			$this->setError($this->_db->getErrorMsg());
			return false;	
		}	
			
		if ( !$row->store(true) ) 
		{	
			$this->setError( $this->_db->getErrorMsg());
			return false;	
		}



		jimport('joomla.log.log');
		JLog::addLogger(array( 'text_file' => 'log.request.php'));
		
		if($this->_id):
			$row->checkin($this->_id);
			JLog::add($this->_user->get('id') . JText::_('		Edit Request -  id('.$this->_id.')'), JLog::INFO, 'request');
		else:
			$this->setId( $row->get('id_service') ); 	
			JLog::add($this->_user->get('id') . JText::_('		New Request -  id('.$this->_id.')'), JLog::INFO, 'request');
		endif;

		JRequest::setVar( 'cid', $this->_id );
			
		$query = $this->_db->getQuery(true);
		$query->insert( $this->_db->quoteName('#__intranet_service_map') );
		$query->columns($this->_db->quoteName(array('id_service', 'id_user', 'id_service_stage', 'title_service','message_service', 'update_service')));	
	

		$values = array($this->_db->quote($this->_id),
						$this->_db->quote($options['id_user']), 
						$this->_db->quote($options['id_service_stage']), 
						$this->_db->quote($options['title_service']),
						$this->_db->quote($options['message_service']),
						$this->_db->quote($options['update_service'])
					);
						
		$query->values(implode(',', $values));
		
		
		$this->_db->setQuery($query);
		$this->_db->query();
					
		return true;
	}

	
	function isCheckedOut()
	{		
		$this->addTablePath(JPATH_SITE.'/tables');
	    $row = $this->getTable('arma');
		$cid = JRequest::getVar( 'cid', array(0), '', 'array' );
		$cid = $cid[0];
		if ( $row->load( $cid ) ) 
		{	
			if (!$row->isCheckedOut( $this->_userAdmin ) )
			{
				return false;
			}
			return true;
		}
		return false;
	}
	
	function checkout()
	{		
		$this->addTablePath(JPATH_SITE.'/tables');
	    $row = $this->getTable('arma');
		$cid = JRequest::getVar( 'cid', array(0), '', 'array' );
		$cid = $cid[0];
		if ( $row->load( $cid ) ) 
		{	
			if(! $row->checkout( $this->_userAdmin ) ) 
			{
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			return true;
		}
		return false;
	}
	   
	function checkin()
	{	
		$this->addTablePath(JPATH_SITE.'/tables');
	    $row = $this->getTable('arma');
		
		$cid = JRequest::getVar( 'cid', array(0), '', 'array' );
		$cid = $cid[0];
		if ( $row->load( $cid ) ) 
		{	
			if(! $row->checkin( $cid ) ) 
			{
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			return true;
		}
		return false;
	}
	
}