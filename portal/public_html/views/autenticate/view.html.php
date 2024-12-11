<?php
/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */
 
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class EASistemasViewAutenticate extends JViewLegacy
{
	function display($tpl = null)
	{	
		
		JText::script('EASISTEMAS_SCRIPT_VALIDATE_ERROR_INPUT_REQUIRED');
		$document = JFactory::getDocument();
		$document->addstylesheet( 'assets/css-custom/autenticate.css' );
		$this->result = $this->get( 'AutenticateCode');
		if($this->result !== false)
			$this->item = $this->get('Item');

		parent::display( $tpl);
		
	}
}