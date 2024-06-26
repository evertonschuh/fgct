<?php

/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class EASistemasControllerClubes extends JController
{
	public function display($cachable = false, $urlparams = array())
	{
		
		$message = null;
		JRequest::checkPublicToken();

		$requestType = JRequest::getMethod();
		$header = 400;

		if(in_array($requestType, array('GET'))){

			$model	= $this->getModel('Clubes');

			switch ($requestType) {
				case 'GET':
					$get = JRequest::get( 'get' );
					if(isset($get['query_rest'][2]) && !empty($get['query_rest'][2]) && is_numeric($get['query_rest'][2]) ){
						if((boolean) $response = $model->getItem())
							$header = 200;
						else
							$header = 400;
					}
					else{
						if((boolean) $response = $model->getItems())
							$header = 200;
						else
							$header = 400;	
					}
				break;

				default:
					$header = 400;
				break;
			}
		}

		
		JResponse::renderJSON($response, $header, $message);
	}
}