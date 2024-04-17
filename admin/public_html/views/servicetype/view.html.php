<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

include_once (JPATH_BASE.DS.'views'.DS.'ServiceType'.DS.'language.scrips.php');

class EASistemasViewServiceType extends JView 
{
	public function display($tpl = null)
	{	

		$document = JFactory::getDocument();
		
		$document->addStyleSheet('/assets/vendor/libs/select2/select2.css');
		$document->addScript('/assets/vendor/libs/select2/select2.js');

		$document->addScript( '/assets/js-custom/servicetype.js' );

		$this->item	= $this->get('Item');
		$this->documentos = $this->get('Documentos');		

		if( empty($this->item->id_service_type) )
			JToolBarHelper::title('<span class="text-muted fw-light">CONFIGURAÇÕES / Serviços / </span>' . JText::_('Novo Tipo') );	
		else
			JToolBarHelper::title('<span class="text-muted fw-light">CONFIGURAÇÕES / Serviços / </span>' . JText::_('Editar Tipo') );	
		
		
			// Display the template*/
		//JToolBarHelper::apply();	
		//JToolBarHelper::divider();	
		//JToolBarHelper::save();
		//JToolBarHelper::divider();	
		//if( !empty($this->item->id_mailmessage_theme) )
		//	JToolBarHelper::modal('sendcobranca', 'fa-envelope-o', 'Enviar Teste');
											   
		//JToolBarHelper::cancel();														   
		
		parent::display($tpl);													   
	}
}

