<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');


class EASistemasViewPayment extends JView 
{
	public function display($tpl = null)
	{		
		
		$this->item = $this->get('Item');

		$this->cobranca = $this->get( 'Cobranca' );
		//$document = JFactory::getDocument();

		//$document->setName('FGCT - Cobrança ' . $this->item->id_pagamento);

		//JRequest::setVar('tmpl','blank');
		//JRequest::setVar('format','pdf');
		//$this->cobranca = $this->get( 'Cobranca' );
	

		parent::display($tpl);	

													   
															   
	}
}


