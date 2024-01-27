<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

include_once (JPATH_ADMINISTRATOR.DS.'views'.DS.'documento'.DS.'language.scrips.php');

class IntranetViewDocumento extends JView 
{
	public function display($tpl = null)
	{	
		//// Set the toolbar
		$this->addToolBar();
		$item = $this->get('Item');
		$this->assignRef( 'item', $item);

		$skinDocumentos = $this->get('SkinDocumentos');
		$this->assignRef( 'skinDocumentos', $skinDocumentos);

		
		
		
		
		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');			
		$layout	= $this->getLayout();
		
		switch($layout)
		{
			case 'modalm':
		
				$this->pagination->setAdditionalUrlParam('view','documento');
				$this->pagination->setAdditionalUrlParam('layout','modalm');
				$this->pagination->setAdditionalUrlParam('cid',$item->id_documento);
				$this->pagination->setAdditionalUrlParam('tmpl','modal');
			break;
			default:
				$this->pagination->setAdditionalUrlParam('view','documento');
				$this->pagination->setAdditionalUrlParam('cid',$item->id_documento);
			break;
		}
			
		
		
		//$associados = $this->get('Associados');
		//$this->assignRef( 'associados', $associados);
		
		//$itemsAssociados = $this->get('ItemsAssociados');
		//$this->assignRef( 'itemsAssociados', $itemsAssociados);

		if( empty($item->id_documento) )
			JToolBarHelper::title('<i class="fa fa fa-folder-open fa-fw fa-fw"></i> ' . JText::_( 'Novo Documento' ) );
		else
			JToolBarHelper::title('<i class="fa fa fa-folder-open fa-fw fa-fw"></i> ' . JText::_( 'Editar Documento' ) );
		// Display the template*/
		parent::display($tpl);

		$document = JFactory::getDocument();
		//$document->addStyleSheet( 'views/system/css/select2.css' );
		//$document->addScript( 'views/system/js/select2.js' );
		$document->addScript( 'views/system/js/documento.js' );

		/*
		$document->addScriptDeclaration( "tinymce.PluginManager.add('custon', function(editor, url) {
			editor.addButton('custon', {
				text: 'Número do documento',
				type: 'menubutton',
				icon: false,
				menu: [{
						text: 'Número do Documento',
						onclick: function() {
						  editor.insertContent('{{NUMERO_DOCUMENTO}}');
						}
					  }
					
			   ]
			});
		});");
		*/
	}

	protected function addToolBar() 
	{

		JToolBarHelper::apply();	
		//JToolBarHelper::divider();	
		JToolBarHelper::save();
		//JToolBarHelper::divider();										   
		JToolBarHelper::cancel();														   
															   
	}
}

