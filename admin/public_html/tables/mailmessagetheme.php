<?php

defined('_JEXEC') or die('Restricted access');

class TableMailMessageTheme extends JTable {

	var $id_mailmessage_theme = null;
	var $status_mailmessage_theme = null;
	var $name_mailmessage_theme = null;
	var $theme_mailmessage_theme = null;
	var $checked_out = null;
	var $checked_out_time = null;

	function __construct(& $db)
	{
		parent::__construct( '#__intranet_mailmessage_theme', 'id_mailmessage_theme', $db);	
	}
}