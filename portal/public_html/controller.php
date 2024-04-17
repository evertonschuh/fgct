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

		$user	= JFactory::getUser();
		if ( $user->get('guest'))
			JRequest::setVar( 'view', JRequest::getCmd('view','login') );	
		else
			JRequest::setVar( 'view', JRequest::getCmd('view','dashboard') );	

		parent::display();
	}

}
