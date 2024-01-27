<?php
/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.controller');

class EASistemasControllerCobrancas extends JController
{
	public function display($cachable = false, $urlparams = array())
	{
		JRequest::setVar('view', 'cobrancas');	
		JRequest::setVar( 'hidemainmenu', 0 );
		parent::display();			
	}
	
	
	function Cnab400teste() 
	{	
				
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		$model = $this->getModel('cobrancas');
		$model->getCnab400();
		exit;
/*
		
		if( $model->Cnab400() )
		{
			$msg = JText::_('Correção de Valores Atualizada!');
			$msgType = 'alert-success';
		}
		else
		{
			$msg = JText::_('Erro ao tentar corrigir valores lote');
			$msgType = 'alert-danger';
		}
		//$this->setRedirect(JRoute::_('index.php?view=cobrancas&format=raw', false), $msg, $msgType);
		$this->setRedirect(JRoute::_('index.php?view=cobrancas', false), $msg, $msgType);
		//$this->setRedirect(JRoute::_('index.php?view=cobrancas&format=raw', false), $msg, $msgType);
		*/
		//'index.php?view=mmails&task=tracks.display&format=raw'
	}	
		
	function plots() 
	{
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		JRequest::setVar( 'view', 'cobrancas' );
		$model = $this->getModel('cobrancas');
		if( $model->testPlots() )
		{
			if( $model->plots_pagamento() )
			{
				$msg = JText::_('Parcela(s) cadastradas com sucesso!');
				$msgType = 'alert-success';
			}
			else
			{
				$msg = JText::_('Erro ao tentar cadastrar parcela(s)!');
				$msgType = 'alert-danger';
			}
		}
			else
			{
				$msg = JText::_('Não existem parcela(s) a cadastrar para a(s) cobrança(s) selecionado(s)');
				$msgType = 'alert-danger';
			}
	
		$this->setRedirect(JRoute::_('index.php?view=cobrancas', false), $msg, $msgType);
	}
	
	
	function process() 
	{	
				
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		JRequest::setVar( 'view', 'cobrancas' );
		$model = $this->getModel('cobrancas');
		if( $model->ProcessLote() )
		{
			$msg = JText::_('Processo em Lote realizado com Sucesso!');
			$msgType = 'alert-success';
		}
		else
		{
			$msg = JText::_('Erro ao tentar processar lote');
			$msgType = 'alert-danger';
		}
		//$this->setRedirect(JRoute::_('index.php?view=cobrancas&format=raw', false), $msg, $msgType);
		$this->setRedirect(JRoute::_('index.php?view=cobrancas', false), $msg, $msgType);
		//$this->setRedirect(JRoute::_('index.php?view=cobrancas&format=raw', false), $msg, $msgType);
		
		//'index.php?view=mmails&task=tracks.display&format=raw'
	}	
	
	function cnab() 
	{	
		$this->setRedirect(JRoute::_('index.php?view=cobrancas&format=raw', false), $msg, $msgType);
	}	
	
	function add() 
	{	
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		JRequest::setVar( 'view', 'cobranca' );
		JRequest::setVar( 'hidemainmenu', 1 );
		parent::display();
	}      
	
	function edit() 
	{	
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		JRequest::setVar( 'view', 'cobranca' );
		JRequest::setVar( 'hidemainmenu', 1 );
		parent::display();
	}

	function remove() 
	{	
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		JRequest::setVar( 'view', 'cobrancas' );
		$model = $this->getModel('cobrancas');
		if( $model->remove_pagamento() )
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_REMOVE_SUCCESS');
			$msgType = 'alert-success';
		}
		else
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_REMOVE_ERROR');
			$msgType = 'alert-danger';
		}
		$this->setRedirect(JRoute::_('index.php?view=cobrancas', false), $msg, $msgType);
	}
	
	function publish() 
	{
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		JRequest::setVar( 'view', 'cobrancas' );
		$model = $this->getModel('cobrancas');
		if( $model->publish_pagamento() )
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_PUBLISH_SUCCESS');
			$msgType = 'alert-success';
		}
		else
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_PUBLISH_ERROR');
			$msgType = 'alert-danger';
		}
		$this->setRedirect(JRoute::_('index.php?view=cobrancas', false), $msg, $msgType);
	}
	
	function unpublish() 
	{	
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		JRequest::setVar( 'view', 'cobrancas' );
		$model = $this->getModel('cobrancas');
		if( $model->unpublish_pagamento() )
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_UNPUBLISH_SUCCESS');
			$msgType = 'alert-success';
		}
		else
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_PUBLISH_ERROR');
			$msgType = 'alert-danger';
		}
		$this->setRedirect(JRoute::_('index.php?view=cobrancas', false), $msg, $msgType);
	}

	function orderup() 
	{
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		JRequest::setVar( 'view', 'cobrancas' );
		$model = $this->getModel('cobrancas');
		if( $model->move(-1) )
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_ORDER_SUCCESS');
			$msgType = 'alert-success';
		}
		else
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_ORDER_ERROR');
			$msgType = 'alert-danger';		
		}
		$this->setRedirect(JRoute::_('index.php?view=cobrancas', false), $msg, $msgType);
	}
	
	function orderdown() 
	{
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		JRequest::setVar( 'view', 'cobrancas' );
		$model = $this->getModel('cobrancas');  
		if( $model->move(1) )
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_ORDER_SUCCESS');
			$msgType = 'alert-success';
		}
		else
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_ORDER_ERROR');
			$msgType = 'alert-danger';		
		}
		$this->setRedirect(JRoute::_('index.php?view=cobrancas', false), $msg, $msgType);
	}

	function saveOrder()
	{
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		JRequest::setVar( 'view', 'cobrancas' );
		$model = $this->getModel('cobrancas');
		if( $model->saveOrder() )
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_ORDER_SUCCESS');
			$msgType = 'alert-success';
		}
		else
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_ORDER_ERROR');
			$msgType = 'alert-danger';		
		}
		$this->setRedirect(JRoute::_('index.php?view=cobrancas', false), $msg, $msgType);
	}

	function checkin()
	{	
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		JRequest::setVar( 'view', 'cobrancas' );
		$model = $this->getModel('cobrancas');
		if( $model->checkin() )
		{	
			$msg = JText::_('JGLOBAL_CONTROLLER_CHECKIN_SUCCESS');
			$msgType = 'alert-success';
		}
		else
		{
			$msg = JText::_('JGLOBAL_CONTROLLER_CHECKIN_ERROR');
			$msgType = 'alert-danger';			
		}
		$this->setRedirect(JRoute::_('index.php?view=cobrancas', false), $msg, $msgType);
	}
	
	
	
	
	
	
	
	
}
