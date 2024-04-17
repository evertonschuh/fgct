<?php

include( 'joomla.inc.php' );

$obj = new EASistemasDynamicCheck();

class EASistemasDynamicCheck {
	
	var $_db = null;
	var $_app = null;
	var $_user = null;
	var $_siteOffset = null;

	function __construct()
	{	
	
		$lang = JFactory::getLanguage();
		$extension = 'tpl_system';
		$base_dir = JPATH_SITE;
		$language_tag = 'pt-BR';
		$reload = true;
		$lang->load($extension, $base_dir, $language_tag, $reload);
        $this->_db	= JFactory::getDBO();
		$this->_app = JFactory::getApplication();	
		$this->_user = JFactory::getUser();
		$this->_siteOffset = $this->_app->getCfg('offset');


		$value = JRequest::getVar('value', '', 'post' );
		$execute = JRequest::getVar( 'execute', '', 'post' );
		if(!empty($value))
			$value = json_decode($value);
		else
			return false;

		switch($execute){
			case 'check-cpf':
				if($this->checkCPF($value)){
					echo 'ok';
					return;
				}
					
			break;
		}
		echo 'error';
		return;
	}


	function checkCPF(stdClass $value){
	
		$query = $this->_db->getQuery(true);
		$query->select($this->_db->quoteName('id_pf'));
		$query->from($this->_db->quoteName('#__pf'));
		$query->innerJoin($this->_db->quoteName('#__associado').' USING ('.$this->_db->quoteName('id_pf').')');
		$query->where($this->_db->quoteName('cpf_pf') . '=' . $this->_db->quote($value->cpf_pf));
		if($value->id_associado)
			$query->where($this->_db->quoteName('id_associado') . '<>' . $this->_db->quote($value->id_associado));
		$this->_db->setQuery($query);
		return !(boolean) $this->_db->loadResult();
	}
}

