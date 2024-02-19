<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

include_once (JPATH_SITE.DS.'views'.DS.'weapon'.DS.'language.scrips.php');

class EASistemasViewCalendar extends JView 
{
	public function display($tpl = null)
	{	

		
		$this->items = $this->get('Items');
		$this->modalidades = $this->get('Modalidades');

		$document 			=  JFactory::getDocument();

		$document->addStyleSheet('/assets/vendor/libs/fullcalendar/fullcalendar.css');
		$document->addScript('/assets/vendor/libs/fullcalendar/fullcalendar.js');
		//$document->addScript('/assets/vendor/libs/fullcalendar/locales/pt-br.global.js');

		$document->addStyleSheet('/assets/vendor/libs/select2/select2.css');
		$document->addScript('/assets/vendor/libs/select2/select2.js');


		$document->addStyleSheet('/assets/vendor/libs/flatpickr/flatpickr.css');
		$document->addScript('/assets/vendor/libs/flatpickr/flatpickr.js');
		
		/*
		$document->addStyleSheet('/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css');
		$document->addScript('/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js');
		$document->addScript('/assets/vendor/libs/bootstrap-datepicker/locales/bootstrap-datepicker.pt-BR.js');
		$document->addStyleSheet('/assets/vendor/libs/bootstrap-select/bootstrap-select.css');
		$document->addStyleSheet('/assets/vendor/libs/bootstrap-select/bootstrap-select.js');

		$document->addScript('/assets/js/jquery.mask.js');
		$document->addScript('/assets/js-custom/weapon.js');
	


		$document->addScriptDeclaration('window.Helpers.initCustomOptionCheck();');
		*/

		$calendarScript = '
		"use strict";
		let date = new Date
		  , nextDay = new Date((new Date).getTime() + 864e5)
		  , nextMonth = 11 === date.getMonth() ? new Date(date.getFullYear() + 1,0,1) : new Date(date.getFullYear(),date.getMonth() + 1,1)
		  , prevMonth = 11 === date.getMonth() ? new Date(date.getFullYear() - 1,0,1) : new Date(date.getFullYear(),date.getMonth() - 1,1);
		  window.events = [';


		foreach($this->items as $i => $item):
			
			$calendarScript .= '{
				id: '.$i.',
				url: "",
				title: "'.$item->name_calendar.'",

				start: "'.$item->data_beg_etapa.'",
				end: "'.$item->data_end_etapa.'",
				allDay: 1,
				extendedProps: {
					calendar: "'.$item->id_modalidade.'"
				}
			},';
		
		endforeach;

		$calendarScript .= '];';

		$document->addScript('/assets/js-custom/calendar.js');

		$document->addScriptDeclaration($calendarScript);
		


		
		

		if( empty($this->id_arma) )
			JToolBarHelper::title('<span class="text-muted fw-light">CADASTRO / Minhas Armas / </span> Nova Arma');
		else
			JToolBarHelper::title('<span class="text-muted fw-light">CADASTRO / Minhas Armas / </span> Arma número ' . $this->numero_arma);


		parent::display($tpl);	
		
		
	}

}

