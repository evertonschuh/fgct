<?php

/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class EASistemasControllerAuth extends JController
{
	public function display($cachable = false, $urlparams = array())
	{
		
		JRequest::checkPublicToken();
		$message = 'Erro 403 Forbidden';
		$header = 403;
		$response = null;
				
		$requestType = JRequest::getMethod();
		if(in_array($requestType, array('PUT','POST'))){

			$response = new stdClass();
			$response->status = 'error';
			$response->message = null;
			$response->data = null;
			$header = 200;

			$model	= $this->getModel('Auth');
			switch ($requestType) {
				case 'POST':
					if((boolean) $data = $model->getAuth()){
						$response->data =$data;
						$response->status = 'success';
						$message = null;
					}
					else
						$response->message = 'E-mail ou Senha informados não estão corretos.';
				break;
				case 'PUT':
					if((boolean) $data = $model->resetPassword()){
						$response->data =$data;
						$response->status = 'success';
						$message = null;
					}
					else
						$response->message = 'Erro ao tentar salvar o password.';
				break;
				default:
					$header = 400;
				break;
			}
		}

		
		JResponse::renderJSON($response, $header, $message);
	}
}