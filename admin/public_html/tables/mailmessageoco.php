<?php

defined('_JEXEC') or die('Restricted access');

class TableMailMessage extends JTable {

	var $id_mailmessage_occurrence = null;
	var $status_mailmessage_occurrence = null;
	var $name_mailmessage_occurrence = null;
	var $robot_mailmessage_occurrence = null;
	var $checked_out = null;
	var $checked_out_time = null;

	function __construct(& $db)
	{
		parent::__construct( '#__intranet_mailmessage_occurrence', 'id_mailmessage_occurrence', $db);	
	}
}