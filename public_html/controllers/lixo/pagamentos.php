<?php
/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.controller');

class EASistemasControllerPagamentos extends JController
{
	public function display($cachable = false, $urlparams = array())
	{
		JRequest::setVar('view', 'pagamentos');	
		JRequest::setVar( 'hidemainmenu', 0 );
		parent::display();			
	}
	
	
	function Cnab400teste() 
	{	
				
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		$model = $this->getModel('pagamentos');
		$model->getCnab400();
		exit;
/*
		
		if( $model->Cnab400() )
		{
			$msg = JText::_('Correção de Valores Atualizada!');
			$msgType = 'success';
		}
		else
		{
			$msg = JText::_('Erro ao tentar corrigir valores lote');
			$msgType = 'danger';
		}
		//$this->setRedirect(JRoute::_('index.php?view=pagamentos&format=raw', false), $msg, $msgType);
		$this->setRedirect(JRoute::_('index.php?view=pagamentos', false), $msg, $msgType);
		//$this->setRedirect(JRoute::_('index.php?view=pagamentos&format=raw', false), $msg, $msgType);
		*/
		//'index.php?view=mmails&task=tracks.display&format=raw'
	}	
		
	function plots() 
	{
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		JRequest::setVar( 'view', 'pagamentos' );
		$model = $this->getModel('pagamentos');
		if( $model->testPlots() )
		{
			if( $model->plots_pagamento() )
			{
				$msg = JText::_('Parcela(s) cadastradas com sucesso!');
				$msgType = 'success';
			}
			else
			{
				$msg = JText::_('Erro ao tentar cadastrar parcela(s)!');
				$msgType = 'danger';
			}
		}
			else
			{
				$msg = JText::_('Não existem parcela(s) a cadastrar para a(s) cobrança(s) selecionado(s)');
				$msgType = 'danger';
			}
	
		$this->setRedirect(JRoute::_('index.php?view=pagamentos', false), $msg, $msgType);
	}
	
	
	function process() 
	{	
				
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		JRequest::setVar( 'view', 'pagamentos' );
		$model = $this->getModel('pagamentos');
		if( $model->ProcessLote() )
		{
			$msg = JText::_('Processo em Lote realizado com Sucesso!');
			$msgType = 'success';
		}
		else
		{
			$msg = JText::_('Erro ao tentar processar lote');
			$msgType = 'danger';
		}
		//$this->setRedirect(JRoute::_('index.php?view=pagamentos&format=raw', false), $msg, $msgType);
		$this->setRedirect(JRoute::_('index.php?view=pagamentos', false), $msg, $msgType);
		//$this->setRedirect(JRoute::_('index.php?view=pagamentos&format=raw', false), $msg, $msgType);
		
		//'index.php?view=mmails&task=tracks.display&format=raw'
	}	
	
	function cnab() 
	{	
		$this->setRedirect(JRoute::_('index.php?view=pagamentos&format=raw', false), $msg, $msgType);
	}	
	
	function add() 
	{	
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		JRequest::setVar( 'view', 'pagamento' );
		JRequest::setVar( 'hidemainmenu', 1 );
		parent::display();
	}      
	
	function edit() 
	{	
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		JRequest::setVar( 'view', 'pagamento' );
		JRequest::setVar( 'hidemainmenu', 1 );
		parent::display();
	}

	function remove() 
	{	
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		JRequest::setVar( 'view', 'pagamentos' );
		$model = $this->getModel('pagamentos');
		if( $model->remove_pagamento() )
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_REMOVE_SUCCESS');
			$msgType = 'success';
		}
		else
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_REMOVE_ERROR');
			$msgType = 'danger';
		}
		$this->setRedirect(JRoute::_('index.php?view=pagamentos', false), $msg, $msgType);
	}
	
	function publish() 
	{
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		JRequest::setVar( 'view', 'pagamentos' );
		$model = $this->getModel('pagamentos');
		if( $model->publish_pagamento() )
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_PUBLISH_SUCCESS');
			$msgType = 'success';
		}
		else
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_PUBLISH_ERROR');
			$msgType = 'danger';
		}
		$this->setRedirect(JRoute::_('index.php?view=pagamentos', false), $msg, $msgType);
	}
	
	function unpublish() 
	{	
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		JRequest::setVar( 'view', 'pagamentos' );
		$model = $this->getModel('pagamentos');
		if( $model->unpublish_pagamento() )
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_UNPUBLISH_SUCCESS');
			$msgType = 'success';
		}
		else
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_PUBLISH_ERROR');
			$msgType = 'danger';
		}
		$this->setRedirect(JRoute::_('index.php?view=pagamentos', false), $msg, $msgType);
	}

	function orderup() 
	{
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		JRequest::setVar( 'view', 'pagamentos' );
		$model = $this->getModel('pagamentos');
		if( $model->move(-1) )
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_ORDER_SUCCESS');
			$msgType = 'success';
		}
		else
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_ORDER_ERROR');
			$msgType = 'danger';		
		}
		$this->setRedirect(JRoute::_('index.php?view=pagamentos', false), $msg, $msgType);
	}
	
	function orderdown() 
	{
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		JRequest::setVar( 'view', 'pagamentos' );
		$model = $this->getModel('pagamentos');  
		if( $model->move(1) )
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_ORDER_SUCCESS');
			$msgType = 'success';
		}
		else
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_ORDER_ERROR');
			$msgType = 'danger';		
		}
		$this->setRedirect(JRoute::_('index.php?view=pagamentos', false), $msg, $msgType);
	}

	function saveOrder()
	{
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		JRequest::setVar( 'view', 'pagamentos' );
		$model = $this->getModel('pagamentos');
		if( $model->saveOrder() )
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_ORDER_SUCCESS');
			$msgType = 'success';
		}
		else
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_ORDER_ERROR');
			$msgType = 'danger';		
		}
		$this->setRedirect(JRoute::_('index.php?view=pagamentos', false), $msg, $msgType);
	}

	function checkin()
	{	
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		JRequest::setVar( 'view', 'pagamentos' );
		$model = $this->getModel('pagamentos');
		if( $model->checkin() )
		{	
			$msg = JText::_('JGLOBAL_CONTROLLER_CHECKIN_SUCCESS');
			$msgType = 'success';
		}
		else
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_CHECKIN_ERROR');
			$msgType = 'danger';			
		}
		$this->setRedirect(JRoute::_('index.php?view=pagamentos', false), $msg, $msgType);
	}
	
	
	
	
	
	
	
	
}
