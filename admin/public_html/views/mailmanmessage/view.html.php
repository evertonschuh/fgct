<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

//include_once (JPATH_BASE.DS.'views'.DS.'autmanmessage'.DS.'language.scrips.php');

class EASistemasViewMailManMessage extends JView 
{
	public function display($tpl = null)
	{	
		//// Set the toolbar
		//$this->addToolBar();

		$document 			=  JFactory::getDocument();

		$document->addStyleSheet( 'views/system/css/select2.css' );
		$document->addScript( 'views/system/js/select2.js' );
        
		$document->addScript( 'views/system/js/autmessage.js' );
/*		$document->addScript( 'views/system/js/moment-with-locales.js' );
		$document->addScript( 'views/system/js/bootstrap-datetimepicker.js' );
		$document->addstylesheet( 'views/system/css/bootstrap-datetimepicker.css' );
		*/
        
        $this->item	= $this->get('Item');
        
        
        if(isset($this->item->plugin_mailmessage_occurrence) && !empty($this->item->plugin_mailmessage_occurrence)):
		$document = JFactory::getDocument();
		$document->addScriptDeclaration( "tinymce.PluginManager.add('custon', function(editor, url) {
                                            editor.addButton('custon', {
                                                text: 'Variaveis',
                                                type: 'menubutton',
                                                icon: false,
                                                menu: [{
                                                        text: 'Abre Condicional SE',
                                                        onclick: function() {
                                                          editor.insertContent('{{SE}}');
                                                        }
                                                      }, {
                                                        text: 'Fecha Condicional SE',
                                                        onclick: function() {
                                                          editor.insertContent('{{/SE}}');
                                                        }
                                                      },"
                                                    .$this->item->plugin_mailmessage_occurrence.
                                               "]
                                            });
                                        });");
        
		endif;
				
		$occurrences = $this->get('Occurrences');
		$this->assignRef( 'occurrences', $occurrences );
        
		$themes = $this->get('Themes');
		$this->assignRef( 'themes', $themes );
        
		$tipos = $this->get('Tipos');
		$this->assignRef( 'tipos', $tipos );
				
		$eventos = $this->get('Eventos');
		$this->assignRef( 'eventos', $eventos );	
		
		/*
		$conteudos = $this->get('Conteudos');
		$this->assignRef( 'conteudos', $conteudos );		
		
		$tipos = $this->get('Tipos');
		$this->assignRef( 'tipos', $tipos );
		*/
		if( empty($this->item->id_mailmessage) )
			JToolBarHelper::title('<i class="fa fa-envelope fa-fw"></i> ' . JText::_( 'Nova Mensagem Por Ação' ) );
		else
			JToolBarHelper::title('<i class="fa fa-envelope fa-fw"></i> ' . JText::_( 'Editar Mensagem Por Ação' ) );
		// Display the template*/
		
		


		JToolBarHelper::apply();	
		//JToolBarHelper::divider();	
		JToolBarHelper::save();
		//JToolBarHelper::divider();	
		if( !empty($this->item->id_mailmessage) )
			JToolBarHelper::modal('sendcobranca', 'fa-envelope-o', 'Enviar Teste');
											   
		JToolBarHelper::cancel();														   
		
		parent::display($tpl);													   
	}
}

