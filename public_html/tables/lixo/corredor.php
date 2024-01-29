<?php

defined('_JEXEC') or die('Restricted access');

class TableCorredor  extends JTable {
	
	var $id_corredor = null;
	var $status_corredor = null;
	var $name_corredor = null;
	var $email_corredor = null;
	var $block_corredor = null;
	var $public_corredor = null;
	var $password_corredor = null;
	var $activation_corredor = null;
	var $cpf_corredor = null;
	var $nmae_corredor = null;
	var $npai_corredor = null;
	var $tsangue_corredor = null;
	var $filho_corredor = null;
	var $rg_corredor = null;
	var $orgao_expeditor_corredor = null;
	var $uf_orga_expeditor_corredor = null;
	var $data_expedicao_corredor = null;
	var $sexo_corredor = null;
	var $data_nascimento_corredor = null;
	var $id_estado_civil = null;
	var $nacionalidade_corredor = null;
	var $naturalidade_corredor = null;
	var $naturalidade_uf_corredor = null;
	var $pcd_corredor = null;
	var $profissao_corredor = null;
	var $tel_celular_corredor = null;
	var $tel_residencial_corredor = null;
	var $id_estado = null;
	var $id_cidade = null;
	var $cep_corredor = null;
	var $bairro_corredor = null;
	var $logradouro_corredor = null;
	var $numero_corredor = null;
	var $complemento_corredor = null;
	var $image_corredor = null;
	var $observacao_corredor = null;
	var $id_corredor_categoria = null;
	var $id_corredor_grupo = null;
	var $user_register_corredor = null;
	var $register_corredor = null;
	var $user_update_corredor = null;
	var $update_corredor = null;
	var $checked_out = null;
	var $checked_out_time = null;

	function __construct(& $db)
	{
		parent::__construct( '#__corredor', 'id_corredor', $db);	
	}
}
