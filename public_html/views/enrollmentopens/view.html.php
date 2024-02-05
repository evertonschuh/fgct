<?php
/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */
 
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class EASistemasViewEnrollmentOpens extends JView 
{
	function display($tpl = null)
	{	
				
		$document = JFactory::getDocument();
		$document->addStyleSheet('/assets/vendor/libs/bs-stepper/bs-stepper.css');
		$document->addScript('/assets/vendor/libs/bs-stepper/bs-stepper.js');
		//$document->addScript('/assets/vendor/libs/cleavejs/cleave.js');
		//$document->addScript('/assets/vendor/libs/cleavejs/cleave-phone.js');

		//$document->addScript('/assets/vendor/libs/stepper/jquery.bootstrap.wizard.js');
		$document->addScript('/assets/vendor/libs/validate/jquery.validate.min.js');
		$document->addScript('/assets/vendor/libs/validate/jquery.validate.pt-br.js');
		//$document->addScript('/assets/vendor/libs/stepper/paper-bootstrap-wizard.js');



		$document->addScript('/assets/js-custom/enrollmentopens.js');
		//$document->addScriptDeclaration('
		//jQuery("#wizard-create-app").steps("remove", 2);
		//');
		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');		
		$this->total		= $this->get('Total');	

		$this->pagination->setAdditionalUrlParam('view','enrollmentopens');
				
		JToolBarHelper::title('<span class="text-muted fw-light">MENU ESPORTIVO / Inscrições</span> / Inscrições Abertas ');

		parent::display($tpl);

		
	}
}