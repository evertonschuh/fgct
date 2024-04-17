<?php

include( 'joomla.inc.php' );

$obj = new EASistemasDynamicSelect();

class EASistemasDynamicSelect {
	
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


		$execute = JRequest::getVar( 'execute', '', 'post' );

		switch($execute) {
			case 'getSignatures':
				$this->getSignatures();
			break;
			default:


			$id_estado = JRequest::getVar( 'id_estado', '', 'post' );
			$id_corredor_categoria = JRequest::getVar( 'id_corredor_categoria', '', 'post' );
			if ( !empty( $id_estado ) )
			{
				$query = $this->_db->getQuery(true);		
				$query->select($this->_db->quoteName('id_cidade') .' as value, '. $this->_db->quoteName('name_cidade') . ' as text');
				$query->from($this->_db->quoteName('#__intranet_cidade'));
				$query->where($this->_db->quoteName('status_cidade') . ' = 1');
				$query->where($this->_db->quoteName('id_estado') . ' = '. $this->_db->quote( $id_estado ));
				$query->order($this->_db->quoteName('ordering'));
				$this->_db->setQuery($query);
				$rows = $this->_db->loadObjectList();
				echo '<option disabled selected class="default" value="">' . JText::_('- Cidades -') . '</option>';
				echo JHTML::_('select.options',  $rows, 'value', 'text', ''); 
			}
	
			if ( !empty( $id_corredor_categoria ) )
			{
	
				$rows = array();
				$arrayTest = array();
				if(!empty($id_corredor_categoria)) {
	
					$query = $this->_db->getQuery(true);
					$query->select('mes_treino');
					$query->from('#__treino');
					$query->where($this->_db->quoteName('id_corredor_categoria') . '=' . $this->_db->quote($id_corredor_categoria));
					$this->_db->setQuery($query);
					$mesesDisabled = $this->_db->loadColumn();
		
					$mesInicio = JFactory::getDate('now - 2 month', $this->_siteOffset )->toFormat('%Y-%m', true) . '-01';
					for($x = 1; $x <= 4; $x++){
						$disabled = '';
						$value = JFactory::getDate($mesInicio . ' + '. $x .' month', $this->_siteOffset )->toFormat('%Y-%m-%d', true);
						$text = JFactory::getDate($mesInicio . ' + '. $x .' month', $this->_siteOffset )->toFormat('%m/%Y', true);
						if(in_array($value, $mesesDisabled)){
							$disabled  = ' disabled="disabled"';
							$text .= ' (Treino Existente)';
						}	
						$rows[] = JHTML::_('select.option', $value, $text, 'value', 'text', $disabled );
					}
				}
				echo '<option disabled selected class="default" value="">' . JText::_('- Mês do Treino -') . '</option>';
				echo JHTML::_('select.options',  $rows, 'value', 'text', ''); 
			}
			

			break;
		}

	}


	function getSignatures() {

		$query = $this->_db->getQuery(true);		
		$query->select($this->_db->quoteName('id_signature') .' as value, '. $this->_db->quoteName('name_signature') . ' as text');
		$query->from($this->_db->quoteName('#__intranet_signature'));
		$query->where($this->_db->quoteName('status_signature') . ' = 1');
		$query->order($this->_db->quoteName('text'));
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();

		if($rows)
			echo json_encode($rows); 
		else
		
			echo json_encode(array()); 
		//echo '<option disabled selected class="default" value="">' . JText::_('- Assinaturas -') . '</option>';
		//echo JHTML::_('select.options',  $rows, 'value', 'text', ''); 

	}


}

