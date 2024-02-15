<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class EASistemasViewMyEnrollment extends JView 
{
	public function display($tpl = null)
	{	
		//// Set the toolbar
	
		$this->item = $this->get('Item');

		
		$inscricaoPrint = $this->get('InscricaoPrint');
		$this->assignRef( 'inscricaoPrint', $inscricaoPrint);

		$agendamentosPrint = $this->get('AgendamentosPrint');
		$this->assignRef('agendamentosPrint' , $agendamentosPrint  );	
		
		
		$additionalPrint = $this->get('AdditionalPrint');
		$this->assignRef( 'additionalPrint', $additionalPrint);

		parent::display($tpl);


	}

}

