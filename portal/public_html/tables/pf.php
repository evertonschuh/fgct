<?php

defined('_JEXEC') or die('Restricted access');

class TablePF extends JTable {

	var $id_pf = null;
	var $status_pf = null;
	var $block_pf = null;
	var $public_pf = null;
	var $id_user = null;
	var $compressed_air_pf = null;
	var $cpf_pf = null;
	var $nmae_pf = null;
	var $npai_pf = null;
	var $tsangue_pf = null;
	var $filho_pf = null;
	var $rg_pf = null;
	var $orgao_expeditor_pf = null;
	var $uf_orga_expeditor_pf = null;
	var $data_expedicao_pf = null;
	var $sexo_pf = null;
	var $data_nascimento_pf = null;
	var $id_estado_civil = null;
	var $nacionalidade_pf = null;
	var $naturalidade_pf = null;
	var $naturalidade_uf_pf = null;
	var $pcd_pf = null;
	var $profissao_pf = null;
	var $tel_celular_pf = null;
	var $tel_residencial_pf = null;
	var $id_estado = null;
	var $id_cidade = null;
	var $cep_pf = null;
	var $bairro_pf = null;
	var $logradouro_pf = null;
	var $numero_pf = null;
	var $complemento_pf = null;
	var $add_id_estado = null;
	var $add_id_cidade = null;
	var $add_cep_pf = null;
	var $add_bairro_pf = null;
	var $add_logradouro_pf = null;
	var $add_numero_pf = null;
	var $add_complemento_pf = null;
	var $image_pf = null;
	var $observacao_pf = null;
	var $confederado_pf = null;
	var $numcr_pf = null;
	var $vencr_pf = null;
	var $stacr_pf = null;
	var $user_register_pf = null;
	var $register_pf = null;
	var $user_update_pf = null;
	var $update_pf = null;
	var $checked_out = null;
	var $checked_out_time = null;

	function __construct(& $db)
	{
		parent::__construct( '#__intranet_pf', 'id_pf', $db);	
	}
}
