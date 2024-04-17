<?php
/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */
 
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class EASistemasViewDModels extends JView 
{
	function display($tpl = null)
	{	
				
		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');		
		$this->total		= $this->get('Total');	

		$this->pagination->setAdditionalUrlParam('view','dmodels');
				
		$situacao =  $this->get( 'Situacao');
		$this->assignRef('situacao',$situacao);	

				
		JToolBarHelper::title('<span class="text-muted fw-light">CONFIGURAÇÕES / Documentos / </span>' . JText::_('Modelos') );	

			
		parent::display($tpl);

		
	}
}