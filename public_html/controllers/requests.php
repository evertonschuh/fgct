<?php
/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.controller');

class EASistemasControllerRequests extends JController
{
	

	function renomeia() 
	{	
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		$model = $this->getModel('requests');
		if( $model->renomeia() )
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_REMOVE_SUCCESS');
			$msgType = 'alert-success';
		}
		else
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_REMOVE_ERROR');
			$msgType = 'alert-danger';
		}
		$this->setRedirect(JRoute::_('index.php?view=requests', false), $msg, $msgType);
	}
	
	
	public function display($cachable = false, $urlparams = array())
	{
		JRequest::setVar('view', 'requests');	
		JRequest::setVar( 'hidemainmenu', 0 );
		parent::display();			
	}
	
	function add() 
	{	
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		JRequest::setVar( 'view', 'associado' );
		JRequest::setVar( 'hidemainmenu', 1 );
		parent::display();
	}      
	
	function remove() 
	{	
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		JRequest::setVar( 'view', 'requests' );
		$model = $this->getModel('requests');
		if( $model->remove() )
		{
			$msg = JText::_('Arma Removida com sucesso!');
			$msgType = 'success';
		}
		else
		{
			$msg = JText::_('Erro ao tentar remover arma do cadastro!');
			$msgType = 'danger';
		}
		$this->setRedirect(JRoute::_('index.php?view=requests', false), $msg, $msgType);
	}

	
	
	
}
