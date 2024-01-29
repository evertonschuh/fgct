<?php

defined('_JEXEC') or die('Restricted access');

class TableAssociado extends JTable {

	var $id_associado = null;
	var $id_user = null;
	var $status_associado = null;
	var $cadastro_associado = null;
	var $confirmado_associado = null;
	var $validate_associado = null;
	var $checked_out = null;
	var $checked_out_time = null;

	function __construct(& $db)
	{
		parent::__construct( '#__intranet_associado', 'id_associado', $db);	
	}
}
