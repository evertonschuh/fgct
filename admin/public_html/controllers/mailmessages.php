<?php
/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.controller');

class EASistemasControllerMailMessages extends JController
{
	public function display($cachable = false, $urlparams = array())
	{
		JRequest::setVar('view', 'mailmessages');	
		JRequest::setVar( 'hidemainmenu', 0 );
		parent::display();			
	}

	function add() 
	{	
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		JRequest::setVar( 'view', 'mailmessages' );
		JRequest::setVar( 'hidemainmenu', 1 );
		parent::display();
	}      
	
	function edit() 
	{	
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		JRequest::setVar( 'view', 'mailmessages' );
		JRequest::setVar( 'hidemainmenu', 1 );
		parent::display();
	}

	
	
	function remove()
	{
		JRequest::checkToken() or jexit('JINVALID_TOKEN');
		JRequest::setVar('view', 'mailmessages');
		$model = $this->getModel('mailmessages');
		if ($model->remove()) {
			$msg = JText::_('JGLOBAL_CONTROLLER_REMOVE_SUCCESS');
			$msgType = 'success';
		} else {
			$msg = JText::_('JGLOBAL_CONTROLLER_REMOVE_ERROR');
			$msgType = 'danger';
		}
		$this->setRedirect(JRoute::_('index.php?view=mailmessages', false), $msg, $msgType);
	}

	function trash()
	{
		JRequest::checkToken() or jexit('JINVALID_TOKEN');
		JRequest::setVar('view', 'mailmessages');
		$model = $this->getModel('mailmessages');
		if ($model->trash()) {
			$msg = JText::_('JGLOBAL_CONTROLLER_TRASH_SUCCESS');
			$msgType = 'success';
		} else {
			$msg = JText::_('JGLOBAL_CONTROLLER_REMOVE_ERROR');
			$msgType = 'danger';
		}
		$this->setRedirect(JRoute::_('index.php?view=mailmessages', false), $msg, $msgType);
	}

	function untrash()
	{
		JRequest::checkToken() or jexit('JINVALID_TOKEN');
		JRequest::setVar('view', 'mailmessages');
		$model = $this->getModel('mailmessages');
		if ($model->unpublish()) {
			$msg = JText::_('JGLOBAL_CONTROLLER_UNTRASH_SUCCESS');
			$msgType = 'success';
		} else {
			$msg = JText::_('JGLOBAL_CONTROLLER_UNTRASH_ERROR');
			$msgType = 'danger';
		}
		$this->setRedirect(JRoute::_('index.php?view=mailmessages', false), $msg, $msgType);
	}

	function publish()
	{
		JRequest::checkToken() or jexit('JINVALID_TOKEN');
		JRequest::setVar('view', 'mailmessages');
		$model = $this->getModel('mailmessages');
		if ($model->publish()) {
			$msg = JText::_('JGLOBAL_CONTROLLER_PUBLISH_SUCCESS');
			$msgType = 'success';
		} else {
			$msg = JText::_('JGLOBAL_CONTROLLER_PUBLISH_ERROR');
			$msgType = 'danger';
		}
		$this->setRedirect(JRoute::_('index.php?view=mailmessages', false), $msg, $msgType);
	}

	function unpublish()
	{
		JRequest::checkToken() or jexit('JINVALID_TOKEN');
		JRequest::setVar('view', 'mailmessages');
		$model = $this->getModel('mailmessages');
		if ($model->unpublish()) {
			$msg = JText::_('JGLOBAL_CONTROLLER_UNPUBLISH_SUCCESS');
			$msgType = 'success';
		} else {
			$msg = JText::_('JGLOBAL_CONTROLLER_UNPUBLISH_ERROR');
			$msgType = 'danger';
		}
		$this->setRedirect(JRoute::_('index.php?view=mailmessages', false), $msg, $msgType);
	}

}
