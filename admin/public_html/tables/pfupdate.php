<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

class TablePfUpdate extends JTable {
	
	var $id_pf_update = null;
	var $status_pf_update = null;
	var $register_pf_update = null;
	var $name_pf_update = null;
	var $email_pf_update = null;
	var $cpf_pf_update = null;
	var $nmae_pf_update = null;
	var $npai_pf_update = null;
	var $tsangue_pf_update = null;
	var $filho_pf_update = null;
	var $rg_pf_update = null;
	var $orgao_expeditor_pf_update = null;
	var $uf_orga_expeditor_pf_update = null;
	var $data_expedicao_pf_update = null;
	var $sexo_pf_update = null;
	var $data_nascimento_pf_update = null;
	var $id_estado_civil = null;
	var $nacionalidade_pf_update = null;
	var $naturalidade_pf_update = null;
	var $naturalidade_uf_pf_update = null;
	var $pcd_pf_update = null;
	var $profissao_pf_update = null;
	var $tel_celular_pf_update = null;
	var $tel_residencial_pf_update = null;
	var $id_estado = null;
	var $id_cidade = null;
	var $cep_pf_update = null;
	var $bairro_pf_update = null;
	var $logradouro_pf_update = null;
	var $numero_pf_update = null;
	var $complemento_pf_update = null;
	var $add_id_estado = null;
	var $add_id_cidade = null;
	var $add_cep_pf_update = null;
	var $add_bairro_pf_update = null;
	var $add_logradouro_pf_update = null;
	var $add_numero_pf_update = null;
	var $add_complemento_pf_update = null;
	var $id_clube = null;
	var $confederado_pf_update = null;
	var $numcr_pf_update = null;
	var $vencr_pf_update = null;
	var $stacr_pf_update = null;
	var $checked_out = null;
	var $checked_out_time = null;

	
	function __construct(& $db) {
		parent::__construct('#__intranet_pf_update', 'id_pf_update', $db);
	}
}
