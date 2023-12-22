<?php
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
jimport('joomla.image.image');

require_once(JPATH_THEMES_NATIVE .DS. 'includes' .DS. 'trataimagem.php');

class IntranetModelUPJ extends JModel {

	var $_id = null;
	var $_data = null;
	var $_db = null;
	var $_isRoot = null;
	var $_userLogged = null;
	var $_userAdmin = null;
	var $_trataImagem = null;
	var $_session_id = null;
	var $_siteOffset = null;
	var $_app = null;
	
	
	function __construct()
	{
		parent::__construct();

		$this->_app = JFactory::getApplication(); 
		$this->_db	= JFactory::getDBO();
		$this->_siteOffset = $this->_app->getCfg('offset');
		
		$this->_trataImagem = new TrataImagem();
		$this->_session_id = JFactory::getSession()->getId();
		$this->_user		= JFactory::getUser();
		
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
				$link = 'index.php?view=pjs';
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
			$query->from('#__intranet_pj');	
			$query->leftJoin($this->_db->quoteName('#__users').'  ON ('.$this->_db->quoteName('id').'='.$this->_db->quoteName('id_user').')');
			$query->where( $this->_db->quoteName('id_pj') . '=' . $this->_db->escape( $this->_id ) );
			$this->_db->setQuery($query);
			if(!(boolean) $this->_data = $this->_db->loadObject()) {
				$this->addTablePath(JPATH_ADMINISTRATOR.'/tables');
	    		$this->_data = $this->getTable('pj');
				$this->_data->name='';
				$this->_data->email='';
			}
		}
		return $this->_data;
	}

	function getTrataImagem()
	{
		return $this->_trataImagem;
	}
	
	function getSession() 
	{
		return $this->_session_id;
	}	

	function getUfs() 
	{
		$query = $this->_db->getQuery(true);
		$query->select('id_estado as value, sigla_estado as text');
		$query->from('#__intranet_estado');
		$query->where('status_estado = 1');
		$query->order($this->_db->quoteName('ordering'));
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();	
	}
	
	function getEstados() 
	{
		$query = $this->_db->getQuery(true);
		$query->select('id_estado as value, name_estado as text');
		$query->from('#__intranet_estado');
		$query->where('status_estado = 1');
		$query->order($this->_db->quoteName('ordering'));
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();	
	}
	
	function getCidades() 
	{
		$this->getItem();
		if ($this->_data->id_estado) 
		{
			$query = $this->_db->getQuery(true);
			$query->select('id_cidade as value, name_cidade as text');
			$query->from('#__intranet_cidade');
			$query->where('id_estado = '. $this->_db->quote( $this->_data->id_estado ));
			$query->where('status_cidade = 1');
			$query->order($this->_db->quoteName('ordering'));
			$this->_db->setQuery($query);
			return $this->_db->loadObjectList();	
		}
	}
	function getAddCidades() 
	{
		$this->getItem();
		if ($this->_data->add_id_estado) 
		{
			$query = $this->_db->getQuery(true);
			$query->select('id_cidade as value, name_cidade as text');
			$query->from('#__intranet_cidade');
			$query->where('id_estado = '. $this->_db->quote( $this->_data->add_id_estado ));
			$query->where('status_cidade = 1');
			$query->order($this->_db->quoteName('ordering'));
			$this->_db->setQuery($query);
			return $this->_db->loadObjectList();	
		}
	}

	function store() 
	{
		$config   = JFactory::getConfig();
		$siteOffset = $config->getValue('offset');

		$this->addTablePath(JPATH_ADMINISTRATOR.'/tables');
	    $row = $this->getTable('pj');
		$data = JRequest::get( 'post' );
		$image = JRequest::getVar( 'logo_pj_new', '', 'files', 'array' );
		$_path = JPATH_MEDIA.DS.'images' . DS . 'logos';
		
		if(isset($data['remove_logo_pj']))
		{
			if ( JFile::exists( $_path . DS .$data['logo_pj'] ) )
				JFile::delete( $_path . DS . $data['logo_pj'] );	
				
			$data['logo_pj'] = NULL;				
		}
		elseif(isset($image['name']) && $image['name'] != '') {   
			$ext = strtolower( JFile::getExt($image['name']) );
			$name =  md5(uniqid());	
			$thumb = $name .'.'. $ext;		

			if ( JFile::exists( $_path . DS .$thumb ) )
				JFile::delete( $_path . DS . $thumb );
				
				

			if (!JFile::upload($image['tmp_name'], $_path .DS. $thumb)) 	
				return false;

			
			if ( JFile::exists( $_path . DS .$data['logo_pj'] ) )
				JFile::delete( $_path . DS . $data['logo_pj'] );


			$data['logo_pj'] = $thumb;
			
		}
		
		if($this->_id)
			$row->load($this->_id);
		
		//salva data se for novo
		if(!$this->_id) {
			$data['register_pj'] = JFactory::getDate('now', $siteOffset)->toISO8601(true);
			$data['user_register_pj'] = $this->_userAdmin;
		}
		else {
			$data['update_pj'] = JFactory::getDate('now', $siteOffset)->toISO8601(true);
			$data['user_update_pj'] = $this->_userAdmin;
		}

		$data['observacao_pj'] = JRequest::getVar('observacao_pj', null, 'default', 'none', JREQUEST_ALLOWHTML);

		if(!empty($data['fundacao_pj']))
		{
			$dataaTmp = explode(" ",$data['fundacao_pj']);
			$data['fundacao_pj'] = implode("-",array_reverse(explode("/", $dataaTmp[0]))) .' '. $dataaTmp[1];
			$data['fundacao_pj'] = JFactory::getDate($data['fundacao_pj'], $this->_siteOffset)->toFormat('%Y-%m-%d', true);
		}
		else
			$data['fundacao_pj'] = NULL;

		//if(!empty($data['data_expedicao_pf']))
		//{
		//	$dataaTmp = explode(" ",$data['data_expedicao_pf']);
		//	$data['data_expedicao_pf'] = implode("-",array_reverse(explode("/", $dataaTmp[0]))) .' '. $dataaTmp[1];
		//	$data['data_expedicao_pf'] = JFactory::getDate($data['data_expedicao_pf'], $siteOffset)->toFormat('%Y-%m-%d', true);
		//}
		//else
		//	$data['data_expedicao_pf'] = NULL;


		if ( !$row->bind($data)) 
		{
			$this->setError($this->_db->getErrorMsg());
			return false;	
		}
		
		if($data['remove_logo_pj'])
			$row->logo_pj = NULL;
		
		if ( !$row->check($data)) 
		{
			$this->setError($this->_db->getErrorMsg());
			return false;	
		}	
			
		if ( !$row->store($data) ) 
		{	
			$this->setError( $this->_db->getErrorMsg());
			return false;	
		}

		jimport('joomla.log.log');
		JLog::addLogger(array( 'text_file' => 'log.upj.php'));
		
		if($this->_id):
			$row->checkin($this->_id);
			JLog::add($this->_user->get('id') . JText::_('		Pessoa Jurídica Editada -  idPJ('.$this->_id.')'), JLog::INFO, 'upj');
		else:
			$this->setId( $row->get('id_pj') ); 	
			JLog::add($this->_user->get('id') . JText::_('		Pessoa Jurídica Cadastrada -  idPJ('.$this->_id.')'), JLog::INFO, 'upj');
		endif;
		
		JRequest::setVar( 'cid', $this->_id );
				
		return true;
	}

	
	function testUniqueCnpj() 
	{
		$cnpj_pj = JRequest::getVar( 'cnpj_pj',	'', 'post' );
		$id_pj = JRequest::getVar( 'id_pj',	'', 'post' );
		$query = $this->_db->getQuery(true);
		$query->select('cnpj_pj');
		$query->from('#__pj');
		$query->where('cnpj_pj = '. $this->_db->quote( $cnpj_pj ));
		if($id_pj)
			$query->where('id_pj != '. $this->_db->quote( $id_pj ));
		$this->_db->setQuery($query);
		
		if ( (boolean) $this->_db->loadObject() ) 
			return false;		
		return true;	
	}		
	
	
	function isCheckedOut()
	{		
		$this->addTablePath(JPATH_ADMINISTRATOR.'/tables');
	    $row = $this->getTable('pj');
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
	    $row = $this->getTable('pj');
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
	    $row = $this->getTable('pj');
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