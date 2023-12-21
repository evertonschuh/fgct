<?php 

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class EASistemasControllerPagamento extends JController {

	public function display($cachable = false, $urlparams = array()) 
	{

		JRequest::setVar( 'hidemainmenu', 1 );
		parent::display(); 
	}

	function save()
	{
		JRequest::checkToken() or jexit('JINVALID_TOKEN');
		$urlReturn = JRequest::getVar('return', '', 'GET', 'base64');
		$model = $this->getModel('pagamento');
		if ($model->store()) 
		{
			JRequest::setVar( 'hidemainmenu', 0 );
			$msg = JText::_('JGLOBAL_CONTROLLER_SAVE_SUCCESS');
			$msgType = 'success';
			$view = 'pagamentos';
		} 
		else 
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_SAVE_ERROR');
			$msgType = 'danger';
			$view = 'pagamento&cid[]=' . $model->_id;
		}


        
		if ( !empty( $urlReturn ) && $view == 'pagamentos') {
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
		$model = $this->getModel('pagamento');
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
		$this->setRedirect(JRoute::_('index.php?view=pagamento&cid[]=' . $model->_id, false), $msg, $msgType);
	}
	
	function sendmail()
	{
		JRequest::checkToken() or jexit('JINVALID_TOKEN');
		$model = $this->getModel('pagamento');
		if ($model->sendmail()) 
		{
			JRequest::setVar( 'hidemainmenu', 0 );
			$msg = JText::_('JGLOBAL_CONTROLLER_SENDMAIL_SUCCESS');
			$msgType = 'success';
		} 
		else 
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_SENDMAIL_ERROR');
			$msgType = 'danger';
		}
		$this->setRedirect(JRoute::_('index.php?view=pagamento&cid[]=' . $model->_id, false), $msg, $msgType);
	}
	
	function nfe()
	{
		JRequest::checkToken() or jexit('JINVALID_TOKEN');
		$model = $this->getModel('pagamento');
		if ($model->getValues()) 
		{
			//JRequest::setVar( 'hidemainmenu', 0 );
		//	$msg = JText::_('JGLOBAL_CONTROLLER_SENDMAIL_SUCCESS');
		//	$msgType = 'success';
		} 
		else 
		{
		//	$msg = JText::_('JGLOBAL_CONTROLLER_SENDMAIL_ERROR');
	//		$msgType = 'danger';
		}
	//	$this->setRedirect(JRoute::_('index.php?view=pagamento&cid[]=' . $model->_id, false), $msg, $msgType);
	}

	function cancel()
	{
		JRequest::checkToken() or jexit('JINVALID_TOKEN');
		$urlReturn = JRequest::getVar('return', '', 'GET', 'base64');
		$model = $this->getModel('pagamento');
		$model->checkin();
		$msg = JText::_('JGLOBAL_CONTROLLER_CANCEL');
		$msgType = 'success';
		JRequest::setVar( 'hidemainmenu', 0 );
		
		if ( !empty( $urlReturn ) ) {
			$urlReturn = base64_decode($urlReturn);
			$this->setRedirect(JRoute::_($urlReturn, false), $msg, $msgType);
		}
		else {
			$this->setRedirect(JRoute::_('index.php?view=pagamentos', false), $msg, $msgType);
		}
	}
}
