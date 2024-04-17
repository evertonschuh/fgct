<?php

defined('_JEXEC') or die('Restricted access');

class TableSignature extends JTable {

	var $id_signature = null;
	var $status_signature = null;
	var $name_signature = null;
	var $signature_signature = null;
	var $certificate_signature = null;
	var $password_signature = null;
	var $checked_out = null;
	var $checked_out_time = null;

	function __construct(& $db)
	{
		parent::__construct( '#__intranet_signature', 'id_signature', $db);	
	}
}