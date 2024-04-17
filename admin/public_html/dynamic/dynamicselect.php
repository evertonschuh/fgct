<?php

include( 'joomla.inc.php' );

$obj = new InejeDynamicSelect();

class InejeDynamicSelect {
	
	var $_db = null;
	var $_data = null;
	
	function __construct()
	{	
	
		$lang = JFactory::getLanguage();
		$extension = 'tpl_system';
		$base_dir = JPATH_SITE;
		//$language_tag = 'pt-BR';
		$reload = true;
		$lang->load($extension, $base_dir, $language_tag, $reload);

		
		$id_estado = JRequest::getVar( 'id_estado', '', 'post' );
		
		$estado_buscacep = JRequest::getVar( 'estado_buscacep', '', 'post' );
		
		if ( !empty( $id_estado ) )
		{
			$this->_db	= JFactory::getDBO();
			$query = $this->_db->getQuery(true);		
			$query->select($this->_db->quoteName('id_cidade') .' as value, '. $this->_db->quoteName('name_cidade') . ' as text');
			$query->from($this->_db->quoteName('#__intranet_cidade'));
			$query->where($this->_db->quoteName('status_cidade') . ' = 1');
			$query->where($this->_db->quoteName('id_estado') . ' = '. $this->_db->quote( $id_estado ));
			$query->order($this->_db->quoteName('ordering'));
			$this->_db->setQuery($query);
			$rows = $this->_db->loadObjectList();
			echo '<option disabled selected class="default" value="">' . JText::_('INTRANET_GLOBAL_CIDADE_SELECT') . '</option>';
			echo JHTML::_('select.options',  $rows, 'value', 'text', ''); 
		}
		
		
		if ( !empty( $estado_buscacep ) )
		{
			$this->_db	= JFactory::getDBO();
			$query = $this->_db->getQuery(true);		
			$query->select($this->_db->quoteName('id_cidade') .' as value, '. $this->_db->quoteName('name_cidade') . ' as text');
			$query->from($this->_db->quoteName('#__intranet_cidade'));
			$query->where($this->_db->quoteName('status_cidade') . ' = 1');
			$query->where($this->_db->quoteName('id_estado') . ' = '. $this->_db->quote( $estado_buscacep ));
			$query->order($this->_db->quoteName('ordering'));
			$this->_db->setQuery($query);
			$rows = $this->_db->loadObjectList();
			echo '<option disabled selected class="default" value="">' . JText::_('INTRANET_GLOBAL_CIDADE_SELECT') . '</option>';
			echo JHTML::_('select.options',  $rows, 'value', 'text', ''); 
		}
		
	}
}

