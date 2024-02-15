<?php
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
//jimport( 'joomla.html.pagination' );


class TorneiosViewMyInscriProva extends JViewLegacy
{

	//protected $items;
	//protected $pagination;
	//protected $state;

	function display($tpl = null) 
	{
		
		
		$app = JFactory::getApplication();	
		$params = $app->getParams();
		$this->assignRef('params',		$params);
		
		$idSession = $this->get('Session');
		$this->assignRef('idSession',	$idSession);	
		
		$trataimagem = $this->get('TrataImagem');
		$this->assignRef('trataimagem', $trataimagem);
		
		$tagLanguage = $this->get('TagLanguage');
		$this->assignRef('tagLanguage' , $tagLanguage  );	
			
		$this->user = $this->get('User');
		
		//Get functions
		$this->item			= $this->get('Item');
		$this->items		= $this->get('Items');


		$this->pais = $this->get('Pais');
		$this->acervo = $this->get('Acervo');
		$this->especie = $this->get('Especie');
		$this->funcionamento = $this->get('Funcionamento');
		$this->marca = $this->get('Marca');
		$this->calibre = $this->get('Calibre');

	

		$generos = array();
		$categorias = array();
		$classes = array();
		
		$gencatclass = array();
		
		$inscricaoAgenda = array();
		$datasAg = array();
		$bateriasAg = array();
		$turmasAg = array();
		$postosAg = array();

	
		$layout	= $this->getLayout();
		switch($layout)
		{
			case 'comprovante':
				$inscricaoPrint = $this->get('InscricaoPrint');
				$this->assignRef( 'inscricaoPrint', $inscricaoPrint);
				
				//if($item->inscricao_agenda_prova==1):	
					$agendamentosPrint = $this->get('AgendamentosPrint');
					$this->assignRef('agendamentosPrint' , $agendamentosPrint  );	
				//endif;	
				
				$additionalPrint = $this->get('AdditionalPrint');
				$this->assignRef( 'additionalPrint', $additionalPrint);
				
			break;
			
			default:
				$model = $this->getModel('MyInscriProva');
				
				$clubes = $this->get('Clubes');
				$this->assignRef( 'clubes', $clubes);
		
				$estados = $this->get('Estados');
				$this->assignRef( 'estados', $estados);
				
				
				$etapa = $this->get('Etapa');
				$this->assignRef('etapa', $etapa);
				
				foreach($this->items as $i => $item ) {	
				
					$paramProva[$i] = $model->getParamProva($item->id_prova , $item->params_inscricao_etapa);
					$name = 'paramProva_' . $item->id_prova;
					$this->assignRef($name , $paramProva[$i]);
				
					$armas[$i] = $model->getArmas( $item->id_prova );
					$name = 'armas_' . $item->id_prova;
					$this->assignRef($name , $armas[$i]);

					$gencatclass[$i] = $model->getGenCatClass($item->id_prova );
					$name = 'gencatclass_' . $item->id_prova;
					$this->assignRef($name , $gencatclass[$i]);
					
					$generos[$i] = $model->getGenero( $item->id_prova );
					$name = 'genero_' . $item->id_prova;
					$this->assignRef($name , $generos[$i]);
					
					$categorias[$i] = $model->getCategoria( (!empty($item->id_genero) ? $item->id_genero : $gencatclass[$i]->id_genero ) );
					$name = 'categoria_' . $item->id_prova;
					$this->assignRef($name , $categorias[$i]);			
					
					$classes[$i] = $model->getClasse( (!empty($item->id_categoria) ? $item->id_categoria : $gencatclass[$i]->id_categoria ) );
					$name = 'classe_' . $item->id_prova;
					$this->assignRef($name , $classes[$i]);			
					
					//AQUI TEM Q VER SE EXISTE AGENDAMENTO E SE SIM COLOCA ESSES ABAIXO
					if($item->inscricao_agenda_prova==1):	
		
						$additionalAgenda = $this->get('AdditionalAgenda');
						$this->assignRef('additionalAgenda' , $additionalAgenda  );	
						
						$options = array();
						$options['id_prova'] = $item->id_prova;
						
						$options['id_inscricao_etapa'] = $item->id_inscricao_etapa;
						$options['inscricao_bateria_prova'] = $item->inscricao_bateria_prova;
						$options['inscricao_turma_prova'] = $item->inscricao_turma_prova;
						$options['inscricao_posto_prova'] = $item->inscricao_posto_prova;
		
						$inscricaoAgenda[$i] = $model->getInscricaoAgenda( $options );
						$name = 'inscricaoAgenda_' . $item->id_prova;
						$this->assignRef($name , $inscricaoAgenda[$i]);
					
			
						if($item->rf_inscricao_prova==1):
							$agendas = $item->nr_etapa_prova + $this->additionalAgenda;
						else:
							$agendas = $this->additionalAgenda + 1;
						endif;
						
						for ($z = 0; $z <= $agendas-1; $z++) :
						
							$options['id_inscricao_agenda'] = isset($inscricaoAgenda[$i][$z]->id_inscricao_agenda) ? $inscricaoAgenda[$i][$z]->id_inscricao_agenda : '';
							$options['date_inscricao_agenda'] = isset($inscricaoAgenda[$i][$z]->date_inscricao_agenda) ? $inscricaoAgenda[$i][$z]->date_inscricao_agenda : '';
							$options['bateria_inscricao_agenda'] = isset($inscricaoAgenda[$i][$z]->bateria_inscricao_agenda) ? $inscricaoAgenda[$i][$z]->bateria_inscricao_agenda : '';
							$options['turma_inscricao_agenda'] = isset($inscricaoAgenda[$i][$z]->turma_inscricao_agenda) ? $inscricaoAgenda[$i][$z]->turma_inscricao_agenda : '';
							$options['numero_inscricao_agenda'] = ($z+1);	
							$options['DatasAg'] = NULL;
							$options['BateriasAg'] = NULL;
							$options['TurmasAg'] = NULL;
							$options['PostosAg'] =	NULL;	
																
							if($z>0):
								$options['date_inscricao_agenda_x'] = isset($inscricaoAgenda[$i][$z-1]->date_inscricao_agenda) ? $inscricaoAgenda[$i][$z-1]->date_inscricao_agenda : '';
								$options['bateria_inscricao_agenda_x'] = isset($inscricaoAgenda[$i][$z-1]->bateria_inscricao_agenda) ? $inscricaoAgenda[$i][$z-1]->bateria_inscricao_agenda : '';
								$options['turma_inscricao_agenda_x'] = isset($inscricaoAgenda[$i][$z-1]->turma_inscricao_agenda) ? $inscricaoAgenda[$i][$z-1]->turma_inscricao_agenda : '';
								$options['posto_inscricao_agenda_x'] = isset($inscricaoAgenda[$i][$z-1]->posto_inscricao_agenda) ? $inscricaoAgenda[$i][$z-1]->posto_inscricao_agenda : '';	
							else:
								$options['date_inscricao_agenda_x'] = NULL;
								$options['bateria_inscricao_agenda_x'] = NULL;
								$options['turma_inscricao_agenda_x'] = NULL;
								$options['posto_inscricao_agenda_x'] = NULL;
							endif;
							
							$datasAg[$i][$z] = $model->getDatasAg( $options );
							$name = 'datasAg_' . $z. '_' . $item->id_prova;
							$this->assignRef($name , $datasAg[$i][$z]);	
							$options['DatasAg'] = $datasAg[$i][$z];
							
							$bateriasAg[$i][$z] = $model->getBateriasAg( $options );
							$name = 'bateriasAg_' . $z. '_' . $item->id_prova;
							$this->assignRef($name , $bateriasAg[$i][$z]);	
							$options['BateriasAg'] = $bateriasAg[$i][$z];
							
							$turmasAg[$i][$z] = $model->getTurmasAg( $options );
							$name = 'turmasAg_' . $z. '_' . $item->id_prova;
							$this->assignRef($name , $turmasAg[$i][$z]);	
							$options['TurmasAg'] = $turmasAg[$i][$z];
							
							$postosAg[$i][$z] = $model->getPostosAg( $options );
							$name = 'postosAg_' . $z. '_' . $item->id_prova;
							$this->assignRef($name , $postosAg[$i][$z]);
							$options['PostosAg'] = $postosAg[$i][$z];
						endfor;
						
						JText::script('TORNEIOS_VIEWS_INSCRICOES_AGENDAMENTO_'.$tagLanguage.'DATA');
						JText::script('TORNEIOS_VIEWS_INSCRICOES_AGENDAMENTO_'.$tagLanguage.'DATA_SELECT');
						JText::script('TORNEIOS_VIEWS_INSCRICOES_AGENDAMENTO_'.$tagLanguage.'BATERIA');
						JText::script('TORNEIOS_VIEWS_INSCRICOES_AGENDAMENTO_'.$tagLanguage.'BATERIA_SELECT');
						JText::script('TORNEIOS_VIEWS_INSCRICOES_AGENDAMENTO_'.$tagLanguage.'TURMA');
						JText::script('TORNEIOS_VIEWS_INSCRICOES_AGENDAMENTO_'.$tagLanguage.'TURMA_SELECT');
						JText::script('TORNEIOS_VIEWS_INSCRICOES_AGENDAMENTO_'.$tagLanguage.'POSTO');
						JText::script('TORNEIOS_VIEWS_INSCRICOES_AGENDAMENTO_'.$tagLanguage.'POSTO_SELECT');
						
						
						
					endif;
				}
			break;
		}
		
		// Display the template
		parent::display($tpl);
 
		// Set the document
		$this->setDocument();
	}
	
