<?php

/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class EASistemasControllerEtapa extends JController
{
	public function display($cachable = false, $urlparams = array())
	{
		
		$message = null;
		JRequest::checkPublicToken();

		$requestType = JRequest::getMethod();
		$header = 400;

		if(in_array($requestType, array('GET'))){

			$model	= $this->getModel('Etapa');

			switch ($requestType) {
				case 'GET':
					if((boolean) $response = $model->getCorredor())
						$header = 200;
					else
						$header = 400;
				break;
;
				default:
					$header = 400;
				break;
			}
		}

		
		JResponse::renderJSON($response, $header, $message);
	}
}