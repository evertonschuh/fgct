<?php

defined('_JEXEC') or die('Restricted access');

class TableAtividade  extends JTable {
	
	var $id_atividade  = null;
	var $status_atividade = null;
	var $name_atividade = null;
	var $observacao_atividade = null;
	var $checked_out = null;
	var $checked_out_time = null;

	function __construct(& $db)
	{
		parent::__construct( '#__atividade', 'id_atividade', $db);	
	}
}
