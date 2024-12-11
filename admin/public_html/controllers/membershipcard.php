<?php 

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class EASistemasControllerMemberShipCard extends JController {

	public function display($cachable = false, $urlparams = array()) 
	{
		JRequest::setVar( 'hidemainmenu', 1 );
		parent::display(); 
	}

	function save()
	{

		JRequest::checkToken() or jexit('JINVALID_TOKEN');
		$model = $this->getModel('membershipcard');
		if ($model->store()) 
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_SAVE_SUCCESS');
			$msgType = 'success';
		} 
		else 
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_SAVE_ERROR');
			$msgType = 'danger';
		}
		$this->setRedirect(JRoute::_('index.php?view=membershipcard&cid[]=' . $model->_id, false), $msg, $msgType);
	}

	function cancel()
	{
		JRequest::checkToken() or jexit('JINVALID_TOKEN');
		$model = $this->getModel('membershipcard');
		$model->checkin();
		JRequest::setVar( 'hidemainmenu', 0 );
		$this->setRedirect(JRoute::_('index.php?view=membershipcards', false));
	}

}
