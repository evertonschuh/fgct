<?php
/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */
 
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

include_once (JPATH_BASE.DS.'views'.DS.'profile'.DS.'language.scrips.php');

class EASistemasViewProfile extends JView 
{
	function display($tpl = null)
	{
		
		$token = md5(uniqid(''));
		
		$document = JFactory::getDocument();
		
		$document->addStyleSheet('/views/system/css/cropper.min.css');
		$document->addStyleSheet('/views/system/css/crop.main.css');
		$document->addStyleSheet('views/system/css/imgmanager.css');
		
		$document->addstylesheet( 'views/system/css/bootstrap-datetimepicker.css' );
				//
		$document->addScript( 'views/system/js/moment-with-locales.js' );
		$document->addScript( 'views/system/js/bootstrap-datetimepicker.js' );
		$document->addScript( 'views/system/js/jquery.bootstrap-touchspin.js' );
		
		$document->addScript( 'views/system/js/cropper.min.js' );
		$document->addScript( 'views/system/js/crop.main.js' );
		$document->addScript( 'views/system/js/jquery.maskedinput.js' );
		$document->addScript( 'views/system/js/spin.js' );
		$document->addScript( 'views/system/js/loading.js' );
		$document->addScript( 'views/system/js/util.js' );

		$document->addScript('views/system/js/perfil.js?ver='.$token);		

		//$document->addScript('/views/system/js/jquery.themepunch.tools.min.js');
		
		
		/*
		$document->addScript('/views/system/apanel/plugins/daterangepicker/moment.min.js');
		$document->addScript('/views/system/apanel/plugins/datepicker/bootstrap-datepicker.js');
		$document->addScript('/views/system/apanel/plugins/daterangepicker/daterangepicker.js');
		$document->addScript('/views/system/apanel/plugins/datepicker/locales/bootstrap-datepicker.pt-BR.js');
		$document->addStyleSheet('/views/system/apanel/plugins/datepicker/datepicker3.css');
		$document->addScript('/views/system/apanel/plugins/slimScroll/jquery.slimscroll.min.js');
		
		
		
		$document->addStyleSheet('/views/system/css/dynamic-captions.css');
		$document->addStyleSheet('/views/system/css/static-captions.css');
		$document->addScript('/views/system/js/jquery.themepunch.tools.min.js');
		$document->addScript('/views/system/js/jquery.themepunch.revolution.min.js');
		*/
		
		
		$this->item =  $this->get( 'Item');

		$ufs = $this->get('Ufs');
		$this->assignRef( 'ufs', $ufs);
		
		$paises = $this->get('Paises');
		$this->assignRef( 'paises', $paises);	
		
		$estados = $this->get('Estados');
		$this->assignRef( 'estados', $estados);
		
		$cidades = $this->get('Cidades');
		$this->assignRef( 'cidades', $cidades);	
		
		$register = $this->get('Register');
		$this->assignRef( 'register', $register);	
		
		$estadosCivil = $this->get('EstadosCivil');
		$this->assignRef( 'estadosCivil', $estadosCivil);	
				
		JToolBarHelper::title('<i class="fas fa-user fa-fw"></i> ' . JText::_( 'Perfil' ) );
		
		//// Set the toolbar
		JToolBarHelper::apply();		
				
		//$this->items =  $this->get( 'Items');
		
		/*
		$cursosDisciplinas =  $this->get( 'CursosDisciplinas');
		$this->assignRef('cursosDisciplinas',	$cursosDisciplinas);
		
		$cursosOutros =  $this->get( 'CursosOutros');
		$this->assignRef('cursosOutros',	$cursosOutros);

		$agenda =  $this->get( 'Agenda');
		$this->assignRef('agenda',	$agenda);	
		
		$avisos =  $this->get( 'Avisos');
		$this->assignRef('avisos',	$avisos);
		*/
		
		parent::display( $tpl);
		
	}
}