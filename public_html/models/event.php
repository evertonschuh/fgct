<?php
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class EASistemasModelEvent extends JModel {

	var $_id = null;
	var $_data = null;
	var $_db = null;
	var $_user = null;
	var $_app = null;
	var $_isRoot = null;
	var $_userAdmin = null;
	var $_siteOffset = null;
	
	
	function __construct()
	{
		parent::__construct();
		$this->_db	= JFactory::getDBO();
		$this->_app 	= JFactory::getApplication(); 
		$this->_user		= JFactory::getUser();
		$this->_userAdmin = $this->_user->get('id');
		$this->_isRoot		= $this->_user->get('isRoot');	
		$this->_siteOffset	= $this->_app->getCfg('offset');
	

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
				$link = 'index.php?view=events';
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
			$query->from('#__event');		
			$query->where( $this->_db->quoteName('id_event') . '=' . $this->_db->escape( $this->_id ) );
			if( ! $this->_user->authorize('core.admin', 'com_easistemas') )
				$query->where( $this->_db->quoteName('id_client') . '=' . $this->_db->escape( $this->_user->get('id') ) );
			$this->_db->setQuery($query);

			if(!(boolean) $this->_data = $this->_db->loadObject()) {
				$this->addTablePath(JPATH_ADMINISTRATOR.'/tables');
				$this->_data = $this->getTable('event');	
			}
			else{
				$registry = new JRegistry;
				$registry->loadString($this->_data->methods_event);
				$this->_data->methods_event = $registry->toArray();

				$registry = new JRegistry;
				$registry->loadString($this->_data->results_event);
				$this->_data->results_event = $registry->toArray();

				$registry = new JRegistry;
				$registry->loadString($this->_data->genres_event);
				$this->_data->genres_event = $registry->toArray();

				$registry = new JRegistry;
				$registry->loadString($this->_data->categorys_event);
				$this->_data->categorys_event = $registry->toArray();

				$registry = new JRegistry;
				$registry->loadString($this->_data->classs_event);
				$this->_data->classs_event = $registry->toArray();

			}	
		}
		return $this->_data;
	}

	function getClients()
	{
		
		$id_users = JAccess::getUsersByGroup(6);

		if (count( $id_users )) 
		{
			$id_users = implode( ',', $id_users );
			$query = $this->_db->getQuery(true);
			$query->select( $this->_db->quoteName('id') . ' AS value, ' . 
							' CONCAT(' . $this->_db->quoteName('name') . ', \' (\', ' . $this->_db->quoteName('email') . ', \')\') AS text');
							
			$query->from( $this->_db->quoteName('#__users') );
			$query->where($this->_db->quoteName('id') . ' IN (' . $id_users . ')'	);
			$query->order('text');
			$this->_db->setQuery($query);
			$result = $this->_db->loadObjectList();
			
		}
		else
		{
			
			$result[] = JHTML::_('select.option', '1', JText::_( 'Nenhum Cliente Cadastrado' ), 'value', 'text', ' disabled="disabled"' );
		}
		return $result;

	}

	function getUtil(){

		$_util = new stdClass();

		$_util->_app = $this->_app; 
		$_util->_user = $this->_user;
		$_util->_siteOffset = $this->_siteOffset;
		return $_util;
	}

	function store() 
	{
		$this->addTablePath(JPATH_ADMINISTRATOR.'/tables');
	    $row = $this->getTable('event');
		$post = JRequest::get( 'post' );
		
		if ($this->_id)
			$row->load($this->_id);

		if(empty($post['slug_event']))
			$post['slug_event'] = JFilterOutput::stringURLSafe($post['name_event']);	
		
		if ($this->_id) {
			$row->load($this->_id);
			if($row->slug_event != $post['slug_event'])
				$post['slug_event'] = $this->generateNewSlug($this->_id, $post['slug_event']);
		}
		else{
			$post['slug_event'] = $this->generateNewSlug(null, $post['slug_event']);
		}

		if( ! $this->_user->authorize('core.admin', 'com_easistemas') )
			$post['id_client']  = $this->_user->get('id');

		if(!empty($post['begin_event']))
		{
			$dataaTmp = explode(" ",$post['begin_event']);
			$post['begin_event'] = implode("-",array_reverse(explode("/", $dataaTmp[0])));
			$post['begin_event'] = JFactory::getDate($post['begin_event'], $this->_siteOffset)->toISO8601(true);
		}
		else
			$post['begin_event'] = JFactory::getDate('now', $this->_siteOffset)->toISO8601(true);

		if(!empty($post['end_event']))
		{
			$dataaTmp = explode(" ",$post['end_event']);
			$post['end_event'] = implode("-",array_reverse(explode("/", $dataaTmp[0])));
			$post['end_event'] = JFactory::getDate($post['end_event'], $this->_siteOffset)->toISO8601(true);
		}
		else
			$post['end_event'] = JFactory::getDate('now', $this->_siteOffset)->toISO8601(true);

		if(!empty($post['begin_reserve_event']))
		{
			$dataaTmp = explode(" ",$post['begin_reserve_event']);
			$post['begin_reserve_event'] = implode("-",array_reverse(explode("/", $dataaTmp[0]))) .' '. $dataaTmp[1];
			$post['begin_reserve_event'] = JFactory::getDate($post['begin_reserve_event'], $this->_siteOffset)->toISO8601(true);
		}
		else
			$post['begin_reserve_event'] = JFactory::getDate('now', $this->_siteOffset)->toISO8601(true);

		if(!empty($post['end_reserve_event']))
		{
			$dataaTmp = explode(" ",$post['end_reserve_event']);
			$post['end_reserve_event'] = implode("-",array_reverse(explode("/", $dataaTmp[0]))) .' '. $dataaTmp[1];
			$post['end_reserve_event'] = JFactory::getDate($post['end_reserve_event'], $this->_siteOffset)->toISO8601(true);
		}
		else
			$post['end_reserve_event'] = JFactory::getDate('now', $this->_siteOffset)->toISO8601(true);
	

		if(!empty($post['time_drums_event']))
			$post['time_drums_event'] = JFactory::getDate($post['time_drums_event'], $this->_siteOffset)->toISO8601(true);
		else
			$post['time_drums_event'] = NULL;
		
		if(!empty($post['time_squad_event']))
			$post['time_squad_event'] = JFactory::getDate($post['time_squad_event'], $this->_siteOffset)->toISO8601(true);
		else
			$post['time_squad_event'] = NULL;

		if(!empty($post['time_position_event']))
			$post['time_position_event'] = JFactory::getDate($post['time_position_event'], $this->_siteOffset)->toISO8601(true);
		else
			$post['time_position_event'] = NULL;

		if(empty($post['duration_drums_event']))
			$post['duration_drums_event'] = NULL;
			
		if(empty($post['duration_squad_event']))
			$post['duration_squad_event'] = NULL;

		if(empty($post['duration_position_event']))
			$post['duration_position_event'] = NULL;

		$post['description_event'] = JRequest::getVar('description_event', null, 'default', 'none', JREQUEST_ALLOWHTML);

		$registry = new JRegistry;
		foreach($post['name_method'] as $key => $method_event) {
			$tempRegistry = array();
			$tempRegistry['name_method'] = $post['name_method'][$key];
			$tempRegistry['amount_method'] = $post['amount_method'][$key];
			$registry->set($key, $tempRegistry);
		}
		$post['methods_event'] = $registry->toString();
	
		$registry = new JRegistry;
		foreach($post['name_result'] as $key => $result_event) {
			$tempRegistry = array();
			$tempRegistry['name_result'] = $post['name_result'][$key];
			$tempRegistry['rf_result'] = $post['rf_result'][$key];
			$tempRegistry['rs_result'] = $post['rs_result'][$key];
			$tempRegistry['method_result'] = $post['method_result'][$key];
			$registry->set($key, $tempRegistry);
		}
		$post['results_event'] = $registry->toString();


		$registry = new JRegistry;
		foreach($post['name_genre'] as $key => $genre_event) {
			$tempRegistry = array();
			$tempRegistry['name_genre'] = $post['name_genre'][$key];
			$registry->set($key, $tempRegistry);
		}
		$post['genres_event'] = $registry->toString();

		$registry = new JRegistry;
		foreach($post['name_category'] as $key => $category_event) {
			$tempRegistry = array();
			$tempRegistry['name_category'] = $post['name_category'][$key];
			$registry->set($key, $tempRegistry);
		}
		$post['categorys_event'] = $registry->toString();

		$registry = new JRegistry;
		foreach($post['name_class'] as $key => $class_event) {
			$tempRegistry = array();
			$tempRegistry['name_class'] = $post['name_class'][$key];
			$registry->set($key, $tempRegistry);
		}
		$post['classs_event'] = $registry->toString();

		if ( !$row->bind($post)) 
		{
			$this->setError($this->_db->getErrorMsg());
			return false;	
		}
				
		
		if(is_null($post['time_drums_event']))
			$row->time_drums_event = NULL;
		
		if(is_null($post['time_squad_event']))
			$row->time_squad_event = NULL;

		if(is_null($post['time_position_event']))
			$row->time_position_event = NULL;

		if(is_null($post['duration_drums_event']))
			$row->duration_drums_event = NULL;
			
		if(is_null($post['duration_squad_event']))
			$row->duration_squad_event = NULL;

		if(is_null($post['duration_position_event']))
			$row->duration_position_event = NULL;


		if ( !$row->check($post)) 
		{
			$this->setError($this->_db->getErrorMsg());
			return false;	
		}	
			
		if ( !$row->store(TRUE) ) 
		{	
			$this->setError( $this->_db->getErrorMsg());
			return false;	
		}	

		jimport('joomla.log.log');
		JLog::addLogger(array( 'text_file' => 'log.event.php'));

		if($this->_id):
			$row->checkin($this->_id);
			JLog::add($this->_user->get('id') . JText::_('		Evento Editado -  id_event('.$this->_id.')'), JLog::INFO, 'event');
		else:
			$this->setId( $row->get('id_event') ); 	
			JLog::add($this->_user->get('id') . JText::_('		Evento Cadastrado -  id_event('.$this->_id.')'), JLog::INFO, 'event');
		endif;
		
		$this->setId( $row->get('id_event') ); 
		JRequest::setVar( 'cid', $this->_id );

		return true;
	}

	protected function generateNewSlug($id = null, $slug)
	{
		$this->addTablePath(JPATH_ADMINISTRATOR.'/tables');
	    $table = $this->getTable('event');
		$alias = $slug;
		while ($table->load(array('slug_event' => $alias)))
		{
			if($table->id_aula != $id && $table->slug_event == $alias )
				$alias = JString::increment($alias, 'dash');
		}
		return $alias;
	}

	function isCheckedOut()
	{		
		$this->addTablePath(JPATH_ADMINISTRATOR.'/tables');
	    $row = $this->getTable('event');
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
		$this->addTablePath(JPATH_ADMINISTRATOR.'/tables');
	    $row = $this->getTable('event');
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
		$this->addTablePath(JPATH_ADMINISTRATOR.'/tables');
	    $row = $this->getTable('event');
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