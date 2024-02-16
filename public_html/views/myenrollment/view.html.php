<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class EASistemasViewMyEnrollment extends JView 
{
	public function display($tpl = null)
	{	
		
		$document = JFactory::getDocument();
		$this->item = $this->get('Item');

		$document->setName('FGCT - Inscrição ' . $this->item->id_inscricao_etapa);


		$tagLanguage = $this->get('TagLanguage');
		$this->assignRef('tagLanguage' , $tagLanguage  );	




		$reload = true;
		$base_dir_custon = JPATH_LIBRARIES .DS. 'classes';	
		$extension = 'com_torneios';
		$language_tag = 'pt-BR';
		$extension_custon = $extension . '_trap';

		$lang = JFactory::getLanguage();
		$lang->load($extension_custon, $base_dir_custon, $tagLanguage, $reload);

		$agendamentosPrint = $this->get('AgendamentosPrint');
		$this->assignRef('agendamentosPrint' , $agendamentosPrint  );	
		
		
		$additionalPrint = $this->get('AdditionalPrint');
		$this->assignRef( 'additionalPrint', $additionalPrint);

		parent::display($tpl);


	}

}

