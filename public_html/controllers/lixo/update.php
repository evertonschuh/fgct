<?php 

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class EASistemasControllerUpdate extends JController {

	public function display($cachable = false, $urlparams = array()) 
	{
		JRequest::setVar( 'hidemainmenu', 1 );
		parent::display(); 
	}
	
	function update()
	{
		JRequest::checkToken() or jexit('JINVALID_TOKEN');
		$model = $this->getModel('update');
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
		$this->setRedirect(JRoute::_('index.php?view=update&cid[]=' . $model->_id, false), $msg, $msgType);
	}	

	function cancel()
	{
		JRequest::checkToken() or jexit('JINVALID_TOKEN');
		$model = $this->getModel('update');
		$model->checkin();
		$msg = JText::_('JGLOBAL_CONTROLLER_CANCEL');
		$msgType = 'alert-success';
		JRequest::setVar( 'hidemainmenu', 0 );
		$this->setRedirect(JRoute::_('index.php?view=updates', false), $msg, $msgType);
	}

}
