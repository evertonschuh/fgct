<?php

defined('_JEXEC') or die('Restricted access');

class TableDocumentNumber extends JTable {

	var $id_documento_numero = null;
	var $id_documento = null;
	var $id_user = null;
	var $numero_documento_numero = null;
	var $ano_documento_numero = null;
	var $name_documento = null;
	var $texto_documento_numero = null;
	var $register_documento_numero = null;
	var $user_register_documento_numero = null;


	function __construct(& $db)
	{
		parent::__construct( '#__intranet_documento_numero', 'id_documento_numero', $db);	
	}
}

