<?php

defined('_JEXEC') or die('Restricted access');

class TablePagamentoRemessa extends JTable {

	var $id_pagamento_remessa = null;
	var $name_pagamento_remessa = null;
	var $id_pagamento_metodo = null;
	var $pagamentos_pagamento_remessa = null;
	var $date_pagamento_remessa = null;
	var $register_pagamento_remessa = null;
	var $user_register_pagamento_remessa = null;
	var $update_pagamento_remessa = null;
	var $user_update_pagamento_remessa = null;
	var $checked_out = null;
	var $checked_out_time = null;

	function __construct(& $db)
	{
		parent::__construct( '#__intranet_pagamento_remessa', 'id_pagamento_remessa', $db);	
	}
}
