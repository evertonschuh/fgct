<?php

/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class EASistemasViewLogin extends JView
{
	function display($tpl = null)
	{

		$document 	=  JFactory::getDocument();

		$document->addstylesheet( '/assets/vendor/css/pages/page-auth.css' );
		//	$document->addstylesheet( 'views/system/css/jquery.bxslider.css' );
		//$document->addScript( 'views/system/assets/vendor/js/helpers.js' );
		/*
		$document->addScript( 'views/system/assets/js/config.js' );

		$document->addScript( 'views/system/assets/vendor/libs/jquery/jquery.js' );
		$document->addScript( 'views/system/assets/vendor/libs/popper/popper.js' );
		$document->addScript( 'views/system/assets/vendor/js/bootstrap.js' );
		$document->addScript( 'views/system/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js' );
	
		$document->addScript( 'views/system/assets/vendor/js/menu.js' );



		$document->addscriptdeclaration("jQuery(document).ready(function(){
			jQuery('body').notify({
				message: 'Hello World',
				type: 'danger'
			});
			        });");
					*/
					
		parent::display($tpl);
	}
}