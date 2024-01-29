<?php

defined('_JEXEC') or die('Restricted access');

class TableDocumento extends JTable {
	
	var $id_documento = null;
	var $status_documento = null;
	var $name_documento = null;
	var $text_documento = null;
	var $user_register_documento = null;
	var $register_documento = null;
	var $user_update_documento = null;
	var $update_documento = null;
	var $checked_out = null;
	var $checked_out_time = null;
	



	function __construct(& $db)
	{
		parent::__construct( '#__intranet_documento', 'id_documento', $db);	
	}
}
