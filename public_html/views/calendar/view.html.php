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

		$calendarStyle = '';
		foreach($this->modalidades as $i => $modalidade)
		$calendarStyle .= '.light-style .fc-event-'.$modalidade->id_modalidade.':not(.fc-list-event) {
			background-color: '.$modalidade->color_modalidade.' !important;
			color: '.$this->getContrastColor($modalidade->color_modalidade).' !important
		}
		.light-style .fc-event-'.$modalidade->id_modalidade.':not(.fc-list-event) {
			border-color: '.$this->getContrastColor($modalidade->color_modalidade).'
		}
		.light-style .fc-event-'.$modalidade->id_modalidade.'.fc-list-event .fc-list-event-dot {
			border-color: '.$this->getContrastColor($modalidade->color_modalidade).' !important
		}
		.dark-style .fc-event-'.$modalidade->id_modalidade.':not(.fc-list-event) {
			background-color: '.$modalidade->color_modalidade.' !important;
			color: '.$this->getContrastColor($modalidade->color_modalidade).' !important
		}
		.dark-style .fc-event-'.$modalidade->id_modalidade.':not(.fc-list-event) {
			border-color: '.$modalidade->color_modalidade.';
			box-shadow: none
		}
		.dark-style .fc-event-'.$modalidade->id_modalidade.'.fc-list-event .fc-list-event-dot {
			border-color: '.$this->getContrastColor($modalidade->color_modalidade).' !important
		}
		.form-check-'.$modalidade->id_modalidade.' .form-check-input:checked, .form-check-danger .form-check-input[type=checkbox]:indeterminate {
			background-color: '.$modalidade->color_modalidade.';
			/*border-color: '.$this->getContrastColor($modalidade->color_modalidade).';
			box-shadow: 0 2px 4px 0 #000;*/
		}';

		$document->addStyleDeclaration($calendarStyle);



		$calendarScript = '
		"use strict";
		window.colors =  {';

		foreach($this->modalidades as $i => $modalidade)
			$calendarScript .= $modalidade->id_modalidade . ': "'.$modalidade->id_modalidade.'",';
		$calendarScript .= '};';

		$calendarScript .= '
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

	function getContrastColor($hexColor) 
	{

        // hexColor RGB
        $R1 = hexdec(substr($hexColor, 1, 2));
        $G1 = hexdec(substr($hexColor, 3, 2));
        $B1 = hexdec(substr($hexColor, 5, 2));

        // Black RGB
        $blackColor = "#000000";
        $R2BlackColor = hexdec(substr($blackColor, 1, 2));
        $G2BlackColor = hexdec(substr($blackColor, 3, 2));
        $B2BlackColor = hexdec(substr($blackColor, 5, 2));

         // Calc contrast ratio
         $L1 = 0.2126 * pow($R1 / 255, 2.2) +
               0.7152 * pow($G1 / 255, 2.2) +
               0.0722 * pow($B1 / 255, 2.2);

        $L2 = 0.2126 * pow($R2BlackColor / 255, 2.2) +
              0.7152 * pow($G2BlackColor / 255, 2.2) +
              0.0722 * pow($B2BlackColor / 255, 2.2);

        $contrastRatio = 0;
        if ($L1 > $L2) {
            $contrastRatio = (int)(($L1 + 0.05) / ($L2 + 0.05));
        } else {
            $contrastRatio = (int)(($L2 + 0.05) / ($L1 + 0.05));
        }

        // If contrast is more than 5, return black color
        if ($contrastRatio > 8) {
            return '#000000';
        } else { 
            // if not, return white color.
            return '#FFFFFF';
        }
	}

}

