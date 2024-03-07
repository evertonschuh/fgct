<?php 

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class EASistemasControllerRequest extends JController {

	public function display($cachable = false, $urlparams = array()) 
	{
		JRequest::setVar( 'hidemainmenu', 1 );
		parent::display(); 
	}
	

	function save()
	{
		JRequest::checkToken() or jexit('JINVALID_TOKEN');
		$model = $this->getModel('request');
		if ($model->store()) 
		{
			JRequest::setVar( 'hidemainmenu', 0 );
			$msg = JText::_('JGLOBAL_CONTROLLER_SAVE_SUCCESS');
			$msgType = 'success';
		} 
		else 
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_SAVE_ERROR');
			$msgType = 'danger';
		}
		$this->setRedirect(JRoute::_('index.php?view=request&cid[]=' . $model->_id, false), $msg, $msgType);
	}
	

	function cancel()
	{
		JRequest::checkToken() or jexit('JINVALID_TOKEN');
		$model = $this->getModel('request');
		$model->checkin();
		JRequest::setVar( 'hidemainmenu', 0 );
		$this->setRedirect(JRoute::_('index.php?view=requests', false));
	}

}
