<?php

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.model' );

class EASistemasModelResults extends JModel
{

	var $_db = null;
	var $_app = null;	
	var $_data = null;
	var $_class = null;
	var $_siteOffset = null;	

	function __construct()
	{		
		$this->_db	= JFactory::getDBO();
		$this->_app = JFactory::getApplication();
		$this->_siteOffset = $this->_app->getCfg('offset');
		parent::__construct();
	}


	public function getItem()
	{
		$varsGet = JRequest::get( 'get' );
		if(empty($varsGet['prova']))
			return false;

		if($this->_db){
			switch($varsGet['get']) {
				case 'etapa':

					$response = new stdClass();
					$response->info = $this->getInfoEtapa();
					$response->tableConfig = $this->getInforTables();
					//print_r( $this->getResultsGeralEtapa());
					//exit;

					switch($varsGet['type']) {
						case 'geral':							
							$response->results = $this->getResultsGeralEtapa();
							$response->info->name_consulta = 'Resustado Geral na Etapa';
						break;
						case 'classe':							
							$response->results = $this->getResultsEtapa();
							$response->info->name_consulta = 'Resustados por Classes na Etapa';
						break;
						case 'equipe':							
							$response->results = $this->getEquipeEtapa();
							$response->info->name_consulta = 'Resustados das Equipes na Etapa';
						break;
						case 'participacao':							
							$response->results = $this->getParticipacaoEtapa();
							$response->info->name_consulta = 'Resustados de Participações por Equipes na Etapa';
						break;
						case 'especial':							
							$response->results = $this->getCustomizeEtapa();
						break;
						
					}

					return $response;

				default:
					return false;

			}

		
		}
		return false;

	}







	function getEtapa()
	{

		
		if(empty($this->_data)) {

			$varsGet = JRequest::get( 'get' );

			$vars = array(
				'id_etapa' => $varsGet['etapa'],
				'id_prova' =>  $varsGet['prova'],
			);
	
			$query = $this->_db->getQuery(true);
			$query->select( $this->_db->quoteName(array('id_etapa',
														'state_etapa',
														'name_etapa',
														'special_status_etapa',
														'special_value_etapa',
														'special_text_etapa',
														'data_beg_etapa',
														'data_end_etapa',
														'insc_beg_etapa',
														'insc_end_etapa',
														'text_etapa',													
														'id_campeonato',			
														'name_campeonato',
														'ano_campeonato',													
														'id_modalidade',			
														'name_modalidade',
														'finalized_campeonato',
														'id_prova',
														'name_prova',
														'type_prova',
														'nr_etapa_prova',
														'ns_etapa_prova',
														'ns_variavel_prova',
														'rs_etapa_prova',
														'rf_etapa_prova',
														'nr_equipe_prova',
														'decimal_prova',
														'equipe_prova',
														'special_names_prova',
														'shot_off_prova',
														'convenio_prova',
														'nsocio_prova',
														'customize_status_prova',
														'customize_value_prova',
														'customize_text_prova',
														)));
							
			$query->from($this->_db->quoteName('#__ranking_etapa'));
			$query->innerJoin($this->_db->quoteName('#__ranking_campeonato') . 'USING(' . $this->_db->quoteName('id_campeonato') . ')');
			$query->innerJoin($this->_db->quoteName('#__ranking_prova') . 'USING(' . $this->_db->quoteName('id_campeonato') . ')');	
			$query->innerJoin($this->_db->quoteName('#__ranking_modalidade') . 'USING(' . $this->_db->quoteName('id_modalidade') . ')');
			
			$query->where($this->_db->quoteName('id_etapa') . ' = ' . $this->_db->quote($vars['id_etapa']));	
			$query->where( $this->_db->quoteName('id_prova') . '=' . $this->_db->quote( $vars['id_prova'] ) );
			
			
			$query->where($this->_db->quoteName('status_etapa') . ' = ' . $this->_db->quote('1'));			
			$query->where($this->_db->quoteName('status_campeonato') . ' = ' . $this->_db->quote('1'));
	
			$query->where($this->_db->quoteName('status_modalidade') . ' = ' . $this->_db->quote('1'));
				   
			$this->_db->setQuery($query);

			$this->_data = $this->_db->loadObject();


		}


		return $this->_data; 

		/*
		$this->_special_arguments = new stdClass();
		$this->_special_arguments->special_status_etapa = isset($this->_data->special_status_etapa) ? $this->_data->special_status_etapa : NULL;
		$this->_special_arguments->special_value_etapa =  isset($this->_data->special_value_etapa) ? $this->_data->special_value_etapa : NULL;
		$this->_special_arguments->special_text_etapa =  isset($this->_data->special_text_etapa) ? $this->_data->special_text_etapa : NULL;
		*/
					
	}

