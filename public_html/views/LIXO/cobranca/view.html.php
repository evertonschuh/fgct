<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

include_once (JPATH_ADMINISTRATOR.DS.'views'.DS.'finpagamento'.DS.'language.scrips.php');

class IntranetViewFinPagamento extends JView 
{
	public function display($tpl = null)
	{	
	
		$layout	= $this->getLayout();
		switch($layout)
		{
			case 'view':
				JRequest::setVar('tmpl','blank');
				$cobranca = $this->get( 'Cobranca' );
			break;
			case 'pdf':
				JRequest::setVar('tmpl','blank');
				$cobranca = $this->get( 'Cobranca' );
			break;
			default:

				$document 			=  JFactory::getDocument();
				
				$document->addStyleSheet( 'views/system/css/select2.css' );
				$document->addScript( 'views/system/js/select2.js' );
				
				$document->addstylesheet( 'views/system/css/bootstrap-datetimepicker.css' );
				$document->addScript( 'views/system/js/moment-with-locales.js' );
				$document->addScript( 'views/system/js/bootstrap-datetimepicker.js' );
				$document->addScript( 'views/system/js/jquery.bootstrap-touchspin.js' );
				$document->addScript( 'views/system/js/jquery.number.min.js' );
				//$document->addScript( 'views/system/js/jquery.maskedinput.js' );
				$document->addScript( 'views/system/js/jquery.maskMoney.min.js' );
				$document->addScript( 'views/system/js/finpagamento.js' );
				//$document->addScript( 'views/system/js/util.js' );
				//$document->addstylesheet( 'views/system/css/bootstrap-datetimepicker.css' );
		
				$this->item		= $this->get('Item');
				
				$metodos =  $this->get( 'Metodos');
				$this->assignRef('metodos',	$metodos);	
				
				$associadosList =  $this->get( 'AssociadosList');
				$this->assignRef('associadosList',	$associadosList);				
				
				$anuidadePagamento =  $this->get( 'AnuidadePagamento');
				$this->assignRef('anuidadePagamento',	$anuidadePagamento);			
				
				$produtoPagamento =  $this->get( 'ProdutoPagamento');
				$this->assignRef('produtoPagamento',	$produtoPagamento);	
				
				$nonDiscountedValue =  $this->get('NonDiscountedValue');
				$this->assignRef('nonDiscountedValue',	$nonDiscountedValue);		
				
				if( empty($this->item->id_pagamento) ):
					JToolBarHelper::title('<i class="fa fa-money fa-fw"></i> ' . JText::_( 'Nova Receita' ) );
				else:
					JToolBarHelper::title('<i class="fa fa-money fa-fw"></i> ' . JText::_( 'Editar Receita' ) );
					if( $this->item->print_pagamento_metodo == 1 ):
						JToolBarHelper::modal('sendcobranca', 'fa-envelope-o', 'Enviar');
						JToolBarHelper::customLink(JRoute::_('index.php?view=finpagamento&layout=view&cid=' . $this->item->id_pagamento, false), $this->item->icon_pagamento_metodo, $this->item->icon_pagamento_metodo, $this->item->button_pagamento_metodo);			
					endif;
				endif;
		
				//JToolBarHelper::custom('nfe', 'fa-file-zip-o',  'file-zip-o', 'Gerar NFe');
		
				JToolBarHelper::apply();	
				JToolBarHelper::divider();	
				JToolBarHelper::save();
				JToolBarHelper::divider();										   
				JToolBarHelper::cancel();		

			break;
		}
	
	
	

		// Display the template*/
		parent::display($tpl);	

													   
															   
	}
}


