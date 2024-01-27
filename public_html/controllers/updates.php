<?php
/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.controller');

class EASistemasControllerUpdates extends JController
{

	public function display($cachable = false, $urlparams = array())
	{
		JRequest::setVar('view', 'updates');	
		JRequest::setVar( 'hidemainmenu', 0 );
		parent::display();			
	}
	
	function edit() 
	{	
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		JRequest::setVar( 'view', 'update' );
		JRequest::setVar( 'hidemainmenu', 1 );
		parent::display();
	}

	function remove() 
	{	
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		JRequest::setVar( 'view', 'updates' );
		$model = $this->getModel('updates');
		if( $model->remove() )
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_REMOVE_SUCCESS');
			$msgType = 'alert-success';
		}
		else
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_REMOVE_ERROR');
			$msgType = 'alert-danger';
		}
		$this->setRedirect(JRoute::_('index.php?view=updates', false), $msg, $msgType);
	}

	function checkin()
	{	
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		JRequest::setVar( 'view', 'updates' );
		$model = $this->getModel('updates');
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
		$this->setRedirect(JRoute::_('index.php?view=updates', false), $msg, $msgType);
	}
	
	
	
	
	
	
	
	
}
