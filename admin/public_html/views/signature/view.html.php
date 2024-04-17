<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

include_once (JPATH_BASE.DS.'views'.DS.'signature'.DS.'language.scrips.php');

class EASistemasViewSignature extends JView 
{
	public function display($tpl = null)
	{	

		$document = JFactory::getDocument();
		$document->addstylesheet( '/assets/vendor/css/pages/page-auth.css' );
		/*
		$document->addScriptDeclaration( "tinymce.PluginManager.add('variable', function (editor, url) {
																		var _dialog = false;
																		var _type_table = false;
																		var _typeOptions = [];
																		editor.ui.registry.addMenuButton('variable', {
																		icon: 'code-sample',
																		text: 'Inserir Variáveis',
																		fetch: function (callback) {
																			var items = [
																			{
																				type: 'menuitem',
																				text: 'Mensagem',
																				onAction: function() {
																				editor.insertContent('{{MENSAGEM}}');
																				}
																			}
																			];
																			callback(items);
																		}});
																	});");
*/
		$this->item	= $this->get('Item');
				

		if( empty($this->item->id_signature) )
			JToolBarHelper::title('<span class="text-muted fw-light">CONFIGURAÇÕES / Documentos / Assinaturas / </span>' . JText::_('Nova Assinatura') );	
		else
			JToolBarHelper::title('<span class="text-muted fw-light">CONFIGURAÇÕES / Documentos / Assinaturas / </span>' . JText::_('Editar Assinatura') );	
		
		
			// Display the template*/
		//JToolBarHelper::apply();	
		//JToolBarHelper::divider();	
		//JToolBarHelper::save();
		//JToolBarHelper::divider();	
		//if( !empty($this->item->id_mailmessage_theme) )
		//	JToolBarHelper::modal('sendcobranca', 'fa-envelope-o', 'Enviar Teste');
											   
		//JToolBarHelper::cancel();														   
		
		parent::display($tpl);													   
	}
}

