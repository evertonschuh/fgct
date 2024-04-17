<?php

defined('_JEXEC') or die('Restricted access');

class TableMailMessage extends JTable {

	var $id_mailmessage = null;
	var $status_mailmessage = null;
	var $id_mailmessage_occurrence = null;
	var $id_mailmessage_theme = null;
	var $name_mailmessage = null;
	var $account_mailmessage = null;
	var $password_mailmessage = null;
	var $relatory_mailmessage = null;
	var $recipient_mailmessage = null;
	var $subject_mailmessage = null;
	var $mensagem_mailmessage = null;
	var $checked_out = null;
	var $checked_out_time = null;

	function __construct(& $db)
	{
		parent::__construct( '#__intranet_mailmessage', 'id_mailmessage', $db);	
	}
}