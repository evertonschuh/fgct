<?php

/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */


defined('_JEXEC') or die;
jimport('joomla.application.component.view');

class EASistemasViewDashboard extends JView
{
	function display($tpl = null)
	{
		JToolBarHelper::title('<i class="fa fa-home fa-fw"></i> ' . JText::_('Home'));
		/*
        $token = md5(uniqid(''));
        
		$document = JFactory::getDocument();
        
		$document->addStyleSheet('views/system/css/morris.css');
		//$document->addStyleSheet('views/system/assets/css/style.css');
		//$document->addStyleSheet('views/system/css/style-charts.css');
		//$document->addScript('views/system/js/jquery-charts.min.js');  
		//$document->addScript('views/system/js/bootstrap-charts.js'); 
		$document->addScript('views/system/js/waves.js');   
		$document->addScript('views/system/js/raphael.min.js');
		$document->addScript('views/system/js/morris.js');

		

		$graficoRosca 	=  $this->get( 'GraficoRosca' );
		$document->addScriptDeclaration("Morris.Donut({element: 'grafico_rosca',
														data: [".$graficoRosca->labels."],
													  colors: ['rgb(0, 150, 136)', 'rgb(255, 152, 0)', 'rgb(233, 30, 99)'],
												   formatter: function (y) {
														return y + ' vaga(s)'
														}
													});");
		$graficoArea 	=  $this->get( 'GraficoArea' );
													
		
		$document->addScriptDeclaration("Morris.Area({element: 'grafico_area',
														 data: [".$graficoArea->labels."],
														 xkey: 'datey',
														 ykeys: ['views', 'redirect'],
														 labels: ['Visualizações', 'Redirecionamentos'],
														 hideHover: 'auto',
														 lineColors: ['rgb(255, 152, 0)', 'rgb(0, 150, 136)'],
														 parseTime: false,
													});");
/*

		Morris.Area({
			element: 'grafico_area',
			data: [{ 
				data: '05/10/2020', 
				views: 4, 
				redirect: 1},
				{ data: '04/10/2020',
					views: 17, 
					redirect: 2},
					{ data: '30/09/2020', views: 1, redirect: 0}
				],
			xkey: 'data',
			ykeys: ['views', 'redirect'],
			labels: ['Visualizações', 'Redirecionamentos'],
			pointSize: 2,
			hideHover: 'auto',
			lineColors: ['rgb(255, 152, 0)', ''rgb(0, 150, 136)'],
			parseTime: false,
		});

/*

        Morris.Area({
            element: element,
            data: [{
                dias: '2010 Q1',
                views: 2666,
                redirects: 2647
            }, 
            xkey: 'period',
            ykeys: ['iphone', 'ipad', 'itouch'],
            labels: ['iPhone', 'iPad', 'iPod Touch'],
            pointSize: 2,
            hideHover: 'auto',
            lineColors: ['rgb(233, 30, 99)', 'rgb(0, 188, 212)', 'rgb(0, 150, 136)']
        });
















*/


		$corredors = $this->get('Corredors');
		$this->assignRef('corredors', $corredors);


		$aniversariantes = $this->get('Aniversariantes');
		$this->assignRef('aniversariantes', $aniversariantes);

		parent::display($tpl);
	}
}