	function getInforTables()
	{
		if(empty($this->_data)) 
			$this->getEtapa();

		if(!empty($this->_data))
		{

			$tagLanguage = $this->getTagLanguage();

			$addColsBeforeEtapa = $this->getAddColsBeforeEtapa();
			$addColsAfterEtapa = $this->getAddColsAfterEtapa();
			
			$tableHeaders = array();
			$tableHeaders[] = 'Posição';
			$tableHeaders[] = 'Atleta';

			$tableKeys = array();
			$tableKeys[] = 'rank';
			$tableKeys[] = 'name';

			$cols = 3;

			if( $this->_data->equipe_prova>0):
				$tableHeaders[] = 'Equipe';
				if( $this->_data->equipe_prova==1 || $this->_data->equipe_prova==2):
					$tableKeys[] = 'name_equipe';
				elseif( $this->_data->equipe_prova==3 || $this->_data->equipe_prova==4 || $this->_data->equipe_prova==5):
					$tableKeys[] = 'name_estado';
				elseif( $this->_data->equipe_prova==6 || $this->_data->equipe_prova==7):
					$tableKeys[] = 'name_pais'; 
				endif;
			else:
				$tableHeaders[] = 'Cidade';
				$tableKeys[] = 'name_cidade';
			endif;

			for ($x = 1; $x <= $this->_data->nr_etapa_prova; $x++):
						 
				if( $this->_data->ns_variavel_prova==0):
					$ns_etapa_prova = $this->_data->ns_etapa_prova;
				else:
					$ns_etapa_prova = $this->maxNs;
				endif;
				
				for ($m = 1; $m <= $ns_etapa_prova; $m++): 

					$nameField = 'r'.$x.'s'.$m;
					$tableKeys[] = $nameField;
					$cols++;
					if ( $ns_etapa_prova > 1 ): 
						$nrName = $m;
					else:
						$nrName = $x;
					endif;	
		
					if ( $this->_data->special_names_prova ):
						$tableHeaders[] = JText::_( 'EASISTEMAS_VIEWS_RESULT_ETAPA_NAME_TH_' . $tagLanguage . $this->_data->type_prova . '_' . $nrName ); 
					else:
						$tableHeaders[] = JText::sprintf('EASISTEMAS_VIEWS_RESULT_ETAPA_NAME_TH_' . $tagLanguage . $this->_data->type_prova , $nrName);
					endif; 
				endfor; 

				if ( $this->_data->nr_etapa_prova > 1 &&  $this->_data->ns_etapa_prova >1 ): 

					$nameFieldRs = 'rs'.$x;
					$tableKeys[] = $nameFieldRs;
					$cols++;
					if ( $this->_data->special_names_prova ):
						$tableHeaders[] = JText::_( 'EASISTEMAS_VIEWS_RESULT_ETAPA_NAME_TH_RS_' . $tagLanguage . $this->_data->type_prova . '_' . $x );
					else:
						$tableHeaders[] = JText::sprintf('EASISTEMAS_VIEWS_RESULT_ETAPA_NAME_TH_RS_' . $tagLanguage . $this->_data->type_prova , $x); 
					endif;
				endif;	
			endfor;


			if ( $addColsBeforeEtapa > 0 ): 
				for ($x = 1; $x <= $addColsBeforeEtapa; $x++): 
					$cols++;
					$nameColAdd = 'rAddColsBefore'.$x;
					$tableKeys[] = $nameColAdd;

					if ( $this->_data->special_names_prova ):
						$tableHeaders[] = JText::sprintf( 'EASISTEMAS_VIEWS_RESULT_ETAPA_NAME_ADDCOLSBEFORE_TH_' . $tagLanguage . $this->_data->type_prova . '_' . $x , $x);
					else:
						$tableHeaders[] = JText::sprintf('EASISTEMAS_VIEWS_RESULT_ETAPA_NAME_ADDCOLSBEFORE_TH_' . $tagLanguage . $this->_data->type_prova , $x);
					endif;
				endfor; 
			endif;

			$tableHeaders[] = JText::_( 'EASISTEMAS_VIEWS_RESULT_ETAPA_NAME_' . $tagLanguage . 'RF' );
			$tableKeys[] = 'rf';
			$cols++;
			if ( $addColsAfterEtapa > 0 ): 
				for ($x = 1; $x <= $addColsAfterEtapa; $x++) : 
					$cols++;
					$nameColAdd = 'rAddColsAfter'.$x;
					$tableKeys[] = $nameColAdd;

					if ( $this->_data->special_names_prova ):
						$tableHeaders[] = JText::sprintf( 'EASISTEMAS_VIEWS_RESULT_ETAPA_NAME_ADDCOLSAFTER_TH_' . $tagLanguage . $this->_data->type_prova . '_' . $x , $x);
					else:
						$tableHeaders[] = JText::sprintf('EASISTEMAS_VIEWS_RESULT_ETAPA_NAME_ADDCOLSAFTER_TH_' . $tagLanguage . $this->_data->type_prova . '_'.$x, $x);
					endif;
				endfor; 
			endif;

			$return = new stdClass();
			$return->cols = $cols;
			$return->tableHeaders = $tableHeaders;
			$return->tableKeys = $tableKeys;

			return $return;

		}

		return false;

	}


