<?php

include( 'joomla.inc.php' );

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
jimport('joomla.image.image');

$obj = new FbtUploadUploadAction();

class FbtUploadUploadAction
{
	
	var $_session;
	var $_session_id;
	var $_db;
	var $_path;
/*
$image = new JImage();
$image->loadFile($item->logo);
$image->resize('208', '125');

$properties = JImage::getImageFileProperties($item->logo);

echo $image->toFile(JPATH_CACHE . DS . $item->logo, $properties->type);
	*/
	
	function __construct()
	{
		/*
		if ( !JSession::checkToken('post') ) 
			echo "<script language=\"javascript\" type=\"text/javascript\">\n window.parent.Joomla.submitform('token_invalid'); \n </script>" ;
		else {
		*/
			
			$this->_db	= JFactory::getDBO();
			
			/*$this->_session = JFactory::getSession();
			$this->_session_id = $this->_session->getId();
			if (!$this->_session_id) {
				echo "<script language=\"javascript\" type=\"text/javascript\">\n window.parent.Joomla.submitform('token_invalid'); \n </script>" ;
			return;
			}
				*/
			$_path  = JPATH_BASE .DS. 'cache'	;
			//$file = JRequest::getVar( 'file0', '', 'files', 'array' );
			$image = JRequest::getVar( 'filename', '', 'files', 'array' );

			if(isset($image['name']) && $image['name'] != '') {   		
				$ext = JFile::getExt($image['name']);
				$thumb =  md5(uniqid()) .'.'. $ext;
				if (JFile::upload($image['tmp_name'], $_path  . DS . $thumb)) {
						/*echo "<script language=\"javascript\" type=\"text/javascript\">\n window.parent.imageload('" . $this->_session_id.'/tmp_' . $thumb. "'); \n </script>" ;*/
					echo 'cache/' . $thumb;	
					return;
				}
			}
			
		echo 'error';	
		return;
		//}
	}

	function &getCachePath()
	{
		$_forders_array = JFolder::folders(JPATH_BASE .DS. 'cache');

		JArrayHelper::toString($_forders_array);
		
		foreach($_forders_array as $key => $value) {
			if ( empty($_forders) )
				$_forders .= $this->_db->quote($this->_db->escape($value));
			else
				$_forders .= ',' . $this->_db->quote($this->_db->escape($value));
		}
		
		$query = $this->_db->getQuery(true);
		$query->select($this->_db->quoteName('session_id') . ' AS '. $this->_db->quoteName('folder_name') );
		$query->from($this->_db->quoteName('#__session'));
		$query->where($this->_db->quoteName('session_id') . ' IN (' . $_forders . ')');
		
		$this->_db->setQuery($query);
		
		$result = $this->_db->loadResultArray();
		foreach ( $_forders_array as $_forder) 
		{
			if ( !in_array( $_forder , $result ) && JFolder::exists( JPATH_BASE .DS. 'cache' .DS. $_forder ) )
					JFolder::delete( JPATH_BASE .DS. 'cache' .DS. $_forder );
		}
		
		$this->_path = JPATH_BASE .DS. 'cache' .DS. $this->_session_id;
		$_folder_permissions = "0777";
		$_folder_permissions = octdec( (int) $_folder_permissions );
		if ( !JFolder::exists( $this->_path ) )
			JFolder::create( $this->_path, $_folder_permissions );
	}
}

?>
