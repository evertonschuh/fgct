<?php
/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */

defined( '_JEXEC' ) or die; 

jimport('joomla.application.component.controller');

class EASistemasController extends JController
{
	
	public function display($cachable = false, $urlparams = array())
	{

		JRequest::setVar( 'view', 'home' );	
		JRequest::setVar( 'controller', 'home' );	
		parent::display();
	}

}