	function getInfoEtapa()
	{
		if(empty($this->_data)) 
			$this->getEtapa();

		if(!empty($this->_data))
		{
			$return = new stdClass();

			$return->name_etapa = $this->_data->name_etapa;
			$return->text_etapa = $this->_data->text_etapa;
			$return->data_beg_etapa = JFactory::getDate($this->_data->data_beg_etapa, $this->_siteOffset)->toISO8601(true);
			$return->data_end_etapa = JFactory::getDate($this->_data->data_end_etapa, $this->_siteOffset)->toISO8601(true);
			$return->insc_beg_etapa = JFactory::getDate($this->_data->insc_beg_etapa, $this->_siteOffset)->toISO8601(true);
			$return->insc_end_etapa = JFactory::getDate($this->_data->insc_end_etapa, $this->_siteOffset)->toISO8601(true);

			$return->name_prova = $this->_data->name_prova;
			$return->name_campeonato = $this->_data->name_campeonato;
			$return->ano_campeonato = $this->_data->ano_campeonato;
			$return->name_modalidade = $this->_data->name_modalidade;

			return $return;

		}

		return false;

	}

	function getOptionsClassEtapa()
	{
		if(empty($this->_data)) 
			$this->getEtapa();

		if(!empty($this->_data))
		{
			$options = array();	
			$options['id_prova'] = $this->_data->id_prova;
			$options['type_prova']= $this->_data->type_prova;	
			$options['nr_etapa_prova'] = $this->_data->nr_etapa_prova;
			$options['ns_etapa_prova'] = $this->_data->ns_etapa_prova;
			$options['rs_etapa_prova'] = $this->_data->rs_etapa_prova;	
			$options['rf_etapa_prova'] = $this->_data->rf_etapa_prova;	
			$options['nr_equipe_prova'] = $this->_data->nr_equipe_prova;
			$options['equipe_prova'] = $this->_data->equipe_prova;
			$options['shot_off_prova'] = $this->_data->shot_off_prova;
			$options['convenio_prova'] = $this->_data->convenio_prova;
			$options['nsocio_prova'] = $this->_data->nsocio_prova;
			$options['customize_value_prova'] = $this->_data->customize_value_prova;
			$options['customize_text_prova']= $this->_data->customize_text_prova;
			$options['id_etapa'] = $this->_data->id_etapa;;
			return $options;
		}
		
		return false;
	}
	
