<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

include_once (JPATH_BASE.DS.'views'.DS.'membershipcard'.DS.'language.scrips.php');

class EASistemasViewMembershipCard extends JView
{
	public function display($tpl = null)
	{
		$document = JFactory::getDocument();
		$document->addStyleSheet('/assets/vendor/libs/select2/select2.css');
		$document->addScript('/assets/vendor/libs/select2/select2.js');

		$document->addScript('/assets/vendor/libs/spectrum/spectrum.js');
		$document->addStyleSheet('/assets/vendor/libs/spectrum/spectrum.css');

		$document->addScript('/assets/js-custom/membershipcard.js');
		//// Set the toolbar
		$this->item = $this->get('Item');
		$this->anuidades = $this->get('Anuidades');
		$this->associadoTipos = $this->get('AssociadoTipos');
		
		if( empty($this->item->id_carteira_digital) )
			JToolBarHelper::title('<span class="text-muted fw-light">CONFIGURAÇÕES / Carteira Digital /</span> Novo Modelo Cartira Digital' );	
		else
			JToolBarHelper::title('<span class="text-muted fw-light">CONFIGURAÇÕES / Carteira Digital /</span> Editar Modelo Cartira Digital' );	

		parent::display($tpl);
	}
}