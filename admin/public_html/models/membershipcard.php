<?php
/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */

defined( '_JEXEC' ) or die;

jimport( 'joomla.application.component.modellist' );

class EASistemasModelMembershipCard extends JModel
{
	var $_id = null;
	var $_data = null;
	var $_db = null;
	var $_app = null;	
	var $_user = null;
	var $_userAdmin = null;
	
	function __construct()
	{	
		parent::__construct();
		
		$this->_db	= JFactory::getDBO();
		$this->_user = JFactory::getUser();
		$this->_app = JFactory::getApplication();
		
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
				$link = 'index.php?view=membershipcards';
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
			$query->from($this->_db->quoteName('#__intranet_carteira_digital'));
			$query->where( $this->_db->quoteName('id_carteira_digital') . '=' . $this->_db->quote( $this->_id ) );
			$this->_db->setQuery($query);
			if(!(boolean) $this->_data = $this->_db->loadObject()){
				$this->addTablePath(JPATH_BASE.'/tables');
				$this->_data = $this->getTable('carteiradigital');	
			}
		}
		return $this->_data;
	}

	
	function store() 
	{

		$this->addTablePath(JPATH_BASE.'/tables');
	    $row = $this->getTable('carteiradigital');
		$post = JRequest::get( 'post' );
		
		$image_front_carteira_digital_new = JRequest::getVar( 'image_front_carteira_digital_new', '', 'files', 'array' );
		$image_back_carteira_digital_new = JRequest::getVar( 'image_back_carteira_digital_new', '', 'files', 'array' );
		$_path = JPATH_CDN.DS.'images'.DS.'carteiradigital';

		if($this->_id)
			$row->load($this->_id);

		if (!JFolder::exists($_path)) {
			$_folder_permissions = "0755";
			$_folder_permissions = octdec((int)$_folder_permissions);
			JFolder::create($_path, $_folder_permissions);
			$buffer = '<html><body bgcolor="#FFFFFF"></body></html>';
			JFile::write($_path . DS . 'index.html', $buffer);
		}

		if (isset($image_front_carteira_digital_new['name']) && $image_front_carteira_digital_new['name'] != '') {
			$ext = strtolower(JFile::getExt($image_front_carteira_digital_new['name']));
			$name =  md5(uniqid());
			$thumb = $name . '.' . $ext;

			if (JFile::exists($_path . DS . $thumb))
				JFile::delete($_path . DS . $thumb);

			if (!JFile::upload($image_front_carteira_digital_new['tmp_name'], $_path . DS . $thumb))
				return false;

			if (JFile::exists($_path . DS . $post['image_front_carteira_digital']))
				JFile::delete($_path . DS . $post['image_front_carteira_digital']);

			$post['image_front_carteira_digital'] = $thumb;
		}


		if (isset($image_back_carteira_digital_new['name']) && $image_back_carteira_digital_new['name'] != '') {
			$ext = strtolower(JFile::getExt($image_back_carteira_digital_new['name']));
			$name =  md5(uniqid());
			$thumb = $name . '.' . $ext;

			if (JFile::exists($_path . DS . $thumb))
				JFile::delete($_path . DS . $thumb);

			if (!JFile::upload($image_back_carteira_digital_new['tmp_name'], $_path . DS . $thumb))
				return false;

			if (JFile::exists($_path . DS . $post['image_back_carteira_digital']))
				JFile::delete($_path . DS . $post['image_back_carteira_digital']);

			$post['image_back_carteira_digital'] = $thumb;
		}

		

			
		$keysSwitch = array('status_carteira_digital');
		foreach($keysSwitch as $key => $value)
			$post[$value] = isset($post[$value]) ? $post[$value] : '0';


		if ( !$row->bind($post)) 
		{
			$this->setError($this->_db->getErrorMsg());
			return false;	
		}
			
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
		
		JRequest::setVar( 'cid', $this->_id );

		jimport('joomla.log.log');
		JLog::addLogger(array('text_file' => 'log.membershipcard.php'));

		if ($this->_id) :
			$row->checkin($this->_id);
			JLog::add('	User: ' . $this->_userAdmin . JText::_('		Action: Edit - id('.$this->_id .')'), JLog::INFO, 'membershipcard');
		else :
			$this->setId($row->get('id_carteira_digital'));
			JLog::add('	User: ' . $this->_userAdmin . JText::_('		Action: Create - id('.$this->_id .')'), JLog::INFO, 'membershipcard');
		endif;
		
			JRequest::setVar('cid', $this->_id);
		return true;
	}
	
	function getAnuidades() {

		$query = $this->_db->getQuery(true);	
		$query->select('id_anuidade as value, name_anuidade AS  text');							 
		$query->from($this->_db->quoteName('#__intranet_anuidade'));
		$query->order( $this->_db->quoteName('text') . ' DESC' );
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();

	}
	
		
	function getAssociadoTipos() {

		$query = $this->_db->getQuery(true);	
		$query->select('id_associado_tipo as value, name_associado_tipo AS  text');							 
		$query->from($this->_db->quoteName('#__intranet_associado_tipo'));
		$query->order( $this->_db->quoteName('text') . ' DESC' );
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();

	}

	function isCheckedOut()
	{		
		$this->addTablePath(JPATH_BASE.'/tables');
	    $row = $this->getTable('carteiradigital');
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
	    $row = $this->getTable('carteiradigital');
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
	    $row = $this->getTable('carteiradigital');
		
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