	function getMaxNs( $options = array() )
	{

		$varsGet = JRequest::get( 'get' );
		$vars = array(
			'id_etapa' => $varsGet['etapa'],
			'id_prova' =>  $varsGet['prova'],
		);
		
		$query = $this->_db->getQuery(true);	
		$query->select( 'MAX(ordering)');
		$query->from( '#__ranking_resultado' );
		$query->innerJoin( '#__ranking_inscricao USING(id_inscricao)' );
																																						
		$query->where('id_etapa = ' . $this->_db->quote( $vars['id_etapa'] ) );													
		$query->where('id_prova = ' . $this->_db->quote( $vars['id_prova'] ));	
	
		$this->_db->setQuery($query);

		return 	$this->_db->loadResult();
	}

	function getEquipeEtapa() {

		if(empty($this->_data)) 
			$this->getEtapa();

		if(isset($this->_data->equipe_prova) && $this->_data->equipe_prova>0){

			if($this->_data->equipe_prova==3 || $this->_data->equipe_prova==4 || $this->_data->equipe_prova==5) 
				return $this->getEquipeEstadoEtapa();				
			elseif($this->_data->equipe_prova==6 || $this->_data->equipe_prova==7) 
				return $this->getEquipePaisEtapa();				
			elseif($this->_data->equipe_prova==1 || $this->_data->equipe_prova==4) 
				return $this->getEquipeDefaultEtapa();					
			elseif($this->_data->equipe_prova==2 || $this->_data->equipe_prova==5 || $this->_data->equipe_prova==7) 
				return $this->getEquipeLivreEtapa();				
					
		}	
		else
			return false;
	}


	function getEspecialEtapaView()
	{
		if($this->_id)
		{
			if(!$this->_class)
				$this->_class = $this->getClassModalidade();

			
			if($this->_class)
				return $this->_class->getSpecialEtapaView(	'site',
															$this->_special_arguments->special_status_etapa, 
															$this->_special_arguments->special_value_etapa,
															$this->_special_arguments->special_text_etapa	);

		}	
		return false;
	}

	function getResultsEtapa()
	{
		if((boolean) $options = $this->getOptionsClassEtapa())
		{
			if(!$this->_class)
				$this->_class = $this->getClassModalidade();

			if($this->_class)
				return $this->_class->getResultsEtapa( $options );

		}	
		return false;
	}


	function getResultsGeralEtapa()
	{
		if((boolean) $options = $this->getOptionsClassEtapa())
		{
			if(!$this->_class)
				$this->_class = $this->getClassModalidade();
							
			if($this->_class)
				return $this->_class->getResultsGeralEtapa( $options );
		}	
		return false;
	}
	
	function getEquipeDefaultEtapa()
	{
		if((boolean) $options = $this->getOptionsClassEtapa())
		{
			if(!$this->_class)
				$this->_class = $this->getClassModalidade();
							
			if($this->_class)
				return $this->_class->getEquipeEtapa( $options );
		}	
		return false;
	}
	
	function getEquipeLivreEtapa()
	{
		if((boolean) $options = $this->getOptionsClassEtapa())
		{
			if(!$this->_class)
				$this->_class = $this->getClassModalidade();
							
			if($this->_class_modalidade)
				return $this->_class->getEquipeEtapaLivre( $options );
		}	
		return false;
	}
	
	function getEquipeEstadoEtapa( )
	{
		if((boolean) $options = $this->getOptionsClassEtapa())
		{
			if(!$this->_class)
				$this->_class = $this->getClassModalidade();
							
			if($this->_class)
				return $this->_class->getEquipeEstadoEtapa( $options );
		}	
		return false;
	}
	
	function getEquipePaisEtapa( )
	{
		if((boolean) $options = $this->getOptionsClassEtapa())
		{
			if(!$this->_class)
				$this->_class = $this->getClassModalidade();
							
			if($this->_class)
				return $this->_class->getEquipePaisEtapa( $options );
		}	
		return false;
	}
	
		
	function getParticipacaoEtapa()
	{
		if((boolean) $options = $this->getOptionsClassEtapa())
		{
			if(!$this->_class)
				$this->_class = $this->getClassModalidade();
							
			if($this->_class)
				return $this->_class->getParticipacaoEtapa( $options );
		}	
		return false;
	}
		

