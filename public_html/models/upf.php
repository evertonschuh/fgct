<?php
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_THEMES_NATIVE .DS. 'includes' .DS. 'trataimagem.php');

jimport( 'joomla.application.component.model' );

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
jimport('joomla.image.image');

class IntranetModelUPF extends JModel {

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
		
		$this->_trataImagem = new TrataImagem();
		$this->_session_id = JFactory::getSession()->getId();
		
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
				$link = 'index.php?view=upfs';
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
			$query->from('#__intranet_pf');	
			$query->leftJoin($this->_db->quoteName('#__users').'  ON ('.$this->_db->quoteName('id').'='.$this->_db->quoteName('id_user').')');
			$query->where( $this->_db->quoteName('id_pf') . '=' . $this->_db->escape( $this->_id ) );
			$this->_db->setQuery($query);
			if(!(boolean) $this->_data = $this->_db->loadObject()) {
				$this->addTablePath(JPATH_ADMINISTRATOR.'/tables');
	    		$this->_data = $this->getTable('pf');
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
	
	function getEstadoCivil() 
	{
		$query = $this->_db->getQuery(true);
		$query->select('id_estado_civil as value, name_estado_civil as text');
		$query->from('#__intranet_estado_civil');
		$query->where('status_estado_civil = 1');
		$query->order($this->_db->quoteName('ordering'));
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();	
	}
    
	function getPaises() 
	{
		$query = $this->_db->getQuery(true);
		$query->select('id_pais as value, name_pais as text');
		$query->from('#__intranet_pais');
	//	$query->where('status_pais = 1');
		$query->order($this->_db->quoteName('text'));
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
	function getCidadesNasceu() 
	{
		$this->getItem();
		if ($this->_data->naturalidade_uf_pf) 
		{
			$query = $this->_db->getQuery(true);
			$query->select('id_cidade as value, name_cidade as text');
			$query->from('#__intranet_cidade');
			$query->where('id_estado = '. $this->_db->quote( $this->_data->naturalidade_uf_pf ));
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
	    $row = $this->getTable('pf');
		$data = JRequest::get( 'post' );
		$image = JRequest::getVar( 'image_pf_new', '', 'files', 'array' );
		$_path = JPATH_MEDIA.DS.'images' . DS . 'avatar';

		$pk		= (!empty($data['id_user'])) ? $data['id_user'] : 0;
		if(!empty($data['id_user'])):
			$user	= JUser::getInstance($data['id_user']);

			if ( $user->get('name') != $data['name'] || $user->get('email') != $data['email'] ):
				
				$dataUser = array();
				$dataUser['name'] = $data['name'];
				$dataUser['email'] = $data['email'];
				
				if (!$user->bind($dataUser)):
					$this->setError($user->getError());
					return false;
				endif;
				
				if (!$user->save()):
					$this->setError($user->getError());
					return false;
				endif;
				
				unset($dataUser);
			endif;	

		else:
			$dataUser = array();
			$dataUser['name'] = $data['name'];
			$dataUser['email'] = $data['email'];
			$dataUser['username'] = $data['email'];
			$dataUser['password'] = '123465789'; 
			$dataUser['block'] = 0;	
			$dataUser['sendEmail'] = 0;
            $dataUser['groups'] = array(2);

			$user = new JUser;
			$dataU = (array)$dataUser;
	
			if (!$user->bind($dataU)) {
				return false;
			}
			
			if (!$user->save()) {
				return false;
			}
			$data['id_user'] = $user->get('id');
	
		endif;
		
		
		if (!JFolder::exists($_path))
		{
			$_folder_permissions = "0755";
			$_folder_permissions = octdec((int)$_folder_permissions);
			JFolder::create($_path, $_folder_permissions);
			$buffer = '<html><body bgcolor="#FFFFFF"></body></html>';
			JFile::write( $_path . DS. 'index.html', $buffer ); 
		}
		
		if($this->_id)
			$row->load($this->_id);	
				
		if($data['remove_image_pf'])
		{
			if ( JFile::exists( $_path . DS .$data['image_pf'] ) )
				JFile::delete( $_path . DS . $data['image_pf'] );	
			
			$data['image_pf'] = NULL;	
		}
		elseif(isset($image['name']) && $image['name'] != '') {   
			$ext = strtolower( JFile::getExt($image['name']) );
			$name =  md5(uniqid());	
			$thumb = $name .'.'. $ext;		

			if ( JFile::exists( $_path . DS .$thumb ) )
				JFile::delete( $_path . DS . $thumb );
				
			if (!JFile::upload($image['tmp_name'], $_path .DS. $thumb)) 	
				return false;
			
			if ( JFile::exists( $_path . DS .$data['image_pf'] ) )
				JFile::delete( $_path . DS . $data['image_pf'] );

			$data['image_pf'] = $thumb;
			
		}
		
		//salva data se for novo
		if(!$this->_id) {
			$data['register_pf'] = JFactory::getDate('now', $siteOffset)->toISO8601(true);
			$data['user_register_pf'] = $this->_userAdmin;
		}
		else {
			$data['update_pf'] = JFactory::getDate('now', $siteOffset)->toISO8601(true);
			$data['user_update_pf'] = $this->_userAdmin;
		}

		//$data['curriculum_pf'] = JRequest::getVar('curriculum_pf', null, 'default', 'none', JREQUEST_ALLOWHTML);
		$data['observacao_pf'] = JRequest::getVar('observacao_pf', null, 'default', 'none', JREQUEST_ALLOWHTML);
		
		if(!empty($data['data_nascimento_pf']))
		{
			$dataaTmp = explode(" ",$data['data_nascimento_pf']);
			$data['data_nascimento_pf'] = implode("-",array_reverse(explode("/", $dataaTmp[0]))) .' '. $dataaTmp[1];
			$data['data_nascimento_pf'] = JFactory::getDate($data['data_nascimento_pf'], $siteOffset)->toFormat('%Y-%m-%d', true);
		}
		else
			$data['data_nascimento_pf'] = NULL;

		if(!empty($data['data_expedicao_pf']))
		{
			$dataaTmp = explode(" ",$data['data_expedicao_pf']);
			$data['data_expedicao_pf'] = implode("-",array_reverse(explode("/", $dataaTmp[0]))) .' '. $dataaTmp[1];
			$data['data_expedicao_pf'] = JFactory::getDate($data['data_expedicao_pf'], $siteOffset)->toFormat('%Y-%m-%d', true);
		}
		else
			$data['data_expedicao_pf'] = NULL;


		if ( !$row->bind($data)) 
		{
			$this->setError($this->_db->getErrorMsg());
			return false;	
		}
		
		if($data['remove_image_pf'])
			$row->image_pf = NULL;
		
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
		JLog::addLogger(array( 'text_file' => 'log.upf.php'));
		
		if($this->_id):
			$row->checkin($this->_id);
			JLog::add($this->_user->get('id') . JText::_('		Pessoa Física Editada -  idPF('.$this->_id.')'), JLog::INFO, 'upf');
		else:
			$this->setId( $row->get('id_pf') ); 	
			JLog::add($this->_user->get('id') . JText::_('		Pessoa Física Cadastrada -  idPF('.$this->_id.')'), JLog::INFO, 'upf');
		endif;
		
		JRequest::setVar( 'cid', $this->_id );
				
		return true;
	}
	
	function isCheckedOut()
	{		
		$this->addTablePath(JPATH_ADMINISTRATOR.'/tables');
	    $row = $this->getTable('pf');
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
	    $row = $this->getTable('pf');
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
	    $row = $this->getTable('pf');
		
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