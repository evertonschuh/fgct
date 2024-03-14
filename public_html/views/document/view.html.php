<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class EASistemasViewDocument extends JView 
{
	public function display($tpl = null)
	{	
		//// Set the toolbar
		$document = JFactory::getDocument();

		$this->item = $this->get('Item');
		$document->setTitle($this->item->name_documento);
		parent::display($tpl);


	}

}

