<?php

defined('_JEXEC') or die('Restricted access');

class TableArma extends JTable {
	
	var $id_arma = null;
	var $status_arma = null;
	var $id_user = null;
	var $id_especie = null;
	var $id_marca = null;
	var $id_calibre = null;
	var $id_acervo = null;
	var $id_pais = null;
	var $id_funcionamento = null;
	var $numero_arma = null;
	var $quant_cano_arma = null;
	var $comp_cano_arma = null;
	var $cap_carreg_arma = null;
	var $num_raia_arma = null;
	var $sentido_arma = null;
	var $registro_arma = null;
	var $registro_tipo_arma = null;
	var $data_registro_arma = null;
	var $vencimento_registro_arma = null;
	var $image_arma = null;
	var $modelo_arma = null;
	var $acabamento_arma = null;
	var $user_register_arma = null;
	var $register_arma = null;
	var $user_update_arma = null;
	var $update_arma = null;
	var $checked_out = null;
	var $checked_out_time = null;
	

	function __construct(& $db)
	{
		parent::__construct( '#__intranet_arma', 'id_arma', $db);	
	}
}
