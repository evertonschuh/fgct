<?php

/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.model');

class EASistemasModelDashboard extends JModel
{

	var $_db = null;
	var $_app = null;
	var $_siteOffset = null;
	var $_user = null;

	function __construct()
	{
		parent::__construct();
		$this->_db	= JFactory::getDBO();
		$this->_app = JFactory::getApplication();
		$this->_siteOffset = $this->_app->getCfg('offset');
		$this->_user = JFactory::getUser();
	}

	function getApp()
	{
		return $this->_app;
	}

	function getCorredors()
	{
		$query = $this->_db->getQuery(true);
		$query->select('COUNT(id_associado)');
		$query->from($this->_db->quoteName('#__intranet_associado'));
		//$query->innerJoin($this->_db->quoteName('#__id_corredor') . ' ON(' . $this->_db->quoteName('id') . '=' . $this->_db->quoteName('id_user') . ')');	
		//$query->where( $this->_db->quoteName('id') . '=' . $this->_db->quote( $this->_user->get('id') ) );
		//$query->where( $this->_db->quoteName('block') . '=' . $this->_db->quote( '0' ) );
		//$query->where( $this->_db->quoteName('tipo') . '=' . $this->_db->quote( '0' ) );

		$this->_db->setQuery($query);
		return $this->_db->loadResult();
	}
	function getAniversariantes()
	{
		/*
		$query = $this->_db->getQuery(true);
		$query->select($this->_db->quoteName(array(
			'name_corredor',
			'id_corredor',
			'email_corredor',
			'data_nascimento_corredor'
		)));
		$query->from($this->_db->quoteName('#__corredor'));
		$query->where('DATE(CONCAT(DATE_FORMAT(CURRENT_DATE,\'%Y\'),DATE_FORMAT(data_nascimento_corredor,\'-%m-%d\'))) <= DATE_ADD(CURRENT_DATE,INTERVAL 1 MONTH)');
		$query->where('DATE(CONCAT(DATE_FORMAT(CURRENT_DATE,\'%Y\'),DATE_FORMAT(data_nascimento_corredor,\'-%m-%d\'))) >= CURRENT_DATE');
		$query->where($this->_db->quoteName('status_corredor') . '=' . $this->_db->quote('1'));

		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();*/
	}
}