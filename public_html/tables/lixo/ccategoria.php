<?php

defined('_JEXEC') or die('Restricted access');

class TableCCategoria  extends JTable {
	
	var $id_corredor_categoria  = null;
	var $status_corredor_categoria = null;
	var $name_corredor_categoria = null;
	var $observacao_corredor_categoria = null;
	var $checked_out = null;
	var $checked_out_time = null;

	function __construct(& $db)
	{
		parent::__construct( '#__corredor_categoria', 'id_corredor_categoria', $db);	
	}
}
