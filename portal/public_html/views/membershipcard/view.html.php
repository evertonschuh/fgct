<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class EASistemasViewMembershipCard extends JView 
{
	public function display($tpl = null)
	{	
		//// Set the toolbar
		

		$this->item = $this->get('Item');

		$layout	= $this->getLayout();
		
		switch($layout)
		{
			case 'print':
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
					
					$documento = array();
					$documento['id'] = $this->item->id_documento_numero;
					$documento['texto'] = $this->item->texto_documento_numero;
					$documento['skin'] = $this->item->skin_documento;
					$_module_pdf->setData($documento);
					$responsePdf = $_module_pdf->getPdf();
		
				}
		
				header('Content-type: application/pdf');
				header("Cache-Control: no-cache");
				header("Pragma: no-cache");
				header('Content-Disposition: inline; filename="'.$this->item->name_documento.'.pdf"');
				header("Content-length: ".strlen(base64_decode($responsePdf)));
				die(base64_decode($responsePdf));
		
			break;
			default:

				JToolBarHelper::title('<span class="text-muted fw-light">CADASTRO / </span> Carteira Digital');
				// Display the template*/
				parent::display($tpl);
			break;
		}


		
	}

}

