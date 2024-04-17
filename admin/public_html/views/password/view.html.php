<?php
/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */
 
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

include_once (JPATH_BASE.DS.'views'.DS.'password'.DS.'language.scrips.php');

class EAsistemasViewPassword extends JView 
{
	function display($tpl = null)
	{
				
		JToolBarHelper::title('<i class="fas fa-lock fa-fw"></i> ' . JText::_( 'Alterar Senha' ) );
		
		//// Set the toolbar
		//JToolBarHelper::apply();		
				
		$this->item =  $this->get( 'Item');
				
		parent::display( $tpl);
		
	}
}