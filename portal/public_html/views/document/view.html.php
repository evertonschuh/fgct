<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class EASistemasViewDocument extends JView 
{
	public function display($tpl = null)
	{	
		//// Set the toolbar
		

		$this->item = $this->get('Item');


		if ( base64_encode(base64_decode($this->item->texto_documento_numero, true)) === $this->item->texto_documento_numero) {
			$responsePdf = $this->item->texto_documento_numero;
		} 
		else {

			require_once(JPATH_MODULE .DS. 'mod_pdf' .DS. 'mod_pdf.php');
			
			$prefix  = 'EAsistemas';
			$type = 'PdfModule';
			$type = preg_replace('/[^A-Z0-9_\.-]/i', '', $type);
			$modelClass = $prefix . ucfirst($type);
			
			if (!class_exists($modelClass))
				return false;
			
			$_module_pdf = new $modelClass();
			
			$_module_pdf->setData($this->item);
			$responsePdf = $_module_pdf->getPdf();

		}

		header('Content-type: application/pdf');
		header("Cache-Control: no-cache");
		header("Pragma: no-cache");
		header('Content-Disposition: inline; filename="'.$this->item->name_documento.'.pdf"');
		header("Content-length: ".strlen(base64_decode($responsePdf)));
		die(base64_decode($responsePdf));

	}

}

