<?php

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.model' );

class EASistemasModelAvatar extends JModel
{
	var $_db = null;
	var $_app = null;	
	var $_siteOffset = null;

	function __construct()
	{		
		$this->_app = JFactory::getApplication();
		$this->_siteOffset = $this->_app->getCfg('offset');
		parent::__construct();
	}


	public function deleteItem()
	{	

		$folder = JRequest::getVar('folder', '', 'POST');
		$name_file = JRequest::getVar('name_file', '', 'POST');
		
		if ( JFile::exists( JPATH_IMAGES.DS.$folder.DS.$name_file ) ){
			JFile::delete( JPATH_IMAGES.DS.$folder.DS.$name_file  );	
			return true;
		}
	}

	public function updateItem() {

		$folder = JRequest::getVar('folder', '', 'POST');
		$name_file = JRequest::getVar('name_file', '', 'POST');
		$remove_file = JRequest::getVar('remove_file', '', 'POST');
		$file = JRequest::getVar( 'file', '', 'files', 'array' );

		if (!JFolder::exists(JPATH_IMAGES.DS.$folder))
		{
			$_folder_permissions = "0755";
			$_folder_permissions = octdec((int)$_folder_permissions);
			JFolder::create(JPATH_IMAGES.DS.$folder, $_folder_permissions);
			$buffer = '<html><body bgcolor="#FFFFFF"></body></html>';
			JFile::write( JPATH_IMAGES . DS. 'index.html', $buffer ); 
		}
		
		if ( !empty($remove_file)) {
		
			if(JFile::exists( JPATH_IMAGES.DS.$folder.DS.'small_'.$remove_file ))
				if (!JFile::delete( JPATH_IMAGES.DS.$folder.DS.'small_'.$remove_file  ))
					return false;

			if(JFile::exists( JPATH_IMAGES.DS.$folder.DS.'medium_'.$remove_file ))
				if (!JFile::delete( JPATH_IMAGES.DS.$folder.DS.'medium_'.$remove_file  ))
					return false;

			if(JFile::exists( JPATH_IMAGES.DS.$folder.DS.'large_'.$remove_file ))
				if (!JFile::delete( JPATH_IMAGES.DS.$folder.DS.'large_'.$remove_file  ))
					return false;

			if(JFile::exists( JPATH_IMAGES.DS.$folder.DS.$remove_file ))
				if (!JFile::delete( JPATH_IMAGES.DS.$folder.DS.$remove_file  ))
					return false;					
		}

		if(isset($file['name']) && $file['name'] != '') {   
			if (!JFile::thumb($file['tmp_name'], JPATH_IMAGES.DS.$folder.DS.'small_'.$name_file, 40, 40, true))
				return false;
			   
			if (!JFile::thumb($file['tmp_name'], JPATH_IMAGES.DS.$folder.DS.'medium_'.$name_file, 120, 120, true))
				return false;
			
			if (!JFile::thumb($file['tmp_name'], JPATH_IMAGES.DS.$folder.DS.'large_'.$name_file, 240, 240, true))
				return false;
			
		}
		
		return true;
	}

}