<?php 

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class EASistemasControllerProfile extends JController {

	public function display($cachable = false, $urlparams = array()) 
	{
		$user	= JFactory::getUser();
		if ($user->get('guest')) {
			$uri			= 	JFactory::getURI();
			$redirectUrl	=	$uri->toString();
			$redirectUrl 	=	urlencode(base64_encode($redirectUrl));
			$msg = JText::_('É necessário estar logado para acessar está página!');
			$this->setRedirect(JRoute::_('index.php?view=login&return=' . $redirectUrl, false), $msg, 'danger');
			return;
		}
		parent::display();
	}
	
	function save()
	{
		JRequest::checkToken() or jexit('JINVALID_TOKEN');
		$model = $this->getModel('profile');
		if ($model->store()) 
		{
			JRequest::setVar( 'hidemainmenu', 0 );
			$msg = JText::_('Perfil salvo com sucesso!');
			$msgType = 'success';
		} 
		else 
		{
			$msg = JText::_('Erro ao tentar salvar o perfil');
			$msgType = 'danger';
		}
		$this->setRedirect(JRoute::_('index.php?view=profile', false), $msg, $msgType);
	}
	


	function cancel()
	{
		JRequest::checkToken() or jexit('JINVALID_TOKEN');
		$this->setRedirect(JRoute::_('index.php?view=profiles', false));
	}

}
