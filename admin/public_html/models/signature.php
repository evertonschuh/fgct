<?php
/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */

defined( '_JEXEC' ) or die;

jimport( 'joomla.application.component.modellist' );

class EASistemasModelSignature extends JModel
{
	var $_id = null;
	var $_data = null;
	var $_db = null;
	var $_app = null;	
	var $_isRoot = null;
	var $_user = null;
	var $_userAdmin = null;
	
	function __construct()
	{	
		parent::__construct();
		
		$this->_db	= JFactory::getDBO();
		$this->_user = JFactory::getUser();
		$this->_app = JFactory::getApplication();
		
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
				$link = 'index.php?view=signatures';
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
			$query->from($this->_db->quoteName('#__intranet_signature'));
			$query->where( $this->_db->quoteName('id_signature') . '=' . $this->_db->quote( $this->_id ) );
			$this->_db->setQuery($query);
			if(!(boolean) $this->_data = $this->_db->loadObject()){
				$this->addTablePath(JPATH_BASE.'/tables');
				$this->_data = $this->getTable('signature');	
			}
		}
		return $this->_data;
	}

	
	function store() 
	{


		$data = JRequest::get( 'post' );
		$file = JRequest::getVar( 'certificate_signature_new', '', 'files', 'array' );

		$this->addTablePath(JPATH_BASE.'/tables');
	    $row = $this->getTable('signature');

		if($this->_id)
			$row->load($this->_id);

		$data['signature_signature'] = JRequest::getVar('signature_signature', null, 'default', 'none', JREQUEST_ALLOWRAW);
		
		$_path = JPATH_CDN .DS. 'certificate';
		
		if (!JFolder::exists($_path))
		{
			$_folder_permissions = "0755";
			$_folder_permissions = octdec((int)$_folder_permissions);
			JFolder::create($_path, $_folder_permissions);
			$buffer = '<html><body bgcolor="#FFFFFF"></body></html>';
			JFile::write( $_path . DS. 'index.html', $buffer ); 
		}
		if($data['remove_certificate'])
		{
			$data['certificate_signature'] = NULL;
			if ( JFile::exists( $_path . DS .$row->certificate_signature ) )
				JFile::delete( $_path . DS . $row->certificate_signature );	
			
		}
		elseif(isset($file['name']) && $file['name'] != '') 
		{   
			$ext = strtolower( JFile::getExt($file['name']) );
			$name =  md5(uniqid());	
			$filename = $name .'.'. $ext;		
			if ( JFile::exists( $_path . DS .$data['certificate_signature'] ) )
				JFile::delete( $_path . DS . $data['certificate_signature'] );

			if (!JFile::upload($file['tmp_name'], $_path .DS. $filename)) {	
				return false;
			}
			$data['certificate_signature'] = $filename;
			
		}
		
		if($this->_id)
			$row->load($this->_id);
		
			
		$keysSwitch = array('status_signature',);
		foreach($keysSwitch as $key => $value)
			$data[$value] = isset($data[$value]) ? $data[$value] : '0';


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
			
		if ( !$row->store(TRUE) ) 
		{	
			$this->setError( $this->_db->getErrorMsg());
			return false;	
		}
		

		if($this->_id):
			$row->checkin($this->_id);
			$textLog = 'edit item';
		else:
			$this->setId( $row->get('id_signature') ); 			
			$textLog = 'new item';
		endif;

		JRequest::setVar( 'cid', $this->_id );

		jimport('joomla.log.log');
		JLog::addLogger(array( 'text_file' => 'log.signature.php'));
		JLog::add($this->_user->get('id') . ' - ' . $textLog.' -  id item ('.$this->_id.')', JLog::INFO, 'signature');
		
		return true;
	}
	

	function isCheckedOut()
	{		
		$this->addTablePath(JPATH_BASE.'/tables');
	    $row = $this->getTable('signature');
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
		$this->addTablePath(JPATH_BASE.'/tables');
	    $row = $this->getTable('signature');
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
		$this->addTablePath(JPATH_BASE.'/tables');
	    $row = $this->getTable('signature');
		
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
