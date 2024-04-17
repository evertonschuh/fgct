<?php
/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */


defined('_JEXEC') or die;
jimport('joomla.application.component.view');

class EASistemasViewHome extends JView 
{
	function display($tpl = null)
	{
		$document = JFactory::getDocument();
		$document->addstylesheet( 'views/system/css/home.css' );
		parent::display( $tpl);

	}
}