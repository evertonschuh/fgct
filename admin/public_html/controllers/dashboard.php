<?php

/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2020, All rights reserved. 
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class EASistemasControllerDashboard extends JController
{
	public function display($cachable = false, $urlparams = array())
	{
		$user	= JFactory::getUser();
		if ($user->get('guest')) {
			$uri			= 	JFactory::getURI();
			$redirectUrl	=	$uri->toString();
			$redirectUrl 	=	urlencode(base64_encode($redirectUrl));
			$msg = JText::_('É necessário estar logado para acessar está página!');
			$this->setRedirect(JRoute::_('index.php?view=login&return=' . $redirectUrl, false), $msg, 'danger');
			return;
		}
		parent::display();
	}
}