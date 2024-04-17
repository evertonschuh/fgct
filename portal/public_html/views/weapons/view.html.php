<?php
/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */
 
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class EASistemasViewWeapons extends JView 
{
	function display($tpl = null)
	{	
				
		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');		
		$this->total		= $this->get('Total');	

		$this->pagination->setAdditionalUrlParam('view','weapons');
				
		JToolBarHelper::title('<span class="text-muted fw-light">CADASTRO / </span>  Minhas Armas ');

		parent::display($tpl);

		
	}
}