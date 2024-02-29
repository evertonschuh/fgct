<?php
/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.controller');


class EASistemasControllerRemember extends JController
{
	public function display($cachable = false, $urlparams = array())
	{	
	
		$user = JFactory::getUser();
		if ( $user->get('guest'))
		{
			$layout	= JFactory::getApplication()->input->get('layout');
			switch($layout)
			{
				case 'complete':
				case 'reset':
					$model = $this->getModel('remember');
					if ( !$model->confirmCod() ) {
						$this->setRedirect(JRoute::_('index.php?view=remember', false));
					
					}
				break;
				case 'result':
					$model = $this->getModel('remember');
					if ( !$model->getMail() ) {
						$this->setRedirect(JRoute::_('index.php?view=remember&layout=rememail', false));
					
					}
				break;				
			}
			parent::display();

		}
		else
		{
			$this->setRedirect(JRoute::_('index.php?view=dashboard', false));
			return;				
		}
	}
	
	function remember()
	{
		JSession::checkToken('post') or jexit(JText::_('JINVALID_TOKEN'));
		$model = $this->getModel('remember');
		//$model->setData();
		//if ($model->testCaptcha()) {
			$userinfo = $model->rememberUser();
			if($userinfo === false) {
				$msg = JText::_('Não encontramos nenhum usuário com este nome de acesso.');	
				$this->setRedirect(JRoute::_('index.php?view=remember', false), $msg, 'danger');				
			}
			elseif($userinfo->block != '0') {
				$msg = JText::_('Parece qie existe um bloqueio para este nome de usuário. Entre en contato com a FGCT');	
				$this->setRedirect(JRoute::_('index.php?view=remember', false), $msg, 'danger');
			}
			else {
				if ($model->sendMail()) {
				
					$msg = JText::_('Enviamos uma mensagem para seu endereço de e-mail, contendo um codigo que permitirá resetar sua senha.');	
					$this->setRedirect(JRoute::_('index.php?view=remember&layout=confirm', false), $msg, 'info');
				}
				else {
					$msg = JText::_('Ocorreu um erro ao tentar enviar a mensagem com o código.');	
					$this->setRedirect(JRoute::_('index.php?view=remember', false), $msg, 'danger');
				}	
			}
	//	}
	//	else {
	//		$msg = JText::_('Você deve ser aprovado no teste Captcha para prosseguir');	
	//		$this->setRedirect(JRoute::_('index.php?view=remember', false), $msg, 'danger');
	//	}
	}
	
	function confirm()
	{
		JSession::checkToken('post') or jexit(JText::_('JINVALID_TOKEN'));
		$model = $this->getModel('remember');
		$model->setData();
		$userinfo = $model->rememberMail();
		if($userinfo === false) {
			$msg = JText::_('OEMPREGO_CONTROLLER_REMEMBER_EMAL_ERROR');	
			$this->setRedirect(JRoute::_('index.php?view=remember&layout=confirm', false), $msg, 'alert-danger');				
		}
		elseif($userinfo->block != '0') {
			$msg = JText::_('OEMPREGO_CONTROLLER_REMEMBER_BLOCK_ERROR');	
			$this->setRedirect(JRoute::_('index.php?view=remember&layout=confirm', false), $msg, 'alert-danger');
		}
		else {
			if ($model->confirmCod()) {
				$msg = JText::_('OEMPREGO_CONTROLLER_REMEMBER_COD');	
				$this->setRedirect(JRoute::_('index.php?view=remember&layout=reset', false), $msg, 'alert-info' );
			}
			else {
				$msg = JText::_('OEMPREGO_CONTROLLER_REMEMBER_COD_ERROR');	
				$this->setRedirect(JRoute::_('index.php?view=remember&layout=confirm', false), $msg, 'alert-danger');
			}	
		}
	}
	
	function resetpass()
	{
		JSession::checkToken('post') or jexit(JText::_('JINVALID_TOKEN'));
		$model = $this->getModel('remember');
		if ($model->resetPassword()) {
			$msg = JText::_('OEMPREGO_CONTROLLER_REMEMBER_RESET_COMPLET');	
			$this->setRedirect(JRoute::_('index.php?view=login', false), $msg, 'alert-success');
		}
		else {
			$msg = JText::_('OEMPREGO_CONTROLLER_REMEMBER_RESET_ERROR');	
			$this->setRedirect(JRoute::_('index.php?view=remember', false), $msg, 'alert-danger');
		}	

	}
	
	
	function activator()
	{
		JSession::checkToken('post') or jexit(JText::_('JINVALID_TOKEN'));
		$model = $this->getModel('remember');
		$model->setData();
		if ($model->testCaptcha()) {
			if ($model->activatorMail()) {
				$msg = JText::_('OEMPREGO_CONTROLLER_REMEMBER_ACTIVATOR');	
				$this->setRedirect(JRoute::_('index.php?view=remember&layout=activator', false), $msg, 'alert-success');
			}
			else {
				$msg = JText::_('OEMPREGO_CONTROLLER_REMEMBER_ACTIVATOR_ERROR');	
				$this->setRedirect(JRoute::_('index.php?view=remember&layout=activator', false), $msg, 'alert-danger');
			}	
		}
		else {
			$msg = JText::_('OEMPREGO_CONTROLLER_REMEMBER_CAPTCHA_ERROR');	
			$this->setRedirect(JRoute::_('index.php?view=remember&layout=activator', false), $msg, 'alert-danger');
		}



	}
	

	function login()
	{

		JSession::checkToken('post') or jexit(JText::_('JINVALID_TOKEN'));

		$model = $this->getModel('login');		

		$urlReturn = JRequest::getVar('return', '', 'GET', 'base64');
		
		if ( empty( $urlReturn ) )
		{
			$urlReturn = JRequest::getVar('return', '', 'POST', 'base64');
		}
		
		if( $model->getLogin() )
		{
			//$msg = JText::_('Login Confirmado');	
			if ( !empty( $urlReturn ) )
			{
				
				$urlReturn = base64_decode($urlReturn);
				$this->setRedirect(JRoute::_($urlReturn, false));
			}
			else
			{
				$this->setRedirect(JRoute::_('index.php', false));
			}				
		}
		else
		{
			$link = '';
			if ( !empty( $urlReturn ) )
			{
				$link = '&return=' . $urlReturn;
			}
			$msg = JText::_('OEMPREGO_CONTROLLER_LOGIN_ERROR');	
			$this->setRedirect(JRoute::_('index.php?view=login' . $link, false), $msg, 'alert-danger');
		}	
		

	}	
	
	function rememail()
	{
		JSession::checkToken('post') or jexit(JText::_('JINVALID_TOKEN'));
		$model = $this->getModel('remember');
		$model->setData();
		if ($model->testCaptcha()) {
			if ($model->getMail()) {
			
				$msg = JText::_('OEMPREGO_CONTROLLER_REMEMBER_GETMAIL');	
				$this->setRedirect(JRoute::_('index.php?view=remember&layout=result', false), $msg, 'alert-info');
			}
			else {
				$msg = JText::_('OEMPREGO_CONTROLLER_REMEMBER_GETMAIL_ERROR');	
				$this->setRedirect(JRoute::_('index.php?view=remember&layout=rememail', false), $msg, 'alert-danger');
			}	
		}
		else {
			$msg = JText::_('OEMPREGO_CONTROLLER_REMEMBER_CAPTCHA_ERROR');	
			$this->setRedirect(JRoute::_('index.php?view=remember', false), $msg, 'alert-danger');
		}
	}
}
