<?php

/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class EASistemasControllerTreinos extends JController
{
	public function display($cachable = false, $urlparams = array())
	{
		JRequest::setVar('view', 'treinos');
		JRequest::setVar('hidemainmenu', 0);
		parent::display();
	}


	function add()
	{
		JRequest::checkToken() or jexit('JINVALID_TOKEN');
		JRequest::setVar('view', 'treino');
		JRequest::setVar('hidemainmenu', 1);
		parent::display();
	}

	function edit()
	{
		JRequest::checkToken() or jexit('JINVALID_TOKEN');
		JRequest::setVar('view', 'treino');
		JRequest::setVar('hidemainmenu', 1);
		parent::display();
	}

	function remove()
	{
		JRequest::checkToken() or jexit('JINVALID_TOKEN');
		JRequest::setVar('view', 'treinos');
		$model = $this->getModel('treinos');
		if ($model->remove()) {
			$msg = JText::_('JGLOBAL_CONTROLLER_REMOVE_SUCCESS');
			$msgType = 'success';
		} else {
			$msg = JText::_('JGLOBAL_CONTROLLER_REMOVE_ERROR');
			$msgType = 'danger';
		}
		$this->setRedirect(JRoute::_('index.php?view=treinos', false), $msg, $msgType);
	}

	function trash()
	{
		JRequest::checkToken() or jexit('JINVALID_TOKEN');
		JRequest::setVar('view', 'treinos');
		$model = $this->getModel('treinos');
		if ($model->trash()) {
			$msg = JText::_('JGLOBAL_CONTROLLER_REMOVE_SUCCESS');
			$msgType = 'success';
		} else {
			$msg = JText::_('JGLOBAL_CONTROLLER_REMOVE_ERROR');
			$msgType = 'danger';
		}
		$this->setRedirect(JRoute::_('index.php?view=treinos', false), $msg, $msgType);
	}

	function publish()
	{
		JRequest::checkToken() or jexit('JINVALID_TOKEN');
		JRequest::setVar('view', 'treinos');
		$model = $this->getModel('treinos');
		if ($model->publish()) {
			$msg = JText::_('JGLOBAL_CONTROLLER_PUBLISH_SUCCESS');
			$msgType = 'success';
		} else {
			$msg = JText::_('JGLOBAL_CONTROLLER_PUBLISH_ERROR');
			$msgType = 'danger';
		}
		$this->setRedirect(JRoute::_('index.php?view=treinos', false), $msg, $msgType);
	}

	function unpublish()
	{
		JRequest::checkToken() or jexit('JINVALID_TOKEN');
		JRequest::setVar('view', 'treinos');
		$model = $this->getModel('treinos');
		if ($model->unpublish()) {
			$msg = JText::_('JGLOBAL_CONTROLLER_UNPUBLISH_SUCCESS');
			$msgType = 'success';
		} else {
			$msg = JText::_('JGLOBAL_CONTROLLER_UNPUBLISH_ERROR');
			$msgType = 'danger';
		}
		$this->setRedirect(JRoute::_('index.php?view=treinos', false), $msg, $msgType);
	}

	function block()
	{
		JRequest::checkToken() or jexit('JINVALID_TOKEN');
		JRequest::setVar('view', 'treinos');
		$model = $this->getModel('treinos');
		if ($model->block_treino()) {
			$msg = JText::_('JGLOBAL_CONTROLLER_BLOCK_SUCCESS');
			$msgType = 'success';
		} else {
			$msg = JText::_('JGLOBAL_CONTROLLER_BLOCK_ERROR');
			$msgType = 'danger';
		}
		$this->setRedirect(JRoute::_('index.php?view=treinos', false), $msg, $msgType);
	}

	function unblock()
	{
		JRequest::checkToken() or jexit('JINVALID_TOKEN');
		JRequest::setVar('view', 'treinos');
		$model = $this->getModel('treinos');
		if ($model->unblock()) {
			$msg = JText::_('JGLOBAL_CONTROLLER_UNBLOCK_SUCCESS');
			$msgType = 'success';
		} else {
			$msg = JText::_('JGLOBAL_CONTROLLER_UNBLOCK_ERROR');
			$msgType = 'danger';
		}
		$this->setRedirect(JRoute::_('index.php?view=treinos', false), $msg, $msgType);
	}

	function checkin()
	{
		JRequest::checkToken() or jexit('JINVALID_TOKEN');
		JRequest::setVar('view', 'treinos');
		$model = $this->getModel('treinos');
		if ($model->checkin()) {
			$msg = JText::_('JGLOBAL_CONTROLLER_CHECKIN_SUCCESS');
			$msgType = 'success';
		} else {
			$msg = JText::_('JGLOBAL_CONTROLLER_CHECKIN_ERROR');
			$msgType = 'danger';
		}
		$this->setRedirect(JRoute::_('index.php?view=treinos', false), $msg, $msgType);
	}
}