	protected function setDocument() 
	{
		// Set custom icon

		$token = md5(uniqid(''));
	
		$document = JFactory::getDocument();
		$document->addStyleSheet( 'components/com_torneios/css/bootstrap.css' );
		$document->addStyleSheet( 'components/com_torneios/css/radio-bootstrap.css?ver=1' );
		$document->addStyleSheet( 'components/com_torneios/css/checkradio.css' );
		$document->addStyleSheet( '//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.2.0/css/font-awesome.min.css' );

		//$document->addStyleSheet( 'components/com_torneios/css/autocomplete.css' );
		$document->addScript( 'components/com_torneios/js/submitbutton.js' );
		$document->addScript( 'administrator/components/com_torneios/js/jquery-1.11.2.min.js' );
		$document->addScript( 'components/com_torneios/js/bootstrap.min.js' );
		//$document->addScript( 'administrator/components/com_torneios/js/jquery.mockjax.js' );
		$document->addScript( 'administrator/components/com_torneios/js/spin.js' );
		$document->addScript( 'administrator/components/com_torneios/js/loading.js' );
		//$document->addScript( 'administrator/components/com_torneios/js/jquery.autocomplete.js' );
		//$document->addScript( 'administrator/components/com_torneios/js/jquery.maskedinput.js' );
		//$document->addScript( 'components/com_torneios/js/select2.js' );
		//$document->addStyleSheet('components/com_torneios/css/select2.css');

		$document->addScript( 'components/com_torneios/js/webservice.js?ver=1.1' );
		$document->addScript( 'components/com_torneios/js/myinscriprova.js?ver='.$token );
//		$document->addScript( 'components/com_torneios/js/bootstrap-toggle.min.js' );
		
		$document->addScript( 'administrator/components/com_torneios/js/jquerynoconflict.js' );
		
		JText::script('TORNEIOS_SCRIPT_SELET_LOADING');
		JText::script('TORNEIOS_VIEWS_CAMPEONATO_FILTER');	
		JText::script('TORNEIOS_VIEWS_PROVA_FILTER');	
		JText::script('TORNEIOS_VIEWS_ETAPA_FILTER');
		JText::script('TORNEIOS_VIEWS_GENERO_FILTER');	
		JText::script('TORNEIOS_VIEWS_CATEGORIA_FILTER');		
		JText::script('TORNEIOS_VIEWS_CLASSE_FILTER');	
		JText::script('TORNEIOS_NO_RESULT');	


	}
	

}
