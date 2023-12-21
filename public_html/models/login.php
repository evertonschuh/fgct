<?php

defined('_JEXEC') or die;

jimport('joomla.application.component.model');


class EASistemasModelLogin extends JModel
{

	var $_db = null;
	var $_trataImagem = null;
	var $_session = null;
	var $_params = null;

	function __construct()
	{
		parent::__construct();
		$this->_db	= JFactory::getDBO();
	}

	function getLogin()
	{
		$mainframe = JFactory::getApplication();

		$username = JRequest::getVar('username', '', 'post');
		$password = JRequest::getVar('password', '', 'post');

		/*
		if ($mainframe->isAdmin()) {
			return;
		}
	*/
		$user = JFactory::getUser();
		if (!$user->get('gid')) {

			$options = array();
			$options['silent'] = true;
			$options['forecelogon'] = true;
			$credentials['username'] = $username;
			$credentials['password'] = $password;

			if ($mainframe->login($credentials, $options)) {
				return true;
			} else {
				return false;
			}
		}
	}
	function testParceiro()
	{
		//if($this->testParceiro());	


		return false;
	}
}