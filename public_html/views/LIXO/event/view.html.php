<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

include_once(JPATH_SITE . DS . 'views' . DS . 'event' . DS . 'language.scrips.php');

class EASistemasViewEvent extends JView 
{
	public function display($tpl = null)
	{	
		// Set the toolbar
		$this->addToolBar();

		$token = md5(uniqid(''));

        $document 	=  JFactory::getDocument();	
		$document->addStyleSheet( 'views/system/css/jquery.bootstrap-touchspin.css' );
		$document->addScript( 'views/system/js/jquery.bootstrap-touchspin.js' );
		
		$document->addScript('views/system/js/moment-with-locales.js');
		$document->addScript('views/system/js/bootstrap-datetimepicker.js');
		$document->addstylesheet('views/system/css/bootstrap-datetimepicker.css');

		$document->addStyleSheet('views/system/css/select2.css');
		$document->addScript('views/system/js/select2.js');
		
		$document->addScript( 'views/system/js/event.js?ver=' . $token);
			
		$this->item = $this->get('Item');
		$this->util = $this->get('Util');
		$this->clients = $this->get('Clients');

		if( empty($this->item->id_event) )
			JToolBarHelper::title( JText::_( 'Novo Evento' ), '<i class="fas fa-tasksfa-fw"></i>' );
		else
			JToolBarHelper::title( JText::_( 'Editar Evento' ), '<i class="fas fa-tasks fa-fw"></i>' );
		// Display the template
		parent::display($tpl);	
	}

	protected function addToolBar() 
	{
		JToolBarHelper::apply();	
		JToolBarHelper::divider();	
		JToolBarHelper::save();
		JToolBarHelper::divider();										   
		JToolBarHelper::cancel();														   														   
	}
}

