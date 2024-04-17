<?php

defined('_JEXEC') or die('Restricted access');

class TableRequest extends JTable {
	
	var $id_service = null;
	var $status_service = null;
	var $id_service_type = null;
	var $id_user = null;
	var $create_service = null;
	var $lastupdate_service = null;
	var $checked_out = null;
	var $checked_out_time = null;
	

	function __construct(& $db)
	{
		parent::__construct( '#__intranet_service', 'id_service', $db);	
	}
}
