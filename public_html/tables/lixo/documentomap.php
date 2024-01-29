<?php

defined('_JEXEC') or die('Restricted access');

class TableDocumentoMap extends JTable {

	var $id_documento = null;
	var $id_user = null;

	function __construct(& $db)
	{
		parent::__construct( '#__intranet_documento_map', array('id_documento','id_user'), $db);	
	}
}
