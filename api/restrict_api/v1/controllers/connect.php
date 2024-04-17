<?php

/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');



class EASistemasControllerConnect extends JController
{
	public function display($cachable = false, $urlparams = array())
	{
		$message = null;
		JRequest::checkPublicToken();
		
		$model	= $this->getModel('Connect');

		if((boolean) $response = $model->getConnect())
			$header = 200;
		else
			$header = 400;
		JResponse::renderJSON($response, $header, $message);
	}
}
