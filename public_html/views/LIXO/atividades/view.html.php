<?php

/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class EASistemasViewAtividades extends JView
{
	function display($tpl = null)
	{

        $document 	=  JFactory::getDocument();	
				
		JText::script('EASISTEMAS_SCRIPT_VALIDATE_ERROR_INPUT_CPF');
		JText::script('EASISTEMAS_SCRIPT_VALIDATE_ERROR_INPUT_REQUIRED');

		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->total		= $this->get('Total');
		

		$this->pagination->setAdditionalUrlParam('view', 'atividades');

		JToolBarHelper::title('<span class="text-muted fw-light">CADASTRO /</span> ' . JText::_('Atividades de Corrida'));

		parent::display($tpl);
	}
}