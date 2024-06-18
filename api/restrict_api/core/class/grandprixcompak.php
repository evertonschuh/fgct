<?php 

defined('_JEXEC') or die('Restricted access');

class TorneiosClassesGrandPrixCompak {
	
	const addColsBeforeProva = 2;
	const addColsAfterProva = 0;
	
	const addColsBeforeEtapa = 0;
	const addColsAfterEtapa = 0;
	
	const addColsBeforeClasse = 1;
	const addColsAfterClasse = 0;

	const addColsBeforeEquipe = 0;
	const addColsAfterEquipe = 0;
	
	const additionalAgenda = 0;

	const tableClassVisivle = true;
	const specialEtapa = true;
	const multiLocal = true;
	const useFinal = false;
	const tagLanguage =  'COMPAK_';

	const colsEdit = 3;
	const colsEtapa = 4;
	const colsRanking = 8;

	const colsSlideShow = 5;

	var $_db = null;
	var $_data = null;
	var $_app = null;
	var $_siteOffset = null;
	
 	function __construct() 
	{   
		$this->_db	= JFactory::getDBO();		
		$this->_app	 = JFactory::getApplication(); 
		$this->_user = JFactory::getUser();
		$this->_siteOffset = $this->_app->getCfg('offset');
		
		$token = md5(uniqid(""));
		$document = JFactory::getDocument();
		$document->addStyleSheet( 'administrator/components/com_torneios/classes/css/compak.css?v=' . $token  );		
	
		$lang = JFactory::getLanguage();

		$base_dir = JPATH_SITE;	
		
		$extension = 'com_torneios';
		$language_tag = 'pt-BR';
		$lang->load($extension, $base_dir, $language_tag, false);
				
		$reload = true;
		$base_dir_custon = JPATH_SITE .DS. 'administrator' .DS. 'components' .DS. $extension .DS. 'classes';	
		$extension_custon = $extension . '_grandprixcompak';
		$lang->load($extension_custon, $base_dir_custon, $language_tag, $reload);
	}
	
	function getUseFinal()
	{
		return self::useFinal;
	}

	function getTableClassVisivle()
	{
		return self::tableClassVisivle;
	}

	function getAddColsBeforeEquipe()
	{
		return self::addColsBeforeEquipe;
	}
		
	function getAddColsAfterEquipe()
	{
		return self::addColsAfterEquipe;
	}	
	
	function getAddColsBeforeProva()
	{
		return self::addColsBeforeProva;
	}
		
	function getAddColsAfterProva()
	{
		return self::addColsAfterProva;
	}	
	
	function getAddColsBeforeEtapa()
	{
		return self::addColsBeforeEtapa;
	}	
			
	function getAddColsAfterEtapa()
	{
		return self::addColsAfterEtapa;
	}	
		
	function getAddColsBeforeClasse()
	{
		return self::addColsBeforeClasse;
	}
		
	function getAddColsAfterClasse()
	{
		return self::addColsAfterClasse;
	}	
			
	function getAdditionalAgenda()
	{
		return self::additionalAgenda;
	}	
	
	function getSpecialEtapa()
	{
		return self::specialEtapa;
	}		
	
	function getTagLanguage()
	{
		return self::tagLanguage; 
	}
	
	function getMultiLocal()
	{
		return self::multiLocal;
	}	

	function getCustomizeEtapa( $options = array() )
	{
		return null;
	}

	function getCustomizeProva( $options = array() )
	{
		return null;
	}

	function getParamProva( $options = array() )
	{
		/*
		$registry = new JRegistry;
		$registry->loadString($options['params_inscricao_etapa']);
		$params_inscricao_etapa = $registry->toArray();
		return ????
		*/
	}
	
	function getAdditionalPrint( $options = array() )
	{
 		return;
	}
	
