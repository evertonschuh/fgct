<?php
/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */
 
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class EASistemasViewMailManMessages extends JView 
{
	function display($tpl = null)
	{	
				
		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');	

		$this->pagination->setAdditionalUrlParam('view','autmanmessages');
				
	
		$tipos =  $this->get( 'Tipos');
		$this->assignRef('tipos', $tipos);
	

		JToolBarHelper::title('<i class="fa fa-envelope fa-fw"></i> ' . JText::_( 'Mensagens Por Ação' ) );	
		JToolBarHelper::addNew();
		JToolBarHelper::editList();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();	
		JToolBarHelper::checkin();
		JToolBarHelper::deleteList(JText::_( 'JGLOBAL_VIEW_TOOLBAR_DELETE_QUESTION' ));		


		JSubMenuHelper::addEntry('<i class="fa fa-envelope fa-fw"></i> '. JText::_( 'Mensagens Automáticas' ), 'index.php?view=autmessages');	
		JSubMenuHelper::addEntry('<i class="fa fa-envelope fa-fw"></i> '. JText::_( 'Mensagens Por Ação' ), 'index.php?view=autmanmessages',true);	
		JSubMenuHelper::addEntry('<i class="fa fa-image fa-fw"></i> '. JText::_( 'Layouts' ), 'index.php?view=autmessagethemes');	  
        /*
		JSubMenuHelper::addEntry('<i class="fa fa-calendar fa-fw"></i> '. JText::_( 'Agenda' ), 'index.php?view=gschedules',true);	
		JSubMenuHelper::addEntry('<i class="fa fa-bullhorn fa-fw"></i> '. JText::_( 'Avisos' ), 'index.php?view=gnotifications');	
		//JSubMenuHelper::addEntry('<i class="fa fa-picture-o fa-fw"></i> '. JText::_( 'Imagens' ), 'index.php?view=mimages');
		JSubMenuHelper::addEntry('<i class="fa fa fa-pencil-square-o fa-fw"></i> '. JText::_( 'Matriculas' ), 'index.php?view=gmatriculas');	
		JSubMenuHelper::addEntry('<i class="fa fa-users fa-fw"></i> '. JText::_( 'Turmas' ), 'index.php?view=gturmas');	
		
		*/
        
        
		parent::display($tpl);

		
	}
}