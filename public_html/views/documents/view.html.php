<?php
/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */
 
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class EASistemasViewDocuments extends JView 
{
	function display($tpl = null)
	{	
				
		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');		
		$this->total		= $this->get('Total');	

		$this->pagination->setAdditionalUrlParam('view','docassociados');
				
		$situacao =  $this->get( 'Situacao');
		$this->assignRef('situacao',$situacao);	
				
		$idSession =  $this->get( 'Session');
		$this->assignRef('idSession',	$idSession);			

		JToolBarHelper::title('<i class="fa fa-file-text fa-fw"></i> ' . JText::_( 'Documentos' ) );
		//JToolBarHelper::addNew();
		//JToolBarHelper::editList();
		//JToolBarHelper::publishList();
		//JToolBarHelper::unpublishList();	
		//JToolBarHelper::checkin();
		JToolBarHelper::deleteList(JText::_( 'JGLOBAL_VIEW_TOOLBAR_DELETE_QUESTION' ));		

		//JSubMenuHelper::addEntry('<i class="fa fa-crosshairs fa-fw"></i> '. JText::_( 'Armas' ), 'index.php?view=protocolos',true);	
		//JSubMenuHelper::addEntry('<i class="fa fa-building-o fa-fw"></i> '. JText::_( 'Pessoa Jurídica' ), 'index.php?view=pjs');
	
		
			
		parent::display($tpl);

		
	}
}