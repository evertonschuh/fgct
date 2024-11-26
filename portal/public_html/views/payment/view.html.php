<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');


class EASistemasViewPayment extends JView 
{
	public function display($tpl = null)
	{		
		


		//$this->item = $this->get('Item');

		$this->cobranca = $this->get( 'Cobranca' );



		require_once(JPATH_MODULE .DS. 'mod_pdf' .DS. 'mod_pdf.php');
			
		$prefix  = 'EAsistemas';
		$type = 'PdfModule';
		$type = preg_replace('/[^A-Z0-9_\.-]/i', '', $type);
		$modelClass = $prefix . ucfirst($type);
		
		if (!class_exists($modelClass))
			return false;
		
		$_module_pdf = new $modelClass();

		$boleto = array();
		$boleto['id'] = null;
		$boleto['texto'] = $this->cobranca;
		$boleto['skin'] = null;
		$boleto['style'] = 'boleto';
		$_module_pdf->setData($boleto);
		$responsePdf = $_module_pdf->getPdf();
		
	//	}

		header('Content-type: application/pdf');
		header("Cache-Control: no-cache");
		header("Pragma: no-cache");
		//header('Content-Disposition: inline; filename="'.$this->item->name_documento.'.pdf"');
		header("Content-length: ".strlen(base64_decode($responsePdf)));
		die(base64_decode($responsePdf));







		//$document = JFactory::getDocument();

		//$document->setName('FGCT - Cobrança ' . $this->item->id_pagamento);

		//JRequest::setVar('tmpl','blank');
		//JRequest::setVar('format','pdf');
		//$this->cobranca = $this->get( 'Cobranca' );
	

		parent::display($tpl);	

													   
															   
	}
}


