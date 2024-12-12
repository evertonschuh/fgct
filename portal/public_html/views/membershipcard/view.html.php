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
				
				require_once(JPATH_MODULE .DS. 'mod_pdf' .DS. 'mod_pdf.php');
			
				$prefix  = 'EAsistemas';
				$type = 'PdfModule';
				$type = preg_replace('/[^A-Z0-9_\.-]/i', '', $type);
				$modelClass = $prefix . ucfirst($type);
				
				if (!class_exists($modelClass))
					return false;
				
				$_module_pdf = new $modelClass();
		
				$carteira = array();
				$carteira['id'] = null;
				$carteira['values'] = $this->item;
				$carteira['skin'] = null;
				$carteira['style'] = 'carteira';

				$carteira['fonts'] = array();
				$carteira['fonts']['family'] = 'Public Sans';
				$carteira['fonts']['types'] = Array(	'normal' => 'PublicSans.ttf',
														'italic' => 'PublicSans-Oblique.ttf',
														'bold' => 'PublicSans-Bold.ttf',
														'bold_italic' => 'PublicSans-BoldOblique.ttf'
													);


				$_module_pdf->setData($carteira);
				$responsePdf = $_module_pdf->getPdf();
				
			//	}
		
				header('Content-type: application/pdf');
				header("Cache-Control: no-cache");
				header("Pragma: no-cache");
				header('Content-Disposition: inline; filename="carteira-digital.pdf"');
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

