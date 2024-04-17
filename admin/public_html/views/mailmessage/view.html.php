<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

//include_once (JPATH_BASE.DS.'views'.DS.'autmessage'.DS.'language.scrips.php');

class EASistemasViewMailMessage extends JView 
{
	public function display($tpl = null)
	{	
		$document 			=  JFactory::getDocument();

	

		$document->addStyleSheet('/assets/vendor/libs/select2/select2.css');
		$document->addScript('/assets/vendor/libs/select2/select2.js');

		$document->addScript('/assets/js-custom/mailmessage.js');








        $this->item	= $this->get('Item');
        
        if(isset($this->item->plugin_mailmessage_occurrence) && !empty($this->item->plugin_mailmessage_occurrence)):

			$document->addScriptDeclaration( "tinymce.PluginManager.add('variable', function (editor, url) {
																			var _dialog = false;
																			var _type_table = false;
																			var _typeOptions = [];
																			editor.ui.registry.addMenuButton('variable', {
																			icon: 'code-sample',
																			text: 'Inserir Variáveis',
																			fetch: function (callback) {
																				var items = [
																				".$this->item->plugin_mailmessage_occurrence."
																				,{
																					type: 'menuitem',
																					text: 'Abre Condicional SE',
																					onAction: function() {
																					editor.insertContent('{{SE}}');
																					}
																				}, {
																					type: 'menuitem',
																					text: 'Fecha Condicional SE',
																					onAction: function() {
																					editor.insertContent('{{/SE}}');
																					}
																				}
																				];
																				callback(items);
																			}});
																		});");

        
		endif;
				
		$occurrences = $this->get('Occurrences');
		$this->assignRef( 'occurrences', $occurrences );
        
		$themes = $this->get('Themes');
		$this->assignRef( 'themes', $themes );
        
		if( empty($this->item->id_mailmessage) )
			JToolBarHelper::title('<span class="text-muted fw-light">CONFIGURAÇÕES / E-mails / Mensagens / </span>Nova Mensagem' );	
		else
			JToolBarHelper::title('<span class="text-muted fw-light">CONFIGURAÇÕES / E-mails / Mensagens / </span>Editar Mensagem' );	
		

		//JToolBarHelper::apply();		
		//JToolBarHelper::save();

		//if( !empty($this->item->id_mailmessage) )
		////	JToolBarHelper::modal('sendcobranca', 'fa-envelope-o', 'Enviar Teste');
											   
		//JToolBarHelper::cancel();														   							   
		
		parent::display($tpl);													   
	}
}

