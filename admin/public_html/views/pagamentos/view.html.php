<?php
/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */
 
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

include_once (JPATH_BASE.DS.'views'.DS.'pagamentos'.DS.'language.scrips.php');

class EASistemasViewPagamentos extends JView 
{
	function display($tpl = null)
	{	
	
		$layout	= $this->getLayout();
		switch($layout)
		{		
			case 'report':	
				$report = $this->get( 'Report' );
				$this->assignRef('report' , $report);			
			break;
			
			case 'etiqueta':	
				$etiqueta = $this->get( 'Etiqueta' );
				$this->assignRef('etiqueta' , $etiqueta);			
			break;

			default:

				$document 			=  JFactory::getDocument();
				$document->addScript( 'views/system/js/spin.js' );
				$document->addScript( 'views/system/js/loading.js' );
				
				$document->addScript( 'views/system/js/jquery.maskMoney.min.js' );
				$document->addScript( 'views/system/js/moment-with-locales.js' );
				$document->addScript( 'views/system/js/bootstrap-datetimepicker.js' );
				$document->addstylesheet( 'views/system/css/bootstrap-datetimepicker.css' );
				
				//$document->addScript( 'views/system/js/bootstrap-multiselect.js' );	
				//$document->addStyleSheet( 'views/system/css/bootstrap-multiselect.css' );
				
				$document->addStyleSheet( 'views/system/css/bootstrap-select.css' );
				$document->addScript( 'views/system/js/bootstrap-select.js' );	
				
				$document->addScript( 'views/system/js/pagamentos.js' );
				
				$this->state		= $this->get('State');
				$this->items		= $this->get('Items');
				$this->pagination	= $this->get('Pagination');		
				$this->total		= $this->get('Total');	
				$this->vtotal		= $this->get('VTotal');		
				$this->vtotalp		= $this->get('VTotalP');		
				$this->vtotald		= $this->get('VTotalD');		
				$this->_user		= JFactory::getUser();
		
				$this->pagination->setAdditionalUrlParam('view','pagamentos');
						
				$metodos =  $this->get( 'Metodos');
				$this->assignRef('metodos',	$metodos);
		
				$anuidades =  $this->get( 'Anuidades');
				$this->assignRef('anuidades',	$anuidades);
		
				$produtos =  $this->get( 'Produtos');
				$this->assignRef('produtos',	$produtos);
				
				JToolBarHelper::title('<i class="fas fa-money-check-alt"></i> ' . JText::_( 'Cobranças' ) );	

				//JToolBarHelper::customX('Cnab400teste', 'fa-sitemap', 'fa-sitemap', 'testeteste');
								
				if( $this->_user->authorize('core.financeiro.operacional', 'com_easistemas') ):
					JToolBarHelper::addNew();
					JToolBarHelper::editList();
					//if( $this->_user->get('id')=='42')
					JToolBarHelper::modal('modal-print', 'fa-download', 'Exportar');
					JToolBarHelper::publishList();
					JToolBarHelper::unpublishList();
					//JToolBarHelper::customX('plots', 'fa-sitemap', 'fa-sitemap', 'Parcelas');
				endif;

				//JToolBarHelper::customLink('index.php?view=finpagamentos&layout=report&tmpl=print', 'fa-print', 'fa-print', 'Imprimir');
				

				
				//JToolBarHelper::customLink('index.php?view=finpagamentos&format=excel', 'fa-file-excel-o', 'fa-file-excel-o', 'Relatório');
				if( $this->_user->authorize('core.financeiro.gestor', 'com_easistemas') ):
					JToolBarHelper::checkin();	
					JToolBarHelper::deleteList(JText::_( 'JGLOBAL_VIEW_TOOLBAR_DELETE_QUESTION' ));		
				endif;
				
			
				//JSubMenuHelper::addEntry('<i class="fa fa-retweet fa-fw"></i> '. JText::_( 'Anuidades' ), 'index.php?view=finanuidades');	
			//	JSubMenuHelper::addEntry('<i class="fa fa-money fa-fw"></i> '. JText::_( 'Cobranças' ), 'index.php?view=finpagamentos',true);
			//	JSubMenuHelper::addEntry('<i class="fa fa-dot-circle-o fa-fw"></i> '. JText::_( 'Taxas de Inscrição' ), 'index.php?view=fininscricaos');	
			//	JSubMenuHelper::addEntry('<i class="fa fa-university fa-fw"></i> '. JText::_( 'Métodos' ), 'index.php?view=finpmetodos');
				
				$rowTop = 
					'<table class="table table-striped">
						<thead>
							<tr>
								<th class="text-center">Integral</th>
								<th class="text-center">Desconto ou Integral</th>
								<th class="text-center">Pago</th>
								<th class="text-center">Itens</th>
							</tr>								
						</thead>
						<tbody>
						  <tr>
							<td>R$ ' . number_format($this->vtotal, 2, ',', '.') . '</td>
							<td>R$ ' . number_format($this->vtotald, 2, ',', '.') . '</td>

							<td>R$ ' . number_format($this->vtotalp, 2, ',', '.') . '</td>
							<td>' .  $this->total . '</td>
						  </tr>
						<tbody>
					</table>';
		
				//JSubInfoHelper::addEntry($rowTop);
				
				
			break;
		}			

		parent::display($tpl);

		
	}
}