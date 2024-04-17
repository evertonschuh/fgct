<?php
/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.controller');

class EASistemasControllerMailManMessages extends JController
{
	public function display($cachable = false, $urlparams = array())
	{
		JRequest::setVar('view', 'autmanmessages');	
		JRequest::setVar( 'hidemainmenu', 0 );
		parent::display();			
	}
	
	
	function add() 
	{	
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		JRequest::setVar( 'view', 'autmanmessage' );
		JRequest::setVar( 'hidemainmenu', 1 );
		parent::display();
	}      
	
	function edit() 
	{	
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		JRequest::setVar( 'view', 'autmanmessage' );
		JRequest::setVar( 'hidemainmenu', 1 );
		parent::display();
	}

	function remove() 
	{	
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		JRequest::setVar( 'view', 'autmanmessages' );
		$model = $this->getModel('autmanmessages');
		if( $model->remove_automessage() )
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_REMOVE_SUCCESS');
			$msgType = 'alert-success';
		}
		else
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_REMOVE_ERROR');
			$msgType = 'alert-danger';
		}
		$this->setRedirect(JRoute::_('index.php?view=autmanmessages', false), $msg, $msgType);
	}
	
	function publish() 
	{
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		JRequest::setVar( 'view', 'autmanmessages' );
		$model = $this->getModel('autmanmessages');
		if( $model->publish_automessage() )
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_PUBLISH_SUCCESS');
			$msgType = 'alert-success';
		}
		else
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_PUBLISH_ERROR');
			$msgType = 'alert-danger';
		}
		$this->setRedirect(JRoute::_('index.php?view=autmanmessages', false), $msg, $msgType);
	}
	
	function unpublish() 
	{	
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		JRequest::setVar( 'view', 'autmanmessages' );
		$model = $this->getModel('autmanmessages');
		if( $model->unpublish_automessage() )
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_UNPUBLISH_SUCCESS');
			$msgType = 'alert-success';
		}
		else
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_PUBLISH_ERROR');
			$msgType = 'alert-danger';
		}
		$this->setRedirect(JRoute::_('index.php?view=autmanmessages', false), $msg, $msgType);
	}

	function checkin()
	{	
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		JRequest::setVar( 'view', 'autmanmessages' );
		$model = $this->getModel('autmanmessages');
		if( $model->checkin() )
		{	
			$msg = JText::_('JGLOBAL_CONTROLLER_CHECKIN_SUCCESS');
			$msgType = 'alert-success';
		}
		else
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_CHECKIN_ERROR');
			$msgType = 'alert-danger';			
		}
		$this->setRedirect(JRoute::_('index.php?view=autmanmessages', false), $msg, $msgType);
	}

}
