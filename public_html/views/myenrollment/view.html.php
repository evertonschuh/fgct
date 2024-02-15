<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class EASistemasViewMyEnrollment extends JView 
{
	public function display($tpl = null)
	{	
		//// Set the toolbar
	
		$this->item = $this->get('Item');

		parent::display($tpl);


	}

}

