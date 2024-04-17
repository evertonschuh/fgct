<?php

defined('_JEXEC') or die('Restricted access');

class TableServiceType extends JTable {

	var $id_service_type = null;
	var $status_service_type = null;
	var $public_service_type = null;
	var $name_service_type = null;
	var $codigo_service_type = null;
	var $message_service_type = null;
	var $automatic_service_type = null;
	var $id_documento = null;
	var $checked_out = null;
	var $checked_out_time = null;

	function __construct(& $db)
	{
		parent::__construct( '#__intranet_service_type', 'id_service_type', $db);	
	}
}