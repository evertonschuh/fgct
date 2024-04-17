<?php

/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class EASistemasControllerAvatar extends JController
{
	public function display($cachable = false, $urlparams = array())
	{
		
		$message = null;
		//JRequest::checkPublicToken();
		//$file = JRequest::getVar( 'file', '', 'files', 'array' );

		$requestType = JRequest::getMethod();
		$header = 400;

		if(in_array($requestType, array('GET', 'PUT','POST','DELETE'))){
			
			$model	= $this->getModel('Avatar');
			
			switch ($requestType) {
				case 'POST':
					if((boolean) $response = $model->updateItem())
						$header = 200;
					else
						$header = 400;
				break;
				case 'DELETE':
					if((boolean) $response = $model->deleteItem())
						$header = 200;
					else
						$header = 400;
				break;
				default:
					$header = 400;
				break;
			}
		}

		JResponse::renderJSON($response, $header, $message);
	}
}


