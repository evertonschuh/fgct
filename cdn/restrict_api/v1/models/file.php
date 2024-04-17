<?php

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.model' );

class EASistemasModelFile extends JModel
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

		$folder = JRequest::getVar('folder');
		$name_file = JRequest::getVar('name_file');

		if ( JFile::exists( JPATH_FILES.DS.$folder.DS.$name_file ) )
			JFile::delete( JPATH_FILES.DS.$folder.DS.$name_file  );	
			
		return true;
	}

	public function updateItem() {

		$folder = JRequest::getVar('folder', '', 'POST');
		$name_file = JRequest::getVar('name_file', '', 'POST');
		$remove_file = JRequest::getVar('remove_file', '', 'POST');
		$file = JRequest::getVar( 'file', '', 'files', 'array' );

		if (!JFolder::exists(JPATH_FILES.DS.$folder))
		{
			$_folder_permissions = "0755";
			$_folder_permissions = octdec((int)$_folder_permissions);
			JFolder::create(JPATH_FILES.DS.$folder, $_folder_permissions);
			$buffer = '<html><body bgcolor="#FFFFFF"></body></html>';
			JFile::write( JPATH_FILES . DS. 'index.html', $buffer ); 
		}
		
		if ( !empty($remove_file)) {
		
			if(JFile::exists( JPATH_FILES.DS.$folder.DS.$remove_file ))
				if (!JFile::delete( JPATH_FILES.DS.$folder.DS.$remove_file  ))
					return false;					
		}

		if(isset($file['name']) && $file['name'] != '') {   
			if (!JFile::upload($file['tmp_name'], JPATH_FILES.DS.$folder.DS. $name_file)) {	
				return false;
			}			
		}
		
		return true;
	}

}