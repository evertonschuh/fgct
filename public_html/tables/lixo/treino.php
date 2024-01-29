<?php

defined('_JEXEC') or die('Restricted access');

class TableTreino  extends JTable {
	
	var $id_treino  = null;
	var $status_treino = null;
	var $name_treino = null;
	var $observacao_treino = null;
	var $checked_out = null;
	var $checked_out_time = null;

	function __construct(& $db)
	{
		parent::__construct( '#__treino', 'id_treino', $db);	
	}
}
