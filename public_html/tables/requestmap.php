<?php

defined('_JEXEC') or die('Restricted access');

class TableRequestMap extends JTable {
	
	var $id_service = null;
	var $id_user = null;
	var $id_service_stage = null;
	var $title_service = null;
	var $message_service = null;
	var $update_service = null;
	

	function __construct(& $db)
	{
		parent::__construct( '#__intranet_service_map', '', $db);	
	}
}
