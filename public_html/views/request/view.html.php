<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

include_once (JPATH_SITE.DS.'views'.DS.'request'.DS.'language.scrips.php');

class EASistemasViewRequest extends JView 
{
	public function display($tpl = null)
	{	

		$document 			=  JFactory::getDocument();
/*
		$document->addStyleSheet('/assets/vendor/libs/select2/select2.css');
		$document->addScript('/assets/vendor/libs/select2/select2.js');

		$document->addStyleSheet('/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css');
		$document->addScript('/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js');
		$document->addScript('/assets/vendor/libs/bootstrap-datepicker/locales/bootstrap-datepicker.pt-BR.js');
		$document->addStyleSheet('/assets/vendor/libs/bootstrap-select/bootstrap-select.css');
		$document->addStyleSheet('/assets/vendor/libs/bootstrap-select/bootstrap-select.js');

		$document->addScript('/assets/js/jquery.mask.js');
		$document->addScript('/assets/js-custom/weapon.js');
	


		$document->addScriptDeclaration('window.Helpers.initCustomOptionCheck();');
		*/
		
		$this->item = $this->get('Item');

		
		$this->services = $this->get('Services');


		if( empty($this->id_service) )
			JToolBarHelper::title('<span class="text-muted fw-light">PEDIDOS / Acompanhar Pedidos / </span> Novo Pedido');
		else
			JToolBarHelper::title('<span class="text-muted fw-light">PEDIDOS / Acompanhar Pedidos / </span> Pedido - ' . $this->id_service);


		parent::display($tpl);	
		
		
	}

}

