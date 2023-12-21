<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

include_once(JPATH_SITE . DS . 'views' . DS . 'treino' . DS . 'language.scrips.php');

class EASistemasViewTreino extends JView
{
	public function display($tpl = null)
	{

		$this->item = $this->get('Item');
		$this->categorias = $this->get('Categorias');
		$this->meses = $this->get('Meses');
		$this->grupos = $this->get('Grupos');


		$document = JFactory::getDocument();
		$document->addScript('/assets/vendor/libs/fullcalendar/fullcalendar.js');
		$document->addScript('/assets/vendor/libs/fullcalendar/fullcalendar_locales-all.min.js');
		$document->addStyleSheet('/assets/vendor/libs/select2/select2.css');
		$document->addScript('/assets/vendor/libs/select2/select2.js');
		$document->addScript('/assets/js-custom/treino.js');
		$document->addScriptDeclaration("jQuery(document).ready(function(){
											jQuery('.form-switch .form-check-input').click(function(){
												if(jQuery(this).is(':checked')){
													jQuery(this).next('.form-check-label').html('Ativo');
												}
												else{
													jQuery(this).next('.form-check-label').html('Inativo');
												}
											}); 
										});");
		$document->addStyleDeclaration('.fc .fc-bg-event{
											opacity: 1 !important;
										} 
										.fc-event-title {
											white-space: normal !important; 
											font-size: 10px; 
											text-align: center;
										}
										.fc .fc-daygrid-day-number {
											backgroud:"#EEEEEE",
											padding: 4px;
											position: absolute;
											z-index: 10;
											font-size: 10px;
										}');




	if(isset($this->grupos) && count($this->grupos)>0){
		foreach($this->grupos as $grupo){

		}
	}
		
/*

	$document->addScriptDeclaration(''
			calendar.render();

			
$execute = "FullCalendar.Calendar(jQuery('#treino_grupo_" . $grupo->value . "'), {
	name: 'grupo_id_1',
	headerToolbar: {
	  left: '',
	  center: 'title',
	  right: ''
	},
	locale:'pt-br',
	initialDate: '2023-07-12',
	navLinks: false,
	businessHours: false,
	editable: false,
	selectable: true,
	selectHelper: true,
	expandRows:false,

*/

		$document->addScriptDeclaration("
		
		document.addEventListener('DOMContentLoaded', function() {
			var calendarEl = document.getElementById('calendar');
		
			var calendar = new FullCalendar.Calendar(calendarEl, {
			  name: 'grupo_id_1',
			  headerToolbar: {
				left: '',
				center: 'title',
				right: ''
			  },
			  locale:'pt-br',
			  initialDate: '2023-07-12',
			  navLinks: false,
			  businessHours: false,
			  editable: false,
			  selectable: true,
			  selectHelper: true,
			  expandRows:false,
			  eventRender: function(event, element){
				element.popover({
					animation:true,
					delay: 300,
					content: '<b>Inicio</b>:'+event.description,
					trigger: 'hover'
				});
			  },
			  events: [


				{
					daysOfWeek: [6],
					display: 'background',
					color: '#ff5766',
					textColor:'#FFFF'
				},

				{
					daysOfWeek: [0],
					display: 'background',
					color: '#FFFF00'
				},

				{
					daysOfWeek: [4],
					display: 'background',
					color: '#cce5ff'
				},
				
				{
					daysOfWeek: [1,3],
					display: 'background',
					color: '#e2e3e5'
				},


				{
				  title: 'LONGÃO                  MINIMO DE 7 KM E MÁXIMO DE 12 KM',
				  start: '2023-07-08',
				  allDay: true,
				  description: 'MINIMO DE 7 KM E MÁXIMO DE 12 KM',
				  color:'transparent',
				  textColor:'#FFFF'
				},
				
				{
					title: 'REGENERATIVO 4-5km ATÉ 75% VAM (opcional)',
					start: '2023-07-09',
					allDay: true,
					description: 'REGENERATIVO 4-5km ATÉ 75% VAM (opcional)',
					color:'transparent',
					textColor:'#697a8d'
				  },

			],
			eventRender: function(info) {
				var tooltip = new Tooltip(info.el, {
				  title: info.event.extendedProps.description,
				  placement: 'top',
				  trigger: 'hover',
				  container: 'body'
				});
			  }
			});
		
			calendar.render();
		  });		
		");



		$document->addScriptDeclaration('window.Helpers.initCustomOptionCheck();');


		if( empty($this->item->id_treino) )
			JToolBarHelper::title('<span class="text-muted fw-light">SERVIÇOS / Cronograma de Treinos / </span> ' . JText::_('Novo Cronograma'));
		else
			JToolBarHelper::title('<span class="text-muted fw-light">SERVIÇOS / Cronograma de Treinos / </span> ' . $this->item->name_treino);

		parent::display($tpl);
	}
}