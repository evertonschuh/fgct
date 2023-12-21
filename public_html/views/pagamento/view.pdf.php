<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

include_once (JPATH_ADMINISTRATOR.DS.'views'.DS.'finpagamento'.DS.'language.scrips.php');

class FbtViewFinPagamento extends JView 
{
	public function display($tpl = null)
	{	
	
	
		$layout	= $this->getLayout();
		switch($layout)
		{
			case 'view':
				JRequest::setVar('tmpl','blank');
				$cobranca = $this->get( 'Cobranca' );
			break;
			case 'pdf':
				JRequest::setVar('tmpl','pdf');
				$cobranca = $this->get( 'Cobranca' );
			break;
		}
		//$document = JFactory::getDocument();
		//$document->setName('mypdf');
		//$document->setDestination()
		//$document->render();
	

	
	
		// Display the template*/
		parent::display($tpl);	
	}

}


