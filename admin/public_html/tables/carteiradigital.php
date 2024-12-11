<?php

defined('_JEXEC') or die('Restricted access');

class TableCarteiraDigital  extends JTable {
	
	var $id_carteira_digital  = null;
	var $status_carteira_digital = null;
	var $id_anuidade = null;
	var $image_front_carteira_digital = null;
	var $image_back_carteira_digital = null;
	var $user_register_carteira_digital = null;
	var $register_carteira_digital = null;
	var $user_update_carteira_digital = null;
	var $update_carteira_digital = null;
	var $checked_out = null;
	var $checked_out_time = null;

	function __construct(& $db)
	{
		parent::__construct( '#__intranet_carteira_digital', 'id_carteira_digital', $db);	
	}
}
