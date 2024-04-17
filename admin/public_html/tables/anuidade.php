<?php

defined('_JEXEC') or die('Restricted access');

class TableAnuidade extends JTable {

	var $id_anuidade = null;
	var $status_anuidade = null;
	var $name_anuidade = null;
	var $ano_anuidade = null;
	var $validate_anuidade = null;
	var $description_anuidade = null;
	var $valor_atleta_novo_anuidade = null;
	var $valor_atleta_periodo1_anuidade = null;
	var $vencimento_atleta_periodo1_anuidade = null;
	var $valor_atleta_periodo2_anuidade = null;
	var $vencimento_atleta_periodo2_anuidade = null;
	var $valor_atleta_periodo3_anuidade = null;
	var $vencimento_atleta_periodo3_anuidade = null;
	var $valor_atleta_periodo4_anuidade = null;
	var $vencimento_atleta_periodo4_anuidade = null;
	var $valor_clube_novo_anuidade = null;
	var $valor_clube_periodo1_anuidade = null;
	var $vencimento_clube_periodo1_anuidade = null;
	var $valor_clube_periodo2_anuidade = null;
	var $vencimento_clube_periodo2_anuidade = null;
	var $valor_clube_periodo3_anuidade = null;
	var $vencimento_clube_periodo3_anuidade = null;
	var $valor_clube_periodo4_anuidade = null;
	var $vencimento_clube_periodo4_anuidade = null;
	var $desconto_compressed_air_anuidade = null;
	var $desconto_paratleta_anuidade = null;
	var $desconto_maior_idade_anuidade = null;
	var $maior_idade_anuidade = null;
	var $desconto_menor_idade_anuidade = null;
	var $menor_idade_anuidade = null;
	var $desconto_sexo_anuidade = null;
	var $sexo_anuidade = null;
	var $desconto_filho_anuidade = null;
	var $idade_filho_anuidade = null;
	var $user_register_anuidade = null;
	var $register_anuidade = null;
	var $user_update_anuidade = null;
	var $update_anuidade = null;
	var $checked_out = null;
	var $checked_out_time = null;
	
	function __construct(& $db)
	{
		parent::__construct( '#__intranet_anuidade', 'id_anuidade', $db);	
	}
}
