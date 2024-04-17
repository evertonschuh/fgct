<?php
defined('_JEXEC') or die('Restricted access');


jimport( 'joomla.application.component.model' );

class EASistemasModelWeapon extends JModel {

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
			$query->from('#__intranet_arma');	
			//$query->leftJoin($this->_db->quoteName('#__users').'  ON ('.$this->_db->quoteName('id').'='.$this->_db->quoteName('id_user').')');
			$query->where( $this->_db->quoteName('id_arma') . '=' . $this->_db->escape( $this->_id ) );
			$this->_db->setQuery($query);
			if(!(boolean) $this->_data = $this->_db->loadObject()) {
				$this->addTablePath(JPATH_SITE.'/tables');
	    		$this->_data = $this->getTable('arma');
				//$this->_data->name='';
				//$this->_data->email='';
			}
		}
		return $this->_data;
	}

	function getAcervo() 
	{
		$query = $this->_db->getQuery(true);
		$query->select('id_acervo as value, name_acervo as text');
		$query->from('#__intranet_acervo');
		$query->where('status_acervo = 1');
		$query->order($this->_db->quoteName('name_acervo'));
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();	
	}
	
	function getEspecie() 
	{
		$query = $this->_db->getQuery(true);
		$query->select('id_especie as value, name_especie as text');
		$query->from('#__intranet_especie');
		$query->where('status_especie = 1');
		$query->order($this->_db->quoteName('name_especie'));
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();	
	}
		
	function getCalibre() 
	{
		$query = $this->_db->getQuery(true);
		$query->select('id_calibre as value, name_calibre as text');
		$query->from('#__intranet_calibre');
		$query->where('status_calibre = 1');
		$query->order($this->_db->quoteName('name_calibre'));
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();	
	}

	function getFuncionamento() 
	{
		$query = $this->_db->getQuery(true);
		$query->select('id_funcionamento as value, name_funcionamento as text');
		$query->from('#__intranet_funcionamento');
		$query->where('status_funcionamento = 1');
		$query->order($this->_db->quoteName('name_funcionamento'));
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();	
	}
	
	function getMarca() 
	{
		$query = $this->_db->getQuery(true);
		$query->select('id_marca as value, name_marca as text');
		$query->from('#__intranet_marca');
		$query->where('status_marca = 1');
		$query->order($this->_db->quoteName('name_marca'));
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();	
	}
	
	function getPais() 
	{
		$query = $this->_db->getQuery(true);
		$query->select('id_pais as value, name_pais as text');
		$query->from('#__intranet_pais');
		$query->where('status_pais = 1');
		$query->order($this->_db->quoteName('name_pais'));
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();	
	}
	
	function store() 
	{
		$config   = JFactory::getConfig();
		$siteOffset = $config->getValue('offset');
		$this->addTablePath(JPATH_SITE.'/tables');
	    $row = $this->getTable('arma');
		$data = JRequest::get( 'post' );
		$data['id_user'] = $this->_user->get('id');

		/*$image = JRequest::getVar( 'img_arma_new', '', 'files', 'array' );
		$_path = JPATH_MEDIA.DS.'images' . DS . 'armas';
		
		if(isset($data['remove_img_arma']))
		{
			if ( JFile::exists( $_path . DS .$data['img_arma'] ) )
				JFile::delete( $_path . DS . $data['img_arma'] );	
				
			$data['img_arma'] = NULL;				
		}
		elseif(isset($image['name']) && $image['name'] != '') {   
			$ext = strtolower( JFile::getExt($image['name']) );
			$name =  md5(uniqid());	
			$thumb = $name .'.'. $ext;		

			if ( JFile::exists( $_path . DS .$thumb ) )
				JFile::delete( $_path . DS . $thumb );
				
				

			if (!JFile::upload($image['tmp_name'], $_path .DS. $thumb)) 	
				return false;

			
			if ( JFile::exists( $_path . DS .$data['img_arma'] ) )
				JFile::delete( $_path . DS . $data['img_arma'] );


			$data['img_arma'] = $thumb;
			
		}
		*/
		
		if($this->_id)
			$row->load($this->_id);	
		else
			$data['status_arma'] = '1';
				
		if(!$this->_id) {
			$data['register_arma'] = JFactory::getDate('now', $siteOffset)->toISO8601(true);
			$data['user_register_arma'] = $this->_user->get('id');
		}
		else {
			$data['update_arma'] = JFactory::getDate('now', $siteOffset)->toISO8601(true);
			$data['user_update_arma'] = $this->_userAdmin;
		}

		if(!empty($data['data_registro_arma']))
		{
			$dataaTmp = explode(" ",$data['data_registro_arma']);
			$data['data_registro_arma'] = implode("-",array_reverse(explode("/", $dataaTmp[0]))) .' '. $dataaTmp[1];
			$data['data_registro_arma'] = JFactory::getDate($data['data_registro_arma'], $siteOffset)->toFormat('%Y-%m-%d', true);
		}
		else
			$data['data_registro_arma'] = NULL;

		if(!empty($data['vencimento_registro_arma']))
		{
			$dataaTmp = explode(" ",$data['vencimento_registro_arma']);
			$data['vencimento_registro_arma'] = implode("-",array_reverse(explode("/", $dataaTmp[0]))) .' '. $dataaTmp[1];
			$data['vencimento_registro_arma'] = JFactory::getDate($data['vencimento_registro_arma'], $siteOffset)->toFormat('%Y-%m-%d', true);
		}
		else
			$data['vencimento_registro_arma'] = NULL;


		if ( !$row->bind($data)) 
		{
			$this->setError($this->_db->getErrorMsg());
			return false;	
		}
		
		//if($data['remove_img_arma'])
		//	$row->img_arma = NULL;
		
		if(!$data['data_registro_arma'])
			$row->data_registro_arma = NULL;
			
		if(!$data['vencimento_registro_arma'])
			$row->vencimento_registro_arma = NULL;	
		
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
		JLog::addLogger(array( 'text_file' => 'log.weapon.php'));
		
		if($this->_id):
			$row->checkin($this->_id);
			JLog::add($this->_user->get('id') . JText::_('		Edit Weapon -  id('.$this->_id.')'), JLog::INFO, 'weapon');
		else:
			$this->setId( $row->get('id_arma') ); 	
			JLog::add($this->_user->get('id') . JText::_('		New Weapon -  id('.$this->_id.')'), JLog::INFO, 'weapon');
		endif;
		
		JRequest::setVar( 'cid', $this->_id );
				
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