	function getCustomizeEtapa()
	{
		if((boolean) $options = $this->getOptionsClassEtapa())
		{
			if(!$this->_class)
				$this->_class = $this->getClassModalidade();
							
			if($this->_class)
				return $this->_class->getCustomizeEtapa( $options );
		}	
		return false;
	}

	function getAddColsBeforeEtapa()
	{
		if(!$this->_class)
			$this->_class = $this->getClassModalidade();
						
		if($this->_class)
			return $this->_class->getAddColsBeforeEtapa();		
	}
	function getAddColsAfterEtapa()
	{
		if(!$this->_class)
			$this->_class = $this->getClassModalidade();
						
		if($this->_class)
			return $this->_class->getAddColsAfterEtapa();	
	}

	function getSpecialEtapa()
	{
		if(!$this->_class)
			$this->_class = $this->getClassModalidade();
						
		if($this->_class)
			return $this->_class->getSpecialEtapa();
	}
	
	function getListClubesEtapa()
	{
		if($this->_id) {
			$query = $this->_db->getQuery(true);
			$query->select( $this->_db->quoteName(array('name',
														'name_cidade',
														'sigla_estado',
														)) );
			$query->from( $this->_db->quoteName('#__users') );
			$query->innerJoin( $this->_db->quoteName('#__intranet_pj') . 'ON('. $this->_db->quoteName('id'). ' = '. $this->_db->quoteName('id_user').')' );
			//$query->innerJoin( $this->_db->quoteName('#__ranking_profile') . 'ON('. $this->_db->quoteName('id'). ' = '. $this->_db->quoteName('id_user').')' );
			
			$query->leftJoin( $this->_db->quoteName('#__intranet_estado') . 'USING('. $this->_db->quoteName('id_estado').')' );
			$query->leftJoin( $this->_db->quoteName('#__intranet_cidade') . 'USING('. $this->_db->quoteName('id_cidade').')' );
			$query->innerJoin( $this->_db->quoteName('#__ranking_etapa_clube_map'). 'ON('. $this->_db->quoteName('id_user'). ' = '. $this->_db->quoteName('id_clube').')' );
			$query->where($this->_db->quoteName('id_etapa') . ' = ' . $this->_db->quote( $this->_id ));
			//$query->where($this->_db->quoteName('tipo') . ' = ' . $this->_db->quote( '1' ));
			$query->where($this->_db->quoteName('block') . ' = ' . $this->_db->quote( '0' ));
			$this->_db->setQuery($query);
			return 	$this->_db->loadObjectList();;
		}			
		return null;				
	}





		
	function getTagLanguage()
	{
		if(!$this->_class)
			$this->_class = $this->getClassModalidade();
						
		if($this->_class)
			return $this->_class->getTagLanguage();
	}

	function getClassModalidade( $id_prova = null )
	{
		$varsGet = JRequest::get( 'get' );

		if($varsGet['prova'])
		{							
		
			$query	= $this->_db->getQuery(true);
			$query->select($this->_db->quoteName('file_modalidade'));
			$query->from($this->_db->quoteName('#__ranking_prova'));
			$query->innerJoin($this->_db->quoteName('#__ranking_campeonato') . 'USING(' . $this->_db->quoteName('id_campeonato') . ')');	
			$query->innerJoin($this->_db->quoteName('#__ranking_modalidade') . 'USING(' . $this->_db->quoteName('id_modalidade') . ')');		
			$query->where($this->_db->quoteName('id_prova') . ' = ' . $this->_db->quote($varsGet['prova']));	  
			$this->_db->setQuery($query);
	
			if( !(boolean) $fileModalidade =  $this->_db->loadObject())
				return false;

			$file =  JPATH_API .DS. 'core' .DS. 'class' .DS. $fileModalidade->file_modalidade;
			
			if (!file_exists($file))
				return false;

			require_once($file);

			$prefix  = 'EASistemasClasses';
			$type = JFile::getName($fileModalidade->file_modalidade);
			$type = str_replace('.' . JFile::getExt($type), '', $type);
			$type = preg_replace('/[^A-Z0-9_\.-]/i', '', $type);
			$metodClass = $prefix . ucfirst($type);
			
			if (!class_exists($metodClass))
				return false;

			return new $metodClass();

		}	
		return false;
	}

}