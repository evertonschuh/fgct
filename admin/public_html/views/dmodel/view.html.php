<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

include_once (JPATH_BASE.DS.'views'.DS.'dmodel'.DS.'language.scrips.php');

class EASistemasViewDModel extends JView 
{
	public function display($tpl = null)
	{	


		$document = JFactory::getDocument();
		$document->addScript( 'assets/js-custom/dmodel.js' );
		$document->addScriptDeclaration('window.Helpers.initCustomOptionCheck();');
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

		
		$this->item = $this->get('Item');
		$this->skinDocumentos = $this->get('SkinDocumentos');

		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->total		= $this->get('Total');
		$this->pagination	= $this->get('Pagination');			
		$layout	= $this->getLayout();
		
		switch($layout)
		{
			case 'modalm':
		
				$this->pagination->setAdditionalUrlParam('view','dmodel');
				$this->pagination->setAdditionalUrlParam('layout','modalm');
				$this->pagination->setAdditionalUrlParam('cid',$this->item->id_documento);
				$this->pagination->setAdditionalUrlParam('tmpl','modal');
			break;
			default:
				$this->pagination->setAdditionalUrlParam('view','dmodel');
				$this->pagination->setAdditionalUrlParam('cid',$this->item->id_documento);
			break;
		}
			
		
		
		//$associados = $this->get('Associados');
		//$this->assignRef( 'associados', $associados);
		
		//$itemsAssociados = $this->get('ItemsAssociados');
		//$this->assignRef( 'itemsAssociados', $itemsAssociados);

		if( empty($this->item->id_documento) )
			JToolBarHelper::title('<span class="text-muted fw-light">CONFIGURAÇÕES / Documentos / Modelos /</span> Novo Modelo');
		else
			JToolBarHelper::title('<span class="text-muted fw-light">CONFIGURAÇÕES / Documentos / Modelos /</span> Editar Modelo' );


		// Display the template*/
		parent::display($tpl);

	}
		
}

