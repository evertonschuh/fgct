<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

//include_once (JPATH_BASE.DS.'views'.DS.'autmessagetheme'.DS.'language.scrips.php');

class EASistemasViewMailMessageTheme extends JView 
{
	public function display($tpl = null)
	{	

		$document = JFactory::getDocument();
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

		$this->item	= $this->get('Item');
				

		if( empty($this->item->id_mailmessage_theme) )
			JToolBarHelper::title('<span class="text-muted fw-light">CONFIGURAÇÕES / E-mails  / </span> ' . JText::_('Novo Modelo') );	
		else
			JToolBarHelper::title('<span class="text-muted fw-light">CONFIGURAÇÕES / E-mails  / </span> ' . JText::_('Editar Modelo') );	
		
		
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

