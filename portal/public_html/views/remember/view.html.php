<?php
/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */
 
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

include_once (JPATH_ROOT.DS.'views'.DS.'remember'.DS.'language.scrips.php');

class EASistemasViewRemember extends JView 
{
	function display($tpl = null)
	{

		JText::script('EASISTEMAS_SCRIPT_VALIDATE_ERROR_INPUT_REQUIRED');

		$document 			=  JFactory::getDocument();
		$document->addstylesheet( '/assets/vendor/css/pages/page-auth.css' );
		//$document->addScript( 'views/system/js/jquery.maskedinput.js' );
		//$document->addScript( 'views/system/js/rememail.js' );
		//$document->addScript( 'views/system/js/util.js' );
		
		//$data =  $this->get( 'Data');		
		//$this->assignRef('data' , $data	);
		
		$layout	= $this->getLayout();
		switch($layout)
		{		
			case 'result':			
				$mailResult =  $this->get( 'MailResult');		
				$this->assignRef('mailResult' , $mailResult	);	
			break;	
		}
		
			
			
		parent::display( $tpl);
	}
}