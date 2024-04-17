<?php

defined('_JEXEC') or die('Restricted access');

class TablePj extends JTable {

  	var $id_pj = null;
  	var $status_pj = null;
  	var $block_pj = null;
  	var $public_pj = null;
  	var $id_user = null;
  	var $cnpj_pj = null;
  	var $nome_fantasia_pj = null;
  	var $default_name_pj = null;
  	var $fundacao_pj = null;
  	var $inscricao_estadual_pj = null;
  	var $uf_inscricao_estadual_pj = null;
  	var $telefone_pj = null;
  	var $celular_pj = null;
  	var $site_pj = null;
  	var $presidente_pj = null;
  	var $email_presidente_pj = null;
  	var $celular_presidente_pj = null;
  	var $telefone_presidente_pj = null;
  	var $vice_pj = null;
  	var $email_vice_pj = null;
  	var $celular_vice_pj = null;
  	var $telefone_vice_pj = null;
  	var $tesoureiro_pj = null;
  	var $email_tesoureiro_pj = null;
  	var $celular_tesoureiro_pj = null;
  	var $telefone_tesoureiro_pj = null;
  	var $id_estado = null;
  	var $id_cidade = null;
  	var $cep_pj = null;
  	var $bairro_pj = null;
  	var $logradouro_pj = null;
  	var $numero_pj = null;
  	var $complemento_pj = null;
  	var $add_id_estado = null;
  	var $add_id_cidade = null;
  	var $add_cep_pj = null;
  	var $add_bairro_pj = null;
  	var $add_logradouro_pj = null;
  	var $add_numero_pj = null;
  	var $add_complemento_pj = null;
  	var $logo_pj = null;
  	var $observacao_pj = null;
  	var $numcr_pj = null;
  	var $vencr_pj = null;
  	var $stacr_pj = null;
  	var $user_register_pj = null;
  	var $register_pj = null;
  	var $user_update_pj = null;
  	var $update_pj = null;
  	var $checked_out = null;
  	var $checked_out_time = null;
	

	function __construct(& $db)
	{
		parent::__construct( '#__intranet_pj', 'id_pj', $db);	
	}
}
