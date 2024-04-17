<?php

defined('_JEXEC') or die('Restricted access');

class TablePagamento extends JTable {

	var $id_pagamento = null;
	var $status_pagamento = null;
	
	var $type_pagamento = null;
	var $id_produto = null;
	var $id_anuidade = null;
	
	var $id_user = null;
	
	var $id_pagamento_metodo = null;
	var $registrado_pagamento = null;
	var $cadastro_pagamento = null;
	
	var $vencimento_pagamento = null;
	var $vencimento_desconto_pagamento = null;
	
	var $baixa_pagamento = null;
	var $update_pagamento = null;
	
	var $valor_pagamento = null;
	var $valor_desconto_pagamento = null;
	
	var $valor_pago_pagamento = null;
	var $taxa_pagamento = null;
	
	var $nossonumero_pagamento = null;
	var $carteira_pagamento = null;
	var $transacao_pagamento = null;
	var $resposta_pagamento = null;
	
	var $text_pagamento = null;

	var $checked_out = null;
	var $checked_out_time = null;
	
	

	function __construct($db)
	{
		parent::__construct( '#__intranet_pagamento', 'id_pagamento', $db);	
	}
}
