<?php 

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class EASistemasControllerMailManMessage extends JController {

	public function display($cachable = false, $urlparams = array()) 
	{
		JRequest::setVar( 'hidemainmenu', 1 );
		parent::display(); 
	}

	function save()
	{
		JRequest::checkToken() or jexit('JINVALID_TOKEN');
		$model = $this->getModel('autmanmessage');
		if ($model->store()) 
		{
			JRequest::setVar( 'hidemainmenu', 0 );
			$msg = JText::_('JGLOBAL_CONTROLLER_SAVE_SUCCESS');
			$msgType = 'alert-success';
			$view = 'autmanmessages';
		} 
		else 
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_SAVE_ERROR');
			$msgType = 'alert-danger';
			$view = 'autmanmessage&cid[]=' . $model->_id;
		}
		$this->setRedirect(JRoute::_('index.php?view=' . $view, false), $msg, $msgType);
	}
	
	function apply()
	{
		JRequest::checkToken() or jexit('JINVALID_TOKEN');
		$model = $this->getModel('autmanmessage');
		if ($model->store()) 
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_SAVE_SUCCESS');
			$msgType = 'alert-success';
		} 
		else 
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_SAVE_ERROR');
			$msgType = 'alert-danger';
		}
		$this->setRedirect(JRoute::_('index.php?view=autmanmessage&cid[]=' . $model->_id, false), $msg, $msgType);
	}
	
	function sendmail()
	{
		JRequest::checkToken() or jexit('JINVALID_TOKEN');
		$model = $this->getModel('autmanmessage');
		if ($model->sendmail()) 
		{
			JRequest::setVar( 'hidemainmenu', 0 );
			$msg = JText::_('JGLOBAL_CONTROLLER_SENDMAIL_SUCCESS');
			$msgType = 'alert-success';
		} 
		else 
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_SENDMAIL_ERROR');
			$msgType = 'alert-danger';
		}
		$this->setRedirect(JRoute::_('index.php?view=autmanmessage&cid[]=' . $model->_id, false), $msg, $msgType);
	}
	
	function cancel()
	{
		JRequest::checkToken() or jexit('JINVALID_TOKEN');
		$model = $this->getModel('autmanmessage');
		$model->checkin();
		$msg = JText::_('JGLOBAL_CONTROLLER_CANCEL');
		$msgType = 'alert-success';
		JRequest::setVar( 'hidemainmenu', 0 );
		$this->setRedirect(JRoute::_('index.php?view=autmanmessages', false), $msg, $msgType);
	}

}
