<?php 

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class EASistemasControllerAssociado extends JController {

	public function display($cachable = false, $urlparams = array()) 
	{
		JRequest::setVar( 'hidemainmenu', 1 );
		parent::display(); 
	}
	
	function next()
	{ 
		JRequest::checkToken() or jexit('JINVALID_TOKEN');
		$model = $this->getModel('associado');
		if ($model->setDoc()) 
		{
			JRequest::setVar( 'hidemainmenu', 0 );
			$this->setRedirect(JRoute::_('index.php?view=associado&type='.  $model->_type . '&doc='.  $model->_doc, false), $msg, $msgType);
		} 
		else 
		{
			$msg = JText::_('Cadastro já existente.');
			$msgType = 'alert-danger';
			$this->setRedirect(JRoute::_('index.php?view=associado&cid[]=', false), $msg, $msgType);
		}
		
	}

	function save()
	{
		JRequest::checkToken() or jexit('JINVALID_TOKEN');
		$model = $this->getModel('associado');
		if ($model->store()) 
		{
			JRequest::setVar( 'hidemainmenu', 0 );
			$msg = JText::_('JGLOBAL_CONTROLLER_SAVE_SUCCESS');
			$msgType = 'alert-success';
			$view = 'associados';
		} 
		else 
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_SAVE_ERROR');
			$msgType = 'alert-danger';
			$view = 'associado&cid[]=' . $model->_id;
		}
		$this->setRedirect(JRoute::_('index.php?view=' . $view, false), $msg, $msgType);
	}
	
	function apply()
	{
		JRequest::checkToken() or jexit('JINVALID_TOKEN');
		$model = $this->getModel('associado');
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
		$this->setRedirect(JRoute::_('index.php?view=associado&cid[]=' . $model->_id, false), $msg, $msgType);
	}

	function cancel()
	{
		JRequest::checkToken() or jexit('JINVALID_TOKEN');
		$model = $this->getModel('associado');
		$model->checkin();
		$msg = JText::_('JGLOBAL_CONTROLLER_CANCEL');
		$msgType = 'alert-success';
		JRequest::setVar( 'hidemainmenu', 0 );
		$this->setRedirect(JRoute::_('index.php?view=associados', false), $msg, $msgType);
	}

}
