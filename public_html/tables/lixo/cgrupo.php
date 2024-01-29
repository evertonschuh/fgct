<?php

defined('_JEXEC') or die('Restricted access');

class TableCGrupo  extends JTable {
	
	var $id_corredor_grupo  = null;
	var $status_corredor_grupo = null;
	var $name_corredor_grupo = null;
	var $observacao_corredor_grupo = null;
	var $id_atividade_segunda = null;
	var $local_atividade_segunda = null;
	var $id_atividade_terca = null;
	var $local_atividade_terca = null;
	var $id_atividade_quarta = null;
	var $local_atividade_quarta = null;
	var $id_atividade_quinta = null;
	var $local_atividade_quinta = null;
	var $id_atividade_sexta = null;
	var $local_atividade_sexta = null;
	var $id_atividade_sabado = null;
	var $local_atividade_sabado = null;
	var $id_atividade_domingo = null;
	var $local_atividade_domingo = null;
	var $checked_out = null;
	var $checked_out_time = null;

	function __construct(& $db)
	{
		parent::__construct( '#__corredor_grupo', 'id_corredor_grupo', $db);	
	}
}
