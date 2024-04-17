<?php 

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class EASistemasControllerDModel extends JController {

	public function display($cachable = false, $urlparams = array()) 
	{
		JRequest::setVar( 'hidemainmenu', 1 );
		parent::display(); 
	}

	function save()
	{
		JRequest::checkToken() or jexit('JINVALID_TOKEN');
		$model = $this->getModel('dmodel');
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
		$this->setRedirect(JRoute::_('index.php?view=dmodel&cid=' . $model->_id, false), $msg, $msgType);
	}

	function listCPF()
	{
		JRequest::checkToken() or jexit('JINVALID_TOKEN');
		$model = $this->getModel('dmodel');
		if ($model->listCPF()) 
		{
			$msg = JText::_('Lista Adicionada com Sucesso.');
			$msgType = 'success';
		} 
		else 
		{
			$msg = JText::_('Erro ao adicionar Lista');
			$msgType = 'danger';
		}
		$this->setRedirect(JRoute::_('index.php?view=dmodel&cid=' . $model->_id, false), $msg, $msgType);
	}

	function cancel()
	{
		JRequest::checkToken() or jexit('JINVALID_TOKEN');
		$model = $this->getModel('dmodel');
		$model->checkin();
		JRequest::setVar( 'hidemainmenu', 0 );
		$this->setRedirect(JRoute::_('index.php?view=dmodels', false));
	}

	function addUsers()
	{
		JRequest::checkToken() or jexit('JINVALID_TOKEN');
		$model = $this->getModel('dmodel');
		if ($model->addUsers()) 
		{
			//$msg = JText::_('JGLOBAL_CONTROLLER_ADD_SUCCESS');
			//$msgType = 'alert-success';
		} 
		else 
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_ADD_ERROR');
			$msgType = 'alert';
		}
		$this->setRedirect(JRoute::_('index.php?view=dmodel&layout=modalm&cid[]=' . $model->_id . '&tmpl=modal', false), $msg, $msgType);
	}

	
	function removeUsers() 
	{	
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		$model = $this->getModel('dmodel');
		if( $model->removeUsers() )
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_REMOVE_SUCCESS');
			$msgType = 'success';
		}
		else
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_REMOVE_ERROR');
			$msgType = 'danger';
		}
		$this->setRedirect(JRoute::_('index.php?view=dmodel&cid[]=' . $model->_id . '#list-users', false), $msg, $msgType);
	}


}
