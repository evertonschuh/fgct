<?php 

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class EASistemasControllerFinPagamento extends JController {

	public function display($cachable = false, $urlparams = array()) 
	{
		JRequest::setVar( 'hidemainmenu', 1 );
		parent::display(); 
	}

	function save()
	{
		JRequest::checkToken() or jexit('JINVALID_TOKEN');
		$urlReturn = JRequest::getVar('return', '', 'GET', 'base64');
		$model = $this->getModel('finpagamento');
		if ($model->store()) 
		{
			JRequest::setVar( 'hidemainmenu', 0 );
			$msg = JText::_('JGLOBAL_CONTROLLER_SAVE_SUCCESS');
			$msgType = 'alert-success';
			$view = 'finpagamentos';
		} 
		else 
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_SAVE_ERROR');
			$msgType = 'alert-danger';
			$view = 'finpagamento&cid[]=' . $model->_id;
		}


        
		if ( !empty( $urlReturn ) && $view == 'finpagamentos') {
			$urlReturn = base64_decode($urlReturn);
			$this->setRedirect(JRoute::_($urlReturn, false), $msg, $msgType);
		}
		else {
			$this->setRedirect(JRoute::_('index.php?view=' . $view, false), $msg, $msgType);
		}
    
	}
	
	function apply()
	{
		JRequest::checkToken() or jexit('JINVALID_TOKEN');
		$model = $this->getModel('finpagamento');
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
		$this->setRedirect(JRoute::_('index.php?view=finpagamento&cid[]=' . $model->_id, false), $msg, $msgType);
	}
	
	function sendmail()
	{
		JRequest::checkToken() or jexit('JINVALID_TOKEN');
		$model = $this->getModel('finpagamento');
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
		$this->setRedirect(JRoute::_('index.php?view=finpagamento&cid[]=' . $model->_id, false), $msg, $msgType);
	}
	
	function nfe()
	{
		JRequest::checkToken() or jexit('JINVALID_TOKEN');
		$model = $this->getModel('finpagamento');
		if ($model->getValues()) 
		{
			//JRequest::setVar( 'hidemainmenu', 0 );
		//	$msg = JText::_('JGLOBAL_CONTROLLER_SENDMAIL_SUCCESS');
		//	$msgType = 'alert-success';
		} 
		else 
		{
		//	$msg = JText::_('JGLOBAL_CONTROLLER_SENDMAIL_ERROR');
	//		$msgType = 'alert-danger';
		}
	//	$this->setRedirect(JRoute::_('index.php?view=finpagamento&cid[]=' . $model->_id, false), $msg, $msgType);
	}

	function cancel()
	{
		JRequest::checkToken() or jexit('JINVALID_TOKEN');
		$urlReturn = JRequest::getVar('return', '', 'GET', 'base64');
		$model = $this->getModel('finpagamento');
		$model->checkin();
		$msg = JText::_('JGLOBAL_CONTROLLER_CANCEL');
		$msgType = 'alert-success';
		JRequest::setVar( 'hidemainmenu', 0 );
		
		if ( !empty( $urlReturn ) ) {
			$urlReturn = base64_decode($urlReturn);
			$this->setRedirect(JRoute::_($urlReturn, false), $msg, $msgType);
		}
		else {
			$this->setRedirect(JRoute::_('index.php?view=finpagamentos', false), $msg, $msgType);
		}
	}
}