	function getTypeProva()
	{
		/*
		
		$returnTypeProva = array();

		$object = new stdClass();
		$object->value = 1;
		$object->text = JText::_('TORNEIOS_VIEWS_PROVA_EDIT_TIROABALA_TYPE_1');
		$returnTypeProva[] = $object;	
		
		$object = new stdClass();	
		$object->value = 2;
		$object->text = JText::_('TORNEIOS_VIEWS_PROVA_EDIT_TIROABALA_TYPE_2');
		$returnTypeProva[] = $object;
		
		$object = new stdClass();	
		$object->value = 3;
		$object->text = JText::_('TORNEIOS_VIEWS_PROVA_EDIT_TIROABALA_TYPE_3');
		$returnTypeProva[] = $object;
		
		$object = new stdClass();	
		$object->value = 4;
		$object->text = JText::_('TORNEIOS_VIEWS_PROVA_EDIT_TIROABALA_TYPE_4');
		$returnTypeProva[] = $object;	
		
		return $returnTypeProva;
		*/
	}
	
	
	function getRelatory( $options = array() )
	{
	
		$queryInscricoes = $this->_db->getQuery(true);
		$queryInscricoes->select( 'COUNT(DISTINCT(SIRES.nr_etapa_prova)) AS Inscricoes');
		$queryInscricoes->select( 'SIRES.id_inscricao');
		$queryInscricoes->from('#__ranking_resultado SIRES');		
		
		$queryInscricoes->innerJoin('#__ranking_inscricao SIINS USING(id_inscricao)');
		
		$queryInscricoes->innerJoin('#__ranking_inscricao_etapa SIIET USING(id_inscricao, id_etapa)');	
		
		$queryInscricoes->innerJoin('#__ranking_campeonato SICAM USING(id_campeonato)');	
		$queryInscricoes->innerJoin('#__ranking_modalidade SIMOD USING(id_modalidade)');
		$queryInscricoes->innerJoin('#__ranking_prova SIPRO USING(id_prova, id_campeonato)');
		$queryInscricoes->innerJoin('#__ranking_etapa SIETA USING(id_etapa, id_campeonato)');
		
		$queryInscricoes->innerJoin('#__users SIUSE ON( SIUSE.id = SIINS.id_user)');	
		
		$queryInscricoes->where('SIRES.status_resultado = 1');
		$queryInscricoes->where('SIINS.status_inscricao = 1');
		$queryInscricoes->where('SIIET.status_inscricao_etapa = 1');
		$queryInscricoes->where('SIETA.status_etapa = 1');			
		$queryInscricoes->where('SICAM.status_campeonato = 1');
		$queryInscricoes->where('SIPRO.status_prova = 1');						
		$queryInscricoes->where('SIMOD.status_modalidade = 1');
		
		$queryInscricoes->where('(' . $this->_db->quoteName('block') . '=' . $this->_db->quote( '0' ) . ' OR ' .  $this->_db->quoteName('ano_campeonato') . ' < YEAR(CURDATE()) )');
		
		if( !empty($options['id_campeonato']) )
		$queryInscricoes->where( 'SICAM.id_campeonato = ' . $options['id_campeonato']  );		
		
		if( !empty($options['id_prova']) )
		$queryInscricoes->where( 'SIINS.id_prova = ' . $options['id_prova']  );
		
		if( !empty($options['id_etapa']) ) {
		$queryInscricoes->where( 'id_etapa = ' . $options['id_etapa']  );	
		
		if( !empty($options['id_local']) )
		$queryInscricoes->where( 'SIIET.id_local = ' . $options['id_local']  );		
		}
		
		$queryInscricoes->group('id_inscricao');
		$queryInscricoes->group('SIRES.id_etapa');
		$queryInscricoes->group('SIRES.nr_etapa_prova');
		
		
		$query = $this->_db->getQuery(true);	
		
		$query->select( 'SUM(Inscricoes)  AS total_inscricoes');
		$query->select( 'COUNT(DISTINCT( #__ranking_inscricao.id_user ) ) AS total_atletas');
		$query->from('('. $queryInscricoes . ') T1');	
		
		$query->innerJoin($this->_db->quoteName('#__ranking_inscricao') . 'USING(' . $this->_db->quoteName('id_inscricao') . ')');

		$this->_db->setQuery($query);
		
		$resp = null;
		
		if ( (boolean) $relatory = $this->_db->loadObject() ) {
			$resp = '<p class="relatory">' . JText::sprintf('TORNEIOS_RELATORY_ATLETAS_TIROABALA', $relatory->total_atletas) . '</p>';
            $resp .= '<p class="relatory">' . JText::sprintf('TORNEIOS_RELATORY_INCRICOES_TIROABALA', $relatory->total_inscricoes) . '</p>';
		}
		
		return $resp;
		
	}
	
	
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//----------------------------------------------------------------------------- QUERY FR GERAL -----------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	function getQueryRF( $options = array() )
	{		
		if($options['nsocio_prova'] != 1):
		
			//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
																																															//----
			$queryPagamento = $this->_db->getQuery(true);																																	//----
			$queryPagamento->select('ano_anuidade AS ano_campeonato');																														//----
			$queryPagamento->select('id_user');																																				//----
			$queryPagamento->select('MIN(baixa_pagamento) AS data_limite');																													//----
			$queryPagamento->from( $this->_db->quoteName('#__intranet_anuidade') );																											//----
			$queryPagamento->innerJoin( $this->_db->quoteName('#__intranet_pagamento') . ' USING('. $this->_db->quoteName('id_anuidade').')');												//----
			$queryPagamento->where($this->_db->quoteName('baixa_pagamento') . ' IS NOT NULL' );																								//----
			$queryPagamento->where($this->_db->quoteName('status_pagamento') . '=' . $this->_db->quote( '1' ) );																			//----
			$queryPagamento->group($this->_db->quoteName('id_anuidade'));																													//----
			$queryPagamento->group($this->_db->quoteName('id_user'));																														//----
																																															//----
			//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		
			if($options['convenio_prova'] == 1):
			
				//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
																																															//----
				$queryConveniado = $this->_db->getQuery(true);																																//----
				$queryConveniado->select('ano_convenio_anuidade AS ano_campeonato');																										//----
				$queryConveniado->select( $this->_db->quoteName('id_user'));																												//----
				$queryConveniado->select('CONCAT(ano_convenio_anuidade, \'-01-01\') AS data_limite');																						//----
				$queryConveniado->from( $this->_db->quoteName('#__intranet_conveniado'));																									//----
				$queryConveniado->innerJoin( $this->_db->quoteName('#__intranet_convenio') . ' USING('. $this->_db->quoteName('id_convenio').')');											//----
				$queryConveniado->innerJoin( $this->_db->quoteName('#__intranet_convenio_anuidade') . ' USING('. $this->_db->quoteName('id_convenio').')');									//----
				$queryConveniado->where($this->_db->quoteName('status_convenio') . '=' . $this->_db->quote( '1' ) );																		//----
				$queryConveniado->where($this->_db->quoteName('status_convenio_anuidade') . '=' . $this->_db->quote( '1' ) );																//----
				$queryConveniado->where($this->_db->quoteName('status_conveniado') . '=' . $this->_db->quote( '1' ) );																		//----				
				$queryConveniado->group($this->_db->quoteName('id_convenio_anuidade'));																										//----
				$queryConveniado->group($this->_db->quoteName('id_user'));																													//----
																																															//----
				//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
			
			endif;
		endif;
																																															
		//----------------------------------------------------------------- RESULTADO SÉRIE ETAPAS CADA INSCRIÇÃO(SEM FINAL) ---------------------------------------------------------------------
		$queryResultsRS = $this->_db->getQuery(true);																																		//----
																																															//----
		for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) {  																															//----
			$queryResultsRS->select( 'MAX(IF( RANRESU.ordering = ' . $i . ', RANRESU.value_resultado, NULL)) AS S' . $i);																	//----
		}																																													//----
																																															//----
		switch( $options['rs_etapa_prova'] )																																				//----
		{																																													//----
			case '1':																																										//----
				$queryResultsRS->select( 'MAX(RANRESU.value_resultado) AS RS');																												//----
			break;																																											//----
																																															//----
			case '2':																																										//----
				$RFString = '';																																								//----
				for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) { 																														//----
					if(!empty($RFString))																																					//----
						$RFString = $RFString . ' + ';																																		//----
					$RFString = $RFString . ' MAX(IF(RANRESU.ordering = '. $i .', RANRESU.value_resultado, 0)) ';																			//----
				}																																											//----
				if($options['decimal_prova']==0)																																			//----
					$options['decimal_prova'] = '2';																																		//----
																																															//----
				$queryResultsRS->select( 'ROUND ( ( (' . $RFString . ') / (' . $options['ns_etapa_prova'] . ')), '. $options['decimal_prova'] .' ) AS RS');									//----
																																															//----
			break;																																											//----
																																															//----
			case '3':																																										//----
				$RFString = '';																																								//----
				for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) { 																														//----
					if(!empty($RFString))																																					//----
						$RFString = $RFString . ' + ';																																		//----
					$RFString = $RFString . ' MAX(IF(RANRESU.ordering = '. $i .', RANRESU.value_resultado, 0)) ';																			//----
				}																																											//----
																																															//----
				$queryResultsRS->select( '( ' . $RFString . ' ) AS RS');																													//----
			break;																																											//----
		}																																													//----
																																															//----
		$queryResultsRS->select( 'RANRESU.id_inscricao');																																	//----
		$queryResultsRS->select( 'RANRESU.nr_etapa_prova');																																	//----
		$queryResultsRS->select( 'RANRESU.id_etapa');																																		//----
		$queryResultsRS->select( 'RANETAPA.special_status_etapa');																															//----
		$queryResultsRS->select( 'RANIETA.id_inscricao_etapa');																																//----
		$queryResultsRS->select( 'RANIETA.shotoff_inscricao_etapa');																														//----
																																															//----
		$queryResultsRS->from( '#__ranking_resultado RANRESU' );																															//----
		$queryResultsRS->innerJoin( '#__ranking_inscricao RANINSC USING( id_inscricao )' );																									//----
		$queryResultsRS->innerJoin( '#__ranking_inscricao_etapa RANIETA USING( id_inscricao,  id_etapa )' );																				//----
		$queryResultsRS->innerJoin( '#__ranking_campeonato RANCAMP USING( id_campeonato )' );																								//----
		$queryResultsRS->innerJoin( '#__ranking_modalidade RANMODA  USING( id_modalidade )' );																								//----
		$queryResultsRS->innerJoin( '#__ranking_prova RANPROV USING( id_prova )' );																											//----
		$queryResultsRS->innerJoin( '#__ranking_etapa RANETAPA USING( id_etapa )' );																										//----
																																															//----
																																															//----
		if($options['nsocio_prova'] != 1):																																					//----
			if($options['convenio_prova'] == 1):																																			//----
				$queryResultsRS->leftJoin('('.$queryPagamento.') Associado USING('.$this->_db->quoteName('id_user').','.$this->_db->quoteName('ano_campeonato').')');						//----
				$queryResultsRS->leftJoin('('.$queryConveniado.') Conveniado USING('.$this->_db->quoteName('id_user').','.$this->_db->quoteName('ano_campeonato').')');						//----
				$queryResultsRS->where('('.$this->_db->quoteName('Associado.data_limite').'<='. $this->_db->quoteName('RANETAPA.data_end_etapa') 											//----
										  .'OR' 																																			//----
										  .$this->_db->quoteName('Conveniado.data_limite').'<='. $this->_db->quoteName('RANETAPA.data_end_etapa')											//----
				 						  .')');																																			//----	
			else:																																											//----
																																															//----
				$queryResultsRS->innerJoin('('.$queryPagamento.') Associado USING('.$this->_db->quoteName('id_user').','.$this->_db->quoteName('ano_campeonato').')');						//----
				$queryResultsRS->where( $this->_db->quoteName('Associado.data_limite').'<='. $this->_db->quoteName( 'RANETAPA.data_end_etapa' ) );											//----	
			endif;																																											//----
		endif;																																												//----																																												
																																															//----
		$queryResultsRS->where($this->_db->quoteName('RANMODA.status_modalidade') . ' = ' . $this->_db->quote('1'));																		//----
		$queryResultsRS->where($this->_db->quoteName('RANCAMP.status_campeonato') . ' = ' . $this->_db->quote('1'));																		//----
		$queryResultsRS->where($this->_db->quoteName('RANPROV.status_prova') . ' = ' . $this->_db->quote('1'));																				//----
																																															//----
		if(isset($options['id_classe']))																																					//----
			$queryResultsRS->where('RANINSC.id_classe = ' . $this->_db->quote( $options['id_classe'] ));																					//----
																																															//----
		if(!empty($options['id_etapa']))																																					//----
			$queryResultsRS->where('RANETAPA.id_etapa = ' . $this->_db->quote( $options['id_etapa'] ));																						//----
																																															//----
		if(!empty($options['id_prova']))																																					//----
			$queryResultsRS->where('RANINSC.id_prova = ' . $this->_db->quote( $options['id_prova'] ));																						//----
																																															//----
		$queryResultsRS->group('RANRESU.id_etapa');																																			//----
		$queryResultsRS->group('RANRESU.id_inscricao');																																		//----
		$queryResultsRS->group('RANRESU.nr_etapa_prova');																																	//----
																																															//----
		$queryResultsRS->order('RS DESC');																																					//----
																																															//----
		for ($i = $options['ns_etapa_prova']; $i > 0; $i--) { 																																//----
			$queryResultsRS->order('S' . $i . ' DESC');																																		//----
		}																																													//----																																		
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
																																															//----
		$queryResultsRF = $this->_db->getQuery(true);																																		//----
																																															//----
		switch( $options['rf_etapa_prova'] )																																				//----
		{																																													//----
			case '1':																																										//----
				$queryResultsRF->select( 'MAX(ResultsRS.RS) AS RF');																														//----
			break;																																											//----
																																															//----
			case '2':																																										//----
				$RFString = '';																																								//----
				for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {																														//----
					if(!empty($RFString))																																					//----
						$RFString = $RFString . ' + ';																																		//----
					$RFString = $RFString . '  MAX(IF( ResultsRS.nr_etapa_prova = '. $i .', ResultsRS.RS, 0))  ';																			//----
				}																																											//----
				if($options['decimal_prova']==0)																																			//----
					$options['decimal_prova'] = '2';																																		//----
																																															//----
				$queryResultsRF->select( 'ROUND ( ( (' . $RFString . ') / (' . $options['nr_etapa_prova'] . ')), '. $options['decimal_prova'] .' ) AS RF');									//----
			break;																																											//----
																																															//----
			case '3':																																										//----																				
				$RFString = '';																																								//----
				for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {																														//----
					if(!empty($RFString))																																					//----
						$RFString = $RFString . ' + ';																																		//----
					$RFString = $RFString . ' MAX(IF( ResultsRS.nr_etapa_prova = '. $i .', ResultsRS.RS, 0)) ';																				//----
				}																																											//----
				$queryResultsRF->select( '( ' . $RFString . ' ) AS RF');																													//----
			break;																																											//----
		}																																													//----
																																															//----
		for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {																																//----
			for ($x = 1; $x <= $options['ns_etapa_prova']; $x++) {  																														//----
				$queryResultsRF->select( 'MAX(IF( ResultsRS.nr_etapa_prova = '. $i .' , ResultsRS.S'.  $x . ', NULL)) AS r' . $i . 's' . $x);												//----
			}																																												//----
																																															//----
			$queryResultsRF->select( 'MAX(IF( ResultsRS.nr_etapa_prova = '. $i .', ResultsRS.RS, NULL)) AS rs' . $i);																		//----
		}																																													//----
																																															//----
		for ($x = 1; $x <= $options['ns_etapa_prova']; $x++) { 																																//----
			$queryResultsRF->select( 'IF(MAX(ResultsRS.RS), ResultsRS.S'.  $x . ', NULL) AS os'. $x);																						//----
		}																																													//----
																																															//----
		$queryResultsRF->select('id_inscricao');																																			//----
		$queryResultsRF->select('id_inscricao_etapa');																																		//----
		$queryResultsRF->select('id_etapa');																																				//----
		$queryResultsRF->select('special_status_etapa');																																	//----
		$queryResultsRF->select('shotoff_inscricao_etapa');																																	//----
																																															//----
		$queryResultsRF->from( '(' . $queryResultsRS . ') ResultsRS' );																														//----
		$queryResultsRF->group('id_inscricao');																																				//----
		$queryResultsRF->group('id_etapa');																																					//----
																																															//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------		
		return $queryResultsRF;
	
	}



	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------- RESULTADO ETAPA -----------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	function getResultsEtapa( $options = array() )
	{		
			
		$queryResultsRF = $this->getQueryRF( $options );
		
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
																																															//----
		$queryRP = $this->_db->getQuery(true);																																				//----
		$queryRP->select( 'IF(ISNULL(ResultsRP.rf),NULL,MAX(ResultsRP.rf)) AS rp');																											//----
		$queryRP->from( '(' . $queryResultsRF . ') ResultsRP' );																															//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
																																															//----																																										
		$queryPosition = $this->_db->getQuery(true);																																		//----
																																															//----
		$queryPosition->select('id_inscricao AS RankInscricao');																															//----
		$queryPosition->select('#__ranking_genero.ordering AS OrderGen');																													//----
		$queryPosition->select('#__ranking_categoria.ordering AS OrderCat');																												//----
		$queryPosition->select('#__ranking_classe.ordering AS OrderCla');																													//----
		$queryPosition->select('id_genero');																																				//----
		$queryPosition->select('id_categoria');																																				//----
		$queryPosition->select('id_classe');																																				//----	
		$queryPosition->select('shotoff_inscricao_etapa');																																	//----
																																															//----
		for ($i = 1; $i <=  $options['nr_etapa_prova']; $i++) { 																															//----
			$queryPosition->select( 'ResultadoPosition.rs' . $i . ' AS RankRS' . $i);																										//----
		}																																													//----
																																															//----	
		for ($i = 1; $i <=  $options['ns_etapa_prova']; $i++) { 																															//----
			$queryPosition->select( 'ResultadoPosition.os' . $i . ' AS RankOS' . $i);																										//----
		}																																													//----
																																															//----	
		for ($i = 1; $i <=  $options['nr_etapa_prova']; $i++) { 																															//----
			for ($x = 1; $x <=  $options['ns_etapa_prova']; $x++) { 																														//----
				$queryPosition->select( 'ResultadoPosition.r' . $i . 's' . $x. ' AS RankR' . $i . 'S' . $x);																				//----
			}																																												//----
		}																																													//----
																																															//----
		$queryPosition->select( 'ResultadoPosition.rf  AS RankRf');																															//----
																																															//----
		$queryPosition->from( '(' . $queryResultsRF . ') ResultadoPosition' );																												//----	
		$queryPosition->innerJoin($this->_db->quoteName('#__ranking_inscricao') . ' USING('. $this->_db->quoteName('id_inscricao').')' );													//----
																																															//----
		$queryPosition->innerJoin( '#__ranking_genero USING( id_genero, id_prova )' );																										//----
		$queryPosition->innerJoin( '#__ranking_categoria USING( id_categoria, id_genero )' );																								//----	
		$queryPosition->innerJoin( '#__ranking_classe USING( id_classe, id_categoria )' );																									//----
																																															//----
		$queryPosition->order($this->_db->quoteName('RankRf') . ' DESC');																													//----
		$queryPosition->group($this->_db->quoteName('id_inscricao'));																														//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
																																															//----
		$queryRank = $this->_db->getQuery(true);																																			//----
																																															//----
		$orderInit = '';																																									//----
		$orderInit .= 'ResultadoRank.OrderGen ASC, ResultadoRank.OrderCat ASC, ResultadoRank.OrderCla ASC, ResultadoRank.RankRf DESC';														//----
																																															//----
		$order='';																																											//----
																																											//----			
		//for ($i = $options['ns_etapa_prova']; $i > 0; $i--) { 																																//----
		//	$order .= ', ResultadoRank.RankOS' . $i . ' DESC';																																//----
		//	$concatOS[] ='ROUND(ResultadoRank.RankOS' . $i . ')';	 																														//----
		//}																																													//----
		//$concatOS = 'CONCAT(\'0.\','.implode(',',$concatOS).')';																																	//----
			
																																															//----			
		for ($i = $options['nr_etapa_prova']; $i > 0; $i--) { 																																//----
			//$order .= ', ResultadoRank.RankRS' . $i . ' DESC';																																//----
		}																																													//----
																																															//----	
		$concatOS = array();																																								//----			
		for ($i = $options['nr_etapa_prova']; $i > 0 ; $i--) {																																//----
			for ($x = $options['ns_etapa_prova']; $x > 0; $x--) {  																															//----
				$order .= ', ResultadoRank.RankR' . $i . 'S' . $x. ' DESC';																													//----
				$concatOS[] ='ROUND(ResultadoRank.RankR' . $i . 'S' . $x. ')';	 																											//----
			}																																												//----
		}																																													//----
		$concatOS = 'CONCAT(\'0.\','.implode(',',$concatOS).')';																															//----
			
		if($options['shot_off_prova']==1)																																					//----
			$order .= ', ResultadoRank.shotoff_inscricao_etapa ASC';																														//----
							

																																															//----
		$queryRank->select('id_genero');																																					//----
		$queryRank->select('id_categoria');																																					//----
		$queryRank->select('id_classe');																																					//----
		if($options['shot_off_prova']==1)																																					//----
			$queryRank->select( 'GROUP_CONCAT(ResultadoRank.RankRf + '																														//----
									. '('.$concatOS.') +	'																																//----
									. ' IF(ISNULL(ResultadoRank.shotoff_inscricao_etapa), 0,CONCAT(\'0.0000000000000000\',ResultadoRank.shotoff_inscricao_etapa))'							//----
									. ' ORDER BY ' . $orderInit . $order . ' ) AS Rank' );																									//----
		else																																												//----					
			$queryRank->select( 'GROUP_CONCAT(ResultadoRank.RankInscricao ORDER BY ' . $orderInit . $order . ' ) AS Rank' );																//----	
																																															//----
		$queryRank->from( '(' . $queryPosition . ') ResultadoRank' );																														//----
																																															//----
		$queryRank->group($this->_db->quoteName('OrderGen'));																																//----
		$queryRank->group($this->_db->quoteName('OrderCat'));																																//----
		$queryRank->group($this->_db->quoteName('OrderCla'));																																//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------																																												

		$query = $this->_db->getQuery(true);
		$query->select( $this->_db->quoteName(array( 'id_inscricao',
													 'id_inscricao_etapa',
													 'id_prova',
													 'id_user',
													 
													 'id_genero',
													 'name_genero',
													 
													 'id_categoria',
													 'name_categoria',

													 'id_classe',
													 'name_classe',

													 'name_pais',
                                                     'sgl_pais',

													 'AtletaDados.name',
												    )));



		
		$concatOS = array();	
		for ($i = $options['nr_etapa_prova']; $i > 0 ; $i--) {
			for ($x = $options['ns_etapa_prova']; $x > 0; $x--) {	
				$concatOS[] ='ROUND(Resultados.r' . $i . 's' . $x. ')';
			}
		}
		$concatOS = 'CONCAT(\'0.\','.implode(',',$concatOS).')';


		if($options['shot_off_prova']==1)
			$query->select( 'FIND_IN_SET(  Resultados.rf + '	
									. '('.$concatOS.') +	'		
									. ' IF(ISNULL(Resultados.shotoff_inscricao_etapa), 0, CONCAT(\'0.0000000000000000\',Resultados.shotoff_inscricao_etapa)) '
								    . ', (Position.Rank) ) AS rank');
		else
			$query->select( 'FIND_IN_SET( Resultados.id_inscricao, (Position.Rank) ) AS rank');
			
		
		for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) { 
			for ($x = 1; $x <= $options['ns_etapa_prova']; $x++) { 
				$query->select( 'IF(ISNULL(Resultados.r' . $i . 's' . $x . '), NULL, Resultados.r' . $i . 's' . $x . ') AS r' . $i . 's' . $x);
			}
			$query->select( 'IF(ISNULL(Resultados.rs' . $i . '), NULL, Resultados.rs' . $i . ') AS rs' . $i );
			
		}

		$query->select( 'IF(ISNULL(Resultados.rf), NULL, Resultados.rf) AS rf');

		//$query->select( 'ROUND((((Resultados.rf)*100)/(' . $queryRP . ')), 3) AS rAddColsAfter1');

		
		$query->from( $this->_db->quoteName('#__ranking_inscricao') );
		$query->innerJoin('(' . $queryResultsRF . ') Resultados USING('. $this->_db->quoteName('id_inscricao').')' );	
		$query->innerJoin('(' . $queryRank . ') Position USING('. $this->_db->quoteName('id_genero').','. $this->_db->quoteName('id_categoria').','. $this->_db->quoteName('id_classe').')' );

		$query->innerJoin( '#__ranking_campeonato USING( id_campeonato )' );
		$query->innerJoin( '#__ranking_modalidade USING( id_modalidade )' );
		$query->innerJoin( '#__ranking_prova USING( id_prova, id_campeonato )' );

		$query->innerJoin( '#__ranking_genero USING( id_genero, id_prova )' );
		$query->innerJoin( '#__ranking_categoria USING( id_categoria, id_genero )' );		
		$query->innerJoin( '#__ranking_classe USING( id_classe, id_categoria )' );
		$query->leftJoin( '#__intranet_pais USING( id_pais )' );
		
		$query->innerJoin( '#__intranet_pf USING('. $this->_db->quoteName('id_user').')' );
		$query->innerJoin( '#__users AtletaDados ON('. $this->_db->quoteName('AtletaDados.id'). ' = '. $this->_db->quoteName('id_user'). ')' );

		if(!empty($options['id_etapa']))											
			$query->where('id_etapa = ' . $this->_db->quote( $options['id_etapa'] ));																				

		if(!empty($options['id_prova']))	
			$query->where('id_prova = ' . $this->_db->quote( $options['id_prova'] ));
				
		if(!empty($options['name']))
			$query->where( $this->_db->quoteName('AtletaDados.name') . ' LIKE \'%' . $this->_db->escape($options['name']) . '%\'' );
		
		if(isset($options['id_user']))
			$query->where($this->_db->quoteName('#__ranking_inscricao.id_user') . ' = ' . $this->_db->quote( $options['id_user'] ));	
																
		$query->group($this->_db->quoteName('id_inscricao'));
		$query->group($this->_db->quoteName('id_genero'));
		$query->group($this->_db->quoteName('id_categoria'));	
		$query->group($this->_db->quoteName('id_classe'));		
		
		$query->order($this->_db->quoteName('#__ranking_genero.ordering') . ' ASC');
		$query->order($this->_db->quoteName('#__ranking_categoria.ordering') . ' ASC');	
		$query->order($this->_db->quoteName('#__ranking_classe.ordering') . ' ASC');
		
		$query->order($this->_db->quoteName('rf') . ' DESC');


		//for ($x = $options['ns_etapa_prova']; $x >= 1; $x--) { 
		//	$query->order( 'os' . $x . ' DESC');
		//}

		//if($options['nr_etapa_prova']>1 && $options['ns_etapa_prova']>1) :
		//	for ($i = $options['nr_etapa_prova']; $i >= 1; $i--) { 
			// $query->order( 'rs' . $i . ' DESC');
		//	}
		//endif;
		
		for ($i = $options['nr_etapa_prova']; $i >= 1; $i--) { 
			for ($x = $options['ns_etapa_prova']; $x >= 1; $x--) { 
				$query->order( 'r' . $i . 's' . $x . ' DESC');
			}
		}
		
		
		if($options['shot_off_prova']==1)													
			$query->order($this->_db->quoteName('shotoff_inscricao_etapa') . ' ASC');	

		$query->order($this->_db->quoteName('id_pf') . ' ASC');

		$this->_db->setQuery($query);
		return 	$this->_db->loadObjectList();
	
	}	
	

	function getResultsGeralEtapa( $options = array() )
	{
        
		$queryResultsRF = $this->getQueryRF( $options );

		
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
																																															//----
		$queryRP = $this->_db->getQuery(true);																																				//----
		$queryRP->select( 'IF(ISNULL(ResultsRP.rf),NULL,MAX(ResultsRP.rf)) AS rp');																											//----
		$queryRP->from( '(' . $queryResultsRF . ') ResultsRP' );																															//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
																																															//----
		$queryPosition = $this->_db->getQuery(true);																																		//----
																																															//----
		$queryPosition->select('id_inscricao AS RankInscricao');																															//----
		$queryPosition->select('shotoff_inscricao_etapa');																																	//----
																																															//----
		for ($i = 1; $i <=  $options['nr_etapa_prova']; $i++) { 																															//----
			$queryPosition->select( 'ResultadoPosition.rs' . $i . ' AS RankRS' . $i);																										//----
		}																																													//----
																																															//----	
		for ($i = 1; $i <=  $options['ns_etapa_prova']; $i++) { 																															//----
			$queryPosition->select( 'ResultadoPosition.os' . $i . ' AS RankOS' . $i);																										//----
		}																																													//----
																																															//----	
		for ($i = 1; $i <=  $options['nr_etapa_prova']; $i++) { 																															//----
			for ($x = 1; $x <=  $options['ns_etapa_prova']; $x++) { 																														//----
				$queryPosition->select( 'ResultadoPosition.r' . $i . 's' . $x. ' AS RankR' . $i . 'S' . $x);																				//----
			}																																												//----
		}																																													//----
																																															//----
		$queryPosition->select( 'ResultadoPosition.rf  AS RankRf');																															//----
																																															//----
		$queryPosition->from( '(' . $queryResultsRF . ') ResultadoPosition' );																												//----	
		$queryPosition->innerJoin($this->_db->quoteName('#__ranking_inscricao') . ' USING('. $this->_db->quoteName('id_inscricao').')' );													//----
																																															//----
		$queryPosition->order($this->_db->quoteName('RankRf') . ' DESC');																													//----
		$queryPosition->group($this->_db->quoteName('id_inscricao'));																														//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
																																															//----
		$queryRank = $this->_db->getQuery(true);																																			//----
																																															//----
		$orderInit = '';																																									//----
		$orderInit .= 'ResultadoRank.RankRf DESC';													                                                                                       	//----
																																															//----
		$order='';																																											//----
																																															//----			
		//for ($i = $options['ns_etapa_prova']; $i > 0; $i--) { 																															//----
		//	$order .= ', ResultadoRank.RankOS' . $i . ' DESC';																																//----
		//	$concatOS[] ='ROUND(ResultadoRank.RankOS' . $i . ')';	 																														//----
		//}																																													//----
		//$concatOS = 'CONCAT(\'0.\','.implode(',',$concatOS).')';																															//----
																																															//----			
		for ($i = $options['nr_etapa_prova']; $i > 0; $i--) { 																																//----
			//$order .= ', ResultadoRank.RankRS' . $i . ' DESC';																															//----
		}																																													//----
																																															//----	
		$concatOS = array();																																								//----			
		for ($i = $options['nr_etapa_prova']; $i > 0 ; $i--) {																																//----
			for ($x = $options['ns_etapa_prova']; $x > 0; $x--) {  																															//----
				$order .= ', ResultadoRank.RankR' . $i . 'S' . $x. ' DESC';																													//----
				$concatOS[] ='ROUND(ResultadoRank.RankR' . $i . 'S' . $x. ')';	 																											//----
			}																																												//----
		}																																													//----
		$concatOS = 'CONCAT(\'0.\','.implode(',',$concatOS).')';																															//----
																																												            //----		
		if($options['shot_off_prova']==1)																																					//----
			$order .= ', ResultadoRank.shotoff_inscricao_etapa ASC';																														//----
																																															//----
		if($options['shot_off_prova']==1)																																					//----
			$queryRank->select( 'GROUP_CONCAT(ResultadoRank.RankRf + '																														//----
									. '('.$concatOS.') +	'																																//----
									. ' IF(ISNULL(ResultadoRank.shotoff_inscricao_etapa), 0,CONCAT(\'0.0000000000000000\',ResultadoRank.shotoff_inscricao_etapa))'							//----
									. ' ORDER BY ' . $orderInit . $order . ' ) AS Rank' );																									//----
		else																																												//----		
            $queryRank->select( 'GROUP_CONCAT(ResultadoRank.RankInscricao ORDER BY ' . $orderInit . $order . ' ) AS Rank' );																//----	
																																															//----
		$queryRank->from( '(' . $queryPosition . ') ResultadoRank' );																														//----
																																															//----
																																												            //----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------																																												

		$query = $this->_db->getQuery(true);
		$query->select( $this->_db->quoteName(array( 'id_inscricao',
													 'id_prova',
													 'id_user',
													 
													 'id_genero',
													 'name_genero',
													 
													 'id_categoria',
													 'name_categoria',
													 
													 'id_classe',
													 'name_classe',
													 
													 'name_pais',
                                                     'sgl_pais',

													 'AtletaDados.name',
												    )));

		
		$concatOS = array();	
		for ($i = $options['nr_etapa_prova']; $i > 0 ; $i--) {
			for ($x = $options['ns_etapa_prova']; $x > 0; $x--) {	
				$concatOS[] ='ROUND(Resultados.r' . $i . 's' . $x. ')';
			}
		}
		$concatOS = 'CONCAT(\'0.\','.implode(',',$concatOS).')';


		if($options['shot_off_prova']==1)
			$query->select( 'FIND_IN_SET(  Resultados.rf + '	
									. '('.$concatOS.') +	'		
									. ' IF(ISNULL(Resultados.shotoff_inscricao_etapa), 0, CONCAT(\'0.0000000000000000\',Resultados.shotoff_inscricao_etapa)) '
								    . ', ('.$queryRank.') ) AS rank');
		else
			$query->select( 'FIND_IN_SET( Resultados.id_inscricao, ('.$queryRank.') ) AS rank');
			
		
		for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) { 
			for ($x = 1; $x <= $options['ns_etapa_prova']; $x++) { 
				$query->select( 'IF(ISNULL(Resultados.r' . $i . 's' . $x . '), NULL, Resultados.r' . $i . 's' . $x . ') AS r' . $i . 's' . $x);
			}
			$query->select( 'IF(ISNULL(Resultados.rs' . $i . '), NULL, Resultados.rs' . $i . ') AS rs' . $i );
			
		}

		$query->select( 'IF(ISNULL(Resultados.rf), NULL, Resultados.rf) AS rf');

		$query->select( 'ROUND((((Resultados.rf)*100)/(' . $queryRP . ')), 3) AS rAddColsAfter1');

		
		$query->from( $this->_db->quoteName('#__ranking_inscricao') );
		$query->innerJoin('(' . $queryResultsRF . ') Resultados USING('. $this->_db->quoteName('id_inscricao').')' );	
		//$query->innerJoin('(' . $queryRank . ') Position USING('. $this->_db->quoteName('id_genero').','. $this->_db->quoteName('id_categoria').','. $this->_db->quoteName('id_classe').')' );

		$query->innerJoin( '#__ranking_campeonato USING( id_campeonato )' );
		$query->innerJoin( '#__ranking_modalidade USING( id_modalidade )' );
		$query->innerJoin( '#__ranking_prova USING( id_prova, id_campeonato )' );

		$query->innerJoin( '#__ranking_genero USING( id_genero, id_prova )' );
		$query->innerJoin( '#__ranking_categoria USING( id_categoria, id_genero )' );		
		$query->innerJoin( '#__ranking_classe USING( id_classe, id_categoria )' );
		$query->leftJoin( '#__intranet_pais USING( id_pais )' );
		
		$query->innerJoin( '#__intranet_pf USING('. $this->_db->quoteName('id_user').')' );
		$query->innerJoin( '#__users AtletaDados ON('. $this->_db->quoteName('AtletaDados.id'). ' = '. $this->_db->quoteName('id_user'). ')' );

		if(!empty($options['id_etapa']))											
			$query->where('id_etapa = ' . $this->_db->quote( $options['id_etapa'] ));																				

		if(!empty($options['id_prova']))	
			$query->where('id_prova = ' . $this->_db->quote( $options['id_prova'] ));
																
		$query->group($this->_db->quoteName('id_inscricao'));
		
		$query->order($this->_db->quoteName('rf') . ' DESC');


		//for ($x = $options['ns_etapa_prova']; $x >= 1; $x--) { 
		//	$query->order( 'os' . $x . ' DESC');
		//}

		//if($options['nr_etapa_prova']>1 && $options['ns_etapa_prova']>1) :
		//	for ($i = $options['nr_etapa_prova']; $i >= 1; $i--) { 
			// $query->order( 'rs' . $i . ' DESC');
		//	}
		//endif;
		
		for ($i = $options['nr_etapa_prova']; $i >= 1; $i--) { 
			for ($x = $options['ns_etapa_prova']; $x >= 1; $x--) { 
				$query->order( 'r' . $i . 's' . $x . ' DESC');
			}
		}
		
		
		if($options['shot_off_prova']==1)													
			$query->order($this->_db->quoteName('shotoff_inscricao_etapa') . ' ASC');	

		$query->order($this->_db->quoteName('id_pf') . ' ASC');

		$this->_db->setQuery($query);
		return 	$this->_db->loadObjectList();
	}

	
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//-------------------------------------------------------------------------------- EQUIPES ESTADOS ETAPA -------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	
	
	function getEquipeEstadoEtapa( $options = array() )
	{		
	
		//------------------------------------------------------------------------------------ CALC RANKING --------------------------------------------------------------------------------------
																																															//----
		$queryResultsRS = $this->_db->getQuery(true);																																		//----
																																															//----
		switch( $options['rs_etapa_prova'] )																																				//----
		{																																													//----
			case '1':																																										//----
				$queryResultsRS->select( 'MAX(RANRESU.value_resultado) AS RS');																												//----
			break;																																											//----
																																															//----
			case '2':																																										//----
				$RFString = '';																																								//----
				for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) { 																														//----
					if(!empty($RFString))																																					//----
						$RFString = $RFString . ' + ';																																		//----
					$RFString = $RFString . ' MAX(IF(RANRESU.ordering = '. $i .', RANRESU.value_resultado, 0)) ';																			//----
				}																																											//----
				if($options['decimal_prova']==0)																																			//----
					$options['decimal_prova'] = '2';																																		//----
																																															//----
				$queryResultsRS->select( 'ROUND ( ( (' . $RFString . ') / (' . $options['ns_etapa_prova'] . ')), '. $options['decimal_prova'] .' ) AS RS');									//----
																																															//----
			break;																																											//----
																																															//----
			case '3':																																										//----
				$RFString = '';																																								//----
				for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) { 																														//----
					if(!empty($RFString))																																					//----
						$RFString = $RFString . ' + ';																																		//----
					$RFString = $RFString . ' MAX(IF(RANRESU.ordering = '. $i .', RANRESU.value_resultado, 0)) ';																			//----
				}																																											//----
																																															//----
				$queryResultsRS->select( '( ' . $RFString . ' ) AS RS');																													//----
			break;																																											//----
		}																																													//----
																																															//----
		$queryResultsRS->select( 'RANRESU.id_inscricao');																																	//----
		$queryResultsRS->select( 'RANRESU.nr_etapa_prova');																																	//----
		$queryResultsRS->select( 'RANRESU.id_etapa');																																		//----
		$queryResultsRS->select( 'RANINSC.id_estado');																																		//----
																																															//----
		$queryResultsRS->from( '#__ranking_resultado RANRESU' );																															//----
		$queryResultsRS->innerJoin( '#__ranking_inscricao RANINSC USING( id_inscricao )' );																									//----
		$queryResultsRS->innerJoin( '#__ranking_inscricao_etapa RANIETA USING( id_inscricao,  id_etapa )' );																				//----
		$queryResultsRS->innerJoin( '#__ranking_campeonato RANCAMP USING( id_campeonato )' );																								//----
		$queryResultsRS->innerJoin( '#__ranking_modalidade RANMODA  USING( id_modalidade )' );																								//----
		$queryResultsRS->innerJoin( '#__ranking_prova RANPROV USING( id_prova )' );																											//----
																																															//----
		if( !$this->_user->authorise('torneios.diretor.geral', 'com_torneios') )																											//----
			$queryResultsRS->where($this->_db->quoteName('RANMODA.status_modalidade') . ' = ' . $this->_db->quote('1'));																	//----
																																															//----
		if( !$this->_user->authorise('torneios.diretor.modalidade', 'com_torneios') )																										//----
		{																																													//----
			$queryResultsRS->where($this->_db->quoteName('RANCAMP.status_campeonato') . ' = ' . $this->_db->quote('1'));																	//----
			$queryResultsRS->where($this->_db->quoteName('RANPROV.status_prova') . ' = ' . $this->_db->quote('1'));																			//----
		}																																													//----
																																															//----						
		if(!empty($options['id_prova']))																																					//----
			$queryResultsRS->where('RANINSC.id_prova = ' . $this->_db->quote( $options['id_prova'] ));																						//----
																																															//----
		if(!empty($options['id_etapa']))																																					//----
			$queryResultsRS->where('RANRESU.id_etapa = ' . $this->_db->quote( $options['id_etapa'] ));																						//----
																																															//----																																															
		$queryResultsRS->group('RANRESU.id_etapa');																																			//----					
		$queryResultsRS->group('RANRESU.id_inscricao');																																		//----
		$queryResultsRS->group('RANRESU.nr_etapa_prova');																																	//----
																																															//----
		$queryResultsRS->order('RS DESC');																																					//----									
																																															//----	
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
																																															//----
		$queryResultsRF = $this->_db->getQuery(true);																																		//----
																																															//----
		switch( $options['rf_etapa_prova'] )																																				//----
		{																																													//----
			case '1':																																										//----
				$queryResultsRF->select( 'MAX(ResultsRS.RS) AS RF');																														//----
			break;																																											//----
																																															//----
			case '2':																																										//----
				$RFString = '';																																								//----
				for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {																														//----
					if(!empty($RFString))																																					//----
						$RFString = $RFString . ' + ';																																		//----
					$RFString = $RFString . '  MAX(IF( ResultsRS.nr_etapa_prova = '. $i .', ResultsRS.RS, 0))  ';																			//----
				}																																											//----
				if($options['decimal_prova']==0)																																			//----
					$options['decimal_prova'] = '2';																																		//----
																																															//----
				$queryResultsRF->select( 'ROUND ( ( (' . $RFString . ') / (' . $options['nr_etapa_prova'] . ')), '. $options['decimal_prova'] .' ) AS RF');									//----
			break;																																											//----
																																															//----
			case '3':																																										//----																				
				$RFString = '';																																								//----
				for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {																														//----
					if(!empty($RFString))																																					//----
						$RFString = $RFString . ' + ';																																		//----
					$RFString = $RFString . ' MAX(IF( ResultsRS.nr_etapa_prova = '. $i .', ResultsRS.RS, 0)) ';																				//----
				}																																											//----
				$queryResultsRF->select( '( ' . $RFString . ' ) AS RF');																													//----
			break;																																											//----
		}																																													//----
																																															//----
		$queryResultsRF->select('id_inscricao');																																			//----
		$queryResultsRF->select('id_estado');																																				//----
		$queryResultsRF->select('id_etapa');																																				//----
																																															//----
		$queryResultsRF->from( '(' . $queryResultsRS . ') ResultsRS' );																														//----
																																															//----
		$queryResultsRF->group('id_inscricao');																																				//----
		$queryResultsRF->group('id_estado');																																				//----
		$queryResultsRF->group('id_etapa');																																					//----
																																															//----
		$queryResultsRF->order($this->_db->quoteName('RF') . ' DESC');																														//----
		$queryResultsRF->order($this->_db->quoteName('id_etapa') . ' ASC');																													//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
																																															//----
		$queryRankPos = $this->_db->getQuery(true);																																			//----																																													
																																															//----
		$queryFinal = '';																																									//----
		$queryRankPos->select( 'id_estado');																																				//----
																																															//----
		for ($i = 1; $i <= $options['nr_equipe_prova']; $i++) { 																															//----
			if(!empty($queryFinal))																																							//----
				$queryFinal .= ' + ';																																						//----
																																															//----
			$queryFinal .= 	'IF(COUNT(ResultadoRankPos.RF) > '.($i-1).																														//----
							', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultadoRankPos.RF ORDER BY ResultadoRankPos.RF DESC ),'													//----
							.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), 0)';																						//----		
		}																																													//----
																																															//----
		if(!empty($queryFinal))																																								//----
			$queryRankPos->select( '('.$queryFinal.') AS RankRF');																															//----
																																															//----
		$queryRankPos->from( '(' . $queryResultsRF . ') ResultadoRankPos' );																												//----
		$queryRankPos->group('id_estado');																																					//----
		$queryRankPos->order('RankRF DESC');																																				//----																																											
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------		
																																															//----																																										
		$queryRank = $this->_db->getQuery(true);																																			//----
		$queryRank->select( 'GROUP_CONCAT( ResultadoRank.RankRF )' );																														//----							
		$queryRank->from( '(' . $queryRankPos . ') ResultadoRank' );																														//----																																									
																																															//----																																												
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------																																												
																																													
		$query = $this->_db->getQuery(true);	
		$query->select( 'name_estado AS name');																					
		
		for ($i = 1; $i <= $options['nr_equipe_prova']; $i++) { 
			$query->select( 'IF(COUNT(ResultsEtapa.RF) > '.($i-1).
							', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultsEtapa.RF ORDER BY ResultsEtapa.RF DESC ),'
							.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), NULL) AS r' . $i);	
		}
		
		
		$queryFinal = '';
		for ($i = 1; $i <= $options['nr_equipe_prova']; $i++) {  
			if(!empty($queryFinal))
				$queryFinal .= ' + ';
		
			$queryFinal .= 	'IF(COUNT(ResultsEtapa.RF) > '.($i-1).
							', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultsEtapa.RF ORDER BY ResultsEtapa.RF DESC),'
							.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), 0)';		
		}
		
		if(!empty($queryFinal))
			$query->select( '('.$queryFinal.') as rf');
				
		
		$query->select( 'FIND_IN_SET( ('.$queryFinal.'), (' . $queryRank . ')) AS rank');
			
		$query->from( '(' . $queryResultsRF . ') ResultsEtapa' );
		$query->innerJoin( '#__intranet_estado USING ('. $this->_db->quoteName('id_estado'). ')' );	
		$query->group('id_estado');																																			

		$query->order('rf desc');	
				
		$this->_db->setQuery($query);
		return 	$this->_db->loadObjectList();	
	
	}	

    //--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//-------------------------------------------------------------------------------- EQUIPES PAIS ETAPA -------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	
	
	function getEquipePaisEtapa( $options = array() )
	{		
	
		//------------------------------------------------------------------------------------ CALC RANKING --------------------------------------------------------------------------------------
																																															//----
		$queryResultsRS = $this->_db->getQuery(true);																																		//----
																																															//----
		switch( $options['rs_etapa_prova'] )																																				//----
		{																																													//----
			case '1':																																										//----
				$queryResultsRS->select( 'MAX(RANRESU.value_resultado) AS RS');																												//----
			break;																																											//----
																																															//----
			case '2':																																										//----
				$RFString = '';																																								//----
				for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) { 																														//----
					if(!empty($RFString))																																					//----
						$RFString = $RFString . ' + ';																																		//----
					$RFString = $RFString . ' MAX(IF(RANRESU.ordering = '. $i .', RANRESU.value_resultado, 0)) ';																			//----
				}																																											//----
				if($options['decimal_prova']==0)																																			//----
					$options['decimal_prova'] = '2';																																		//----
																																															//----
				$queryResultsRS->select( 'ROUND ( ( (' . $RFString . ') / (' . $options['ns_etapa_prova'] . ')), '. $options['decimal_prova'] .' ) AS RS');									//----
																																															//----
			break;																																											//----
																																															//----
			case '3':																																										//----
				$RFString = '';																																								//----
				for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) { 																														//----
					if(!empty($RFString))																																					//----
						$RFString = $RFString . ' + ';																																		//----
					$RFString = $RFString . ' MAX(IF(RANRESU.ordering = '. $i .', RANRESU.value_resultado, 0)) ';																			//----
				}																																											//----
																																															//----
				$queryResultsRS->select( '( ' . $RFString . ' ) AS RS');																													//----
			break;																																											//----
		}																																													//----
																																															//----
		$queryResultsRS->select( 'RANRESU.id_inscricao');																																	//----
		$queryResultsRS->select( 'RANRESU.nr_etapa_prova');																																	//----
		$queryResultsRS->select( 'RANRESU.id_etapa');																																		//----
		$queryResultsRS->select( 'RANINSC.id_pais');																																		//----
																																															//----
		$queryResultsRS->from( '#__ranking_resultado RANRESU' );																															//----
		$queryResultsRS->innerJoin( '#__ranking_inscricao RANINSC USING( id_inscricao )' );																									//----
		$queryResultsRS->innerJoin( '#__ranking_inscricao_etapa RANIETA USING( id_inscricao,  id_etapa )' );																				//----
		$queryResultsRS->innerJoin( '#__ranking_campeonato RANCAMP USING( id_campeonato )' );																								//----
		$queryResultsRS->innerJoin( '#__ranking_modalidade RANMODA  USING( id_modalidade )' );																								//----
		$queryResultsRS->innerJoin( '#__ranking_prova RANPROV USING( id_prova )' );																											//----
																																															//----
		if( !$this->_user->authorise('torneios.diretor.geral', 'com_torneios') )																											//----
			$queryResultsRS->where($this->_db->quoteName('RANMODA.status_modalidade') . ' = ' . $this->_db->quote('1'));																	//----
																																															//----
		if( !$this->_user->authorise('torneios.diretor.modalidade', 'com_torneios') )																										//----
		{																																													//----
			$queryResultsRS->where($this->_db->quoteName('RANCAMP.status_campeonato') . ' = ' . $this->_db->quote('1'));																	//----
			$queryResultsRS->where($this->_db->quoteName('RANPROV.status_prova') . ' = ' . $this->_db->quote('1'));																			//----
		}																																													//----
																																															//----						
		if(!empty($options['id_prova']))																																					//----
			$queryResultsRS->where('RANINSC.id_prova = ' . $this->_db->quote( $options['id_prova'] ));																						//----
																																															//----
		if(!empty($options['id_etapa']))																																					//----
			$queryResultsRS->where('RANRESU.id_etapa = ' . $this->_db->quote( $options['id_etapa'] ));																						//----
																																															//----																																															
		$queryResultsRS->group('RANRESU.id_etapa');																																			//----					
		$queryResultsRS->group('RANRESU.id_inscricao');																																		//----
		$queryResultsRS->group('RANRESU.nr_etapa_prova');																																	//----
																																															//----
		$queryResultsRS->order('RS DESC');																																					//----									
																																															//----	
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
																																															//----
		$queryResultsRF = $this->_db->getQuery(true);																																		//----
																																															//----
		switch( $options['rf_etapa_prova'] )																																				//----
		{																																													//----
			case '1':																																										//----
				$queryResultsRF->select( 'MAX(ResultsRS.RS) AS RF');																														//----
			break;																																											//----
																																															//----
			case '2':																																										//----
				$RFString = '';																																								//----
				for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {																														//----
					if(!empty($RFString))																																					//----
						$RFString = $RFString . ' + ';																																		//----
					$RFString = $RFString . '  MAX(IF( ResultsRS.nr_etapa_prova = '. $i .', ResultsRS.RS, 0))  ';																			//----
				}																																											//----
				if($options['decimal_prova']==0)																																			//----
					$options['decimal_prova'] = '2';																																		//----
																																															//----
				$queryResultsRF->select( 'ROUND ( ( (' . $RFString . ') / (' . $options['nr_etapa_prova'] . ')), '. $options['decimal_prova'] .' ) AS RF');									//----
			break;																																											//----
																																															//----
			case '3':																																										//----																				
				$RFString = '';																																								//----
				for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {																														//----
					if(!empty($RFString))																																					//----
						$RFString = $RFString . ' + ';																																		//----
					$RFString = $RFString . ' MAX(IF( ResultsRS.nr_etapa_prova = '. $i .', ResultsRS.RS, 0)) ';																				//----
				}																																											//----
				$queryResultsRF->select( '( ' . $RFString . ' ) AS RF');																													//----
			break;																																											//----
		}																																													//----
																																															//----
		$queryResultsRF->select('id_inscricao');																																			//----
		$queryResultsRF->select('id_pais');																																				//----
		$queryResultsRF->select('id_etapa');																																				//----
																																															//----
		$queryResultsRF->from( '(' . $queryResultsRS . ') ResultsRS' );																														//----
																																															//----
		$queryResultsRF->group('id_inscricao');																																				//----
		$queryResultsRF->group('id_pais');																																				//----
		$queryResultsRF->group('id_etapa');																																					//----
																																															//----
		$queryResultsRF->order($this->_db->quoteName('RF') . ' DESC');																														//----
		$queryResultsRF->order($this->_db->quoteName('id_etapa') . ' ASC');																													//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
																																															//----
		$queryRankPos = $this->_db->getQuery(true);																																			//----																																													
																																															//----
		$queryFinal = '';																																									//----
		$queryRankPos->select( 'id_pais');																																				//----
																																															//----
		for ($i = 1; $i <= $options['nr_equipe_prova']; $i++) { 																															//----
			if(!empty($queryFinal))																																							//----
				$queryFinal .= ' + ';																																						//----
																																															//----
			$queryFinal .= 	'IF(COUNT(ResultadoRankPos.RF) > '.($i-1).																														//----
							', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultadoRankPos.RF ORDER BY ResultadoRankPos.RF DESC ),'													//----
							.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), 0)';																						//----		
		}																																													//----
																																															//----
		if(!empty($queryFinal))																																								//----
			$queryRankPos->select( '('.$queryFinal.') AS RankRF');																															//----
																																															//----
		$queryRankPos->from( '(' . $queryResultsRF . ') ResultadoRankPos' );																												//----
		$queryRankPos->group('id_pais');																																					//----
		$queryRankPos->order('RankRF DESC');																																				//----																																											
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------		
																																															//----																																										
		$queryRank = $this->_db->getQuery(true);																																			//----
		$queryRank->select( 'GROUP_CONCAT( ResultadoRank.RankRF )' );																														//----							
		$queryRank->from( '(' . $queryRankPos . ') ResultadoRank' );																														//----																																									
																																															//----																																												
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------																																												
																																													
		$query = $this->_db->getQuery(true);	
		$query->select( 'name_pais AS name');	
		$query->select( 'sgl_pais');
        
		
		for ($i = 1; $i <= $options['nr_equipe_prova']; $i++) { 
			$query->select( 'IF(COUNT(ResultsEtapa.RF) > '.($i-1).
							', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultsEtapa.RF ORDER BY ResultsEtapa.RF DESC ),'
							.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), NULL) AS r' . $i);	
		}
		
		
		$queryFinal = '';
		for ($i = 1; $i <= $options['nr_equipe_prova']; $i++) {  
			if(!empty($queryFinal))
				$queryFinal .= ' + ';
		
			$queryFinal .= 	'IF(COUNT(ResultsEtapa.RF) > '.($i-1).
							', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultsEtapa.RF ORDER BY ResultsEtapa.RF DESC),'
							.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), 0)';		
		}
		
		if(!empty($queryFinal))
			$query->select( '('.$queryFinal.') as rf');
				
		
		$query->select( 'FIND_IN_SET( ('.$queryFinal.'), (' . $queryRank . ')) AS rank');
			
		$query->from( '(' . $queryResultsRF . ') ResultsEtapa' );
		$query->innerJoin( '#__intranet_pais USING ('. $this->_db->quoteName('id_pais'). ')' );	
		$query->group('id_pais');																																			

		$query->order('rf desc');	
				
		$this->_db->setQuery($query);
		return 	$this->_db->loadObjectList();	
	
	}	

	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//------------------------------------------------------------------------------------- EQUIPES POR ETAPA ------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	
	function getEquipeEtapaLivre( $options = array() )
	{		
		
		//------------------------------------------------------------------------------------ CALC RANKING --------------------------------------------------------------------------------------
																																															//----
		$queryResultsRS = $this->_db->getQuery(true);																																		//----
																																															//----
		for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) {  																															//----
			$queryResultsRS->select( 'MAX(IF( RANRESU.ordering = ' . $i . ', RANRESU.value_resultado, 0)) AS S' . $i);																		//----
		}																																													//----
																																															//----
		switch( $options['rs_etapa_prova'] )																																				//----
		{																																													//----
			case '1':																																										//----
				$queryResultsRS->select( 'MAX(RANRESU.value_resultado) AS RS');																												//----
			break;																																											//----
																																															//----
			case '2':																																										//----
				$RFString = '';																																								//----
				for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) { 																														//----
					if(!empty($RFString))																																					//----
						$RFString = $RFString . ' + ';																																		//----
					$RFString = $RFString . ' MAX(IF(RANRESU.ordering = '. $i .', RANRESU.value_resultado, 0)) ';																			//----
				}																																											//----
				if($options['decimal_prova']==0)																																			//----
					$options['decimal_prova'] = '2';																																		//----
																																															//----
				$queryResultsRS->select( 'ROUND ( ( (' . $RFString . ') / (' . $options['ns_etapa_prova'] . ')), '. $options['decimal_prova'] .' ) AS RS');									//----
																																															//----
			break;																																											//----
																																															//----
			case '3':																																										//----
				$RFString = '';																																								//----
				for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) { 																														//----
					if(!empty($RFString))																																					//----
						$RFString = $RFString . ' + ';																																		//----
					$RFString = $RFString . ' MAX(IF(RANRESU.ordering = '. $i .', RANRESU.value_resultado, 0)) ';																			//----
				}																																											//----
																																															//----
				$queryResultsRS->select( '( ' . $RFString . ' ) AS RS');																													//----
			break;																																											//----
		}																																													//----
																																															//----
		$queryResultsRS->select( 'RANRESU.id_inscricao');																																	//----
		$queryResultsRS->select( 'RANRESU.nr_etapa_prova');																																	//----
		$queryResultsRS->select( 'RANRESU.id_etapa');																																		//----
		//$queryResultsRS->select( 'RANINSC.id_equipe');																																	//----
		$queryResultsRS->select( 'RANIETA.id_livre_inscricao_etapa');																														//----
																																															//----
		$queryResultsRS->from( '#__ranking_resultado RANRESU' );																															//----
		$queryResultsRS->innerJoin( '#__ranking_inscricao RANINSC USING( id_inscricao )' );																									//----
		$queryResultsRS->innerJoin( '#__ranking_inscricao_etapa RANIETA USING( id_inscricao,  id_etapa )' );																				//----
		$queryResultsRS->innerJoin( '#__ranking_campeonato RANCAMP USING( id_campeonato )' );																								//----
		$queryResultsRS->innerJoin( '#__ranking_modalidade RANMODA  USING( id_modalidade )' );																								//----
		$queryResultsRS->innerJoin( '#__ranking_prova RANPROV USING( id_prova )' );																											//----
																																															//----
		if( !$this->_user->authorise('torneios.diretor.geral', 'com_torneios') )																											//----
			$queryResultsRS->where($this->_db->quoteName('RANMODA.status_modalidade') . ' = ' . $this->_db->quote('1'));																	//----
																																															//----
		if( !$this->_user->authorise('torneios.diretor.modalidade', 'com_torneios') )																										//----
		{																																													//----
			$queryResultsRS->where($this->_db->quoteName('RANCAMP.status_campeonato') . ' = ' . $this->_db->quote('1'));																	//----
			$queryResultsRS->where($this->_db->quoteName('RANPROV.status_prova') . ' = ' . $this->_db->quote('1'));																			//----
		}																																													//----
																																															//----
		$queryResultsRS->where($this->_db->quoteName('RANIETA.id_livre_inscricao_etapa') . '>' . $this->_db->quote('0'));																	//----
																																															//----	
		if(!empty($options['id_prova']))																																					//----
			$queryResultsRS->where('RANINSC.id_prova = ' . $this->_db->quote( $options['id_prova'] ));																						//----
																																															//----
		if(!empty($options['id_etapa']))																																					//----
			$queryResultsRS->where('RANRESU.id_etapa = ' . $this->_db->quote( $options['id_etapa'] ));																						//----
																																															//----																																															
		$queryResultsRS->group('RANRESU.id_etapa');																																			//----
		$queryResultsRS->group('RANRESU.id_inscricao');																																		//----
		$queryResultsRS->group('RANRESU.nr_etapa_prova');																																	//----
																																															//----
		$queryResultsRS->order('RS DESC');																																					//----
																																															//----
		for ($i = $options['ns_etapa_prova']; $i > 0; $i--) { 																																//----
			$queryResultsRS->order('S' . $i . ' DESC');																																		//----
		}																																													//----																																		
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
																																															//----
		$queryResultsRF = $this->_db->getQuery(true);																																		//----
																																															//----
		switch( $options['rf_etapa_prova'] )																																				//----
		{																																													//----
			case '1':																																										//----
				$queryResultsRF->select( 'MAX(ResultsRS.RS) AS RF');																														//----
			break;																																											//----
																																															//----
			case '2':																																										//----
				$RFString = '';																																								//----
				for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {																														//----
					if(!empty($RFString))																																					//----
						$RFString = $RFString . ' + ';																																		//----
					$RFString = $RFString . '  MAX(IF( ResultsRS.nr_etapa_prova = '. $i .', ResultsRS.RS, 0))  ';																			//----
				}																																											//----
				if($options['decimal_prova']==0)																																			//----
					$options['decimal_prova'] = '2';																																		//----
																																															//----
				$queryResultsRF->select( 'ROUND ( ( (' . $RFString . ') / (' . $options['nr_etapa_prova'] . ')), '. $options['decimal_prova'] .' ) AS RF');									//----
			break;																																											//----
																																															//----
			case '3':																																										//----																				
				$RFString = '';																																								//----
				for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {																														//----
					if(!empty($RFString))																																					//----
						$RFString = $RFString . ' + ';																																		//----
					$RFString = $RFString . ' MAX(IF( ResultsRS.nr_etapa_prova = '. $i .', ResultsRS.RS, 0)) ';																				//----
				}																																											//----
				$queryResultsRF->select( '( ' . $RFString . ' ) AS RF');																													//----
			break;																																											//----
		}																																													//----
																																															//----
		for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {																																//----
			$queryResultsRF->select( 'MAX(IF( ResultsRS.nr_etapa_prova = '. $i .', ResultsRS.RS, 0)) AS RS' . $i);																			//----
		}																																													//----
																																															//----
		$queryResultsRF->select('id_inscricao');																																			//----
		//$queryResultsRF->select('id_equipe');																																				//----
		$queryResultsRF->select('id_livre_inscricao_etapa');																																//----
		$queryResultsRF->select('name_genero');																																				//----
		$queryResultsRF->select('name_categoria');																																			//----
		$queryResultsRF->select('name_classe');																																				//----	
		$queryResultsRF->select('name AS atleta');																																			//----	
		$queryResultsRF->select('id_etapa');																																				//----		
																																															//----
		$queryResultsRF->from( '(' . $queryResultsRS . ') ResultsRS' );																														//----
		$queryResultsRF->innerJoin( '#__ranking_inscricao USING( id_inscricao )' );																											//----
		$queryResultsRF->innerJoin( '#__ranking_genero USING( id_genero, id_prova )' );																										//----
		$queryResultsRF->innerJoin( '#__ranking_categoria USING( id_genero, id_categoria )' );																								//----
		$queryResultsRF->innerJoin( '#__ranking_classe USING( id_classe, id_categoria )' );																									//----
		$queryResultsRF->innerJoin( '#__users ON( id = id_user )' );																														//----
																																															//----
		$queryResultsRF->group('id_inscricao');																																				//----
		//$queryResultsRF->group('id_equipe');																																				//----
		$queryResultsRF->group('id_livre_inscricao_etapa');																																	//----
		$queryResultsRF->group('id_etapa');																																					//----
																																															//----
		$queryResultsRF->order($this->_db->quoteName('RF') . ' DESC');																														//----
		for ($i = $options['nr_etapa_prova']; $i > 0; $i--) { 																																//----
			$queryResultsRS->order('RS' . $i . ' DESC');																																	//----
		}																																													//----																																		
		$queryResultsRF->order($this->_db->quoteName('id_etapa') . ' ASC');																													//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
																																															//----
		$queryRankPos = $this->_db->getQuery(true);																																			//----																																													
																																															//----
		$queryFinal = '';																																									//----
		//$queryRankPos->select( 'id_equipe');																																				//----
		$queryRankPos->select( 'id_livre_inscricao_etapa');																																	//----
																																															//----
		for ($i = 1; $i <= $options['nr_equipe_prova']; $i++) { 																															//----
			if(!empty($queryFinal))																																							//----
				$queryFinal .= ' + ';																																						//----
																																															//----
			$queryFinal .= 	'IF(COUNT(ResultadoRankPos.RF) > '.($i-1).																														//----
							', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultadoRankPos.RF ORDER BY ResultadoRankPos.RF DESC ),'													//----
							.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), 0)';																						//----		
		}																																													//----
																																															//----
		if(!empty($queryFinal))																																								//----
			$queryRankPos->select( '('.$queryFinal.') AS RankRF');																															//----
																																															//----
		$queryRankPos->from( '(' . $queryResultsRF . ') ResultadoRankPos' );																												//----
		//$queryRankPos->group('id_equipe');																																				//----
		$queryRankPos->group('id_livre_inscricao_etapa');																																	//----
		$queryRankPos->order('RankRF DESC');																																				//----																																											
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------		
																																															//----																																										
		$queryRank = $this->_db->getQuery(true);																																			//----
		$queryRank->select( 'GROUP_CONCAT( ResultadoRank.RankRF )' );																														//----							
		$queryRank->from( '(' . $queryRankPos . ') ResultadoRank' );																														//----																																									
																																															//----																																												
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------																																												
																																													
		$query = $this->_db->getQuery(true);	
		//$query->select( 'id_equipe');
		//$query->select( 'id_etapa');	
		
		
		if($options['equipe_prova']==1 || $options['equipe_prova']==4):
			$query->select( 'name');	
		elseif($options['equipe_prova']==1 || $options['equipe_prova']==5)	:
			$query->select( 'CONCAT (\'Equipe \','. $this->_db->quoteName('id_livre_inscricao_etapa') . ') AS name');
		endif;																			
		
		for ($i = 1; $i <= $options['nr_equipe_prova']; $i++) { 
			$query->select( 'IF(COUNT(ResultsEtapa.RF) > '.($i-1).
							', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultsEtapa.RF ORDER BY ResultsEtapa.RF DESC ),'
							.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), NULL) AS r' . $i);	
		
			$query->select( 'IF(COUNT(ResultsEtapa.RF) > '.($i-1).
							', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultsEtapa.atleta ORDER BY ResultsEtapa.RF DESC ),'
							.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), NULL) AS n' . $i);	
		
			$query->select( 'IF(COUNT(ResultsEtapa.RF) > '.($i-1).
							', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultsEtapa.name_genero ORDER BY ResultsEtapa.RF DESC ),'
							.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), NULL) AS g' . $i);	
							
			$query->select( 'IF(COUNT(ResultsEtapa.RF) > '.($i-1).
							', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultsEtapa.name_categoria ORDER BY ResultsEtapa.RF DESC ),'
							.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), NULL) AS c' . $i);	
							
			$query->select( 'IF(COUNT(ResultsEtapa.RF) > '.($i-1).
							', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultsEtapa.name_classe ORDER BY ResultsEtapa.RF DESC ),'
							.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), NULL) AS l' . $i);	
		}

		$queryFinal = '';
		for ($i = 1; $i <= $options['nr_equipe_prova']; $i++) {  
			if(!empty($queryFinal))
				$queryFinal .= ' + ';
		
			$queryFinal .= 	'IF(COUNT(ResultsEtapa.RF) > '.($i-1).
							', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultsEtapa.RF ORDER BY ResultsEtapa.RF DESC),'
							.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), 0)';		
		}
		
		if(!empty($queryFinal))
			$query->select( '('.$queryFinal.') as rf');
				
		
		$query->select( 'FIND_IN_SET( ('.$queryFinal.'), (' . $queryRank . ')) AS rank');
		/**/	
		$query->from( '(' . $queryResultsRF . ') ResultsEtapa' );
	//	$query->innerJoin( '#__users ON('. $this->_db->quoteName('id'). ' = '. $this->_db->quoteName('id_equipe'). ')' );	
	//	$query->group('id_equipe');	
		$query->group('id_livre_inscricao_etapa');																																			

		$query->order('rf desc');	

	
		$this->_db->setQuery($query);
		return 	$this->_db->loadObjectList();	
	
	}	





	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------------- PARTICIPAÇÃO ETAPA --------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	function getParticipacaoEtapa( $options = array() )
	{		

		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
																																															//----
		$queryRankGeral = $this->_db->getQuery(true);																																		//----
		$queryRankGeral->select( 'COUNT(DISTINCT(id_inscricao)) as rf');																													//----																						
																																															//----																																					
		$queryRankGeral->from( '#__ranking_resultado' );																																	//----																								
		$queryRankGeral->innerJoin( '#__ranking_inscricao USING( id_inscricao )' );																											//----																	
																																															//----
		if(!empty($options['id_etapa']))																																					//----							
			$queryRankGeral->where('id_etapa = ' . $this->_db->quote( $options['id_etapa'] ));																								//----
																																															//----													
		if(!empty($options['id_prova']))																																					//----			
			$queryRankGeral->where('id_prova = ' . $this->_db->quote( $options['id_prova'] ));																								//----
																																															//----
		//$queryRankGeral->group('id_equipe');																																				//----																				
		$queryRankGeral->group('id_estado');																																				//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
																																															//----												
		$queryRank = $this->_db->getQuery(true);																																			//----														
		$queryRank->select( 'GROUP_CONCAT( ResultadoRank.rf ORDER BY ResultadoRank.rf DESC )' );																							//----
		$queryRank->from( '(' . $queryRankGeral . ') ResultadoRank' );																														//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------


		$query = $this->_db->getQuery(true);	
		$query->select( 'name_estado AS name');	

		$query->select( 'COUNT(DISTINCT(id_inscricao)) AS rf');			
																						
		$query->select( 'FIND_IN_SET( (COUNT(DISTINCT(id_inscricao))), (' . $queryRank . ') ) AS rank');
																																		
		$query->from( '#__ranking_resultado' );																										
		$query->innerJoin( '#__ranking_inscricao USING( id_inscricao )' );																			
		$query->innerJoin( '#__ranking_inscricao_etapa RANIETA USING( id_inscricao,  id_etapa )' );	
		$query->innerJoin( '#__intranet_estado USING( id_estado )' );			
		//$query->innerJoin( '#__users ON('. $this->_db->quoteName('id'). ' = '. $this->_db->quoteName('id_equipe'). ')' );																																	

		if(!empty($options['id_etapa']))											
			$query->where('id_etapa = ' . $this->_db->quote( $options['id_etapa'] ));
													
		if(!empty($options['id_prova']))					
			$query->where('id_prova = ' . $this->_db->quote( $options['id_prova'] ));
																				
		$query->group('id_estado');
		
		$query->order('rf DESC');														
								

		$this->_db->setQuery($query);
		return 	$this->_db->loadObjectList();
	
	}	
	
	
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------------------  GERAL ---------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- 
	function getGeral( $options = array() )
	{
		
			
		$queryResultsRF = $this->getQueryRF( $options );


		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
																																															//----
		$queryRanking = $this->_db->getQuery(true);																																			//----
		$queryRanking->select('id_inscricao');																																				//----
		$queryRanking->select('id_associado');																																				//----
		$queryRanking->select('id_etapa');																																					//----
		$queryRanking->select('name_etapa');																																				//----
		$queryRanking->select('id_classe');																																					//----	
		$queryRanking->select('id_local as id_locals');																																		//----
		$queryRanking->select('#__ranking_etapa.ordering');																																	//----
		$queryRanking->select('name AS names');																																				//----
																																															//----
		$queryRanking->select( 'ResultsRF.RF AS rf');																																		//----
		$queryRanking->from( '(' . $queryResultsRF . ') ResultsRF' );																														//----
		$queryRanking->innerJoin( '#__ranking_inscricao USING( id_inscricao )' );																											//----
		$queryRanking->innerJoin( '#__ranking_campeonato USING( id_campeonato )' );																											//----
		$queryRanking->innerJoin( '#__ranking_inscricao_etapa USING( id_inscricao, id_etapa )' );																							//----
		$queryRanking->innerJoin( '#__ranking_etapa USING( id_etapa )' );																													//----
		$queryRanking->innerJoin( '#__intranet_associado USING( id_user )' );																												//----
		$queryRanking->innerJoin( '#__users ON( id = id_local )' );																															//----
																																															//----
		$queryRanking->group('id_inscricao');																																				//----
		$queryRanking->group('id_etapa');																																					//----
																																															//----
		$queryRanking->order('#__ranking_etapa.ordering');																																	//----																																											
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	


		$query = $this->_db->getQuery(true);
		$query->select( $this->_db->quoteName(array( 'id_inscricao',
													 'id_prova',
													 'id_user',
													 'AtletaDados.name',
												    )));
													
		for ($i = 1; $i <= $options['numero_etapas']; $i++) { 

			$query->select( 'IF(COUNT(Resultados.rf) > '. ($i-1)
								. ', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( Resultados.rf ORDER BY Resultados.ordering ASC ), '
								. $this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), NULL) AS r' . $i);		
								
			$query->select( 'IF(COUNT(Resultados.rf) > '.($i-1)
								. ', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( Resultados.ordering ORDER BY Resultados.ordering ASC ), '
								. $this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), NULL) AS o'.$i);				
		
			$query->select( 'IF(COUNT(Resultados.rf) > '.($i-1)
								. ', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( Resultados.id_etapa ORDER BY Resultados.ordering ASC ), '
								. $this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), NULL) AS e'.$i);
								
			$query->select( 'IF(COUNT(Resultados.rf) > '.($i-1)
								. ', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( Resultados.name_etapa ORDER BY Resultados.ordering ASC SEPARATOR \'@@**@**@@\'), '
								. $this->_db->quote('@@**@**@@').', '.($i).'),'.$this->_db->quote('@@**@**@@').', -1), NULL) AS en'.$i);	
										
			$query->select( 'IF(COUNT(Resultados.rf) > '.($i-1)
								. ', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( Resultados.id_locals ORDER BY Resultados.ordering ASC ), '
								. $this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), NULL) AS l'.$i);	
																				
			$query->select( 'IF(COUNT(Resultados.rf) > '.($i-1)
								. ', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( Resultados.names ORDER BY Resultados.ordering ASC SEPARATOR \'@@**@**@@\'), '
								. $this->_db->quote('@@**@**@@').', '.$i.'),'.$this->_db->quote('@@**@**@@').', -1), NULL) AS ln'.$i);				
		}													
													

		$query->select( 'SUM(Resultados.rf) AS rf');

		$query->from( $this->_db->quoteName('#__ranking_inscricao') );
		$query->innerJoin('(' . $queryRanking . ') Resultados USING('. $this->_db->quoteName('id_inscricao').','. $this->_db->quoteName('id_classe').')' );	
		
		$query->innerJoin( '#__ranking_campeonato USING( id_campeonato )' );
		$query->innerJoin( '#__ranking_modalidade USING( id_modalidade )' );
		$query->innerJoin( '#__ranking_prova USING( id_prova, id_campeonato )' );
	
		$query->innerJoin( '#__intranet_pf USING('. $this->_db->quoteName('id_user').')' );
		$query->innerJoin( $this->_db->quoteName('#__intranet_associado') . ' USING('. $this->_db->quoteName('id_user').')' );
		
		$query->innerJoin( '#__users AtletaDados ON('. $this->_db->quoteName('AtletaDados.id'). ' = '. $this->_db->quoteName('id_user'). ')' );
		$query->leftJoin( '#__users EquipeClube ON('. $this->_db->quoteName('id_equipe') . '='. $this->_db->quoteName('EquipeClube.id'). ')' );
																	
		if(!empty($options['id_prova']))	
			$query->where('id_prova = ' . $this->_db->quote( $options['id_prova'] ));
			
		if($options['name'])
			$query->where( $this->_db->quoteName('AtletaDados.name') . ' LIKE \'%' . $this->_db->escape($options['name']) . '%\'' );
					
		$query->group($this->_db->quoteName('id_inscricao'));
		$query->order($this->_db->quoteName('AtletaDados.name') . ' ASC');
		$this->_db->setQuery($query);
		return 	$this->_db->loadObjectList();

	
	}



	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------- CLASSIFICAÇÃO GERAL --------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- 
	function getClassificacao( $options = array() )
	{
		
			
		$queryResultsRF = $this->getQueryRF( $options );


		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
																																															//----
		$queryRP = $this->_db->getQuery(true);																																				//----
		$queryRP->select('id_etapa');																																						//----
		$queryRP->select( 'IF(ISNULL(ResultsRP.RF),NULL,MAX(ResultsRP.rf)) AS 100_percent');																								//----
		$queryRP->from( '(' . $queryResultsRF . ') ResultsRP' );																															//----
		$queryRP->group('id_etapa');																																						//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
																																															//----
		$queryRanking = $this->_db->getQuery(true);																																			//----
		$queryRanking->select('id_inscricao');																																				//----
		$queryRanking->select('id_associado');																																				//----
		$queryRanking->select('id_etapa');																																					//----
		$queryRanking->select('name_etapa');																																				//----
		$queryRanking->select('id_classe');																																					//----	
		$queryRanking->select('id_local as id_locals');																																		//----
		$queryRanking->select('name AS names');																																				//----
																																															//----
		$queryRanking->select( 'ROUND((((ResultsRF.RF)*100)/(ResultsRP.100_percent)), 3) AS rf');																							//----
		$queryRanking->from( '(' . $queryResultsRF . ') ResultsRF' );																														//----
		$queryRanking->innerJoin( '(' . $queryRP . ') ResultsRP USING( id_etapa )' );																										//----
		$queryRanking->innerJoin( '#__ranking_inscricao USING( id_inscricao )' );																											//----
		$queryRanking->innerJoin( '#__ranking_campeonato USING( id_campeonato )' );																											//----
		$queryRanking->innerJoin( '#__ranking_inscricao_etapa USING( id_inscricao, id_etapa )' );																							//----
		$queryRanking->innerJoin( '#__ranking_etapa USING( id_etapa )' );																													//----
		$queryRanking->innerJoin( '#__intranet_associado USING( id_user )' );																												//----
		$queryRanking->innerJoin( '#__users ON( id = id_local )' );																															//----
																																															//----
		$queryRanking->group('id_inscricao');																																				//----
		$queryRanking->group('id_etapa');																																					//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
																																															//----																																										
		$queryPosition = $this->_db->getQuery(true);																																		//----
																																															//----
		$queryPosition->select('id_inscricao AS RankInscricao');																															//----
		$queryPosition->select('id_associado AS RankAssociado');																															//----
																																															//----
		$RF = '';																																											//----
		$RN = '';																																											//----
																																															//----
		for ($i = 1; $i <= $options['results_prova']; $i++) { 																																//----
			if(!empty($RF))																																									//----
				$RF .= ' + ';																																								//----
																																															//----
			$RN = 	'IF(COUNT(ResultadoPosition.rf) > '.($i-1)																																//----
					. ', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultadoPosition.rf ORDER BY ResultadoPosition.rf DESC ),'														//----
					. $this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), 0)';																								//----
																																															//----
			$RF .= $RN ;																																									//----
			$queryPosition->select( $RN . ' AS RankR' . $i);																																//----
		}																																													//----
																																															//----
		if(!empty($RF))																																										//----
			$queryPosition->select( 'ROUND (( ('.$RF.') / (' .$options['results_prova']. ') ), 3 ) AS RankRf');																				//----
																																															//----
		$queryPosition->from( '(' . $queryRanking . ') ResultadoPosition' );																												//----	
		$queryPosition->innerJoin($this->_db->quoteName('#__ranking_inscricao') . ' USING('. $this->_db->quoteName('id_inscricao').','. $this->_db->quoteName('id_classe').')' );			//----
																																															//----
		$queryPosition->order($this->_db->quoteName('RankRf') . ' DESC');																													//----
																																															//----
		for ($i = 1; $i <=  $options['results_prova']; $i++) { 																																//----
			$queryPosition->order( 'RankR' . $i . ' DESC');																																	//----
		}																																													//----
																																															//----
		$queryPosition->order($this->_db->quoteName('RankAssociado') . ' ASC');																												//----
		$queryPosition->group($this->_db->quoteName('id_inscricao'));																														//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
																																															//----
		$queryRank = $this->_db->getQuery(true);																																			//----
																																															//----
		$order = '';																																										//----
		for ($i = 1; $i <= $options['results_prova']; $i++) { 																																//----
			$order .= ', ResultadoRank.Rankr' . $i . ' DESC';																																//----
		}																																													//----
																																															//----
		$order .= ', ResultadoRank.RankAssociado  ASC';																																		//----
																																															//----
		$queryRank->select( 'GROUP_CONCAT( ResultadoRank.Rankinscricao  ORDER BY ResultadoRank.Rankrf  DESC' . $order . ' )' );																//----
																																															//----
		$queryRank->from( '(' . $queryPosition . ') ResultadoRank' );																														//----	
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------																																												

		$query = $this->_db->getQuery(true);
		$query->select( $this->_db->quoteName(array( 'id_inscricao',
													 'id_prova',
													 'id_user',
													 
													 'id_genero',
													 'name_genero',
													 
													 'id_categoria',
													 'name_categoria',
													 
													 'id_classe',
													 'name_classe',

													 'AtletaDados.name',
												    )));

		$query->select( 'FIND_IN_SET( Resultados.id_inscricao, (' . $queryRank . ') ) AS rank');
		
		for ($i = 1; $i <= $options['results_prova']; $i++) { 
				
			$query->select( 'IF(COUNT(Resultados.rf) > '. ($i-1)
								. ', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( Resultados.rf ORDER BY Resultados.rf DESC ), '
								. $this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), NULL) AS r' . $i);		
								
			$query->select( 'IF(COUNT(Resultados.rf) > '.($i-1)
								. ', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( Resultados.id_etapa ORDER BY Resultados.rf DESC ), '
								. $this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), NULL) AS e'.$i);	
								
			$query->select( 'IF(COUNT(Resultados.rf) > '.($i-1)
								. ', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( Resultados.name_etapa ORDER BY Resultados.rf DESC SEPARATOR \'@@**@**@@\'), '
								. $this->_db->quote('@@**@**@@').', '.($i).'),'.$this->_db->quote('@@**@**@@').', -1), NULL) AS en'.$i);	
										
			$query->select( 'IF(COUNT(Resultados.rf) > '.($i-1)
								. ', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( Resultados.id_locals ORDER BY Resultados.rf DESC ), '
								. $this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), NULL) AS l'.$i);	
										
			$query->select( 'IF(COUNT(Resultados.rf) > '.($i-1)
								. ', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( Resultados.names ORDER BY Resultados.rf DESC SEPARATOR \'@@**@**@@\'), '
								. $this->_db->quote('@@**@**@@').', '.$i.'),'.$this->_db->quote('@@**@**@@').', -1), NULL) AS ln'.$i);	
		}
		
		$query->select( 'COUNT(Resultados.rf) as rAddColsBefore1' );
		$query->select( 'ROUND (( (SUM(Resultados.rf)) / (COUNT(Resultados.rf)) ), 3 ) as rAddColsBefore2');

		
		$queryFinal = '';
		$querySoma = '';
		for ($i = 1; $i <= $options['results_prova']; $i++) { 
			if(!empty($queryFinal))
				$queryFinal .= ' + ';
		
			$querySoma = 	'IF(COUNT(Resultados.rf) > '.($i-1)
								. ', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( Resultados.rf ORDER BY Resultados.rf DESC ), '
								. $this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), 0)';	
			
			$queryFinal .= $querySoma ;
			$query->select( $querySoma . ' AS r' . $i);	
		}
		
		if(!empty($queryFinal))
			$query->select( 'ROUND (( ('.$queryFinal.') / (' .$options['results_prova']. ') ), 3 ) AS rf');

		$query->from( $this->_db->quoteName('#__ranking_inscricao') );
		$query->innerJoin('(' . $queryRanking . ') Resultados USING('. $this->_db->quoteName('id_inscricao').','. $this->_db->quoteName('id_classe').')' );	
		
		$query->innerJoin( '#__ranking_campeonato USING( id_campeonato )' );
		$query->innerJoin( '#__ranking_modalidade USING( id_modalidade )' );
		$query->innerJoin( '#__ranking_prova USING( id_prova, id_campeonato )' );

		$query->innerJoin( '#__ranking_genero USING( id_genero, id_prova )' );
		$query->innerJoin( '#__ranking_categoria USING( id_categoria, id_genero )' );		
		$query->innerJoin( '#__ranking_classe USING( id_classe, id_categoria )' );
	
		$query->innerJoin( '#__intranet_pf USING('. $this->_db->quoteName('id_user').')' );
		$query->innerJoin( $this->_db->quoteName('#__intranet_associado') . ' USING('. $this->_db->quoteName('id_user').')' );
		
		$query->innerJoin( '#__users AtletaDados ON('. $this->_db->quoteName('AtletaDados.id'). ' = '. $this->_db->quoteName('id_user'). ')' );
		$query->leftJoin( '#__users EquipeClube ON('. $this->_db->quoteName('id_equipe') . '='. $this->_db->quoteName('EquipeClube.id'). ')' );
																	
		if(!empty($options['id_prova']))	
			$query->where('id_prova = ' . $this->_db->quote( $options['id_prova'] ));
			
			
		if($options['name'])
			$query->where( $this->_db->quoteName('AtletaDados.name') . ' LIKE \'%' . $this->_db->escape($options['name']) . '%\'' );
					
																
		$query->group($this->_db->quoteName('id_inscricao'));
		
		$query->order($this->_db->quoteName('rf') . ' DESC');

		for ($i = 1; $i <=  $options['results_prova']; $i++) { 
				$query->order( 'r' . $i . ' DESC');
		}

		$query->order($this->_db->quoteName('#__intranet_associado.id_associado') . ' ASC');

		$this->_db->setQuery($query);
		return 	$this->_db->loadObjectList();

	
	}
	
	
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------- CAMPEOES  --------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- 
	function getCampeoes( $options = array() )
	{
		$options['campeoes'] = true;
		$queryResultsRanking = $this->getRanking( $options );
		
		$query = $this->_db->getQuery(true);
		$query->select('*');
		$query->from('('.$queryResultsRanking.') AS Campeoes');
		$query->where($this->_db->quoteName('r'.$options['results_prova']) .'IS NOT NULL');
		//$query->group($this->_db->quoteName('id_inscricao'));
		$query->group($this->_db->quoteName('Campeoes.id_genero'));
		$query->group($this->_db->quoteName('Campeoes.id_categoria'));	
		$query->group($this->_db->quoteName('Campeoes.id_classe'));	

		$this->_db->setQuery($query);
		return 	$this->_db->loadObjectList();
	
	}

	
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------- CLASSIFICAÇÃO RANKING ------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- 
	function getRanking( $options = array() )
	{

		$queryResultsRF = $this->getQueryRF( $options );

		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
																																															//----
		$queryRP = $this->_db->getQuery(true);																																				//----
		$queryRP->select('id_etapa');																																						//----
		$queryRP->select( 'IF(ISNULL(ResultsRP.RF),NULL,MAX(ResultsRP.rf)) AS 100_percent');																								//----
		$queryRP->from( '(' . $queryResultsRF . ') ResultsRP' );																															//----
		$queryRP->group('id_etapa');																																						//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
																																															//----
		$queryRanking = $this->_db->getQuery(true);																																			//----
		$queryRanking->select('id_inscricao');																																				//----
		$queryRanking->select('id_associado');																																				//----
		$queryRanking->select('id_etapa');																																					//----
		$queryRanking->select('name_etapa');																																				//----
		$queryRanking->select('id_classe');																																					//----	
		$queryRanking->select('id_local as id_locals');																																		//----
		$queryRanking->select('name AS names');																																				//----
																																															//----
		$queryRanking->select( 'ROUND((((ResultsRF.RF)*100)/(ResultsRP.100_percent)), 3) AS rf');																							//----
		$queryRanking->from( '(' . $queryResultsRF . ') ResultsRF' );																														//----
		$queryRanking->innerJoin( '(' . $queryRP . ') ResultsRP USING( id_etapa )' );																										//----
		$queryRanking->innerJoin( '#__ranking_inscricao USING( id_inscricao )' );																											//----
		$queryRanking->innerJoin( '#__ranking_campeonato USING( id_campeonato )' );																											//----
		$queryRanking->innerJoin( '#__ranking_inscricao_etapa USING( id_inscricao, id_etapa )' );																							//----
		$queryRanking->innerJoin( '#__ranking_etapa USING( id_etapa )' );																													//----
		$queryRanking->innerJoin( '#__intranet_associado USING( id_user )' );																												//----
		$queryRanking->innerJoin( '#__users ON( id = id_local )' );																															//----
																																															//----
		if(!empty($options['id_prova']))																																					//----
			$queryRanking->where('id_prova = ' . $this->_db->quote( $options['id_prova'] ));																								//----
																																															//----
		$queryRanking->group('id_inscricao');																																				//----
		$queryRanking->group('id_etapa');																																					//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
																																															//----																																										
		$queryPosition = $this->_db->getQuery(true);																																		//----
																																															//----
		$queryPosition->select('id_inscricao AS RankInscricao');																															//----
		$queryPosition->select('id_associado AS RankAssociado');																															//----
		$queryPosition->select('#__ranking_genero.ordering AS OrderGen');																													//----
		$queryPosition->select('#__ranking_categoria.ordering AS OrderCat');																												//----
		$queryPosition->select('#__ranking_classe.ordering AS OrderCla');																													//----
		$queryPosition->select('id_genero');																																				//----
		$queryPosition->select('id_categoria');																																				//----
		$queryPosition->select('id_classe');																																				//----	
																																															//----
		$RF = '';																																											//----
		$RN = '';																																											//----
																																															//----
		for ($i = 1; $i <= $options['results_prova']; $i++) { 																																//----
			if(!empty($RF))																																									//----
				$RF .= ' + ';																																								//----
																																															//----
			$RN = 	'IF(COUNT(ResultadoPosition.rf) > '.($i-1)																																//----
					. ', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultadoPosition.rf ORDER BY ResultadoPosition.rf DESC ),'														//----
					. $this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), 0)';																								//----
																																															//----
			$RF .= $RN ;																																									//----
			$queryPosition->select( $RN . ' AS RankR' . $i);																																//----
		}																																													//----
																																															//----
		if(!empty($RF))																																										//----
			$queryPosition->select( 'ROUND (( ('.$RF.') / (' .$options['results_prova']. ') ), 3 ) AS RankRf');																				//----
																																															//----
		$queryPosition->from( '(' . $queryRanking . ') ResultadoPosition' );																												//----	
		$queryPosition->innerJoin($this->_db->quoteName('#__ranking_inscricao') . ' USING('. $this->_db->quoteName('id_inscricao').','. $this->_db->quoteName('id_classe').')' );			//----
		$queryPosition->innerJoin( '#__ranking_genero USING( id_genero, id_prova )' );																										//----
		$queryPosition->innerJoin( '#__ranking_categoria USING( id_categoria, id_genero )' );																								//----	
		$queryPosition->innerJoin( '#__ranking_classe USING( id_classe, id_categoria )' );																									//----
																																															//----
		$queryPosition->order($this->_db->quoteName('RankRf') . ' DESC');																													//----
																																															//----
		for ($i = 1; $i <=  $options['results_prova']; $i++) { 																																//----
			$queryPosition->order( 'RankR' . $i . ' DESC');																																	//----
		}																																													//----
																																															//----
		$queryPosition->order($this->_db->quoteName('RankAssociado') . ' ASC');																												//----
		$queryPosition->group($this->_db->quoteName('id_inscricao'));																														//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
																																															//----
		$queryRank = $this->_db->getQuery(true);																																			//----
																																															//----
		$orderInit = '';																																									//----
		$orderInit .= 'ResultadoRank.OrderGen ASC, ResultadoRank.OrderCat ASC, ResultadoRank.OrderCla ASC, ResultadoRank.Rankrf DESC';														//----
																																															//----
		$order = '';																																										//----
		for ($i = 1; $i <= $options['results_prova']; $i++) { 																																//----
			$order .= ', ResultadoRank.Rankr' . $i . ' DESC';																																//----
		}																																													//----
																																															//----
		$order .= ', ResultadoRank.RankAssociado  ASC';																																		//----
																																															//----
		$queryRank->select('id_genero');																																					//----
		$queryRank->select('id_categoria');																																					//----
		$queryRank->select('id_classe');																																					//----	
		$queryRank->select( 'GROUP_CONCAT(ResultadoRank.Rankinscricao ORDER BY ' . $orderInit . $order . ' ) AS Rank' );																	//----
																																															//----
		$queryRank->from( '(' . $queryPosition . ') ResultadoRank' );																														//----
																																															//----
		$queryRank->group($this->_db->quoteName('OrderGen'));																																//----
		$queryRank->group($this->_db->quoteName('OrderCat'));																																//----
		$queryRank->group($this->_db->quoteName('OrderCla'));																																//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------																																												


		$query = $this->_db->getQuery(true);
		$query->select( $this->_db->quoteName(array( 'id_inscricao',
													 'id_prova',
													 'id_user',
													 
													 'id_genero',
													 'name_genero',
													 
													 'id_categoria',
													 'name_categoria',
													 
													 'id_classe',
													 'name_classe',
													 
													 'name_estado',
													 
													 'AtletaDados.name',
												    )));

		$query->select('EquipeClube.name AS name_equipe');
		
		$query->select( 'FIND_IN_SET( Resultados.id_inscricao, (Position.Rank) ) AS rank');		
		
		
		for ($i = 1; $i <= $options['results_prova']; $i++) { 
				
			$query->select( 'IF(COUNT(Resultados.rf) > '.($i-1)
								. ', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( Resultados.rf ORDER BY Resultados.rf DESC ), '
								. $this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), NULL) AS r' . $i);		
								
			$query->select( 'IF(COUNT(Resultados.rf) > '.($i-1)
								. ', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( Resultados.id_etapa ORDER BY Resultados.rf DESC ), '
								. $this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), NULL) AS e'.$i);	
								
			$query->select( 'IF(COUNT(Resultados.rf) > '.($i-1)
								. ', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( Resultados.name_etapa ORDER BY Resultados.rf DESC SEPARATOR \'@@**@**@@\'), '
								. $this->_db->quote('@@**@**@@').', '.($i).'),'.$this->_db->quote('@@**@**@@').', -1), NULL) AS en'.$i);	
										
			$query->select( 'IF(COUNT(Resultados.rf) > '.($i-1)
								. ', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( Resultados.id_locals ORDER BY Resultados.rf DESC ), '
								. $this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), NULL) AS l'.$i);	
										
			$query->select( 'IF(COUNT(Resultados.rf) > '.($i-1)
								. ', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( Resultados.names ORDER BY Resultados.rf DESC SEPARATOR \'@@**@**@@\'), '
								. $this->_db->quote('@@**@**@@').', '.$i.'),'.$this->_db->quote('@@**@**@@').', -1), NULL) AS ln'.$i);	
		}
		
		$query->select( 'COUNT(Resultados.rf) as rAddColsBefore1' );
		$query->select( 'ROUND (( (SUM(Resultados.rf)) / (COUNT(Resultados.rf)) ), 3 ) as rAddColsBefore2');

		
		$queryFinal = '';
		$querySoma = '';
		for ($i = 1; $i <= $options['results_prova']; $i++) { 
			if(!empty($queryFinal))
				$queryFinal .= ' + ';
		
			$querySoma = 	'IF(COUNT(Resultados.rf) > '.($i-1)
								. ', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( Resultados.rf ORDER BY Resultados.rf DESC ), ' 
								. $this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), 0)';	
			
			$queryFinal .= $querySoma ;
			$query->select( $querySoma . ' AS r' . $i);	
		}
		
		if(!empty($queryFinal))
			$query->select( 'ROUND (( ('.$queryFinal.') / (' .$options['results_prova']. ') ), 3 ) AS rf');

		$query->from( $this->_db->quoteName('#__ranking_inscricao') );
		$query->innerJoin('(' . $queryRanking . ') Resultados USING('. $this->_db->quoteName('id_inscricao').','. $this->_db->quoteName('id_classe').')' );	
		$query->innerJoin('(' . $queryRank . ') Position USING('. $this->_db->quoteName('id_genero').','. $this->_db->quoteName('id_categoria').','. $this->_db->quoteName('id_classe').')' );

		$query->innerJoin( '#__ranking_campeonato USING( id_campeonato )' );
		$query->innerJoin( '#__ranking_modalidade USING( id_modalidade )' );
		$query->innerJoin( '#__ranking_prova USING( id_prova, id_campeonato )' );

		$query->innerJoin( '#__ranking_genero USING( id_genero, id_prova )' );
		$query->innerJoin( '#__ranking_categoria USING( id_categoria, id_genero )' );		
		$query->innerJoin( '#__ranking_classe USING( id_classe, id_categoria )' );
		$query->leftJoin( '#__intranet_estado USING( id_estado )' );
	
		$query->innerJoin( '#__intranet_pf USING('. $this->_db->quoteName('id_user').')' );
		$query->innerJoin( $this->_db->quoteName('#__intranet_associado') . ' USING('. $this->_db->quoteName('id_user').')' );
		
		$query->innerJoin( '#__users AtletaDados ON('. $this->_db->quoteName('AtletaDados.id'). ' = '. $this->_db->quoteName('id_user'). ')' );
		$query->leftJoin( '#__users EquipeClube ON('. $this->_db->quoteName('id_equipe') . '='. $this->_db->quoteName('EquipeClube.id'). ')' );
					
		if(!empty($options['id_prova']))	
			$query->where('id_prova = ' . $this->_db->quote( $options['id_prova'] ));
			
		if($options['name'])
			$query->where( $this->_db->quoteName('AtletaDados.name') . ' LIKE \'%' . $this->_db->escape($options['name']) . '%\'' );
		
		if(isset($options['id_user']) && $options['id_user'])
			$query->where($this->_db->quoteName('#__ranking_inscricao.id_user') . ' = ' . $this->_db->quote( $options['id_user'] ));					
																
		$query->group($this->_db->quoteName('id_inscricao'));
		$query->group($this->_db->quoteName('id_genero'));
		$query->group($this->_db->quoteName('id_categoria'));	
		$query->group($this->_db->quoteName('id_classe'));		
		
		$query->order($this->_db->quoteName('#__ranking_genero.ordering') . ' ASC');
		$query->order($this->_db->quoteName('#__ranking_categoria.ordering') . ' ASC');	
		$query->order($this->_db->quoteName('#__ranking_classe.ordering') . ' ASC');
		
		$query->order($this->_db->quoteName('rf') . ' DESC');

		for ($i = 1; $i <=  $options['results_prova']; $i++) { 
				$query->order( 'r' . $i . ' DESC');
		}

		$query->order($this->_db->quoteName('#__intranet_associado.id_associado') . ' ASC');

		if(isset($options['campeoes']) && $options['campeoes'] === true)
			return $query;

		$this->_db->setQuery($query);
		return 	$this->_db->loadObjectList();

	}
	
	
	
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------- CLASSES PROXIMO ANO --------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- 
	function getClasses( $options = array() )
	{
			
		$queryResultsRF = $this->getQueryRF( $options );

		
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
																																															//----
		$queryRP = $this->_db->getQuery(true);																																				//----
		$queryRP->select('id_etapa');																																						//----
		$queryRP->select( 'IF(ISNULL(ResultsRP.RF),NULL,MAX(ResultsRP.rf)) AS 100_percent');																								//----
		$queryRP->from( '(' . $queryResultsRF . ') ResultsRP' );																															//----
		$queryRP->group('id_etapa');																																						//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
																																															//----
		$queryRanking = $this->_db->getQuery(true);																																			//----
		$queryRanking->select('id_inscricao');																																				//----
		$queryRanking->select('id_associado');																																				//----
		$queryRanking->select('id_etapa');																																					//----
		$queryRanking->select('name_etapa');																																				//----
		$queryRanking->select('id_classe');																																					//----	
		$queryRanking->select('id_local as id_locals');																																		//----
		$queryRanking->select('name AS names');																																				//----
																																															//----
		$queryRanking->select( 'ROUND((((ResultsRF.RF)*100)/(ResultsRP.100_percent)), 3) AS rf');																							//----
		$queryRanking->from( '(' . $queryResultsRF . ') ResultsRF' );																														//----
		$queryRanking->innerJoin( '(' . $queryRP . ') ResultsRP USING( id_etapa )' );																										//----
		$queryRanking->innerJoin( '#__ranking_inscricao USING( id_inscricao )' );																											//----
		$queryRanking->innerJoin( '#__ranking_campeonato USING( id_campeonato )' );																											//----
		$queryRanking->innerJoin( '#__ranking_inscricao_etapa USING( id_inscricao, id_etapa )' );																							//----
		$queryRanking->innerJoin( '#__ranking_etapa USING( id_etapa )' );																													//----
		$queryRanking->innerJoin( '#__intranet_associado USING( id_user )' );																												//----
		$queryRanking->innerJoin( '#__users ON( id = id_local )' );																															//----
																																															//----
		$queryRanking->where( '(' 																																							//----														
								. '(' 																																						//----
									. $this->_db->quoteName('status_associado') . '=' . $this->_db->quote( '1' )																			//---- 						
									. ' AND '																																				//----								
									. $this->_db->quoteName('validate_associado') . '>=' . $this->_db->quote( JFactory::getDate('now', $this->_siteOffset)->format('Y-m-d',true) )			//----
								. ')'																																						//----
								. ' OR '																																					//----
								. '('																																						//----
									. $this->_db->quoteName('ano_campeonato') . ' < YEAR(CURDATE())'																						//----
								. ')' 																																						//----									
							. ')' );																																						//----
																																															//----
		$queryRanking->group('id_inscricao');																																				//----
		$queryRanking->group('id_etapa');																																					//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
																																															//----
		$queryClasses = $this->_db->getQuery(true);																																			//----
		$queryClasses->select( $this->_db->quoteName(array(	//'pcd_categoria',																												//----
															'ponto_min_classe',																												//----
															'ponto_max_classe',																												//----
															'parent_prova',																													//----
															'pcd_categoria',																												//----
															'idade_min_categoria',																											//----
															'idade_max_categoria',																											//----
															'sexo_genero',																													//----
															'name_categoria',																												//----
															'name_classe',																													//----
															)));																															//----
																																															//----
																																															//----
		$queryClasses->from( $this->_db->quoteName('#__ranking_classe') );																													//----
		$queryClasses->innerJoin($this->_db->quoteName('#__ranking_categoria') . 'USING(' . $this->_db->quoteName('id_categoria') . ')');													//----
		$queryClasses->innerJoin($this->_db->quoteName('#__ranking_genero') . 'USING(' . $this->_db->quoteName('id_genero') . ')');															//----
		$queryClasses->innerJoin($this->_db->quoteName('#__ranking_prova') . 'USING(' . $this->_db->quoteName('id_prova') . ')');															//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
																																															//----
		$queryResults = $this->_db->getQuery(true);																																			//----
																																															//----
		$queryFinalClass = '';																																								//----
		$querySoma = '';																																									//----
		for ($i = 1; $i <= $options['results_classe_prova']; $i++) { 																														//----
			if(!empty($queryFinalClass))																																					//----
				$queryFinalClass .= ' + ';																																					//----
																																															//----
			$queryFinalClass .= 	'IF(COUNT(Results.rf) > '.($i-1)																														//----
								. ', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( Results.rf ORDER BY Results.rf DESC ), '																//----
								. $this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), 0)';																					//----	
		}																																													//----
																																															//----
		if(!empty($queryFinalClass)) {																																						//----
			$queryResults->select( 'ROUND ( ( (' . $queryFinalClass . ')/( IF(COUNT(Results.rf) > ' 																						//----
												 . $options['results_classe_prova'] . ', ' . $options['results_classe_prova'] . ', COUNT(Results.rf)) )), 3 ) AS rfClass');					//----	
		}																																													//----
		$queryResults->select( $this->_db->quoteName(array(	'id_inscricao',																													//----
															'id_classe',																													//----
															)));																															//----
																																															//----
		$queryResults->select( 'TIMESTAMPDIFF(YEAR, CONCAT( YEAR(data_nascimento_pf), "-01-01" ), CONCAT( ano_campeonato + 1, "-12-31" ) ) AS idadeClass');									//----													
																																															//----
		$queryResults->from( $this->_db->quoteName('#__ranking_inscricao') );																												//----
		$queryResults->innerJoin('(' . $queryRanking . ') Results USING('. $this->_db->quoteName('id_inscricao').','. $this->_db->quoteName('id_classe').')' );								//----
		$queryResults->innerJoin( '#__ranking_campeonato USING( id_campeonato )' );																											//----
																																															//----
		$queryResults->innerJoin( '#__intranet_pf USING('. $this->_db->quoteName('id_user').')' );																							//----
		$queryResults->group($this->_db->quoteName('id_inscricao'));																														//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	

		
		$query = $this->_db->getQuery(true);
		$query->select( $this->_db->quoteName(array( 'id_inscricao',
													 'id_prova',
													 'id_user',
													 'AtletaDados.name',
												    )));

		$query->select( 'Classes.name_categoria AS name_categoria');
		$query->select( 'Classes.name_classe AS name_classe');	
		
		for ($i = 1; $i <= $options['results_classe_prova']; $i++) {
				
			$query->select( 'IF(COUNT(Resultados.rf) > '. ($i-1)
								. ', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( Resultados.rf ORDER BY Resultados.rf DESC ), '
								. $this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), NULL) AS r' . $i);		
								
			$query->select( 'IF(COUNT(Resultados.rf) > '.($i-1)
								. ', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( Resultados.id_etapa ORDER BY Resultados.rf DESC ), '
								. $this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), NULL) AS e'.$i);	
								
			$query->select( 'IF(COUNT(Resultados.rf) > '.($i-1)
								. ', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( Resultados.name_etapa ORDER BY Resultados.rf DESC SEPARATOR \'@@**@**@@\'), '
								. $this->_db->quote('@@**@**@@').', '.($i).'),'.$this->_db->quote('@@**@**@@').', -1), NULL) AS en'.$i);	
										
			$query->select( 'IF(COUNT(Resultados.rf) > '.($i-1)
								. ', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( Resultados.id_locals ORDER BY Resultados.rf DESC ), '
								. $this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), NULL) AS l'.$i);	
										
			$query->select( 'IF(COUNT(Resultados.rf) > '.($i-1)
								. ', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( Resultados.names ORDER BY Resultados.rf DESC SEPARATOR \'@@**@**@@\'), '
								. $this->_db->quote('@@**@**@@').', '.$i.'),'.$this->_db->quote('@@**@**@@').', -1), NULL) AS ln'.$i);	
		}
		
		//$query->select( 'COUNT(Resultados.rf) as rAddColsBefore1' );
		//$query->select( 'ROUND (( (SUM(Resultados.rf)) / (COUNT(Resultados.rf)) ), 3 ) as rAddColsBefore2');

		
		$queryFinal = '';
		$querySoma = '';
		
		for ($i = 1; $i <= $options['results_classe_prova']; $i++) { 
			if(!empty($queryFinal))
				$queryFinal .= ' + ';
		
			if(!empty($queryFinalClass))
				$queryFinalClass .= ' + ';
		
			$querySoma = 	'IF(COUNT(Resultados.rf) > '.($i-1)
								. ', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( Resultados.rf ORDER BY Resultados.rf DESC ), '
								. $this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), 0)';	
			
			$queryFinal .= $querySoma ;
			$query->select( $querySoma . ' AS r' . $i);	
		}
		
		if(!empty($queryFinal)) {
			$query->select( 'ROUND ( ('.$queryFinal.'), 3 ) AS rAddColsBefore1');
			$query->select( 'ROUND ( ( (' . $queryFinal . ')/( IF(COUNT(Resultados.rf) > ' . $options['results_classe_prova'] .', '. $options['results_classe_prova'] .', COUNT(Resultados.rf)) )), 3 ) AS rf');		
		}

		$query->from( $this->_db->quoteName('#__ranking_inscricao') );
		$query->innerJoin('(' . $queryRanking . ') Resultados USING('. $this->_db->quoteName('id_inscricao').','. $this->_db->quoteName('id_classe').')' );	
		$query->innerJoin('(' . $queryResults . ') ResultClass USING('. $this->_db->quoteName('id_inscricao').','. $this->_db->quoteName('id_classe').')' );	
		
		$query->innerJoin( '#__ranking_campeonato USING( id_campeonato )' );
		$query->innerJoin( '#__ranking_modalidade USING( id_modalidade )' );
		$query->innerJoin( '#__ranking_prova USING( id_prova, id_campeonato )' );
		
		$query->innerJoin( '#__intranet_pf USING('. $this->_db->quoteName('id_user').')' );
		
		$query->leftJoin('(' . $queryClasses . ') Classes ON('
																. '('
																. $this->_db->quoteName('Classes.sexo_genero').'='. $this->_db->quoteName('sexo_pf')
																. ' OR'
																. $this->_db->quoteName('Classes.sexo_genero') . '=' . $this->_db->quote('2')
																. ')'
																. ' AND '
																. '('
																	. $this->_db->quoteName('Classes.idade_min_categoria').'='. $this->_db->quote('0')
																	. ' OR '	
																	. $this->_db->quoteName('Classes.idade_min_categoria').'<= ResultClass.idadeClass'
																. ')'
																. ' AND '
																. '('
																	. $this->_db->quoteName('Classes.idade_max_categoria').'='. $this->_db->quote('0')
																	. ' OR '	
																	. $this->_db->quoteName('Classes.idade_max_categoria').'>= ResultClass.idadeClass'
																. ')'
																. ' AND '/**/
																. '('
																	. $this->_db->quoteName('Classes.ponto_min_classe').'='. $this->_db->quote('0')
																	. ' OR '	
																	. $this->_db->quoteName('Classes.ponto_min_classe').' <= ResultClass.rfClass'
																. ')'
																. ' AND '
																. '('
																	. $this->_db->quoteName('Classes.ponto_max_classe').'='. $this->_db->quote('0')
																	. ' OR '	
																	. $this->_db->quoteName('Classes.ponto_max_classe').'>= ResultClass.rfClass'
																. ')'
																. ' AND'
																. $this->_db->quoteName('Classes.pcd_categoria').'='. $this->_db->quoteName('pcd_pf')
																. ' AND'
																. $this->_db->quoteName('Classes.parent_prova').'='. $this->_db->quoteName('id_prova')
																.')' );	
		
		
		
		$query->innerJoin( $this->_db->quoteName('#__intranet_associado') . ' USING('. $this->_db->quoteName('id_user').')' );
		
		$query->innerJoin( '#__users AtletaDados ON('. $this->_db->quoteName('AtletaDados.id'). ' = '. $this->_db->quoteName('id_user'). ')' );
		$query->leftJoin( '#__users EquipeClube ON('. $this->_db->quoteName('id_equipe') . '='. $this->_db->quoteName('EquipeClube.id'). ')' );
																	
		if(!empty($options['id_prova']))	
			$query->where('id_prova = ' . $this->_db->quote( $options['id_prova'] ));
			
		$query->where( '(' 																
							. '(' 		
								. $this->_db->quoteName('status_associado') . '=' . $this->_db->quote( '1' ) 						
								. ' AND '																											
								. $this->_db->quoteName('validate_associado') . '>=' . $this->_db->quote( JFactory::getDate('now', $this->_siteOffset)->format('Y-m-d',true) )			
							. ')'
							. ' OR '
							. '('
								. $this->_db->quoteName('ano_campeonato') . ' < YEAR(CURDATE())'
							. ')' 										
						. ')' );	
					
																
		$query->group($this->_db->quoteName('id_inscricao'));
		$query->order($this->_db->quoteName('name'));
		
		$this->_db->setQuery($query);
		return 	$this->_db->loadObjectList();
			
	}
	
	
	
	
	
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//------------------------------------------------------------------------------------- EQUIPES PROVA ----------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	function getEquipeProva( $options = array() )
	{		
	
	

		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
																																															//----
		$queryPagamento = $this->_db->getQuery(true);																																		//----
		$queryPagamento->select('ano_anuidade AS ano_campeonato');																															//----
		$queryPagamento->select('id_user');																																					//----
		$queryPagamento->select('MIN(baixa_pagamento) AS baixa_pagamento');																													//----
		$queryPagamento->from( $this->_db->quoteName('#__intranet_anuidade') );																												//----
		$queryPagamento->innerJoin( $this->_db->quoteName('#__intranet_pagamento') . ' USING('. $this->_db->quoteName('id_anuidade').')');													//----
		$queryPagamento->where($this->_db->quoteName('baixa_pagamento') . ' IS NOT NULL' );																									//----
		$queryPagamento->where($this->_db->quoteName('status_pagamento') . '=' . $this->_db->quote( '1' ) );																				//----
		$queryPagamento->group($this->_db->quoteName('id_anuidade'));																														//----
		$queryPagamento->group($this->_db->quoteName('id_user'));																															//----
																																															//----
		//------------------------------------------------------------------------------------ CALC RANKING --------------------------------------------------------------------------------------
																																															//----
		$queryResultsRS = $this->_db->getQuery(true);																																		//----
																																															//----
		switch( $options['rs_etapa_prova'] )																																				//----
		{																																													//----
			case '1':																																										//----
				$queryResultsRS->select( 'MAX(RANRESU.value_resultado) AS RS');																												//----
			break;																																											//----
																																															//----
			case '2':																																										//----
				$RFString = '';																																								//----
				for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) { 																														//----
					if(!empty($RFString))																																					//----
						$RFString = $RFString . ' + ';																																		//----
					$RFString = $RFString . ' MAX(IF(RANRESU.ordering = '. $i .', RANRESU.value_resultado, 0)) ';																			//----
				}																																											//----
				if($options['decimal_prova']==0)																																			//----
					$options['decimal_prova'] = '2';																																		//----
																																															//----
				$queryResultsRS->select( 'ROUND ( ( (' . $RFString . ') / (' . $options['ns_etapa_prova'] . ')), '. $options['decimal_prova'] .' ) AS RS');									//----
																																															//----
			break;																																											//----
																																															//----
			case '3':																																										//----
				$RFString = '';																																								//----
				for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) { 																														//----
					if(!empty($RFString))																																					//----
						$RFString = $RFString . ' + ';																																		//----
					$RFString = $RFString . ' MAX(IF(RANRESU.ordering = '. $i .', RANRESU.value_resultado, 0)) ';																			//----
				}																																											//----
																																															//----
				$queryResultsRS->select( '( ' . $RFString . ' ) AS RS');																													//----
			break;																																											//----
		}																																													//----
																																															//----
		$queryResultsRS->select( 'RANRESU.id_inscricao');																																	//----
		$queryResultsRS->select( 'RANRESU.nr_etapa_prova');																																	//----
		$queryResultsRS->select( 'RANRESU.id_etapa');																																		//----
		$queryResultsRS->select( 'RANINSC.id_estado');																																		//----
																																															//----
		$queryResultsRS->from( '#__ranking_resultado RANRESU' );																															//----
		$queryResultsRS->innerJoin( '#__ranking_inscricao RANINSC USING( id_inscricao )' );																									//----
		$queryResultsRS->innerJoin( '#__ranking_inscricao_etapa RANIETA USING( id_inscricao,  id_etapa )' );																				//----
		$queryResultsRS->innerJoin( '#__ranking_campeonato RANCAMP USING( id_campeonato )' );																								//----
		$queryResultsRS->innerJoin( '#__ranking_modalidade RANMODA  USING( id_modalidade )' );																								//----
		$queryResultsRS->innerJoin( '#__ranking_prova RANPROV USING( id_prova )' );																											//----
		$queryResultsRS->innerJoin( '#__ranking_etapa RANETAPA USING( id_etapa )' );																										//----
		$queryResultsRS->innerJoin( '(' . $queryPagamento . ') Pagamento USING('. $this->_db->quoteName('id_user').','. $this->_db->quoteName('ano_campeonato').')');						//----
																																															//----
		$queryResultsRS->where( $this->_db->quoteName('Pagamento.baixa_pagamento').'<='. $this->_db->quoteName( 'RANETAPA.data_end_etapa' ) );												//----																																														
																																															//----																																														
		if( !$this->_user->authorise('torneios.diretor.geral', 'com_torneios') )																											//----
			$queryResultsRS->where($this->_db->quoteName('RANMODA.status_modalidade') . ' = ' . $this->_db->quote('1'));																	//----
																																															//----
		if( !$this->_user->authorise('torneios.diretor.modalidade', 'com_torneios') )																										//----
		{																																													//----
			$queryResultsRS->where($this->_db->quoteName('RANCAMP.status_campeonato') . ' = ' . $this->_db->quote('1'));																	//----
			$queryResultsRS->where($this->_db->quoteName('RANPROV.status_prova') . ' = ' . $this->_db->quote('1'));																			//----
		}																																													//----
																																															//----																																															//----
		if(!empty($options['id_prova']))																																					//----
			$queryResultsRS->where('RANINSC.id_prova = ' . $this->_db->quote( $options['id_prova'] ));																						//----
																																															//----
		$queryResultsRS->group('RANRESU.id_etapa');																																			//----										//----
		$queryResultsRS->group('RANRESU.id_inscricao');																																		//----
		$queryResultsRS->group('RANRESU.nr_etapa_prova');																																	//----
																																															//----
		$queryResultsRS->order('RS DESC');																																					//----																																															//----
																																															//----																																													
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
																																															//----
		$queryResultsRF = $this->_db->getQuery(true);																																		//----
																																															//----
		switch( $options['rf_etapa_prova'] )																																				//----
		{																																													//----

			case '1':																																										//----
				$queryResultsRF->select( 'MAX(ResultsRS.RS) AS RF');																														//----
			break;																																											//----
																																															//----
			case '2':																																										//----
				$RFString = '';																																								//----
				for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {																														//----
					if(!empty($RFString))																																					//----
						$RFString = $RFString . ' + ';																																		//----
					$RFString = $RFString . '  MAX(IF( ResultsRS.nr_etapa_prova = '. $i .', ResultsRS.RS, 0))  ';																			//----
				}																																											//----
				if($options['decimal_prova']==0)																																			//----
					$options['decimal_prova'] = '2';																																		//----
																																															//----
				$queryResultsRF->select( 'ROUND ( ( (' . $RFString . ') / (' . $options['nr_etapa_prova'] . ')), '. $options['decimal_prova'] .' ) AS RF');									//----
			break;																																											//----
																																															//----
			case '3':																																										//----																				
				$RFString = '';																																								//----
				for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {																														//----
					if(!empty($RFString))																																					//----
						$RFString = $RFString . ' + ';																																		//----
					$RFString = $RFString . ' MAX(IF( ResultsRS.nr_etapa_prova = '. $i .', ResultsRS.RS, 0)) ';																				//----
				}																																											//----
				$queryResultsRF->select( '( ' . $RFString . ' ) AS RF');																													//----
			break;																																											//----
		}																																													//----
																																															//----
		$queryResultsRF->select('id_inscricao');																																			//----
		$queryResultsRF->select('id_estado');																																				//----
		$queryResultsRF->select('id_etapa');																																				//----
																																															//----
		$queryResultsRF->from( '(' . $queryResultsRS . ') ResultsRS' );																														//----
																																															//----
		$queryResultsRF->group('id_inscricao');																																				//----
		$queryResultsRF->group('id_estado');																																				//----
		$queryResultsRF->group('id_etapa');																																					//----
																																															//----
		$queryResultsRF->order($this->_db->quoteName('RF') . ' DESC');																														//----
		$queryResultsRF->order($this->_db->quoteName('id_etapa') . ' ASC');																													//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------																																												
																																															//----
		$queryResultsEtapa = $this->_db->getQuery(true);																																	//----																																													
																																															//----
		$queryFinal = '';																																									//----
		$queryResultsEtapa->select( 'id_estado');																																			//----
		$queryResultsEtapa->select( 'id_etapa');																																			//----
		$queryResultsEtapa->select( 'ETAPAORDER.ordering');																																	//----
																																															//----
		for ($i = 1; $i <= $options['nr_equipe_prova']; $i++) { 																															//----
			if(!empty($queryFinal))																																							//----
				$queryFinal .= ' + ';																																						//----
																																															//----
			$queryFinal .= 	'IF(COUNT(ResultsRF.RF) > '.($i-1).																																//----
							', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultsRF.RF ORDER BY ResultsRF.RF DESC ),'																	//----
							.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), 0)';																						//----		
		}																																													//----
																																															//----
		if(!empty($queryFinal))																																								//----
			$queryResultsEtapa->select( '('.$queryFinal.') AS EtapaRF');																													//----
																																															//----
		$queryResultsEtapa->from( '(' . $queryResultsRF . ') ResultsRF' );																													//----
		$queryResultsEtapa->innerJoin( '#__ranking_etapa ETAPAORDER USING('. $this->_db->quoteName('id_etapa'). ')' );																		//----
		$queryResultsEtapa->group('id_estado');																																				//----
		$queryResultsEtapa->group('id_etapa');																																				//----
		$queryResultsEtapa->order('EtapaRF DESC');																																			//----																																											
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

		$queryRankPos = $this->_db->getQuery(true);	
		$queryFinal = '';
		for ($i = 1; $i <= $options['numero_etapas']; $i++) { 
			if(!empty($queryFinal))
				$queryFinal .= ' + ';
		
			$queryFinal .= 	'IF(COUNT(ResultadoRankPos.EtapaRF) > '.($i-1).
							', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultadoRankPos.EtapaRF ORDER BY ResultadoRankPos.ordering ASC ),'
							.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), 0)';		
		}
		
		if(!empty($queryFinal))
			$queryRankPos->select( '('.$queryFinal.') as RankRF');

		$queryRankPos->from( '(' . $queryResultsEtapa . ') ResultadoRankPos' );	
		$queryRankPos->innerJoin( '#__intranet_estado USING('. $this->_db->quoteName('id_estado'). ')' );
		$queryRankPos->group('id_estado');																																			
		$queryRankPos->order('RankRF desc');	
		
		$queryRank = $this->_db->getQuery(true);	
		$queryRank->select( 'GROUP_CONCAT( ResultadoRank.RankRF )' );							
		$queryRank->from( '(' . $queryRankPos . ') ResultadoRank' );	


		$query = $this->_db->getQuery(true);	
		$query->select( 'id_estado');
		$query->select( 'id_estado AS id_equipe');
		$query->select( 'name_estado AS name');																								
		
		for ($i = 1; $i <= $options['numero_etapas']; $i++) { 
			$query->select( 'IF(COUNT(ResultsEtapa.EtapaRF) > '.($i-1).
							', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultsEtapa.EtapaRF ORDER BY ResultsEtapa.ordering ASC ),'
							.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), NULL) AS r' . $i);	
							
							
			$query->select( 'IF(COUNT(ResultsEtapa.EtapaRF) > '.($i-1).
							', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultsEtapa.ordering ORDER BY ResultsEtapa.ordering ASC ),'
							.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), NULL) AS o' . $i);	
			
							
		}
		
		$queryFinal = '';
		for ($i = 1; $i <= $options['numero_etapas']; $i++) { 
			if(!empty($queryFinal))
				$queryFinal .= ' + ';
		
			$queryFinal .= 	'IF(COUNT(ResultsEtapa.EtapaRF) > '.($i-1).
							', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultsEtapa.EtapaRF ORDER BY ResultsEtapa.ordering ASC ),'
							.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), 0)';		
		}
		
		if(!empty($queryFinal))
			$query->select( '('.$queryFinal.') as rf');
				
		
		$query->select( 'FIND_IN_SET( ('.$queryFinal.'), (' . $queryRank . ')) AS rank');
			
		$query->from( '(' . $queryResultsEtapa . ') ResultsEtapa' );
		$query->innerJoin( '#__intranet_estado USING('. $this->_db->quoteName('id_estado'). ')' );
		$query->group('id_estado');																																			

		$query->order('rf desc');
			
		$this->_db->setQuery($query);
		return 	$this->_db->loadObjectList();																																							

	
	/*
	
	
	
	

		//------------------------------------------------------------------------------------ CALC RANKING --------------------------------------------------------------------------------------
																																															//----
		$queryResultsRS = $this->_db->getQuery(true);																																		//----
																																															//----
		switch( $options['rs_etapa_prova'] )																																				//----
		{																																													//----
			case '1':																																										//----
				$queryResultsRS->select( 'MAX(RANRESU.value_resultado) AS RS');																												//----
			break;																																											//----
																																															//----
			case '2':																																										//----
				$RFString = '';																																								//----
				for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) { 																														//----
					if(!empty($RFString))																																					//----
						$RFString = $RFString . ' + ';																																		//----
					$RFString = $RFString . ' MAX(IF(RANRESU.ordering = '. $i .', RANRESU.value_resultado, 0)) ';																			//----
				}																																											//----
				if($options['decimal_prova']==0)																																			//----
					$options['decimal_prova'] = '2';																																		//----
																																															//----
				$queryResultsRS->select( 'ROUND ( ( (' . $RFString . ') / (' . $options['ns_etapa_prova'] . ')), '. $options['decimal_prova'] .' ) AS RS');									//----
																																															//----
			break;																																											//----
																																															//----
			case '3':																																										//----
				$RFString = '';																																								//----
				for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) { 																														//----
					if(!empty($RFString))																																					//----
						$RFString = $RFString . ' + ';																																		//----
					$RFString = $RFString . ' MAX(IF(RANRESU.ordering = '. $i .', RANRESU.value_resultado, 0)) ';																			//----
				}																																											//----
																																															//----
				$queryResultsRS->select( '( ' . $RFString . ' ) AS RS');																													//----
			break;																																											//----
		}																																													//----
																																															//----
		$queryResultsRS->select( 'RANRESU.id_inscricao');																																	//----
		$queryResultsRS->select( 'RANRESU.nr_etapa_prova');																																	//----
		$queryResultsRS->select( 'RANRESU.id_etapa');																																		//----
		$queryResultsRS->select( 'RANINSC.id_estado');																																		//----
																																															//----
		$queryResultsRS->from( '#__ranking_resultado RANRESU' );																															//----
		$queryResultsRS->innerJoin( '#__ranking_inscricao RANINSC USING( id_inscricao )' );																									//----
		$queryResultsRS->innerJoin( '#__ranking_inscricao_etapa RANIETA USING( id_inscricao,  id_etapa )' );																				//----
		$queryResultsRS->innerJoin( '#__ranking_campeonato RANCAMP USING( id_campeonato )' );																								//----
		$queryResultsRS->innerJoin( '#__ranking_modalidade RANMODA  USING( id_modalidade )' );																								//----
		$queryResultsRS->innerJoin( '#__ranking_prova RANPROV USING( id_prova )' );																											//----
																																															//----
		if( !$this->_user->authorise('torneios.diretor.geral', 'com_torneios') )																											//----
			$queryResultsRS->where($this->_db->quoteName('RANMODA.status_modalidade') . ' = ' . $this->_db->quote('1'));																	//----
																																															//----
		if( !$this->_user->authorise('torneios.diretor.modalidade', 'com_torneios') )																										//----
		{																																													//----
			$queryResultsRS->where($this->_db->quoteName('RANCAMP.status_campeonato') . ' = ' . $this->_db->quote('1'));																	//----
			$queryResultsRS->where($this->_db->quoteName('RANPROV.status_prova') . ' = ' . $this->_db->quote('1'));																			//----
		}																																													//----
																																															//----																																															//----
		if(!empty($options['id_prova']))																																					//----
			$queryResultsRS->where('RANINSC.id_prova = ' . $this->_db->quote( $options['id_prova'] ));																						//----
																																															//----
		$queryResultsRS->group('RANRESU.id_etapa');																																			//----										//----
		$queryResultsRS->group('RANRESU.id_inscricao');																																		//----
		$queryResultsRS->group('RANRESU.nr_etapa_prova');																																	//----
																																															//----
		$queryResultsRS->order('RS DESC');																																					//----																																															//----
																																															//----	
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
																																															//----
		$queryResultsRF = $this->_db->getQuery(true);																																		//----
																																															//----
		switch( $options['rf_etapa_prova'] )																																				//----
		{																																													//----
			case '1':																																										//----
				$queryResultsRF->select( 'MAX(ResultsRS.RS) AS RF');																														//----
			break;																																											//----
																																															//----
			case '2':																																										//----
				$RFString = '';																																								//----
				for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {																														//----
					if(!empty($RFString))																																					//----
						$RFString = $RFString . ' + ';																																		//----
					$RFString = $RFString . '  MAX(IF( ResultsRS.nr_etapa_prova = '. $i .', ResultsRS.RS, 0))  ';																			//----
				}																																											//----
				if($options['decimal_prova']==0)																																			//----
					$options['decimal_prova'] = '2';																																		//----
																																															//----
				$queryResultsRF->select( 'ROUND ( ( (' . $RFString . ') / (' . $options['nr_etapa_prova'] . ')), '. $options['decimal_prova'] .' ) AS RF');									//----
			break;																																											//----
																																															//----
			case '3':																																										//----																				
				$RFString = '';																																								//----
				for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {																														//----
					if(!empty($RFString))																																					//----
						$RFString = $RFString . ' + ';																																		//----
					$RFString = $RFString . ' MAX(IF( ResultsRS.nr_etapa_prova = '. $i .', ResultsRS.RS, 0)) ';																				//----
				}																																											//----
				$queryResultsRF->select( '( ' . $RFString . ' ) AS RF');																													//----
			break;																																											//----
		}																																													//----
																																															//----
		$queryResultsRF->select('id_inscricao');																																			//----
		$queryResultsRF->select('id_estado');																																				//----
		$queryResultsRF->select('id_etapa');																																				//----
																																															//----
		$queryResultsRF->from( '(' . $queryResultsRS . ') ResultsRS' );																														//----
																																															//----
		$queryResultsRF->group('id_inscricao');																																				//----
		$queryResultsRF->group('id_estado');																																				//----
		$queryResultsRF->group('id_etapa');																																					//----
																																															//----
		$queryResultsRF->order($this->_db->quoteName('RF') . ' DESC');																														//----
		$queryResultsRF->order($this->_db->quoteName('id_etapa') . ' ASC');																													//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------																																												
																																															//----
		$queryResultsEtapa = $this->_db->getQuery(true);																																	//----																																													
																																															//----
		$queryFinal = '';																																									//----
		$queryResultsEtapa->select( 'id_estado');																																			//----
		$queryResultsEtapa->select( 'id_etapa');																																			//----
		$queryResultsEtapa->select( 'ETAPAORDER.ordering');																																	//----
																																															//----
		for ($i = 1; $i <= $options['nr_equipe_prova']; $i++) { 																															//----
			if(!empty($queryFinal))																																							//----
				$queryFinal .= ' + ';																																						//----
																																															//----
			$queryFinal .= 	'IF(COUNT(ResultsRF.RF) > '.($i-1).																																//----
							', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultsRF.RF ORDER BY ResultsRF.RF DESC ),'																	//----
							.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), 0)';																						//----		
		}																																													//----
																																															//----
		if(!empty($queryFinal))																																								//----
			$queryResultsEtapa->select( '('.$queryFinal.') AS EtapaRF');																													//----
																																															//----
		$queryResultsEtapa->from( '(' . $queryResultsRF . ') ResultsRF' );																													//----
		$queryResultsEtapa->innerJoin( '#__ranking_etapa ETAPAORDER USING('. $this->_db->quoteName('id_etapa'). ')' );																		//----
		$queryResultsEtapa->group('id_estado');																																				//----
		$queryResultsEtapa->group('id_etapa');																																				//----
		$queryResultsEtapa->order('EtapaRF DESC');																																			//----																																											
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

		$queryRankPos = $this->_db->getQuery(true);	
		$queryFinal = '';
		for ($i = 1; $i <= $options['numero_etapas']; $i++) { 
			if(!empty($queryFinal))
				$queryFinal .= ' + ';
		
			$queryFinal .= 	'IF(COUNT(ResultadoRankPos.EtapaRF) > '.($i-1).
							', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultadoRankPos.EtapaRF ORDER BY ResultadoRankPos.ordering ASC ),'
							.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), 0)';		
		}
		
		if(!empty($queryFinal))
			$queryRankPos->select( '('.$queryFinal.') as RankRF');

		$queryRankPos->from( '(' . $queryResultsEtapa . ') ResultadoRankPos' );	
		//$queryRankPos->innerJoin( '#__users ON('. $this->_db->quoteName('id'). ' = '. $this->_db->quoteName('id_equipe'). ')' );
		$queryRankPos->innerJoin( '#__intranet_estado USING('. $this->_db->quoteName('id_estado'). ')' );
		$queryRankPos->group('id_estado');																																			
		$queryRankPos->order('RankRF desc');	
		
		$queryRank = $this->_db->getQuery(true);	
		$queryRank->select( 'GROUP_CONCAT( ResultadoRank.RankRF )' );							
		$queryRank->from( '(' . $queryRankPos . ') ResultadoRank' );	


		$query = $this->_db->getQuery(true);	
		$query->select( 'id_estado');
		$query->select( 'name_estado AS name');																					
		
		for ($i = 1; $i <= $options['numero_etapas']; $i++) { 
			$query->select( 'IF(COUNT(ResultsEtapa.EtapaRF) > '.($i-1).
							', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultsEtapa.EtapaRF ORDER BY ResultsEtapa.ordering ASC ),'
							.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), NULL) AS r' . $i);	
		}
		
		
		$queryFinal = '';
		for ($i = 1; $i <= $options['numero_etapas']; $i++) { 
			if(!empty($queryFinal))
				$queryFinal .= ' + ';
		
			$queryFinal .= 	'IF(COUNT(ResultsEtapa.EtapaRF) > '.($i-1).
							', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultsEtapa.EtapaRF ORDER BY ResultsEtapa.ordering ASC ),'
							.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), 0)';		
		}
		
		if(!empty($queryFinal))
			$query->select( '('.$queryFinal.') as rf');
				
		
		$query->select( 'FIND_IN_SET( ('.$queryFinal.'), (' . $queryRank . ')) AS rank');
			
		$query->from( '(' . $queryResultsEtapa . ') ResultsEtapa' );
		//$query->innerJoin( '#__users ON('. $this->_db->quoteName('id'). ' = '. $this->_db->quoteName('id_equipe'). ')' );	
		$query->innerJoin( '#__intranet_estado USING('. $this->_db->quoteName('id_estado'). ')' );
		
		$query->group('id_estado');																																			

		$query->order('rf desc');
			
		$this->_db->setQuery($query);
		return 	$this->_db->loadObjectList();																																							
	
			*/																																												
																																															
																																									

	
	
	}	

	
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//------------------------------------------------------------------------- PARTICIPAÇÃO PROVA -----------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------

	function getParticipacaoProva( $options = array() )
	{		
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
																																															//----
		$queryPagamento = $this->_db->getQuery(true);																																		//----
		$queryPagamento->select('ano_anuidade AS ano_campeonato');																															//----
		$queryPagamento->select('id_user');																																					//----
		$queryPagamento->select('MIN(baixa_pagamento) AS baixa_pagamento');																													//----
		$queryPagamento->from( $this->_db->quoteName('#__intranet_anuidade') );																												//----
		$queryPagamento->innerJoin( $this->_db->quoteName('#__intranet_pagamento') . ' USING('. $this->_db->quoteName('id_anuidade').')');													//----
		$queryPagamento->where($this->_db->quoteName('baixa_pagamento') . ' IS NOT NULL' );																									//----
		$queryPagamento->where($this->_db->quoteName('status_pagamento') . '=' . $this->_db->quote( '1' ) );																				//----
		$queryPagamento->group($this->_db->quoteName('id_anuidade'));																														//----
		$queryPagamento->group($this->_db->quoteName('id_user'));																															//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
																																															//----
		$queryPartEtapas = $this->_db->getQuery(true);																																		//----
																																															//----
		$queryPartEtapas->select( 'COUNT(DISTINCT( id_inscricao )) AS totalAtletas');																										//----
		$queryPartEtapas->select( 'id_estado');																																				//----
		$queryPartEtapas->select( 'id_etapa');																																				//----
		$queryPartEtapas->select( 'ETAPAORDER.ordering');																																	//----																						
																																															//----
		$queryPartEtapas->from( '#__ranking_resultado' );																																	//----																										
		$queryPartEtapas->innerJoin( '#__ranking_inscricao USING( id_inscricao )' );																										//----
		$queryPartEtapas->innerJoin( '#__ranking_campeonato RANCAMP USING( id_campeonato )' );																								//----
		$queryPartEtapas->innerJoin( '#__ranking_etapa ETAPAORDER USING('. $this->_db->quoteName('id_etapa'). ')' );																		//----
		$queryPartEtapas->innerJoin( '(' . $queryPagamento . ') Pagamento USING('. $this->_db->quoteName('id_user').','. $this->_db->quoteName('ano_campeonato').')');						//----
																																															//----
		$queryPartEtapas->where( $this->_db->quoteName('Pagamento.baixa_pagamento').'<='. $this->_db->quoteName( 'ETAPAORDER.data_end_etapa' ) );											//----																																														
																																															//----																																														
		if(!empty($options['id_prova']))																																					//----
			$queryPartEtapas->where('id_prova = ' . $this->_db->quote( $options['id_prova'] ));																								//----
																																															//----									
		$queryPartEtapas->group('id_estado');																																				//----
		$queryPartEtapas->group('id_etapa');																																				//----									
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		
		
		$queryRankPos = $this->_db->getQuery(true);	
		$queryFinal = '';
		for ($i = 1; $i <= $options['numero_etapas']; $i++) { 
			if(!empty($queryFinal))
				$queryFinal .= ' + ';
		
			$queryFinal .= 	'IF(COUNT(ResultadoRankPos.totalAtletas) > '.($i-1).
							', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultadoRankPos.totalAtletas ORDER BY ResultadoRankPos.totalAtletas ASC ),'
							.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), 0)';		
		}
		
		if(!empty($queryFinal))
			$queryRankPos->select( '('.$queryFinal.') as RankRF');

		$queryRankPos->from( '(' . $queryPartEtapas . ') ResultadoRankPos' );	
		$queryRankPos->innerJoin( '#__intranet_estado USING('. $this->_db->quoteName('id_estado'). ')' );

		$queryRankPos->group('id_estado');																																			
		$queryRankPos->order('RankRF desc');	
		
		$queryRank = $this->_db->getQuery(true);	
		$queryRank->select( 'GROUP_CONCAT( ResultadoRank.RankRF )' );							
		$queryRank->from( '(' . $queryRankPos . ') ResultadoRank' );
		

		$query = $this->_db->getQuery(true);	
		$query->select( 'id_estado');	
		$query->select( 'id_estado AS id_equipe');
		$query->select( 'name_estado AS name');																					
		
		for ($i = 1; $i <= $options['numero_etapas']; $i++) { 
			$query->select( 'IF(COUNT(ResultsEtapa.totalAtletas) > '.($i-1).
							', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultsEtapa.totalAtletas ORDER BY ResultsEtapa.ordering ASC ),'
							.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), NULL) AS r' . $i);	
		
							
			$query->select( 'IF(COUNT(ResultsEtapa.totalAtletas) > '.($i-1).
							', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultsEtapa.ordering ORDER BY ResultsEtapa.ordering ASC ),'
							.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), NULL) AS o' . $i);	

		}
		
		$queryFinal = '';
		for ($i = 1; $i <= $options['numero_etapas']; $i++) { 
			if(!empty($queryFinal))
				$queryFinal .= ' + ';
		
			$queryFinal .= 	'IF(COUNT(ResultsEtapa.totalAtletas) > '.($i-1).
							', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultsEtapa.totalAtletas ORDER BY ResultsEtapa.ordering ASC ),'
							.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), 0)';		
		}
		
		if(!empty($queryFinal))
			$query->select( '('.$queryFinal.') as rf');
				
		
		$query->select( 'FIND_IN_SET( ('.$queryFinal.'), (' . $queryRank . ')) AS rank');
			
		$query->from( '(' . $queryPartEtapas . ') ResultsEtapa' );
		$query->innerJoin( '#__intranet_estado USING('. $this->_db->quoteName('id_estado'). ')' );	

		$query->group('id_estado');																																			

		$query->order('rf desc');
	
			
		$this->_db->setQuery($query);
		return 	$this->_db->loadObjectList();																																						
	

		/*
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
																																															//----
		$queryPartEtapas = $this->_db->getQuery(true);																																		//----
																																															//----
		$queryPartEtapas->select( 'COUNT(DISTINCT( id_inscricao )) AS totalAtletas');																										//----
		$queryPartEtapas->select( 'id_estado');																																				//----
		$queryPartEtapas->select( 'id_etapa');																																				//----
		$queryPartEtapas->select( 'ETAPAORDER.ordering');																																	//----																						
																																															//----
		$queryPartEtapas->from( '#__ranking_resultado' );																																	//----																										
		$queryPartEtapas->innerJoin( '#__ranking_inscricao USING( id_inscricao )' );																										//----
		$queryPartEtapas->innerJoin( '#__ranking_etapa ETAPAORDER USING('. $this->_db->quoteName('id_etapa'). ')' );																		//----																			
																																															//----
		if(!empty($options['id_prova']))																																					//----
			$queryPartEtapas->where('id_prova = ' . $this->_db->quote( $options['id_prova'] ));																								//----
																																															//----									
		$queryPartEtapas->group('id_estado');																																				//----
		$queryPartEtapas->group('id_etapa');																																				//----									
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		
		$queryRankPos = $this->_db->getQuery(true);	
		$queryFinal = '';
		for ($i = 1; $i <= $options['numero_etapas']; $i++) { 
			if(!empty($queryFinal))
				$queryFinal .= ' + ';
		
			$queryFinal .= 	'IF(COUNT(ResultadoRankPos.totalAtletas) > '.($i-1).
							', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultadoRankPos.totalAtletas ORDER BY ResultadoRankPos.totalAtletas ASC ),'
							.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), 0)';		
		}
		
		if(!empty($queryFinal))
			$queryRankPos->select( '('.$queryFinal.') as RankRF');

		$queryRankPos->from( '(' . $queryPartEtapas . ') ResultadoRankPos' );	
		//$queryRankPos->innerJoin( '#__users ON('. $this->_db->quoteName('id'). ' = '. $this->_db->quoteName('id_equipe'). ')' );
		$queryRankPos->innerJoin( '#__intranet_estado USING('. $this->_db->quoteName('id_estado'). ')' );
		
		$queryRankPos->group('id_estado');																																			
		$queryRankPos->order('RankRF desc');	
		
		$queryRank = $this->_db->getQuery(true);	
		$queryRank->select( 'GROUP_CONCAT( ResultadoRank.RankRF )' );							
		$queryRank->from( '(' . $queryRankPos . ') ResultadoRank' );
		

		$query = $this->_db->getQuery(true);	
		$query->select( 'id_estado');
		$query->select( 'name_estado AS name');																					
		
		for ($i = 1; $i <= $options['numero_etapas']; $i++) { 
			$query->select( 'IF(COUNT(ResultsEtapa.totalAtletas) > '.($i-1).
							', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultsEtapa.totalAtletas ORDER BY ResultsEtapa.ordering ASC ),'
							.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), NULL) AS r' . $i);	
		}
		
		$queryFinal = '';
		for ($i = 1; $i <= $options['numero_etapas']; $i++) { 
			if(!empty($queryFinal))
				$queryFinal .= ' + ';
		
			$queryFinal .= 	'IF(COUNT(ResultsEtapa.totalAtletas) > '.($i-1).
							', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultsEtapa.totalAtletas ORDER BY ResultsEtapa.ordering ASC ),'
							.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), 0)';		
		}
		
		if(!empty($queryFinal))
			$query->select( '('.$queryFinal.') as rf');
				
		
		$query->select( 'FIND_IN_SET( ('.$queryFinal.'), (' . $queryRank . ')) AS rank');
			
		$query->from( '(' . $queryPartEtapas . ') ResultsEtapa' );
		$query->innerJoin( '#__intranet_estado USING('. $this->_db->quoteName('id_estado'). ')' );	
		$query->group('id_estado');																																			

		$query->order('rf desc');
	
			
		$this->_db->setQuery($query);
		return 	$this->_db->loadObjectList();																																						
		*/
	}	

	
	
	

	function getGenCatClass( $options = array())
	{
		
		$query = $this->_db->getQuery(true);
		$query->select( $this->_db->quoteName(array('sexo_pf','pcd_pf')) );
		$query->select( 'TIMESTAMPDIFF(YEAR,  CONCAT( YEAR(data_nascimento_pf), "-01-01" ), NOW()) AS idade_pf');
		$query->from( $this->_db->quoteName('#__intranet_pf') );
		$query->where($this->_db->quoteName('id_user') . ' = ' . $this->_db->quote(   $options['id_user'] ) );
		$this->_db->setQuery($query);
		$infoUser = $this->_db->loadObject();
		
		$query->clear();
		
		$query->select( $this->_db->quoteName(array('vezes_atleta_prova',
													'valor_atleta_classe')));
		$query->from( $this->_db->quoteName('#__ranking_atleta_classe') );
		$query->where($this->_db->quoteName('status_atleta_classe') . ' = ' . $this->_db->quote( '1' ));
		$query->where($this->_db->quoteName('id_user') . ' = ' . $this->_db->quote(   $options['id_user'] ) );
		$query->where($this->_db->quoteName('id_prova') . ' = ' . $this->_db->quote(   $options['id_prova'] ) );
		$this->_db->setQuery($query);
		$infoClass = $this->_db->loadObject();
		
		
					
		$query->clear();
		$query->select( $this->_db->quoteName(array('id_classe',
													'name_classe',
													'id_categoria',
													'name_categoria',
													'id_genero',
													'name_genero',
													)));
		$query->from( $this->_db->quoteName('#__ranking_classe') );
		$query->innerJoin($this->_db->quoteName('#__ranking_categoria') . 'USING(' . $this->_db->quoteName('id_categoria') . ')');
		$query->innerJoin($this->_db->quoteName('#__ranking_genero') . 'USING(' . $this->_db->quoteName('id_genero') . ')');
		$query->innerJoin($this->_db->quoteName('#__ranking_prova') . 'USING(' . $this->_db->quoteName('id_prova') . ')');
		$query->where($this->_db->quoteName('id_prova') . '=' . $this->_db->quote(   $options['id_prova'] ) );
		
		$query->where('(' .
						$this->_db->quoteName('sexo_genero') . '=' . $this->_db->quote('2')
					 . ' OR '.
						$this->_db->quoteName('sexo_genero') . '=' . $this->_db->quote( $infoUser->sexo_pf )
					 . ')');
		
		$query->where('(' .
						$this->_db->quoteName('idade_min_categoria') . '=' . $this->_db->quote('0')
					 . ' OR '.
						$this->_db->quoteName('idade_min_categoria') . '<=' . $this->_db->quote( $infoUser->idade_pf )
					 . ')');
		$query->where('(' .
						$this->_db->quoteName('idade_max_categoria') . '=' . $this->_db->quote('0')
					 . ' OR '.
						$this->_db->quoteName('idade_max_categoria') . '>=' . $this->_db->quote( $infoUser->idade_pf )
					 . ')');
		$query->where($this->_db->quoteName('pcd_categoria') . '=' . $this->_db->quote( $infoUser->pcd_pf  ) );
		
		
		if( !$infoClass ):
			 $valor_atleta_classe = 0.00009;
		else:
			$valor_atleta_classe = $infoClass->valor_atleta_classe; 
			if( $infoClass->vezes_atleta_prova < 8 ):
				$query->where($this->_db->quoteName('name_categoria') . ' NOT LIKE ' . $this->_db->quote('%premium%') );
				if($infoClass->valor_atleta_classe >= 95)
				$valor_atleta_classe = 94.999;
			endif;
		endif;
		$query->where('(' .
						$this->_db->quoteName('ponto_min_classe') . '=' . $this->_db->quote('0')
					 . ' OR '.
						$this->_db->quoteName('ponto_min_classe') . '<=' . $this->_db->quote( $valor_atleta_classe )
					 . ')');
		$query->where('(' .
						$this->_db->quoteName('ponto_max_classe') . '=' . $this->_db->quote('0')
					 . ' OR '.
						$this->_db->quoteName('ponto_max_classe') . '>=' . $this->_db->quote( $valor_atleta_classe )
					 . ')');
		
		$this->_db->setQuery($query);
		$class = $this->_db->loadObject();
		
		return $class;
		
	}
		
		
		
	function getSpecialEtapaView( $special_etapa_type = null )
	{
		/*
		$returnView = '';
		$returnView = '<label id="special_status_etapa-lbl" for="special_status_etapa" class="hasTip required">';
		$returnView .= JText::_( 'TORNEIOS_VIEWS_ETAPA_EDIT_TIROABALA_SPECIAL_ETAPA_STATUS'  );  
		$returnView .= ':<span class="star">&nbsp;*</span></label>';   
		$returnView .= '<fieldset class="radio-admin">';   
		$returnView .=  JHTML::_('select.booleanlist', 'special_status_etapa', 'class="radiobtn" ', $special_status_etapa);
		$returnView .= '</fieldset>';   
		$returnView .= '<input type="hidden" name="special_value_etapa" id="special_value_etapa" />';
		$returnView .= '<input type="hidden" name="special_text_etapa" id="special_text_etapa" />';
		$returnView .=$base_dir;
		return  $returnView;
		*/		
	}


	function testValidateSpecialEtapa($id_etapa, $id_campeonato, $special_status_etapa, $special_value_etapa, $special_text_etapa )
	{
		if($special_status_etapa) {
			$query = $this->_db->getQuery(true);
			$query->select( $this->_db->quoteName('id_etapa'));
			$query->from($this->_db->quoteName('#__ranking_etapa'));
			$query->where($this->_db->quoteName('id_campeonato') . ' = ' . $this->_db->quote($id_campeonato));	
			$query->where($this->_db->quoteName('id_etapa') . ' <> ' . $this->_db->quote($id_etapa));
			$query->where($this->_db->quoteName('special_status_etapa') . ' =  1');  
			$this->_db->setQuery($query);
			if ( (boolean) $this->_db->loadObject())
				return false;
		}
		return true;

	}
		
	function getTitleTableSlide( $options )
	{
		$title = array();
		$title[] = '<span style="width:12%; float: left; top: 5px; left: 0px; text-align:center">' . mb_strtoupper( JText::_( 'TORNEIOS_POSICAO_TITLE' ) ) . '</span>';
		$title[] = '<span style="float: left; top: 5px;">' . mb_strtoupper( JText::_( 'TORNEIOS_VIEWS_ATLETA_NAME' ) ) . '</span>';
		
		$title[] = '<span style="width:10%; float:right; top: 5px; text-align:center">' . mb_strtoupper( JText::_( 'TORNEIOS_VIEWS_RESULT_PROVA_NAME_' . self::tagLanguage .'RF' ) ) . '</span>';
		
		for ($i = self::addColsBeforeProva; $i >= 1; $i--) { 
			$title[] = '	<span style="width:10%; float:right; top: 5px; text-align:center">' . mb_strtoupper( JText::sprintf('TORNEIOS_VIEWS_RESULT_PROVA_NAME_TH_ADDCOLBEFORE_' . self::tagLanguage . $options['type_prova']  . '_' . $i, $options['results_prova'] ) ) . '</span>';		 
		}
		 
		$title[] = '<span style="width:10%; float:right; top: 5px; text-align:center">' . mb_strtoupper( JText::_( 'TORNEIOS_VIEWS_RESULT_PROVA_NAME_' . self::tagLanguage .'RH' ) ) . '</span>';

		return $title;
	}	

	function getRowTableSlide( $options )
	{
		$row = array();
		$row[] = '<span style="width:12%; float: left;  text-align: center; display:block;">';
		$row['rank'] = ''; 
		$row[] = '</span>';
		
		$row[] = '<img style="float: left; display:block; margin-top:-0.85%; width: 4%; height: 4%;" src="';
		$row['mold'] = ''; 
		$row[] = '" >';
		
		$row[] = '<img style="float: left; display:block; margin-top:-0.465%; margin-left:-3.7%; width: 3.37%; height: 3.37%;" src="';
		$row['avatar'] = ''; 
		$row[] = '" >';	
		
		$row[] = '<span style="float: left; display:block; margin-left:1%; ">';
		$row['name'] = ''; 
		$row[] = '</span>';
		
		$row[] = '<span style="width:10%; float: right; text-align: center; display:block;">';
		$row['rf'] = '';
		$row[] = '</span>';	
		
		for ($i = self::addColsBeforeProva; $i >= 1; $i--) { 
			$rn = 'rAddColsBefore' . +$i;
			
			$row[] = '	<span style="width:10%; float: right; text-align: center; display:block;">';
			$row[$rn] = '';
			$row[] = '</span>';		 
		}
		 
		$row[] = '<span style="width:10%; float: right; text-align: center; display:block;">';
		$row['rhoje'] = '';
		$row[] = '</span>';		 
		 
		return $row;
	}	
	
	function getResultSlide( $options = array() )
	{	
	
	
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
																																															//----
		$queryPagamento = $this->_db->getQuery(true);																																		//----
		$queryPagamento->select('ano_anuidade AS ano_campeonato');																															//----
		$queryPagamento->select('id_user');																																					//----
		$queryPagamento->select('MIN(baixa_pagamento) AS baixa_pagamento');																													//----
		$queryPagamento->from( $this->_db->quoteName('#__intranet_anuidade') );																												//----
		$queryPagamento->innerJoin( $this->_db->quoteName('#__intranet_pagamento') . ' USING('. $this->_db->quoteName('id_anuidade').')');													//----
		$queryPagamento->where($this->_db->quoteName('baixa_pagamento') . ' IS NOT NULL' );																									//----
		$queryPagamento->where($this->_db->quoteName('status_pagamento') . '=' . $this->_db->quote( '1' ) );																				//----
		$queryPagamento->group($this->_db->quoteName('id_anuidade'));																														//----
		$queryPagamento->group($this->_db->quoteName('id_user'));																															//----
																																															//----
		//----------------------------------------------------------------- RESULTADO SÉRIE ETAPAS CADA INSCRIÇÃO(SEM FINAL) ---------------------------------------------------------------------
		$queryResultsRS = $this->_db->getQuery(true);																																		//----
																																															//----
		for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) {  																															//----
			$queryResultsRS->select( 'MAX(IF( RANRESU.ordering = ' . $i . ', RANRESU.value_resultado, NULL)) AS S' . $i);																	//----
		}																																													//----
																																															//----
		switch( $options['rs_etapa_prova'] )																																				//----
		{																																													//----
			case '1':																																										//----
				$queryResultsRS->select( 'MAX(RANRESU.value_resultado) AS RS');																												//----
			break;																																											//----
																																															//----
			case '2':																																										//----
				$RFString = '';																																								//----
				for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) { 																														//----
					if(!empty($RFString))																																					//----
						$RFString = $RFString . ' + ';																																		//----
					$RFString = $RFString . ' MAX(IF(RANRESU.ordering = '. $i .', RANRESU.value_resultado, 0)) ';																			//----
				}																																											//----
				if($options['decimal_prova']==0)																																			//----
					$options['decimal_prova'] = '2';																																		//----
																																															//----
				$queryResultsRS->select( 'ROUND ( ( (' . $RFString . ') / (' . $options['ns_etapa_prova'] . ')), '. $options['decimal_prova'] .' ) AS RS');									//----
																																															//----
			break;																																											//----
																																															//----
			case '3':																																										//----
				$RFString = '';																																								//----
				for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) { 																														//----
					if(!empty($RFString))																																					//----
						$RFString = $RFString . ' + ';																																		//----
					$RFString = $RFString . ' MAX(IF(RANRESU.ordering = '. $i .', RANRESU.value_resultado, 0)) ';																			//----
				}																																											//----
																																															//----
				$queryResultsRS->select( '( ' . $RFString . ' ) AS RS');																													//----
			break;																																											//----
		}																																													//----
																																															//----
		$queryResultsRS->select( 'RANRESU.id_inscricao');																																	//----
		$queryResultsRS->select( 'RANRESU.nr_etapa_prova');																																	//----
		$queryResultsRS->select( 'RANRESU.id_etapa');																																		//----
																																															//----
		$queryResultsRS->from( '#__ranking_resultado RANRESU' );																															//----
		$queryResultsRS->innerJoin( '#__ranking_inscricao RANINSC USING( id_inscricao )' );																									//----
		$queryResultsRS->innerJoin( '#__ranking_inscricao_etapa RANIETA USING( id_inscricao,  id_etapa )' );																				//----
		$queryResultsRS->innerJoin( '#__ranking_campeonato RANCAMP USING( id_campeonato )' );																								//----
		$queryResultsRS->innerJoin( '#__ranking_modalidade RANMODA  USING( id_modalidade )' );																								//----
		$queryResultsRS->innerJoin( '#__ranking_prova RANPROV USING( id_prova )' );																											//----
		$queryResultsRS->innerJoin( '#__ranking_etapa RANETAPA USING( id_etapa )' );																										//----
		$queryResultsRS->innerJoin( '(' . $queryPagamento . ') Pagamento USING('. $this->_db->quoteName('id_user').','. $this->_db->quoteName('ano_campeonato').')');						//----
																																															//----
		$queryResultsRS->where( $this->_db->quoteName('Pagamento.baixa_pagamento').'<='. $this->_db->quoteName( 'RANETAPA.data_end_etapa' ) );												//----																																														
																																															//----																																														
		if( !$this->_user->authorise('torneios.diretor.geral', 'com_torneios') )																											//----
			$queryResultsRS->where($this->_db->quoteName('RANMODA.status_modalidade') . ' = ' . $this->_db->quote('1'));																	//----
																																															//----
		if( !$this->_user->authorise('torneios.diretor.modalidade', 'com_torneios') )																										//----
		{																																													//----
			$queryResultsRS->where($this->_db->quoteName('RANCAMP.status_campeonato') . ' = ' . $this->_db->quote('1'));																	//----
			$queryResultsRS->where($this->_db->quoteName('RANPROV.status_prova') . ' = ' . $this->_db->quote('1'));																			//----
		}																																													//----
																																															//----
		if(!empty($options['id_prova']))																																					//----
			$queryResultsRS->where('RANINSC.id_prova = ' . $this->_db->quote( $options['id_prova'] ));																						//----
																																															//----
		$queryResultsRS->group('RANRESU.id_etapa');																																			//----
		$queryResultsRS->group('RANRESU.id_inscricao');																																		//----
		$queryResultsRS->group('RANRESU.nr_etapa_prova');																																	//----
																																															//----
		$queryResultsRS->order('RS DESC');																																					//----
																																															//----
		for ($i = $options['ns_etapa_prova']; $i > 0; $i--) { 																																//----
			$queryResultsRS->order('S' . $i . ' DESC');																																		//----
		}																																													//----																																		
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
																																															//----
		$queryResultsRF = $this->_db->getQuery(true);																																		//----
																																															//----
		switch( $options['rf_etapa_prova'] )																																				//----
		{																																													//----
			case '1':																																										//----
				$queryResultsRF->select( 'MAX(ResultsRS.RS) AS RF');																														//----
			break;																																											//----
																																															//----
			case '2':																																										//----
				$RFString = '';																																								//----
				for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {																														//----
					if(!empty($RFString))																																					//----
						$RFString = $RFString . ' + ';																																		//----
					$RFString = $RFString . '  MAX(IF( ResultsRS.nr_etapa_prova = '. $i .', ResultsRS.RS, 0))  ';																			//----
				}																																											//----
				if($options['decimal_prova']==0)																																			//----
					$options['decimal_prova'] = '2';																																		//----
																																															//----
				$queryResultsRF->select( 'ROUND ( ( (' . $RFString . ') / (' . $options['nr_etapa_prova'] . ')), '. $options['decimal_prova'] .' ) AS RF');									//----
			break;																																											//----
																																															//----
			case '3':																																										//----																				
				$RFString = '';																																								//----
				for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {																														//----
					if(!empty($RFString))																																					//----
						$RFString = $RFString . ' + ';																																		//----
					$RFString = $RFString . ' MAX(IF( ResultsRS.nr_etapa_prova = '. $i .', ResultsRS.RS, 0)) ';																				//----
				}																																											//----
				$queryResultsRF->select( '( ' . $RFString . ' ) AS RF');																													//----
			break;																																											//----
		}																																													//----
																																															//----
		$queryResultsRF->select('id_inscricao');																																			//----
		$queryResultsRF->select('id_etapa');																																				//----
																																															//----
		$queryResultsRF->from( '(' . $queryResultsRS . ') ResultsRS' );																														//----
		$queryResultsRF->group('id_inscricao');																																				//----
		$queryResultsRF->group('id_etapa');																																					//----
																																															//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
																																															//----
		$queryRP = $this->_db->getQuery(true);																																				//----
		$queryRP->select('id_etapa');																																						//----
		$queryRP->select( 'IF(ISNULL(ResultsRP.RF),NULL,MAX(ResultsRP.rf)) AS 100_percent');																								//----
		$queryRP->from( '(' . $queryResultsRF . ') ResultsRP' );																															//----
		$queryRP->group('id_etapa');																																						//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
																																															//----
		$queryRanking = $this->_db->getQuery(true);																																			//----
		$queryRanking->select('id_inscricao');																																				//----
		$queryRanking->select('id_associado');																																				//----
		$queryRanking->select('id_etapa');																																					//----
		$queryRanking->select('name_etapa');																																				//----
		$queryRanking->select('id_classe');																																					//----	
		$queryRanking->select('id_local as id_locals');																																		//----
		$queryRanking->select('name AS names');																																				//----
																																															//----
		$queryRanking->select( 'ROUND((((ResultsRF.RF)*100)/(ResultsRP.100_percent)), 3) AS rf');																							//----
		$queryRanking->from( '(' . $queryResultsRF . ') ResultsRF' );																														//----
		$queryRanking->innerJoin( '(' . $queryRP . ') ResultsRP USING( id_etapa )' );																										//----
		$queryRanking->innerJoin( '#__ranking_inscricao USING( id_inscricao )' );																											//----
		$queryRanking->innerJoin( '#__ranking_campeonato USING( id_campeonato )' );																											//----
		$queryRanking->innerJoin( '#__ranking_inscricao_etapa USING( id_inscricao, id_etapa )' );																							//----
		$queryRanking->innerJoin( '#__ranking_etapa USING( id_etapa )' );																													//----
		$queryRanking->innerJoin( '#__intranet_associado USING( id_user )' );																												//----
		$queryRanking->innerJoin( '#__users ON( id = id_local )' );																															//----
																																															//----
		if($options['id_classe'])																																							//----
			$queryRanking->where('id_classe = ' . $this->_db->quote( $options['id_classe'] ));																								//----
																																															//----
		if($options['id_categoria'])																																						//----
			$queryRanking->where('id_categoria = ' . $this->_db->quote( $options['id_categoria'] ));																						//----
																																															//----
		if($options['id_genero'])																																							//----
			$queryRanking->where('id_genero = ' . $this->_db->quote( $options['id_genero'] ));																								//----
																																															//----
		if(!empty($options['id_prova']))																																					//----
			$queryRanking->where('id_prova = ' . $this->_db->quote( $options['id_prova'] ));																								//----
																																															//----
		$queryRanking->group('id_inscricao');																																				//----
		$queryRanking->group('id_etapa');																																					//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
																																															//----																																										
		$queryPosition = $this->_db->getQuery(true);																																		//----
																																															//----
		$queryPosition->select('id_inscricao AS RankInscricao');																															//----
		$queryPosition->select('id_associado AS RankAssociado');																															//----
																																															//----
		$RF = '';																																											//----
		$RN = '';																																											//----
																																															//----
		for ($i = 1; $i <= $options['results_prova']; $i++) { 																																//----
			if(!empty($RF))																																									//----
				$RF .= ' + ';																																								//----
																																															//----
			$RN = 	'IF(COUNT(ResultadoPosition.rf) > '.($i-1)																																//----
					. ', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultadoPosition.rf ORDER BY ResultadoPosition.rf DESC ),'														//----
					. $this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), 0)';																								//----
																																															//----
			$RF .= $RN ;																																									//----
			$queryPosition->select( $RN . ' AS RankR' . $i);																																//----
		}																																													//----
																																															//----
		if(!empty($RF))																																										//----
			$queryPosition->select( 'ROUND (( ('.$RF.') / (' .$options['results_prova']. ') ), 3 ) AS RankRf');																				//----
																																															//----
		$queryPosition->from( '(' . $queryRanking . ') ResultadoPosition' );																												//----	
		$queryPosition->innerJoin($this->_db->quoteName('#__ranking_inscricao') . ' USING('. $this->_db->quoteName('id_inscricao').','. $this->_db->quoteName('id_classe').')' );			//----
																																															//----
		$queryPosition->order($this->_db->quoteName('RankRf') . ' DESC');																													//----
																																															//----
		for ($i = 1; $i <=  $options['results_prova']; $i++) { 																																//----
			$queryPosition->order( 'RankR' . $i . ' DESC');																																	//----
		}																																													//----
																																															//----
		$queryPosition->order($this->_db->quoteName('RankAssociado') . ' ASC');																												//----
		$queryPosition->group($this->_db->quoteName('id_inscricao'));																														//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
																																															//----
		$queryRank = $this->_db->getQuery(true);																																			//----
																																															//----
		$order = '';																																										//----
		for ($i = 1; $i <= $options['results_prova']; $i++) { 																																//----
			$order .= ', ResultadoRank.Rankr' . $i . ' DESC';																																//----
		}																																													//----
																																															//----
		$order .= ', ResultadoRank.RankAssociado  ASC';																																		//----
																																															//----
		$queryRank->select( 'GROUP_CONCAT( ResultadoRank.Rankinscricao  ORDER BY ResultadoRank.Rankrf  DESC' . $order . ' )' );																//----
																																															//----
		$queryRank->from( '(' . $queryPosition . ') ResultadoRank' );																														//----	
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
																																															//----
		$queryHoje = $this->_db->getQuery(true);																																			//----
		$queryHoje->select('id_inscricao');																																					//----
																																															//----
		$queryHoje->select( 'ROUND((((ResultsRH.RF)*100)/(ResultsRP.100_percent)), 3) AS rh');																								//----
		$queryHoje->from( '(' . $queryResultsRF . ') ResultsRH' );																															//----
		$queryHoje->innerJoin( '(' . $queryRP . ') ResultsRP USING( id_etapa )' );																											//----
		$queryHoje->innerJoin( '#__ranking_inscricao USING( id_inscricao )' );																												//----
		$queryHoje->innerJoin( '#__ranking_campeonato USING( id_campeonato )' );																											//----
		$queryHoje->innerJoin( '#__ranking_inscricao_etapa USING( id_inscricao, id_etapa )' );																								//----
																																															//----
		if($options['id_classe'])																																							//----
			$queryHoje->where('id_classe = ' . $this->_db->quote( $options['id_classe'] ));																									//----
																																															//----
		if($options['id_categoria'])																																						//----
			$queryHoje->where('id_categoria = ' . $this->_db->quote( $options['id_categoria'] ));																							//----
																																															//----
		if($options['id_genero'])																																							//----
			$queryHoje->where('id_genero = ' . $this->_db->quote( $options['id_genero'] ));																									//----
																																															//----
		if($options['id_etapa'])																																							//----
			$queryHoje->where('id_etapa = ' . $this->_db->quote( $options['id_etapa'] ));																									//----
																																															//----
		if(!empty($options['id_prova']))																																					//----
			$queryHoje->where('id_prova = ' . $this->_db->quote( $options['id_prova'] ));																									//----
																																															//----
																																															//----
		$queryHoje->group('id_inscricao');																																					//----
		$queryHoje->group('id_etapa');																																						//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------																																												


		$query = $this->_db->getQuery(true);
		$query->select( $this->_db->quoteName(array( 'id_inscricao',
													 'id_prova',
													 'id_user',
													 
													 'id_genero',
													 'name_genero',
													 
													 'id_categoria',
													 'name_categoria',
													 
													 'id_classe',
													 'name_classe',

													 'AtletaDados.name',
												    )));
		$query->select('image_pf AS image');
		
		$query->select('EquipeClube.name AS name_equipe');
		
		$query->select( 'FIND_IN_SET( Resultados.id_inscricao, (' . $queryRank . ') ) AS rank');
		$query->select( 'ResultadoHoje.rh AS rhoje');
		
		for ($i = 1; $i <= $options['results_prova']; $i++) { 
				
			$query->select( 'IF(COUNT(Resultados.rf) > '.($i-1)
								. ', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( Resultados.rf ORDER BY Resultados.rf DESC ), '
								. $this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), NULL) AS r' . $i);		
								
			$query->select( 'IF(COUNT(Resultados.rf) > '.($i-1)
								. ', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( Resultados.id_etapa ORDER BY Resultados.rf DESC ), '
								. $this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), NULL) AS e'.$i);	
								
			$query->select( 'IF(COUNT(Resultados.rf) > '.($i-1)
								. ', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( Resultados.name_etapa ORDER BY Resultados.rf DESC SEPARATOR \'@@**@**@@\'), '
								. $this->_db->quote('@@**@**@@').', '.($i).'),'.$this->_db->quote('@@**@**@@').', -1), NULL) AS en'.$i);	
										
			$query->select( 'IF(COUNT(Resultados.rf) > '.($i-1)
								. ', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( Resultados.id_locals ORDER BY Resultados.rf DESC ), '
								. $this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), NULL) AS l'.$i);	
										
			$query->select( 'IF(COUNT(Resultados.rf) > '.($i-1)
								. ', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( Resultados.names ORDER BY Resultados.rf DESC SEPARATOR \'@@**@**@@\'), '
								. $this->_db->quote('@@**@**@@').', '.$i.'),'.$this->_db->quote('@@**@**@@').', -1), NULL) AS ln'.$i);	
		}
		
		$query->select( 'COUNT(Resultados.rf) as rAddColsBefore1' );
		$query->select( 'ROUND (( (SUM(Resultados.rf)) / (COUNT(Resultados.rf)) ), 3 ) as rAddColsBefore2');

		
		$queryFinal = '';
		$querySoma = '';
		for ($i = 1; $i <= $options['results_prova']; $i++) { 
			if(!empty($queryFinal))
				$queryFinal .= ' + ';
		
			$querySoma = 	'IF(COUNT(Resultados.rf) > '.($i-1)
								. ', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( Resultados.rf ORDER BY Resultados.rf DESC ), ' 
								. $this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), 0)';	
			
			$queryFinal .= $querySoma ;
			$query->select( $querySoma . ' AS r' . $i);	
		}
		
		if(!empty($queryFinal))
			$query->select( 'ROUND (( ('.$queryFinal.') / (' .$options['results_prova']. ') ), 3 ) AS rf');

		$query->from( $this->_db->quoteName('#__ranking_inscricao') );
		$query->innerJoin('(' . $queryRanking . ') Resultados USING('. $this->_db->quoteName('id_inscricao').','. $this->_db->quoteName('id_classe').')' );	
		
		$query->leftJoin('(' . $queryHoje . ') ResultadoHoje USING('. $this->_db->quoteName('id_inscricao').')' );	
		
		$query->innerJoin( '#__ranking_campeonato USING( id_campeonato )' );
		$query->innerJoin( '#__ranking_modalidade USING( id_modalidade )' );
		$query->innerJoin( '#__ranking_prova USING( id_prova, id_campeonato )' );

		$query->innerJoin( '#__ranking_genero USING( id_genero, id_prova )' );
		$query->innerJoin( '#__ranking_categoria USING( id_categoria, id_genero )' );		
		$query->innerJoin( '#__ranking_classe USING( id_classe, id_categoria )' );
	
		$query->innerJoin( '#__intranet_pf USING('. $this->_db->quoteName('id_user').')' );
		$query->innerJoin( $this->_db->quoteName('#__intranet_associado') . ' USING('. $this->_db->quoteName('id_user').')' );
		
		$query->innerJoin( '#__users AtletaDados ON('. $this->_db->quoteName('AtletaDados.id'). ' = '. $this->_db->quoteName('id_user'). ')' );
		$query->leftJoin( '#__users EquipeClube ON('. $this->_db->quoteName('id_equipe') . '='. $this->_db->quoteName('EquipeClube.id'). ')' );
					
		if($options['id_classe'])
			$query->where('id_classe = ' . $this->_db->quote( $options['id_classe'] ));	
															
		if($options['id_categoria'])				
			$query->where('id_categoria = ' . $this->_db->quote( $options['id_categoria'] ));
											
		if($options['id_genero'])	
			$query->where('id_genero = ' . $this->_db->quote( $options['id_genero'] ));
													
		if(!empty($options['id_prova']))	
			$query->where('id_prova = ' . $this->_db->quote( $options['id_prova'] ));
			
		if($options['name'])
			$query->where( $this->_db->quoteName('AtletaDados.name') . ' LIKE \'%' . $this->_db->escape($options['name']) . '%\'' );
		
		if(isset($options['id_user']) && $options['id_user'])
			$query->where($this->_db->quoteName('#__ranking_inscricao.id_user') . ' = ' . $this->_db->quote( $options['id_user'] ));					
																
		$query->group($this->_db->quoteName('id_inscricao'));
		
		$query->order($this->_db->quoteName('rf') . ' DESC');

		for ($i = 1; $i <=  $options['results_prova']; $i++) { 
				$query->order( 'r' . $i . ' DESC');
		}

		$query->order($this->_db->quoteName('#__intranet_associado.id_associado') . ' ASC');

		$this->_db->setQuery($query);
		return 	$this->_db->loadObjectList();

	}
	
	
	function getTitleTableSlideEtapa( $options )
	{
		$title = array();
		$title[] = '<span style="width:12%; float: left; top: 5px; left: 0px; text-align:center">' . mb_strtoupper( JText::_( 'TORNEIOS_POSICAO_TITLE' ) ) . '</span>';
		$title[] = '<span style="float: left; top: 5px;">' . mb_strtoupper( JText::_( 'TORNEIOS_VIEWS_ATLETA_NAME' ) ) . '</span>';
		
		for ($i = self::addColsAfterEtapa; $i >= 1; $i--) { 
			$title[] = '	<span style="width:10%; float:right; top: 5px; text-align:center">' .mb_strtoupper( JText::sprintf('TORNEIOS_VIEWS_RESULT_ETAPA_NAME_ADDCOLSAFTER_TH_' . self::tagLanguage . $options['type_prova'] , $i) ) . '</span>';		 
		}
		
		$title[] = '<span style="width:8%; float:right; top: 5px; text-align:center">' . mb_strtoupper( JText::_( 'TORNEIOS_VIEWS_RESULT_ETAPA_NAME_' . self::tagLanguage .'RF' ) ) . '</span>';
		
		
		if($options['nr_etapa_prova']>1 && $options['ns_etapa_prova']>1) :
			for ($i = $options['nr_etapa_prova']; $i >= 1; $i--) { 
				
				$name = 'rs' . $i;
				$title[] = '	<span style="width:6%; float:right; top: 5px; text-align:center">' . mb_strtoupper( JText::sprintf('TORNEIOS_VIEWS_RESULT_ETAPA_NAME_' . self::tagLanguage.'RS', $i) ) . '</span>';			 
			}
			
			
			
			
		endif;
		
		return $title;
	}	
	
	
	function getRowTableSlideEtapa( $options )
	{
		$row = array();
		$row[] = '<span style="width:12%; float: left;  text-align: center; display:block;">';
		$row['rank'] = ''; 
		$row[] = '</span>';
		
		$row[] = '<img style="float: left; display:block; margin-top:-0.85%; width: 4%; height: 4%;" src="';
		$row['mold'] = ''; 
		$row[] = '" >';
		
		$row[] = '<img style="float: left; display:block; margin-top:-0.465%; margin-left:-3.7%; width: 3.37%; height: 3.37%;" src="';
		$row['avatar'] = ''; 
		$row[] = '" >';	
		
		$row[] = '<span style="float: left; display:block; margin-left:1%; ">';
		$row['name'] = ''; 
		$row[] = '</span>';

		//$row[] = '<span style="width:10%; float: right; text-align: center; display:block;">';
		//$row['rAddColsBefore1'] = '';
		//$row[] = '</span>';
		
		for ($i = self::addColsAfterEtapa; $i >= 1; $i--) { 
		
		$row[] = '<span style="width:10%; float: right; text-align: center; display:block;">';
		$name = 'rAddColsAfter' . $i;
		$row[$name] = '';
		$row[] = '</span>';
		}
		
		$row[] = '<span style="width:8%; float: right; text-align: center; display:block;">';
		$row['rf'] = '';
		$row[] = '</span>';
		
		if($options['nr_etapa_prova']>1 && $options['ns_etapa_prova']>1) :
			for ($i = $options['nr_etapa_prova']; $i >= 1; $i--) { 
			 	$name = 'rs' . $i;
				$row[] = '<span style="width:6%; float: right; text-align: center; display:block;">';
				$row[$name] = '';
				$row[] = '</span>';			 
			}
		endif;

		return $row;
	}	
	
	function getResultSlideEtapa( $options = array() )
	{		
		//----------------------------------------------------------------- RESULTADO SÉRIE ETAPAS CADA INSCRIÇÃO(SEM FINAL) ---------------------------------------------------------------------
		$queryResultsRS = $this->_db->getQuery(true);																																		//----
																																															//----
		for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) {  																															//----
			$queryResultsRS->select( 'MAX(IF( RANRESU.ordering = ' . $i . ', RANRESU.value_resultado, NULL)) AS S' . $i);																	//----
		}																																													//----
																																															//----
		switch( $options['rs_etapa_prova'] )																																				//----
		{																																													//----
			case '1':																																										//----
				$queryResultsRS->select( 'MAX(RANRESU.value_resultado) AS RS');																												//----
			break;																																											//----
																																															//----
			case '2':																																										//----
				$RFString = '';																																								//----
				for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) { 																														//----
					if(!empty($RFString))																																					//----
						$RFString = $RFString . ' + ';																																		//----
					$RFString = $RFString . ' MAX(IF(RANRESU.ordering = '. $i .', RANRESU.value_resultado, 0)) ';																			//----
				}																																											//----
				if($options['decimal_prova']==0)																																			//----
					$options['decimal_prova'] = '2';																																		//----
																																															//----
				$queryResultsRS->select( 'ROUND ( ( (' . $RFString . ') / (' . $options['ns_etapa_prova'] . ')), '. $options['decimal_prova'] .' ) AS RS');									//----
																																															//----
			break;																																											//----
																																															//----
			case '3':																																										//----
				$RFString = '';																																								//----
				for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) { 																														//----
					if(!empty($RFString))																																					//----
						$RFString = $RFString . ' + ';																																		//----
					$RFString = $RFString . ' MAX(IF(RANRESU.ordering = '. $i .', RANRESU.value_resultado, 0)) ';																			//----
				}																																											//----
																																															//----
				$queryResultsRS->select( '( ' . $RFString . ' ) AS RS');																													//----
			break;																																											//----
		}																																													//----
																																															//----
		$queryResultsRS->select( 'RANRESU.id_inscricao');																																	//----
		$queryResultsRS->select( 'RANRESU.nr_etapa_prova');																																	//----
		$queryResultsRS->select( 'RANRESU.id_etapa');																																		//----
																																															//----
		$queryResultsRS->from( '#__ranking_resultado RANRESU' );																															//----
		$queryResultsRS->innerJoin( '#__ranking_inscricao RANINSC USING( id_inscricao )' );																									//----
		$queryResultsRS->innerJoin( '#__ranking_inscricao_etapa RANIETA USING( id_inscricao,  id_etapa )' );																				//----
		$queryResultsRS->innerJoin( '#__ranking_campeonato RANCAMP USING( id_campeonato )' );																								//----
		$queryResultsRS->innerJoin( '#__ranking_modalidade RANMODA  USING( id_modalidade )' );																								//----
		$queryResultsRS->innerJoin( '#__ranking_prova RANPROV USING( id_prova )' );																											//----
																																															//----
		if( !$this->_user->authorise('torneios.diretor.geral', 'com_torneios') )																											//----
			$queryResultsRS->where($this->_db->quoteName('RANMODA.status_modalidade') . ' = ' . $this->_db->quote('1'));																	//----
																																															//----
		if( !$this->_user->authorise('torneios.diretor.modalidade', 'com_torneios') )																										//----
		{																																													//----
			$queryResultsRS->where($this->_db->quoteName('RANCAMP.status_campeonato') . ' = ' . $this->_db->quote('1'));																	//----
			$queryResultsRS->where($this->_db->quoteName('RANPROV.status_prova') . ' = ' . $this->_db->quote('1'));																			//----
		}																																													//----
																																															//----
		if(!empty($options['id_prova']))																																					//----
			$queryResultsRS->where('RANINSC.id_prova = ' . $this->_db->quote( $options['id_prova'] ));																						//----
																																															//----
		if(!empty($options['id_etapa']))																																					//----
			$queryResultsRS->where('RANIETA.id_etapa = ' . $this->_db->quote( $options['id_etapa'] ));																						//----
																																															//----
		$queryResultsRS->group('RANRESU.id_etapa');																																			//----
		$queryResultsRS->group('RANRESU.id_inscricao');																																		//----
		$queryResultsRS->group('RANRESU.nr_etapa_prova');																																	//----
																																															//----
		$queryResultsRS->order('RS DESC');																																					//----
																																															//----
		for ($i = $options['ns_etapa_prova']; $i > 0; $i--) { 																																//----
			$queryResultsRS->order('S' . $i . ' DESC');																																		//----
		}																																													//----																																		
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
																																															//----
		$queryResultsRF = $this->_db->getQuery(true);																																		//----
																																															//----
		switch( $options['rf_etapa_prova'] )																																				//----
		{																																													//----
			case '1':																																										//----
				$queryResultsRF->select( 'MAX(ResultsRS.RS) AS rf');																														//----
			break;																																											//----
																																															//----
			case '2':																																										//----
				$RFString = '';																																								//----
				for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {																														//----
					if(!empty($RFString))																																					//----
						$RFString = $RFString . ' + ';																																		//----
					$RFString = $RFString . '  MAX(IF( ResultsRS.nr_etapa_prova = '. $i .', ResultsRS.RS, 0))  ';																			//----
				}																																											//----
				if($options['decimal_prova']==0)																																			//----
					$options['decimal_prova'] = '2';																																		//----
																																															//----
				$queryResultsRF->select( 'ROUND ( ( (' . $RFString . ') / (' . $options['nr_etapa_prova'] . ')), '. $options['decimal_prova'] .' ) AS rf');									//----
			break;																																											//----
																																															//----
			case '3':																																										//----																				
				$RFString = '';																																								//----
				for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {																														//----
					if(!empty($RFString))																																					//----
						$RFString = $RFString . ' + ';																																		//----
					$RFString = $RFString . ' MAX(IF( ResultsRS.nr_etapa_prova = '. $i .', ResultsRS.RS, 0)) ';																				//----
				}																																											//----
				$queryResultsRF->select( '( ' . $RFString . ' ) AS RF');																													//----
			break;																																											//----
		}																																													//----
																																															//----
		for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {																																//----
			for ($x = 1; $x <= $options['ns_etapa_prova']; $x++) {  																														//----
				$queryResultsRF->select( 'MAX(IF( ResultsRS.nr_etapa_prova = '. $i .' , ResultsRS.S'.  $x . ', NULL)) AS r' . $i . 's' . $x);												//----
			}																																												//----
																																															//----
			$queryResultsRF->select( 'MAX(IF( ResultsRS.nr_etapa_prova = '. $i .', ResultsRS.RS, NULL)) AS rs' . $i);																		//----
		}																																													//----
																																															//----
		$queryResultsRF->select('id_inscricao');																																			//----
		//$queryResultsRF->select('id_associado');																																			//----
		$queryResultsRF->select('id_etapa');																																				//----
		$queryResultsRF->select('id_classe');																																				//----		
		$queryResultsRF->select('shotoff_inscricao_etapa');																																	//----
																																															//----
		$queryResultsRF->from( '(' . $queryResultsRS . ') ResultsRS' );																														//----
		$queryResultsRF->innerJoin( '#__ranking_inscricao USING( id_inscricao )' );																											//----
		$queryResultsRF->innerJoin( '#__ranking_inscricao_etapa USING( id_inscricao, id_etapa )' );																							//----
		//$queryResultsRF->innerJoin( '#__intranet_associado USING( id_user )' );																											//----
		$queryResultsRF->group('id_inscricao');																																				//----
		//$queryResultsRF->group('id_etapa');																																				//----
																																															//----
		$queryResultsRF->order($this->_db->quoteName('rf') . ' DESC');																														//----
																																															//----
		if($options['shot_off_prova']==1)																																					//----
			$queryResultsRF->order($this->_db->quoteName('shotoff_inscricao_etapa') . ' ASC');																								//----
																																															//----
		for ($i = $options['nr_etapa_prova']; $i > 0; $i--) { 																																//----
			$queryResultsRF->order('rs' . $i . ' DESC');																																	//----
		}																																													//----
																																															//----	
		for ($i = $options['nr_etapa_prova']; $i > 0 ; $i--) {																																//----
			for ($x = $options['ns_etapa_prova']; $x > 0; $x--) {  																															//----
				$queryResultsRF->order('r' . $i . 's' . $x . ' DESC');																														//----
			}																																												//----
		}																																													//----
																																															//----
	//	$queryResultsRF->order('id_associado ASC');																																			//----	
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
																																															//----
		$queryRP = $this->_db->getQuery(true);																																				//----
		$queryRP->select( 'IF(ISNULL(ResultsRP.rf),NULL,MAX(ResultsRP.rf)) AS rp');																											//----
		$queryRP->from( '(' . $queryResultsRF . ') ResultsRP' );																															//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
																																															//----																																										
		$queryRank = $this->_db->getQuery(true);																																			//----
		$order='';																																											//----
		if($options['shot_off_prova']==1)																																					//----
			$order .= ', ResultadoRank.shotoff_inscricao_etapa ASC';																														//----
																																															//----			
		for ($i = $options['nr_etapa_prova']; $i > 0; $i--) { 																																//----
			$order .= ', ResultadoRank.rs' . $i . ' DESC';																																	//----
		}																																													//----
																																															//----	
		for ($i = $options['nr_etapa_prova']; $i > 0 ; $i--) {																																//----
			for ($x = $options['ns_etapa_prova']; $x > 0; $x--) {  																															//----
				$order .= ', ResultadoRank.r' . $i . 's' . $x. ' DESC';																														//----
			}																																												//----
		}																																													//----
																																															//----
		//$order .= ', ResultadoRank.id_associado ASC';																																		//----	
																																															//----
																																															//----
		if($options['shot_off_prova']==1)																																					//----
			$queryRank->select( 'GROUP_CONCAT( ResultadoRank.rf + '																															//----
									. ' IF(ISNULL(ResultadoRank.shotoff_inscricao_etapa), 0, ResultadoRank.shotoff_inscricao_etapa/100 ) '													//----
									. ' ORDER BY ResultadoRank.rf  DESC' . $order . ' )' );																									//----
		else																																												//----					
			$queryRank->select( 'GROUP_CONCAT( ResultadoRank.rf ORDER BY ResultadoRank.rf  DESC' . $order . ' )' );																			//----							
																																															//----
		$queryRank->from( '(' . $queryResultsRF . ') ResultadoRank' );																														//----	
																																															//----
		if(!empty($options['id_classe']))																																					//----
			$queryRank->where('ResultadoRank.id_classe = ' . $this->_db->quote( $options['id_classe'] ));																					//----
																																															//----
		if(!empty($options['id_etapa']))																																					//----
			$queryRank->where('ResultadoRank.id_etapa = ' . $this->_db->quote( $options['id_etapa'] ));																						//----
																																															//----																																												
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------																																																																																					

		$query = $this->_db->getQuery(true);
		$query->select( $this->_db->quoteName(array( 'id_inscricao',
													 'id_prova',
													 'id_user',
													 
													 'id_genero',
													 'name_genero',
													 
													 'id_categoria',
													 'name_categoria',
													 
													 'id_classe',
													 'name_classe',

													 'AtletaDados.name',
												    )));
		$query->select('image_pf AS image');
		
		$query->select('EquipeClube.name AS name_equipe');
		
		if($options['shot_off_prova']==1)
			$query->select( 'FIND_IN_SET(  Resultados.rf + '	
									. ' IF(ISNULL(Resultados.shotoff_inscricao_etapa), 0, Resultados.shotoff_inscricao_etapa/100 ) '
								    . ', (' . $queryRank . ') ) AS rank');
		else
			$query->select( 'FIND_IN_SET( Resultados.rf, (' . $queryRank . ') ) AS rank');

		for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) { 
			$query->select( 'IF(ISNULL(Resultados.rs' . $i . '), \'-\', Resultados.rs' . $i . ') AS rs' . $i );
		}
		$query->select( 'IF(ISNULL(Resultados.rf), NULL, Resultados.rf) AS rf');

		$query->select( 'ROUND((((Resultados.rf)*100)/(' . $queryRP . ')), 3) AS rAddColsAfter1');

		$query->from( $this->_db->quoteName('#__ranking_inscricao') );
		$query->innerJoin('(' . $queryResultsRF . ') Resultados USING('. $this->_db->quoteName('id_inscricao').','. $this->_db->quoteName('id_classe').')' );	
		
		$query->innerJoin( '#__ranking_campeonato USING( id_campeonato )' );
		$query->innerJoin( '#__ranking_modalidade USING( id_modalidade )' );
		$query->innerJoin( '#__ranking_prova USING( id_prova, id_campeonato )' );

		$query->innerJoin( '#__ranking_genero USING( id_genero, id_prova )' );
		$query->innerJoin( '#__ranking_categoria USING( id_categoria, id_genero )' );		
		$query->innerJoin( '#__ranking_classe USING( id_classe, id_categoria )' );
	
		$query->innerJoin( '#__intranet_pf USING('. $this->_db->quoteName('id_user').')' );
		$query->innerJoin( '#__users AtletaDados ON('. $this->_db->quoteName('AtletaDados.id'). ' = '. $this->_db->quoteName('id_user'). ')' );
		$query->leftJoin( '#__users EquipeClube ON('. $this->_db->quoteName('id_equipe') . '='. $this->_db->quoteName('EquipeClube.id'). ')' );
		
		if(!empty($options['id_classe']))
			$query->where('id_classe = ' . $this->_db->quote( $options['id_classe'] ));																					
		
		if(!empty($options['id_etapa']))											
			$query->where('id_etapa = ' . $this->_db->quote( $options['id_etapa'] ));																				
																	
		if(!empty($options['id_categoria']))					
			$query->where('id_categoria = ' . $this->_db->quote( $options['id_categoria'] ));
															
		if(!empty($options['id_genero']))			
			$query->where('id_genero = ' . $this->_db->quote( $options['id_genero'] ));		
															
		if(!empty($options['id_prova']))	
			$query->where('id_prova = ' . $this->_db->quote( $options['id_prova'] ));
																
		$query->group($this->_db->quoteName('id_inscricao'));
		
		$query->order($this->_db->quoteName('rf') . ' DESC');

		if($options['shot_off_prova']==1)													
			$query->order($this->_db->quoteName('shotoff_inscricao_etapa') . ' ASC');	

		/*
		if($options['nr_etapa_prova']>1 && $options['ns_etapa_prova']>1) :
			for ($i = $options['nr_etapa_prova']; $i >= 1; $i--) { 
				$query->order( 'rs' . $i . ' DESC');
			}
		endif;
		*/
		
		for ($i = $options['nr_etapa_prova']; $i >= 1; $i--) { 
			for ($x = $options['ns_etapa_prova']; $x >= 1; $x--) { 
				$query->order( 'r' . $i . 's' . $x . ' DESC');
			}
		}

		$query->order($this->_db->quoteName('id_pf') . ' ASC');

		$this->_db->setQuery($query);
		return 	$this->_db->loadObjectList();
	}	

	function getProvaProv( $options = array() )
	{
		$query = $this->_db->getQuery(true);
		$query->select( $this->_db->quoteName(array(//'#__ranking_inscricao.id_genero',
													//'#__ranking_inscricao.id_categoria',
													//'name_campeonato',
													//'ano_campeonato',
													//'name_etapa',
													'id_prova',
													'name_prova',
													//'name_classe',
													//'name_categoria',
													//'name_genero',
													'id_etapa',
													
													'type_prova',
													'results_prova',
													'decimal_prova',
													'ns_etapa_prova',																								
													'rf_etapa_prova',
													'rs_etapa_prova',
													'shot_off_prova',
													'#__ranking_prova.nr_etapa_prova',
													'image_prova',
													'image_campeonato'
													)));
		$query->from($this->_db->quoteName('#__ranking_resultado'));		
		$query->innerJoin($this->_db->quoteName('#__ranking_inscricao') . 'USING(' . $this->_db->quoteName('id_inscricao') . ')');
		$query->innerJoin($this->_db->quoteName('#__ranking_inscricao_etapa') . 'USING(' . $this->_db->quoteName('id_inscricao') . ',' . $this->_db->quoteName('id_etapa') . ')');	
			
		$query->innerJoin($this->_db->quoteName('#__ranking_campeonato') . 'USING(' . $this->_db->quoteName('id_campeonato') . ')');	
		$query->innerJoin($this->_db->quoteName('#__ranking_modalidade') . 'USING(' . $this->_db->quoteName('id_modalidade') . ')');
		$query->innerJoin($this->_db->quoteName('#__ranking_prova') . 'USING(' . $this->_db->quoteName('id_prova') . ')');
		$query->innerJoin($this->_db->quoteName('#__ranking_etapa') . 'USING(' . $this->_db->quoteName('id_etapa') . ')');
		
		$query->where($this->_db->quoteName('id_etapa') . ' = ' . $this->_db->quote($options['id_etapa'])); 
		$query->where($this->_db->quoteName('id_prova') . ' = ' . $this->_db->quote($options['id_prova']));
		$query->where($this->_db->quoteName('status_resultado') . ' = ' . $this->_db->quote('1'));
		$query->where($this->_db->quoteName('status_inscricao') . ' = ' . $this->_db->quote('1'));
		$query->where($this->_db->quoteName('status_inscricao_etapa') . ' = ' . $this->_db->quote('1'));
		$query->where($this->_db->quoteName('status_etapa') . ' = ' . $this->_db->quote('1'));			
		$query->where($this->_db->quoteName('status_campeonato') . ' = ' . $this->_db->quote('1'));
		$query->where($this->_db->quoteName('status_prova') . ' = ' . $this->_db->quote('1'));						
		$query->where($this->_db->quoteName('status_modalidade') . ' = ' . $this->_db->quote('1'));
						
		$query->group($this->_db->quoteName('id_prova') );
		
		$query->order($this->_db->quoteName('#__ranking_prova.ordering')); 
		$this->_db->setQuery($query);
		return $this->_db->loadAssoc();
		
		
	}

	function getResultSlideProv()
	{		
		$options = array();
		$options['id_etapa'] = '237';//237
		$options['id_prova'] = '134';
		$provaPercurso = $this->getProvaProv($options);
		
		$options = array();
		$options['id_etapa'] = '234'; //234
		$options['id_prova'] = '133';
		$provaCompak = $this->getProvaProv($options);
	
		for ($zz = 0; $zz <= 1; $zz++) 
		{
		
			if($zz==1)
				$options = $provaCompak;
			else
				$options = $provaPercurso;
			
			//----------------------------------------------------------------- RESULTADO SÉRIE ETAPAS CADA INSCRIÇÃO(SEM FINAL) ---------------------------------------------------------------------
			$queryResultsRS = $this->_db->getQuery(true);																																		//----
																																																//----
			for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) {  																															//----
				$queryResultsRS->select( 'MAX(IF( RANRESU.ordering = ' . $i . ', RANRESU.value_resultado, NULL)) AS S' . $i);																	//----
			}																																													//----
																																																//----
			switch( $options['rs_etapa_prova'] )																																				//----
			{																																													//----
				case '1':																																										//----
					$queryResultsRS->select( 'MAX(RANRESU.value_resultado) AS RS');																												//----
				break;																																											//----
																																																//----
				case '2':																																										//----
					$RFString = '';																																								//----
					for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) { 																														//----
						if(!empty($RFString))																																					//----
							$RFString = $RFString . ' + ';																																		//----
						$RFString = $RFString . ' MAX(IF(RANRESU.ordering = '. $i .', RANRESU.value_resultado, 0)) ';																			//----
					}																																											//----
					if($options['decimal_prova']==0)																																			//----
						$options['decimal_prova'] = '2';																																		//----
																																																//----
					$queryResultsRS->select( 'ROUND ( ( (' . $RFString . ') / (' . $options['ns_etapa_prova'] . ')), '. $options['decimal_prova'] .' ) AS RS');									//----
																																																//----
				break;																																											//----
																																																//----
				case '3':																																										//----
					$RFString = '';																																								//----
					for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) { 																														//----
						if(!empty($RFString))																																					//----
							$RFString = $RFString . ' + ';																																		//----
						$RFString = $RFString . ' MAX(IF(RANRESU.ordering = '. $i .', RANRESU.value_resultado, 0)) ';																			//----
					}																																											//----
																																																//----
					$queryResultsRS->select( '( ' . $RFString . ' ) AS RS');																													//----
				break;																																											//----
			}																																													//----
																																																//----
			$queryResultsRS->select( 'RANRESU.id_inscricao');																																	//----
			$queryResultsRS->select( 'RANRESU.nr_etapa_prova');																																	//----
			$queryResultsRS->select( 'RANRESU.id_etapa');																																		//----
																																																//----
			$queryResultsRS->from( '#__ranking_resultado RANRESU' );																															//----
			$queryResultsRS->innerJoin( '#__ranking_inscricao RANINSC USING( id_inscricao )' );																									//----
			$queryResultsRS->innerJoin( '#__ranking_inscricao_etapa RANIETA USING( id_inscricao,  id_etapa )' );																				//----
			$queryResultsRS->innerJoin( '#__ranking_campeonato RANCAMP USING( id_campeonato )' );																								//----
			$queryResultsRS->innerJoin( '#__ranking_modalidade RANMODA  USING( id_modalidade )' );																								//----
			$queryResultsRS->innerJoin( '#__ranking_prova RANPROV USING( id_prova )' );																											//----
																																																//----
			if( !$this->_user->authorise('torneios.diretor.geral', 'com_torneios') )																											//----
				$queryResultsRS->where($this->_db->quoteName('RANMODA.status_modalidade') . ' = ' . $this->_db->quote('1'));																	//----
																																																//----
			if( !$this->_user->authorise('torneios.diretor.modalidade', 'com_torneios') )																										//----
			{																																													//----
				$queryResultsRS->where($this->_db->quoteName('RANCAMP.status_campeonato') . ' = ' . $this->_db->quote('1'));																	//----
				$queryResultsRS->where($this->_db->quoteName('RANPROV.status_prova') . ' = ' . $this->_db->quote('1'));																			//----
			}																																													//----
																																																//----
			if(!empty($options['id_prova']))																																					//----
				$queryResultsRS->where('RANINSC.id_prova = ' . $this->_db->quote( $options['id_prova'] ));																						//----
																																																//----
			if(!empty($options['id_etapa']))																																					//----
				$queryResultsRS->where('RANIETA.id_etapa = ' . $this->_db->quote( $options['id_etapa'] ));																						//----
																																																//----
			$queryResultsRS->group('RANRESU.id_etapa');																																			//----
			$queryResultsRS->group('RANRESU.id_inscricao');																																		//----
			$queryResultsRS->group('RANRESU.nr_etapa_prova');																																	//----
																																																//----
			$queryResultsRS->order('RS DESC');																																					//----
																																																//----
			for ($i = $options['ns_etapa_prova']; $i > 0; $i--) { 																																//----
				$queryResultsRS->order('S' . $i . ' DESC');																																		//----
			}																																													//----																																		
																																																//----
			//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
																																																//----
			$queryResultsRF = $this->_db->getQuery(true);																																		//----
																																																//----
			switch( $options['rf_etapa_prova'] )																																				//----
			{																																													//----
				case '1':																																										//----
					$queryResultsRF->select( 'MAX(ResultsRS.RS) AS rf');																														//----
				break;																																											//----
																																																//----
				case '2':																																										//----
					$RFString = '';																																								//----
					for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {																														//----
						if(!empty($RFString))																																					//----
							$RFString = $RFString . ' + ';																																		//----
						$RFString = $RFString . '  MAX(IF( ResultsRS.nr_etapa_prova = '. $i .', ResultsRS.RS, 0))  ';																			//----
					}																																											//----
					if($options['decimal_prova']==0)																																			//----
						$options['decimal_prova'] = '2';																																		//----
																																																//----
					$queryResultsRF->select( 'ROUND ( ( (' . $RFString . ') / (' . $options['nr_etapa_prova'] . ')), '. $options['decimal_prova'] .' ) AS rf');									//----
				break;																																											//----
																																																//----
				case '3':																																										//----																				
					$RFString = '';																																								//----
					for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {																														//----
						if(!empty($RFString))																																					//----
							$RFString = $RFString . ' + ';																																		//----
						$RFString = $RFString . ' MAX(IF( ResultsRS.nr_etapa_prova = '. $i .', ResultsRS.RS, 0)) ';																				//----
					}																																											//----
					$queryResultsRF->select( '( ' . $RFString . ' ) AS RF');																													//----
				break;																																											//----
			}																																													//----
																																																//----
			for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {																																//----
				for ($x = 1; $x <= $options['ns_etapa_prova']; $x++) {  																														//----
					$queryResultsRF->select( 'MAX(IF( ResultsRS.nr_etapa_prova = '. $i .' , ResultsRS.S'.  $x . ', NULL)) AS r' . $i . 's' . $x);												//----
				}																																												//----
																																																//----
				$queryResultsRF->select( 'MAX(IF( ResultsRS.nr_etapa_prova = '. $i .', ResultsRS.RS, NULL)) AS rs' . $i);																		//----
			}																																													//----
																																																//----
			$queryResultsRF->select('id_inscricao');																																			//----
			$queryResultsRF->select('id_user');																																					//----
			$queryResultsRF->select('id_etapa');																																				//----
			$queryResultsRF->select('id_classe');																																				//----		
			$queryResultsRF->select('shotoff_inscricao_etapa');																																	//----
																																																//----
			$queryResultsRF->from( '(' . $queryResultsRS . ') ResultsRS' );																														//----
			$queryResultsRF->innerJoin( '#__ranking_inscricao USING( id_inscricao )' );																											//----
			$queryResultsRF->innerJoin( '#__ranking_inscricao_etapa USING( id_inscricao, id_etapa )' );																							//----
			//$queryResultsRF->innerJoin( '#__intranet_associado USING( id_user )' );																											//----
			$queryResultsRF->group('id_inscricao');																																				//----
																																																//----
			$queryResultsRF->order($this->_db->quoteName('rf') . ' DESC');																														//----
																																																//----
			if($options['shot_off_prova']==1)																																					//----
				$queryResultsRF->order($this->_db->quoteName('shotoff_inscricao_etapa') . ' ASC');																								//----
																																																//----
			for ($i = $options['nr_etapa_prova']; $i > 0; $i--) { 																																//----
				$queryResultsRF->order('rs' . $i . ' DESC');																																	//----
			}																																													//----
																																																//----	
			for ($i = $options['nr_etapa_prova']; $i > 0 ; $i--) {																																//----
				for ($x = $options['ns_etapa_prova']; $x > 0; $x--) {  																															//----
					$queryResultsRF->order('r' . $i . 's' . $x . ' DESC');																														//----
				}																																												//----
			}																																													//----
																																																//----
			//$queryResultsRF->order('id_associado ASC');																																		//----	
																																																//----
			//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
			
		
			if($zz==1)
				$queryResultsRFCompak = $queryResultsRF;
			else
				$queryResultsRFPercurso = $queryResultsRF;
			
		}		
		
		
		
		
		$queryResultsRF = $this->_db->getQuery(true);	
		$queryResultsRF->select( 'id_user');
		$queryResultsRF->select( 'ResultadosPercurso.rf AS rs1');
		$queryResultsRF->select( 'ResultadosCompak.rf AS rs2');
		$queryResultsRF->select( '(IF(ISNULL(ResultadosPercurso.rf), 0, ResultadosPercurso.rf) + IF(ISNULL(ResultadosCompak.rf), 0, ResultadosCompak.rf)) AS rf');	
		
		$queryResultsRF->from( $this->_db->quoteName('#__users') );
		$queryResultsRF->innerJoin( $this->_db->quoteName('#__intranet_pf') . ' ON('.$this->_db->quoteName('id').'='.$this->_db->quoteName('id_user').')');
		$queryResultsRF->innerJoin('(' . $queryResultsRFPercurso . ') ResultadosPercurso USING('. $this->_db->quoteName('id_user').')' );
		$queryResultsRF->innerJoin('(' . $queryResultsRFCompak . ') ResultadosCompak USING('. $this->_db->quoteName('id_user').')' );	
		


			
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
																																															//----
		$queryRP = $this->_db->getQuery(true);																																				//----
		$queryRP->select( 'IF(ISNULL(ResultsRP.rf),NULL,MAX(ResultsRP.rf)) AS rp');																											//----
		$queryRP->from( '(' . $queryResultsRF . ') ResultsRP' );																															//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
		
		


																																										
																																															
																																															
																																															

		
		
		
		
																																															//----																																										
		$queryRank = $this->_db->getQuery(true);																																			//----
		$order='';																																											//----
		//if($options['shot_off_prova']==1)																																					//----
		//	$order .= ', ResultadoRank.shotoff_inscricao_etapa ASC';																														//----
																																															//----			
		//for ($i = $options['nr_etapa_prova']; $i > 0; $i--) { 																																//----
		//	$order .= ', ResultadoRank.rs' . $i . ' DESC';																																	//----
		//}																																													//----
																																															//----	
		//for ($i = $options['nr_etapa_prova']; $i > 0 ; $i--) {																																//----
		//	for ($x = $options['ns_etapa_prova']; $x > 0; $x--) {  																															//----
		//		$order .= ', ResultadoRank.r' . $i . 's' . $x. ' DESC';																														//----
		//	}																																												//----
		//}																																													//----
																																															//----
		//$order .= ', ResultadoRank.id_associado ASC';																																		//----	
																																															//----
																																															//----
		//if($options['shot_off_prova']==1)																																					//----
		//	$queryRank->select( 'GROUP_CONCAT( ResultadoRank.rf + '																															//----
		//							. ' IF(ISNULL(ResultadoRank.shotoff_inscricao_etapa), 0, ResultadoRank.shotoff_inscricao_etapa/100 ) '													//----
		//							. ' ORDER BY ResultadoRank.rf  DESC' . $order . ' )' );																									//----
		//else																																												//----					
		$queryRank->select( 'GROUP_CONCAT( ResultadoRank.rf ORDER BY ResultadoRank.rf  DESC' . $order . ' )' );																				//----							
																																															//----
		$queryRank->from( '(' . $queryResultsRF . ') ResultadoRank' );																														//----	
																																															//----
		//if(!empty($options['id_classe']))																																					//----
		//	$queryRank->where('ResultadoRank.id_classe = ' . $this->_db->quote( $options['id_classe'] ));																					//----
																																															//----
		//if(!empty($options['id_etapa']))																																					//----
		//	$queryRank->where('ResultadoRank.id_etapa = ' . $this->_db->quote( $options['id_etapa'] ));																						//----
																																															//----																																												
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------																																												

		$query = $this->_db->getQuery(true);
		$query->select( $this->_db->quoteName(array( //'id_inscricao',
													 //'id_prova',
													 'id_user',
													 
													// 'id_genero',
													// 'name_genero',
													 
													// 'id_categoria',
													// 'name_categoria',
													 
													// 'id_classe',
													// 'name_classe',

													 'name',
													 'image'
												    )));

		//$query->select('EquipeClube.name AS name_equipe');
		
		//if($options['shot_off_prova']==1)
		//	$query->select( 'FIND_IN_SET(  Resultados.rf + '	
		//							. ' IF(ISNULL(Resultados.shotoff_inscricao_etapa), 0, Resultados.shotoff_inscricao_etapa/100 ) '
		//						    . ', (' . $queryRank . ') ) AS rank');
		//else
			$query->select( 'FIND_IN_SET( Resultados.rf, (' . $queryRank . ') ) AS rank');

		for ($i = 1; $i <= 2; $i++) { 
			$query->select( 'IF(ISNULL(Resultados.rs' . $i . '), \'-\', Resultados.rs' . $i . ') AS rs' . $i );
		}
		$query->select( 'IF(ISNULL(Resultados.rf), NULL, Resultados.rf) AS rf');

		$query->select( 'ROUND((((Resultados.rf)*100)/(' . $queryRP . ')), 3) AS rAddColsAfter1');

		//$query->from( $this->_db->quoteName('#__users') );
		//$query->innerJoin('(' . $queryResultsRF . ') Resultados USING('. $this->_db->quoteName('id_inscricao').')' );
		
		$query->from( $this->_db->quoteName('#__users') );
		$query->innerJoin( $this->_db->quoteName('#__intranet_pf') . ' ON('.$this->_db->quoteName('id').'='.$this->_db->quoteName('id_user').')');
		$query->innerJoin('(' . $queryResultsRF . ') Resultados USING('. $this->_db->quoteName('id_user').')' );
			
		
		//$query->innerJoin( '#__ranking_campeonato USING( id_campeonato )' );
		//$query->innerJoin( '#__ranking_modalidade USING( id_modalidade )' );
		//$query->innerJoin( '#__ranking_prova USING( id_prova, id_campeonato )' );

		//$query->innerJoin( '#__ranking_genero USING( id_genero, id_prova )' );
		//$query->innerJoin( '#__ranking_categoria USING( id_categoria, id_genero )' );		
		//$query->innerJoin( '#__ranking_classe USING( id_classe, id_categoria )' );
	
		//$query->innerJoin( '#__intranet_pf USING('. $this->_db->quoteName('id_user').')' );
		$query->innerJoin( '#__ranking_profile USING('. $this->_db->quoteName('id_user').')' );
		//$query->innerJoin( '#__users AtletaDados ON('. $this->_db->quoteName('AtletaDados.id'). ' = '. $this->_db->quoteName('id_user'). ')' );
		//$query->leftJoin( '#__users EquipeClube ON('. $this->_db->quoteName('id_equipe') . '='. $this->_db->quoteName('EquipeClube.id'). ')' );
		
		//if(!empty($options['id_classe']))
		//	$query->where('id_classe = ' . $this->_db->quote( $options['id_classe'] ));																					
		
		//if(!empty($options['id_etapa']))											
		//	$query->where('id_etapa = ' . $this->_db->quote( $options['id_etapa'] ));																				
																	
		//if(!empty($options['id_categoria']))					
		//	$query->where('id_categoria = ' . $this->_db->quote( $options['id_categoria'] ));
															
		//if(!empty($options['id_genero']))			
		//	$query->where('id_genero = ' . $this->_db->quote( $options['id_genero'] ));		
															
		//if(!empty($options['id_prova']))	
		//	$query->where('id_prova = ' . $this->_db->quote( $options['id_prova'] ));
																
		$query->group($this->_db->quoteName('id_user'));
		
		$query->order($this->_db->quoteName('rf') . ' DESC');

		//if($options['shot_off_prova']==1)																																				
		//	$query->order($this->_db->quoteName('shotoff_inscricao_etapa') . ' ASC');

		//if($options['nr_etapa_prova']>1 && $options['ns_etapa_prova']>1) :
		//	for ($i = $options['nr_etapa_prova']; $i >= 1; $i--) { 
		//	 $query->order( 'rs' . $i . ' DESC');
		//	}
		//endif;
		
		//for ($i = $options['nr_etapa_prova']; $i >= 1; $i--) { 
		//	for ($x = $options['ns_etapa_prova']; $x >= 1; $x--) { 
		//		$query->order( 'r' . $i . 's' . $x . ' DESC');
		//	}
		//}

		//$query->order($this->_db->quoteName('id_pf') . ' ASC');

		$this->_db->setQuery($query);
		return 	$this->_db->loadObjectList();
	
	}	




	function getPosition(  $options = array() )
	{		
		//----------------------------------------------------------------- RESULTADO SÉRIE ETAPAS CADA INSCRIÇÃO(SEM FINAL) ---------------------------------------------------------------------
		$queryResultsRS = $this->_db->getQuery(true);																																		//----
																																															//----
		for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) {  																															//----
			$queryResultsRS->select( 'MAX(IF( RANRESU.ordering = ' . $i . ', RANRESU.value_resultado, NULL)) AS S' . $i);																	//----
		}																																													//----
																																															//----
		switch( $options['rs_etapa_prova'] )																																				//----
		{																																													//----
			case '1':																																										//----
				$queryResultsRS->select( 'MAX(RANRESU.value_resultado) AS RS');																												//----
			break;																																											//----
																																															//----
			case '2':																																										//----
				$RFString = '';																																								//----
				for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) { 																														//----
					if(!empty($RFString))																																					//----
						$RFString = $RFString . ' + ';																																		//----
					$RFString = $RFString . ' MAX(IF(RANRESU.ordering = '. $i .', RANRESU.value_resultado, 0)) ';																			//----
				}																																											//----
				if($options['decimal_prova']==0)																																			//----
					$options['decimal_prova'] = '2';																																		//----
																																															//----
				$queryResultsRS->select( 'ROUND ( ( (' . $RFString . ') / (' . $options['ns_etapa_prova'] . ')), '. $options['decimal_prova'] .' ) AS RS');									//----
																																															//----
			break;																																											//----
																																															//----
			case '3':																																										//----
				$RFString = '';																																								//----
				for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) { 																														//----
					if(!empty($RFString))																																					//----
						$RFString = $RFString . ' + ';																																		//----
					$RFString = $RFString . ' MAX(IF(RANRESU.ordering = '. $i .', RANRESU.value_resultado, 0)) ';																			//----
				}																																											//----
																																															//----
				$queryResultsRS->select( '( ' . $RFString . ' ) AS RS');																													//----
			break;																																											//----
		}																																													//----
																																															//----
		$queryResultsRS->select( 'RANRESU.id_inscricao');																																	//----
		$queryResultsRS->select( 'RANRESU.nr_etapa_prova');																																	//----
		$queryResultsRS->select( 'RANRESU.id_etapa');																																		//----
																																															//----
		$queryResultsRS->from( '#__ranking_resultado RANRESU' );																															//----
		$queryResultsRS->innerJoin( '#__ranking_inscricao RANINSC USING( id_inscricao )' );																									//----
		$queryResultsRS->innerJoin( '#__ranking_inscricao_etapa RANIETA USING( id_inscricao,  id_etapa )' );																				//----
		$queryResultsRS->innerJoin( '#__ranking_campeonato RANCAMP USING( id_campeonato )' );																								//----
		$queryResultsRS->innerJoin( '#__ranking_modalidade RANMODA  USING( id_modalidade )' );																								//----
		$queryResultsRS->innerJoin( '#__ranking_prova RANPROV USING( id_prova )' );																											//----
																																															//----
		if( !$this->_user->authorise('torneios.diretor.geral', 'com_torneios') )																											//----
			$queryResultsRS->where($this->_db->quoteName('RANMODA.status_modalidade') . ' = ' . $this->_db->quote('1'));																	//----
																																															//----
		if( !$this->_user->authorise('torneios.diretor.modalidade', 'com_torneios') )																										//----
		{																																													//----
			$queryResultsRS->where($this->_db->quoteName('RANCAMP.status_campeonato') . ' = ' . $this->_db->quote('1'));																	//----
			$queryResultsRS->where($this->_db->quoteName('RANPROV.status_prova') . ' = ' . $this->_db->quote('1'));																			//----
		}																																													//----
																																															//----
		if(!empty($options['id_prova']))																																					//----
			$queryResultsRS->where('RANINSC.id_prova = ' . $this->_db->quote( $options['id_prova'] ));																						//----
																																															//----
		if(!empty($options['id_etapa']))																																					//----
			$queryResultsRS->where('RANIETA.id_etapa = ' . $this->_db->quote( $options['id_etapa'] ));																						//----
																																															//----
		$queryResultsRS->group('RANRESU.id_etapa');																																			//----
		$queryResultsRS->group('RANRESU.id_inscricao');																																		//----
		$queryResultsRS->group('RANRESU.nr_etapa_prova');																																	//----
																																															//----
		$queryResultsRS->order('RS DESC');																																					//----
																																															//----
		for ($i = $options['ns_etapa_prova']; $i > 0; $i--) { 																																//----
			$queryResultsRS->order('S' . $i . ' DESC');																																		//----
		}																																													//----																																		
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
																																															//----
		$queryResultsRF = $this->_db->getQuery(true);																																		//----
																																															//----
		switch( $options['rf_etapa_prova'] )																																				//----
		{																																													//----
			case '1':																																										//----
				$queryResultsRF->select( 'MAX(ResultsRS.RS) AS rf');																														//----
			break;																																											//----
																																															//----
			case '2':																																										//----
				$RFString = '';																																								//----
				for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {																														//----
					if(!empty($RFString))																																					//----
						$RFString = $RFString . ' + ';																																		//----
					$RFString = $RFString . '  MAX(IF( ResultsRS.nr_etapa_prova = '. $i .', ResultsRS.RS, 0))  ';																			//----
				}																																											//----
				if($options['decimal_prova']==0)																																			//----
					$options['decimal_prova'] = '2';																																		//----
																																															//----
				$queryResultsRF->select( 'ROUND ( ( (' . $RFString . ') / (' . $options['nr_etapa_prova'] . ')), '. $options['decimal_prova'] .' ) AS rf');									//----
			break;																																											//----
																																															//----
			case '3':																																										//----																				
				$RFString = '';																																								//----
				for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {																														//----
					if(!empty($RFString))																																					//----
						$RFString = $RFString . ' + ';																																		//----
					$RFString = $RFString . ' MAX(IF( ResultsRS.nr_etapa_prova = '. $i .', ResultsRS.RS, 0)) ';																				//----
				}																																											//----
				$queryResultsRF->select( '( ' . $RFString . ' ) AS RF');																													//----
			break;																																											//----
		}																																													//----
																																															//----
		for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {																																//----
			for ($x = 1; $x <= $options['ns_etapa_prova']; $x++) {  																														//----
				$queryResultsRF->select( 'MAX(IF( ResultsRS.nr_etapa_prova = '. $i .' , ResultsRS.S'.  $x . ', NULL)) AS r' . $i . 's' . $x);												//----
			}																																												//----
																																															//----
			$queryResultsRF->select( 'MAX(IF( ResultsRS.nr_etapa_prova = '. $i .', ResultsRS.RS, NULL)) AS rs' . $i);																		//----
		}																																													//----
																																															//----
		$queryResultsRF->select('id_inscricao');																																			//----
		$queryResultsRF->select('id_associado');																																			//----
		$queryResultsRF->select('id_etapa');																																				//----
		$queryResultsRF->select('id_classe');																																				//----		
		$queryResultsRF->select('shotoff_inscricao_etapa');																																	//----
																																															//----
		$queryResultsRF->from( '(' . $queryResultsRS . ') ResultsRS' );																														//----
		$queryResultsRF->innerJoin( '#__ranking_inscricao USING( id_inscricao )' );																											//----
		$queryResultsRF->innerJoin( '#__ranking_inscricao_etapa USING( id_inscricao, id_etapa )' );																							//----
		$queryResultsRF->innerJoin( '#__intranet_associado USING( id_user )' );																												//----
		$queryResultsRF->group('id_inscricao');																																				//----
		//$queryResultsRF->group('id_etapa');																																				//----
																																															//----
		$queryResultsRF->order($this->_db->quoteName('rf') . ' DESC');																														//----
																																															//----
		if($options['shot_off_prova']==1)																																					//----
			$queryResultsRF->order($this->_db->quoteName('shotoff_inscricao_etapa') . ' ASC');																								//----
																																															//----
		for ($i = $options['nr_etapa_prova']; $i > 0; $i--) { 																																//----
			$queryResultsRF->order('rs' . $i . ' DESC');																																	//----
		}																																													//----
																																															//----	
		for ($i = $options['nr_etapa_prova']; $i > 0 ; $i--) {																																//----
			for ($x = $options['ns_etapa_prova']; $x > 0; $x--) {  																															//----
				$queryResultsRF->order('r' . $i . 's' . $x . ' DESC');																														//----
			}																																												//----
		}																																													//----
																																															//----
		$queryResultsRF->order('id_associado ASC');																																			//----	
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
																																															//----
		$queryRP = $this->_db->getQuery(true);																																				//----
		$queryRP->select( 'IF(ISNULL(ResultsRP.rf),NULL,MAX(ResultsRP.rf)) AS rp');																											//----
		$queryRP->from( '(' . $queryResultsRF . ') ResultsRP' );																															//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
																																															//----																																										
		$queryRank = $this->_db->getQuery(true);																																			//----
		$order='';																																											//----
		if($options['shot_off_prova']==1)																																					//----
			$order .= ', ResultadoRank.shotoff_inscricao_etapa ASC';																														//----
																																															//----			
		for ($i = $options['nr_etapa_prova']; $i > 0; $i--) { 																																//----
			$order .= ', ResultadoRank.rs' . $i . ' DESC';																																	//----
		}																																													//----
																																															//----	
		for ($i = $options['nr_etapa_prova']; $i > 0 ; $i--) {																																//----
			for ($x = $options['ns_etapa_prova']; $x > 0; $x--) {  																															//----
				$order .= ', ResultadoRank.r' . $i . 's' . $x. ' DESC';																														//----
			}																																												//----
		}																																													//----
																																															//----
		$order .= ', ResultadoRank.id_associado ASC';																																		//----	
																																															//----
																																															//----
		if($options['shot_off_prova']==1)																																					//----
			$queryRank->select( 'GROUP_CONCAT( ResultadoRank.rf + '																															//----
									. ' IF(ISNULL(ResultadoRank.shotoff_inscricao_etapa), 0, ResultadoRank.shotoff_inscricao_etapa/100 ) '													//----
									. ' ORDER BY ResultadoRank.rf  DESC' . $order . ' )' );																									//----
		else																																												//----					
			$queryRank->select( 'GROUP_CONCAT( ResultadoRank.rf ORDER BY ResultadoRank.rf  DESC' . $order . ' )' );																			//----							
																																															//----
		$queryRank->from( '(' . $queryResultsRF . ') ResultadoRank' );																														//----	
																																															//----
		if(!empty($options['id_classe']))																																					//----
			$queryRank->where('ResultadoRank.id_classe = ' . $this->_db->quote( $options['id_classe'] ));																					//----
																																															//----
		if(!empty($options['id_etapa']))																																					//----
			$queryRank->where('ResultadoRank.id_etapa = ' . $this->_db->quote( $options['id_etapa'] ));																						//----
																																															//----																																												
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------																																												

		$query = $this->_db->getQuery(true);
		
		if($options['shot_off_prova']==1)
			$query->select( 'FIND_IN_SET(  Resultados.rf + '	
									. ' IF(ISNULL(Resultados.shotoff_inscricao_etapa), 0, Resultados.shotoff_inscricao_etapa/100 ) '
								    . ', (' . $queryRank . ') ) AS rank');
		else
			$query->select( 'FIND_IN_SET( Resultados.rf, (' . $queryRank . ') ) AS rank');

		$query->from( $this->_db->quoteName('#__ranking_inscricao') );
		$query->innerJoin('(' . $queryResultsRF . ') Resultados USING('. $this->_db->quoteName('id_inscricao').','. $this->_db->quoteName('id_classe').')' );	
		
		$query->innerJoin( '#__ranking_campeonato USING( id_campeonato )' );
		$query->innerJoin( '#__ranking_modalidade USING( id_modalidade )' );
		$query->innerJoin( '#__ranking_prova USING( id_prova, id_campeonato )' );

		$query->innerJoin( '#__ranking_genero USING( id_genero, id_prova )' );
		$query->innerJoin( '#__ranking_categoria USING( id_categoria, id_genero )' );		
		$query->innerJoin( '#__ranking_classe USING( id_classe, id_categoria )' );
	
		$query->innerJoin( '#__intranet_pf USING('. $this->_db->quoteName('id_user').')' );
		$query->innerJoin( '#__users AtletaDados ON('. $this->_db->quoteName('AtletaDados.id'). ' = '. $this->_db->quoteName('id_user'). ')' );
		$query->leftJoin( '#__users EquipeClube ON('. $this->_db->quoteName('id_equipe') . '='. $this->_db->quoteName('EquipeClube.id'). ')' );
		

		if( $options['id_user'] )
			$query->where('id_user = ' . $this->_db->quote( $options['id_user'] ) );		

		if(!empty($options['id_classe']))
			$query->where('id_classe = ' . $this->_db->quote( $options['id_classe'] ));																					
		
		if(!empty($options['id_etapa']))											
			$query->where('id_etapa = ' . $this->_db->quote( $options['id_etapa'] ));																				
																	
		if(!empty($options['id_categoria']))					
			$query->where('id_categoria = ' . $this->_db->quote( $options['id_categoria'] ));
															
		if(!empty($options['id_genero']))			
			$query->where('id_genero = ' . $this->_db->quote( $options['id_genero'] ));		
															
		if(!empty($options['id_prova']))	
			$query->where('id_prova = ' . $this->_db->quote( $options['id_prova'] ));
																
		$query->group($this->_db->quoteName('id_inscricao'));
		
		$query->order($this->_db->quoteName('Resultados.rf') . ' DESC');
		
		if($options['shot_off_prova']==1)	
			$query->order($this->_db->quoteName('Resultados.shotoff_inscricao_etapa') . ' ASC');	


		if($options['nr_etapa_prova']>1 && $options['ns_etapa_prova']>1) :
			for ($i = $options['nr_etapa_prova']; $i >= 1; $i--) { 
			 $query->order( 'Resultados.rs' . $i . ' DESC');
			}
		endif;
		
		for ($i = $options['nr_etapa_prova']; $i >= 1; $i--) { 
			for ($x = $options['ns_etapa_prova']; $x >= 1; $x--) { 
				$query->order( 'Resultados.r' . $i . 's' . $x . ' DESC');
			}
		}

		$query->order($this->_db->quoteName('id_pf') . ' ASC');

		$this->_db->setQuery($query);
		return 	$this->_db->loadResult();

	
	}		
	
	function getDiretorerModalidade($prova, $etapa)
	{
		$query = $this->_db->getQuery(true);
		
		$query->select( '*' );
		
		$query->from( $this->_db->quoteName('#__ranking_acess_map') );		
		$query->innerJoin( $this->_db->quoteName('#__ranking_campeonato') . ' ON('. $this->_db->quoteName('id_modalidade'). ' = ' .$this->_db->quoteName('id_type'). ')' );
		
		if($prova) {
			$query->innerJoin( $this->_db->quoteName('#__ranking_prova') . ' USING('. $this->_db->quoteName('id_prova').')' );		
			$query->where( $this->_db->quoteName('id_prova') . ' = ' . $this->_db->quote( $prova ) );
		}
		
		if($etapa) {
			$query->innerJoin( $this->_db->quoteName('#__ranking_etapa') . ' USING('. $this->_db->quoteName('id_etapa').')' );		
			$query->where( $this->_db->quoteName('id_etapa') . ' = ' . $this->_db->quote( $etapa ) );
		}		
		
		$query->where( $this->_db->quoteName('type') . ' = ' . $this->_db->quote( '1' ) );
		$query->where( $this->_db->quoteName('id_user') . ' = ' . $this->_db->quote( $this->_user->get('id') ) );
		
		$this->_db->setQuery( $query );
		
		return (boolean) $this->_db->loadResultArray();
	}		
	
	
	function getDiretorerProva($prova, $etapa)
	{
		$query = $this->_db->getQuery(true);
		
		$query->select( '*' );
		
		$query->from( $this->_db->quoteName('#__ranking_acess_map') );		
		$query->innerJoin( $this->_db->quoteName('#__ranking_campeonato') . ' ON('. $this->_db->quoteName('id_modalidade'). ' = ' .$this->_db->quoteName('id_type'). ')' );
		
		if($prova) {
			$query->innerJoin( $this->_db->quoteName('#__ranking_prova') . ' USING('. $this->_db->quoteName('id_prova').')' );		
			$query->where( $this->_db->quoteName('id_prova') . ' = ' . $this->_db->quote( $prova ) );
		}
		
		if($etapa) {
			$query->innerJoin( $this->_db->quoteName('#__ranking_etapa') . ' USING('. $this->_db->quoteName('id_etapa').')' );		
			$query->where( $this->_db->quoteName('id_etapa') . ' = ' . $this->_db->quote( $etapa ) );
		}		
		
		$query->where( $this->_db->quoteName('type') . ' = ' . $this->_db->quote( '2' ) );
		$query->where( $this->_db->quoteName('id_user') . ' = ' . $this->_db->quote( $this->_user->get('id') ) );
		
		$this->_db->setQuery( $query );
		
		return (boolean) $this->_db->loadResultArray();
	}		
	
	function getListLocais()	
	{
		$query = $this->_db->getQuery(true);
		$query->select( $this->_db->quoteName('id') . ' AS value, ' . 
						$this->_db->quoteName('name') . ' AS text');
						
		$query->from( $this->_db->quoteName('#__users') );
		$query->innerJoin( $this->_db->quoteName('#__intranet_pf') . 'ON('. $this->_db->quoteName('id'). ' = '. $this->_db->quoteName('id_user').')' );
		
		//$query->where($this->_db->quoteName('tipo') . ' = ' . $this->_db->quote( '1' ));
		$query->where('(' . $this->_db->quoteName('block') . '=' . $this->_db->quote( '0' ) . ' OR ' .  $this->_db->quoteName('ano_campeonato') . ' < YEAR(CURDATE()) )');
		
		$query->order($this->_db->quoteName('name'));
		
		$this->_db->setQuery($query);
		return 	$this->_db->loadObjectList();

	}
		
}
