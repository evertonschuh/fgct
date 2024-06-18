<?php 

defined('_JEXEC') or die('Restricted access');

class EASistemasClassesFossa {
	
	
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
	const tagLanguage =  'FOSSA_';

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
		$document->addStyleSheet( JPATH_API .DS. 'core' .DS. 'css' .DS. 'fossa.css?v=' . $token );		
		//$document->addScript( JPATH_API .DS. 'core' .DS. 'js' .DS. 'trap.js?v=' . $token  );
		
		$extension = 'com_easistemas';
		$language_tag = 'pt-BR';
		$reload = true;
		$base_dir = JPATH_SITE;	
		$base_dir_custon = JPATH_API .DS. 'core';	
		$extension_custon = $extension . '_fossa';
		
		$lang = JFactory::getLanguage();
		$lang->load($extension, $base_dir, $language_tag, false);
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
	
	function getAdditionalPrint( $options = array() )
	{
 		return;
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
		$concatOS = array();																																								//----			
		for ($i = $options['ns_etapa_prova']; $i > 0; $i--) { 																																//----
			$order .= ', ResultadoRank.RankOS' . $i . ' DESC';																																//----
			$concatOS[] ='ROUND(ResultadoRank.RankOS' . $i . ')';	 																														//----
		}																																													//----
		$concatOS = 'CONCAT(\'0.\','.implode(',',$concatOS).')';																																	//----
			
		if($options['shot_off_prova']==1)																																					//----
			$order .= ', ResultadoRank.shotoff_inscricao_etapa ASC';																														//----
							
																																															//----			
		for ($i = $options['nr_etapa_prova']; $i > 0; $i--) { 																																//----
			$order .= ', ResultadoRank.RankRS' . $i . ' DESC';																																//----
		}																																													//----
																																															//----	
		for ($i = $options['nr_etapa_prova']; $i > 0 ; $i--) {																																//----
			for ($x = $options['ns_etapa_prova']; $x > 0; $x--) {  																															//----
				$order .= ', ResultadoRank.RankR' . $i . 'S' . $x. ' DESC';																													//----
			}																																												//----
		}																																													//----
																																															//----
		$queryRank->select('id_genero');																																					//----
		$queryRank->select('id_categoria');																																					//----
		$queryRank->select('id_classe');																																					//----
		if($options['shot_off_prova']==1)																																					//----
			$queryRank->select( 'GROUP_CONCAT(ResultadoRank.RankRf + '																														//----
									. '('.$concatOS.') +	'																															//----
									. ' IF(ISNULL(ResultadoRank.shotoff_inscricao_etapa), 0,CONCAT(\'0.00000000\',ResultadoRank.shotoff_inscricao_etapa))'													//----
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

													 'AtletaDados.name',
												    )));

		$query->select('IF( ISNULL(AddEquipe.id_addequipe), IF( EquipeClube.id = 7617, \'AVULSO\', EquipeClube.name), AddEquipe.name_addequipe) AS name_equipe');
		
		$concatOS = array();			
		for ($i = $options['ns_etapa_prova']; $i > 0; $i--) { 
			$concatOS[] ='ROUND(Resultados.os' . $i . ')';
		}			
		$concatOS = 'CONCAT(\'0.\','.implode(',',$concatOS).')';

		if($options['shot_off_prova']==1)
			$query->select( 'FIND_IN_SET(  Resultados.rf + '	
									. '('.$concatOS.') +	'		
									. ' IF(ISNULL(Resultados.shotoff_inscricao_etapa), 0, CONCAT(\'0.00000000\',Resultados.shotoff_inscricao_etapa)) '
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

		
		$query->from( $this->_db->quoteName('#__ranking_inscricao') );
		$query->innerJoin('(' . $queryResultsRF . ') Resultados USING('. $this->_db->quoteName('id_inscricao').')' );	
		$query->innerJoin('(' . $queryRank . ') Position USING('. $this->_db->quoteName('id_genero').','. $this->_db->quoteName('id_categoria').','. $this->_db->quoteName('id_classe').')' );

		$query->innerJoin( '#__ranking_campeonato USING( id_campeonato )' );
		$query->innerJoin( '#__ranking_modalidade USING( id_modalidade )' );
		$query->innerJoin( '#__ranking_prova USING( id_prova, id_campeonato )' );

		$query->innerJoin( '#__ranking_genero USING( id_genero, id_prova )' );
		$query->innerJoin( '#__ranking_categoria USING( id_categoria, id_genero )' );		
		$query->innerJoin( '#__ranking_classe USING( id_classe, id_categoria )' );
	
		$query->innerJoin( '#__intranet_pf USING('. $this->_db->quoteName('id_user').')' );
		$query->innerJoin( '#__users AtletaDados ON('. $this->_db->quoteName('AtletaDados.id'). ' = '. $this->_db->quoteName('id_user'). ')' );
		$query->leftJoin( '#__users EquipeClube ON('. $this->_db->quoteName('id_equipe') . '='. $this->_db->quoteName('EquipeClube.id'). ')' );
		$query->leftJoin( '#__intranet_addequipe AddEquipe USING('. $this->_db->quoteName('id_addequipe'). ')' );
		
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


		for ($x = $options['ns_etapa_prova']; $x >= 1; $x--) { 
			$query->order( 'os' . $x . ' DESC');
		}
		
		if($options['shot_off_prova']==1)													
			$query->order($this->_db->quoteName('shotoff_inscricao_etapa') . ' ASC');	

		$query->order($this->_db->quoteName('id_pf') . ' ASC');

		$this->_db->setQuery($query);
		return 	$this->_db->loadObjectList();

	
	}	
	

	function getResultsGeralEtapa( $options = array() )
	{
		return false;
	}

	
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//----------------------------------------------------------------------- EQUIPES ESTADOS ETAPA ----------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	function getEquipeEstadoEtapa( $options = array() )
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


	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------- EQUIPES POR ETAPA ---------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	function getEquipeEtapaAntiga( $options = array() )
	{		



		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
																																															//----
		$queryAtletas = $this->_db->getQuery(true);																																			//----
		$queryAtletas->select( $this->_db->quoteName(array( 'id_inscricao',																													//----
															'id_campeonato',																												//----
															'id_prova',																														//----
															'id_user',																														//----
															'id_genero',																													//----
															'id_categoria',																													//----
															'id_classe',																													//----
														    //'id_equipe',																													//----
															)));																															//----
																																															//----
		$queryAtletas->select('IF(ISNULL(id_addequipe), id_equipe, CONCAT(\'A\', id_addequipe)) AS id_equipe');																				//----
																																															//----
		$queryAtletas->from( '#__ranking_inscricao' );																																		//----
		$queryAtletas->innerJoin( '#__ranking_inscricao_etapa USING( id_inscricao )' );																										//----
																																															//----
		if(!empty($options['id_etapa']))																																					//----
			$queryAtletas->where('id_etapa = ' . $this->_db->quote( $options['id_etapa'] ));																								//----
																																															//----
		if(!empty($options['id_prova']))																																					//----	
			$queryAtletas->where('id_prova = ' . $this->_db->quote( $options['id_prova'] ));																								//----				
																																															//----
		$queryAtletas->group($this->_db->quoteName('id_user'));																																//----
																	


		$queryPagamento = $this->_db->getQuery(true);																																		//----
		$queryPagamento->select('ano_anuidade AS ano_campeonato');																															//----
		$queryPagamento->select( $this->_db->quoteName(array( 'id_inscricao',																												//----
															  'id_campeonato',																												//----
															  'id_prova',																													//----
															  'id_user',																													//----
															  'id_genero',																													//----
															  'id_categoria',																												//----
															  'id_classe',																													//----
															  'id_equipe',																													//----
															)));																															//----
		$queryPagamento->select('MIN(baixa_pagamento) AS baixa_pagamento');																													//----
		$queryPagamento->from( $this->_db->quoteName('#__intranet_anuidade') );																												//----
		$queryPagamento->innerJoin( $this->_db->quoteName('#__intranet_pagamento') . ' USING('. $this->_db->quoteName('id_anuidade').')');													//----
		$queryPagamento->innerJoin('(' . $queryAtletas . ') AS teste USING('. $this->_db->quoteName('id_user').')');																		//----
		$queryPagamento->where($this->_db->quoteName('baixa_pagamento') . ' IS NOT NULL' );																									//----
		$queryPagamento->where($this->_db->quoteName('status_pagamento') . '=' . $this->_db->quote( '1' ) );																				//----
		$queryPagamento->group($this->_db->quoteName('id_anuidade'));																														//----
		$queryPagamento->group($this->_db->quoteName('id_user'));	


		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
																																															//----
		//$queryPagamento = $this->_db->getQuery(true);																																		//----
		//$queryPagamento->select('ano_anuidade AS ano_campeonato');																															//----
		//$queryPagamento->select('id_user');																																					//----
		//$queryPagamento->select('MIN(baixa_pagamento) AS baixa_pagamento');																													//----
		//$queryPagamento->from( $this->_db->quoteName('#__intranet_anuidade') );																												//----
		//$queryPagamento->innerJoin( $this->_db->quoteName('#__intranet_pagamento') . ' USING('. $this->_db->quoteName('id_anuidade').')');													//----
		//$queryPagamento->where($this->_db->quoteName('baixa_pagamento') . ' IS NOT NULL' );																									//----
		//$queryPagamento->where($this->_db->quoteName('status_pagamento') . '=' . $this->_db->quote( '1' ) );																				//----
		//$queryPagamento->group($this->_db->quoteName('id_anuidade'));																														//----
		//$queryPagamento->group($this->_db->quoteName('id_user'));																															//----
																																															//----
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
					
		/*//----
		$queryResultsRS->select( 'RANRESU.id_inscricao');																																	//----
		$queryResultsRS->select( 'RANRESU.nr_etapa_prova');																																	//----
		$queryResultsRS->select( 'RANRESU.id_etapa');																																		//----
		//$queryResultsRS->select( 'RANINSC.id_equipe');																																		//----
		$queryResultsRS->select('IF(ISNULL(RANINSC.id_addequipe), RANINSC.id_equipe, CONCAT(\'A\', RANINSC.id_addequipe)) AS id_equipe');	
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
			*/
			
			
																																																	//----
		$queryResultsRS->select( $this->_db->quoteName(array( 'id_inscricao',																												//----
															  'id_campeonato',																												//----
															  'id_prova',																													//----
															  'id_user',																													//----
															  'id_genero',																													//----
															  'id_categoria',																												//----
															  'id_classe',																													//----
															  'RANRESU.nr_etapa_prova',																										//----
															  'id_etapa',																													//----
															  'shotoff_inscricao_etapa',																									//----
															  'id_equipe',																													//----
															)));																															//----
																																															//----
		$queryResultsRS->from( '#__ranking_resultado RANRESU' );																															//----
		$queryResultsRS->innerJoin( '(' . $queryPagamento . ') AS RANINSC USING('. $this->_db->quoteName('id_inscricao').')');																//----
		$queryResultsRS->innerJoin( '#__ranking_inscricao_etapa USING( id_inscricao,  id_etapa )' );																						//----
		$queryResultsRS->innerJoin( '#__ranking_campeonato RANCAMP USING( id_campeonato, ano_campeonato )' );																						//----
		$queryResultsRS->innerJoin( '#__ranking_modalidade RANMODA USING( id_modalidade )' );																										//----
		$queryResultsRS->innerJoin( '#__ranking_prova RANPROV USING( id_prova, id_campeonato )' );																									//----
		$queryResultsRS->innerJoin( '#__ranking_etapa USING( id_etapa, id_campeonato )' );		
			
			//----	
		$queryResultsRS->where('RANINSC.id_equipe <> 7617');																																//----																																													
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
		for ($i = $options['ns_etapa_prova']; $i > 0; $i--) { 																																//----
			$queryResultsRS->order('S' . $i . ' DESC');																																		//----
		}																																													//----
																																															//----																																		
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
		/*																																													//----
		$queryResultsRF->select('id_inscricao');																																			//----
		$queryResultsRF->select('ResultsRS.id_equipe');																																				//----
		$queryResultsRF->select('name_genero');																																				//----
		$queryResultsRF->select('name_categoria');																																			//----
		$queryResultsRF->select('name_classe');																																				//----	
		$queryResultsRF->select('name AS atleta');																																			//----	
		$queryResultsRF->select('id_etapa');																																				//----		
																																															//----
		$queryResultsRF->from( '(' . $queryResultsRS . ') ResultsRS' );																														//----
		$queryResultsRF->innerJoin( '#__ranking_inscricao USING( id_inscricao)' );																								//----
		$queryResultsRF->innerJoin( '#__ranking_genero USING( id_genero, id_prova )' );																										//----
		$queryResultsRF->innerJoin( '#__ranking_categoria USING( id_genero, id_categoria )' );																								//----
		$queryResultsRF->innerJoin( '#__ranking_classe USING( id_classe, id_categoria )' );																									//----
		$queryResultsRF->innerJoin( '#__users ON( id = id_user )' );																														//----
																																															//----
		$queryResultsRF->group('id_inscricao');																																				//----
		$queryResultsRF->group('ResultsRS.id_equipe');																																				//----
		$queryResultsRF->group('id_etapa');		
		*/
		
		
		
		$queryResultsRF->select( $this->_db->quoteName(array( 'id_inscricao',																												//----
															  'id_campeonato',																												//----
															  'id_prova',																													//----
															  'id_user',																													//----
															  'id_genero',																													//----
															  'id_categoria',																												//----
															  'id_classe',																													//----
															  'id_etapa',																													//----
															  'id_equipe',																													//----
															  'shotoff_inscricao_etapa',																									//----
															  'id_associado',																												//----
															  'name_genero',																												//----
															  'name_categoria',																												//----
															  'name_classe',																												//----
															)));																															//----
		$queryResultsRF->select('name AS atleta');																																			//----				
																																															//----
	  	$queryResultsRF->from( '(' . $queryResultsRS . ') ResultsRS' );																														//----
		$queryResultsRF->innerJoin( '#__intranet_associado USING( id_user )' );																												//----
		$queryResultsRF->innerJoin( '#__ranking_genero USING( id_genero, id_prova )' );																										//----
		$queryResultsRF->innerJoin( '#__ranking_categoria USING( id_genero, id_categoria )' );																								//----
		$queryResultsRF->innerJoin( '#__ranking_classe USING( id_classe, id_categoria )' );																									//----
		$queryResultsRF->innerJoin( '#__users ON( id = id_user )' );																														//----
																																															//----
		$queryResultsRF->group('id_inscricao');																																				//----
		$queryResultsRF->group('id_equipe');																																				//----
		$queryResultsRF->group('id_etapa');																																					//----
																																															//----
		$queryResultsRF->order($this->_db->quoteName('rf') . ' DESC');		
		
		
		
		
		
		
		
		
		
		
		//----
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
		$queryRankPos->select( 'id_equipe');																																				//----
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
		$queryRankPos->group('id_equipe');																																					//----
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
		
		if($options['equipe_prova']==1 || $options['equipe_prova']==4):
			//$query->select( 'name');	
			$query->select('IF( ISNULL(AddEquipe.id_addequipe), IF( name = 7617, \'AVULSO\', name), AddEquipe.name_addequipe) AS name');
		elseif($options['equipe_prova']==1 || $options['equipe_prova']==5)	:
			$query->select( 'CONCAT (\'Equipe \','. $this->_db->quoteName('id_equipe') . ') AS name');
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
			
		$query->from( '(' . $queryResultsRF . ') ResultsEtapa' );
		$query->leftJoin( '#__users ON('. $this->_db->quoteName('id'). ' = '. $this->_db->quoteName('id_equipe'). ')' );
		$query->leftJoin( '#__intranet_addequipe AddEquipe ON(CONCAT(\'A\','.  $this->_db->quoteName('id_addequipe'). ') = '. $this->_db->quoteName('id_equipe'). ')' ); 
	
		$query->group('id_equipe');																																			

		$query->order('rf desc');	
				
		$this->_db->setQuery($query);
		return 	$this->_db->loadObjectList();	
	
	}	



	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------- EQUIPES POR ETAPA ---------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	function getEquipeEtapa( $options = array() )
	{		

		$queryTestAnoCampeonato = $this->_db->getQuery(true);
		$queryTestAnoCampeonato->select('ano_campeonato');
		$queryTestAnoCampeonato->from('#__ranking_prova');	
		$queryTestAnoCampeonato->innerJoin('#__ranking_campeonato RANCAMP USING( id_campeonato )');
		if(!empty($options['id_prova']))																	
			$queryTestAnoCampeonato->where('#__ranking_prova.id_prova = ' . $this->_db->quote( $options['id_prova'] ));	
	
		$this->_db->setQuery($queryTestAnoCampeonato);
		$ano_campeonato = $this->_db->loadResult();		
	
	
		if($ano_campeonato < 2019 || $ano_campeonato > 2021){
			return $this->getEquipeEtapaAntiga($options);
			exit;
		}
	

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
		//$queryResultsRS->select( 'RANINSC.id_equipe');	
																																				//----
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
		//$queryResultsRS->where('RANINSC.id_equipe <> 7617');																																//----																																													
																																															//----
		//if( !$this->_user->authorise('torneios.diretor.geral', 'com_torneios') )																											//----
			$queryResultsRS->where($this->_db->quoteName('RANMODA.status_modalidade') . ' = ' . $this->_db->quote('1'));																	//----
																																															//----
		//if( !$this->_user->authorise('torneios.diretor.modalidade', 'com_torneios') )																										//----
		//{																																													//----
			$queryResultsRS->where($this->_db->quoteName('RANCAMP.status_campeonato') . ' = ' . $this->_db->quote('1'));																	//----
			$queryResultsRS->where($this->_db->quoteName('RANPROV.status_prova') . ' = ' . $this->_db->quote('1'));																			//----
		//}																																													//----
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
		//$queryResultsRF->select('id_equipe');
		$queryResultsRS->select('IF(ISNULL(id_addequipe), id_equipe, CONCAT(\'A\', id_addequipe)) AS id_equipe');																																		//----
		//$queryResultsRF->select('id_addequipe');
		//$queryResultsRF->select('IF(ISNULL(#__ranking_inscricao.id_addequipe), #__ranking_inscricao.id_equipe, CONCAT(\'A\', #__ranking_inscricao.id_addequipe)) AS id_equipe');																																					//----
		$queryResultsRF->select('name_genero');																																				//----
		$queryResultsRF->select('name_categoria');																																			//----
		$queryResultsRF->select('name_classe');																																				//----	
		$queryResultsRF->select('name AS atleta');																																			//----	
		$queryResultsRF->select('id_etapa');																																				//----		
																																															//----
		$queryResultsRF->from( '(' . $queryResultsRS . ') ResultsRS' );																														//----
		$queryResultsRF->innerJoin( '#__ranking_inscricao USING( id_inscricao)' );																								//----
		$queryResultsRF->innerJoin( '#__ranking_genero USING( id_genero, id_prova )' );																										//----
		$queryResultsRF->innerJoin( '#__ranking_categoria USING( id_genero, id_categoria )' );																								//----
		$queryResultsRF->innerJoin( '#__ranking_classe USING( id_classe, id_categoria )' );																									//----
		$queryResultsRF->innerJoin( '#__users ON( id = id_user )' );																														//----
																																															//----
		$queryResultsRF->group('id_inscricao');																																				//----
		$queryResultsRF->group('id_equipe');																																				//----
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
		$queryRankPos->select( 'id_equipe');																																				//----
																																															//----
		$queryFinal = '';																																									//----
		$queryConcat = '';																																									//----
		for ($i = 1; $i <= $options['nr_equipe_prova']; $i++) {  																															//----
			if(!empty($queryFinal))																																							//----
				$queryFinal .= ' + ';																																						//----
																																															//----
			if(!empty($queryConcat))																																						//----
				$queryConcat .= ',';																																						//----
																																															//----
			$queryFinal .= 	'IF(COUNT(ResultadoRankPos.RF) > '.($i-1).																														//----
							', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultadoRankPos.RF ORDER BY ResultadoRankPos.RF DESC),'														//----
							.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), 0)';																						//----
																																															//----
			$queryConcat .=	'ROUND(IF(COUNT(ResultadoRankPos.RF) > '.($i-1).																												//----
							', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultadoRankPos.RF ORDER BY ResultadoRankPos.RF DESC),'														//----
							.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), NULL))';																					//----		
		}																																													//----
																																															//----
		if(!empty($queryFinal))																																								//----
			$queryFinal = '('.$queryFinal.') + (CONCAT('.$queryConcat.')/POW(100,'.$options['nr_equipe_prova'].'))';																		//----																																											
																																															//----																			
		if(!empty($queryFinal))																																								//----
			$queryRankPos->select( '('.$queryFinal.') AS RankRF');																															//----
																																															//----
		$queryRankPos->from( '(' . $queryResultsRF . ') ResultadoRankPos' );																												//----
		$queryRankPos->group('id_equipe');																																					//----
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
		
		if($options['equipe_prova']==1 || $options['equipe_prova']==4):
			//$query->select( 'name');
			$query->select('IF( ISNULL(AddEquipe.id_addequipe), IF( id = 7617, \'AVULSO\', name), AddEquipe.name_addequipe) AS name');	
		elseif($options['equipe_prova']==1 || $options['equipe_prova']==5)	:
			$query->select( 'CONCAT (\'Equipe \','. $this->_db->quoteName('id_equipe') . ') AS name');
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
		$queryConcat = '';
		for ($i = 1; $i <= $options['nr_equipe_prova']; $i++) {  
			if(!empty($queryFinal))
				$queryFinal .= ' + ';
			
			if(!empty($queryConcat))
				$queryConcat .= ',';
		
			$queryFinal .= 	'IF(COUNT(ResultsEtapa.RF) > '.($i-1).
							', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultsEtapa.RF ORDER BY ResultsEtapa.RF DESC),'
							.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), 0)';	
							
							
			$queryConcat .=	'ROUND(IF(COUNT(ResultsEtapa.RF) > '.($i-1).
							', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultsEtapa.RF ORDER BY ResultsEtapa.RF DESC),'
							.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), NULL))';			
								
		}
		
		if(!empty($queryFinal)):
			$queryFinal = '('.$queryFinal.') + (CONCAT('.$queryConcat.')/POW(100,'.$options['nr_equipe_prova'].'))';
			$query->select( '('.$queryFinal.') as pontos');
		endif;
		
		$query->select( 'IF((FIND_IN_SET( ('.$queryFinal.'), (' . $queryRank . ')))>=8,
							1,
							IF((FIND_IN_SET( ('.$queryFinal.'), (' . $queryRank . ')))=7,
								2,
								IF((FIND_IN_SET( ('.$queryFinal.'), (' . $queryRank . ')))=6,
									3,
									IF((FIND_IN_SET( ('.$queryFinal.'), (' . $queryRank . ')))=5,
										4,						
										IF((FIND_IN_SET( ('.$queryFinal.'), (' . $queryRank . ')))=4,
											5,	
											IF((FIND_IN_SET( ('.$queryFinal.'), (' . $queryRank . ')))=3,
												6,
												IF((FIND_IN_SET( ('.$queryFinal.'), (' . $queryRank . ')))=2,
													8,10
												)
											)
										)
									)
								)
							)
						) AS rf');
		
		$query->select( 'FIND_IN_SET( ('.$queryFinal.'), (' . $queryRank . ')) AS rank');
			
		$query->from( '(' . $queryResultsRF . ') ResultsEtapa' );
		$query->innerJoin( '#__users ON('. $this->_db->quoteName('id'). ' = '. $this->_db->quoteName('id_equipe'). ')' );	
		
		$query->leftJoin( '#__intranet_addequipe AddEquipe ON(CONCAT(\'A\', '. $this->_db->quoteName('id_addequipe'). ') = '. $this->_db->quoteName('id_equipe'). ')' );

		$query->having('(IF(COUNT(ResultsEtapa.RF) > '.($options['nr_equipe_prova']-1).
							', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultsEtapa.RF ORDER BY ResultsEtapa.RF DESC),'
							.$this->_db->quote(',').', '.$options['nr_equipe_prova'].'),'.$this->_db->quote(',').', -1), NULL)) IS NOT NULL');
		
		$query->group('id_equipe');																																			

		$query->order('rf desc');	
		for ($i = 1; $i <= $options['nr_equipe_prova']; $i++) { 
			$query->order( 'r' . $i . ' desc');	
		}
		$query->order('pontos desc');
			
		$this->_db->setQuery($query);
		//print_r($this->_db->loadObjectList());

		return 	$this->_db->loadObjectList();	
	
	}	





	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//------------------------------------------------------------------------- PARTICIPAÇÃO ETAPA -----------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	function getParticipacaoEtapa( $options = array() )
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
		$queryRankGeral = $this->_db->getQuery(true);																																		//----
		$queryRankGeral->select( $this->_db->quoteName('id_inscricao'));																													//----
		$queryRankGeral->select('IF(ISNULL(id_addequipe), id_equipe, CONCAT(\'A\', id_addequipe)) AS id_equipe');																			//----
																																															//----																																					
		$queryRankGeral->from( '#__ranking_resultado' );																																	//----																								
		$queryRankGeral->innerJoin( '#__ranking_inscricao USING( id_inscricao )' );																											//----	
		$queryRankGeral->innerJoin( '#__ranking_campeonato RANCAMP USING( id_campeonato )' );																								//----
		$queryRankGeral->innerJoin( '#__ranking_etapa RANETAPA USING( id_etapa, id_campeonato )' );																							//----
		$queryRankGeral->innerJoin( '(' . $queryPagamento . ') Pagamento USING('. $this->_db->quoteName('id_user').','. $this->_db->quoteName('ano_campeonato').')');						//----
																																															//----
		//$queryRankGeral->where( $this->_db->quoteName('Pagamento.baixa_pagamento').'<='. $this->_db->quoteName( 'RANETAPA.data_end_etapa' ) );												//----
		$queryRankGeral->where( '(' . $this->_db->quoteName('Pagamento.baixa_pagamento').'<='. $this->_db->quoteName( 'RANETAPA.data_end_etapa' ) 											//----
									.'OR' 																																					//----
									. $this->_db->quoteName('RANETAPA.id_etapa').'IN (375, 376)'																							//----	 
									. ')');	
	
																																															//----
		if(!empty($options['id_etapa']))																																					//----							
			$queryRankGeral->where('id_etapa = ' . $this->_db->quote( $options['id_etapa'] ));																								//----
																																															//----													
		if(!empty($options['id_prova']))																																					//----			
			$queryRankGeral->where('id_prova = ' . $this->_db->quote( $options['id_prova'] ));																								//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
																																															//----												
		$queryPos = $this->_db->getQuery(true);																																				//----														
		$queryPos->select( 'COUNT(DISTINCT(id_inscricao)) as rf');																															//----
		$queryPos->from( '(' . $queryRankGeral . ') ResultadoGeral' );																														//----
		$queryPos->where('id_equipe <> 7617');																																				//----																																														
		$queryPos->group('id_equipe');																																						//----																			
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------																																															//----												
		$queryRank = $this->_db->getQuery(true);																																			//----														
		$queryRank->select( 'GROUP_CONCAT( ResultadoRank.rf ORDER BY ResultadoRank.rf DESC )' );																							//----
		$queryRank->from( '(' . $queryPos . ') ResultadoRank' );																															//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		

		$query = $this->_db->getQuery(true);	
		//$query->select( 'name');	
		$query->select('IF( ISNULL(AddEquipe.id_addequipe), name, AddEquipe.name_addequipe) AS name');
		$query->select( 'COUNT(DISTINCT(id_inscricao)) AS rf');			
																						
		$query->select( 'FIND_IN_SET( (COUNT(DISTINCT(id_inscricao))), (' . $queryRank . ') ) AS rank');
																																		
		$query->from( '('.$queryRankGeral.') AS Resultados' );																										
		$query->leftJoin( '#__users ON('. $this->_db->quoteName('id'). ' = '. $this->_db->quoteName('Resultados.id_equipe'). ')' );	
		$query->leftJoin( '#__intranet_addequipe AddEquipe ON(CONCAT(\'A\', '. $this->_db->quoteName('id_addequipe'). ') = '. $this->_db->quoteName('Resultados.id_equipe'). ')' );	
		$query->where('id_equipe <> 7617');																																//----																																														

		$query->group('id_equipe');
		$query->order('rf DESC');														
		$this->_db->setQuery($query);

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


	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//------------------------------------------------------------------------ CLASSIFICAÇÃO GERAL -----------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------- 
	function getClassificacao( $options = array() )
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
		$queryRanking->select('name AS names');																																				//----
		$queryRanking->select( 'ROUND(ResultsRF.RF) AS rf');																																//----
																																															//----
		$queryRanking->from( '(' . $queryResultsRF . ') ResultsRF' );																														//----
		$queryRanking->innerJoin( '#__ranking_inscricao USING( id_inscricao )' );																											//----
		$queryRanking->innerJoin( '#__ranking_campeonato USING( id_campeonato )' );																											//----
		$queryRanking->innerJoin( '#__ranking_inscricao_etapa USING( id_inscricao, id_etapa )' );																							//----
		$queryRanking->innerJoin( '#__ranking_etapa USING( id_etapa )' );																													//----
		$queryRanking->innerJoin( '#__intranet_associado USING( id_user )' );																												//----
		$queryRanking->innerJoin( '#__users ON( id = id_local )' );																															//----
																																															//----
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
		$orderInit = '';																																									//----
		$orderInit .= 'ResultadoRank.Rankrf DESC';																																			//----
																																															//----
		$order = '';																																										//----
		for ($i = 1; $i <= $options['results_prova']; $i++) { 																																//----
			$order .= ', ResultadoRank.Rankr' . $i . ' DESC';																																//----
		}																																													//----
																																															//----
		$order .= ', ResultadoRank.RankAssociado  ASC';																																		//----
																																															//----
		$queryRank->select( 'GROUP_CONCAT(ResultadoRank.Rankinscricao ORDER BY ' . $orderInit . $order . ' ) AS Rank' );																	//----
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

		$query->select('IF( EquipeClube.id = 7617, \'AVULSO\', EquipeClube.name) AS name_equipe');
		
		$query->select( 'FIND_IN_SET( Resultados.id_inscricao, ('.$queryRank.') ) AS rank');			
		
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
		$query->select( 'ROUND (( (SUM(Resultados.rf)) / ( COUNT(Resultados.rf)) ), 2 ) as rAddColsBefore2');

		
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
			$query->select( '('.$queryFinal.') AS rf');

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
		
		if(isset($options['id_user']))
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

	
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//------------------------------------------------------------------------ CLASSIFICAÇÃO RANKING ---------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------- 
	function getRanking( $options = array() )
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
		$queryRanking->select('name AS names');																																				//----
		$queryRanking->select( 'ROUND(ResultsRF.RF) AS rf');																																//----
																																															//----
		$queryRanking->from( '(' . $queryResultsRF . ') ResultsRF' );																														//----
		$queryRanking->innerJoin( '#__ranking_inscricao USING( id_inscricao )' );																											//----
		$queryRanking->innerJoin( '#__ranking_campeonato USING( id_campeonato )' );																											//----
		$queryRanking->innerJoin( '#__ranking_inscricao_etapa USING( id_inscricao, id_etapa )' );																							//----
		$queryRanking->innerJoin( '#__ranking_etapa USING( id_etapa )' );																													//----
		$queryRanking->innerJoin( '#__intranet_associado USING( id_user )' );																												//----
		$queryRanking->innerJoin( '#__users ON( id = id_local )' );																															//----
																																															//----
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

													 'AtletaDados.name',
													 '#__intranet_pf.image_pf'
												    )));
		$query->select('IF(ISNULL(AddEquipe.id_addequipe), IF( EquipeClube.id = 7617, \'AVULSO\', EquipeClube.name), AddEquipe.name_addequipe) AS name_equipe');
		//$query->select('IF( EquipeClube.id = 7617, \'AVULSO\', EquipeClube.name) AS name_equipe');
		
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
		$query->select( 'ROUND (( (SUM(Resultados.rf)) / ( COUNT(Resultados.rf)) ), 2 ) as rAddColsBefore2');

		
		$queryFinal = '';
		$querySoma = '';
		for ($i = 1; $i <= $options['results_prova']; $i++) { 
			if(!empty($queryFinal))
				$queryFinal .= ' + ';
		
			$querySoma = 	'IF(COUNT(Resultados.rf) > '.($i-1)
								. ', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( Resultados.rf ORDER BY Resultados.rf DESC ), ' 
								. $this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), 0)';	
			
			$queryFinal .= $querySoma ;
			//$query->select( $querySoma . ' AS r' . $i);	
		}
		
		if(!empty($queryFinal))
			$query->select( '('.$queryFinal.') AS rf');

		$query->from( $this->_db->quoteName('#__ranking_inscricao') );
		$query->innerJoin('(' . $queryRanking . ') Resultados USING('. $this->_db->quoteName('id_inscricao').','. $this->_db->quoteName('id_classe').')' );	
		$query->innerJoin('(' . $queryRank . ') Position USING('. $this->_db->quoteName('id_genero').','. $this->_db->quoteName('id_categoria').','. $this->_db->quoteName('id_classe').')' );
		
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
		$query->leftJoin( '#__intranet_addequipe AddEquipe USING('. $this->_db->quoteName('id_addequipe') .')' );

		if(!empty($options['id_prova']))	
			$query->where('id_prova = ' . $this->_db->quote( $options['id_prova'] ));
			
		if(isset($options['name']) && isset($options['name']))
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
		$queryRanking = $this->_db->getQuery(true);																																			//----
		$queryRanking->select('id_inscricao');																																				//----
		$queryRanking->select('id_etapa');																																					//----
		$queryRanking->select('name_etapa');																																				//----
		$queryRanking->select('id_classe');																																					//----	
		$queryRanking->select('id_local as id_locals');																																		//----
		$queryRanking->select('name AS names');																																				//----
																																															//----
		$queryRanking->select( 'MAX(ResultsRF.RF) AS rf');																																	//----
		$queryRanking->from( '(' . $queryResultsRF . ') ResultsRF' );																														//----
		$queryRanking->innerJoin( '#__ranking_inscricao USING( id_inscricao )' );																											//----
		$queryRanking->innerJoin( '#__ranking_campeonato USING( id_campeonato )' );																											//----
		$queryRanking->innerJoin( '#__ranking_inscricao_etapa USING( id_inscricao, id_etapa )' );																							//----
		$queryRanking->innerJoin( '#__ranking_etapa USING( id_etapa )' );																													//----
		$queryRanking->innerJoin( '#__intranet_associado USING( id_user )' );																												//----
		$queryRanking->innerJoin( '#__users ON( id = id_local )' );																															//----
																																															//----
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
			$queryResults->select( 'ROUND ( (( (' . $queryFinalClass . ')/( IF(COUNT(Results.rf) > ' 																						//----
												 . $options['results_classe_prova'] . ', ' . $options['results_classe_prova'] . ', COUNT(Results.rf)) ))*100)/75, 3 ) AS rfClass');			//----	
		}																																													//----
		$queryResults->select( $this->_db->quoteName(array(	'id_inscricao',																													//----
															'id_classe',																													//----
															)));																															//----
																																															//----
		$queryResults->select( 'TIMESTAMPDIFF(YEAR, CONCAT( YEAR(data_nascimento_pf), "-01-01" ), CONCAT( (ano_campeonato+1), "-12-31" ) ) AS idadeClass');									//----													
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
		
		$queryFinal = '';
		$querySoma = '';
		for ($i = 1; $i <= $options['results_classe_prova']; $i++) { 
			if(!empty($queryFinal))
				$queryFinal .= ' + ';
		
			$querySoma = 	'IF(COUNT(Resultados.rf) > '.($i-1)
								. ', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( Resultados.rf ORDER BY Resultados.rf DESC ), '
								. $this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), 0)';	
			
			$queryFinal .= $querySoma ;
			$query->select( $querySoma . ' AS r' . $i);	
		}
		
		
		
		if(!empty($queryFinal)) {
			
			$query->select( 'ROUND ( ( (' . $queryFinal . ')/( IF(COUNT(Resultados.rf)> ' . $options['results_classe_prova'] .', '. $options['results_classe_prova'] .', COUNT(Resultados.rf)) )), 3 ) AS rAddColsBefore1');		
			$query->select( 'ROUND ( (( (' . $queryFinal . ')/( IF(COUNT(Resultados.rf)> ' . $options['results_classe_prova'] .', '. $options['results_classe_prova'] .', COUNT(Resultados.rf)) ))*100)/75 , 3 ) AS rf');
		}
		
		$query->select( 'Classes.name_categoria AS name_categoria');
		$query->select( 'Classes.name_classe AS name_classe');

		$query->from( $this->_db->quoteName('#__ranking_inscricao') );
		$query->innerJoin('(' . $queryRanking . ') Resultados USING('. $this->_db->quoteName('id_inscricao').','. $this->_db->quoteName('id_classe').')' );
		$query->innerJoin('(' . $queryResults . ') ResultClass USING('. $this->_db->quoteName('id_inscricao').','. $this->_db->quoteName('id_classe').')' );	
		
		$query->innerJoin( '#__ranking_campeonato USING( id_campeonato )' );
		$query->innerJoin( '#__ranking_modalidade USING( id_modalidade )' );
		$query->innerJoin( '#__ranking_prova USING( id_prova, id_campeonato )' );
	
		$query->innerJoin( '#__intranet_pf USING('. $this->_db->quoteName('id_user').')' );
		$query->innerJoin( $this->_db->quoteName('#__intranet_associado') . ' USING('. $this->_db->quoteName('id_user').')' );
		
		$query->innerJoin( '#__users AtletaDados ON('. $this->_db->quoteName('AtletaDados.id'). ' = '. $this->_db->quoteName('id_user'). ')' );
		$query->leftJoin( '#__users EquipeClube ON('. $this->_db->quoteName('id_equipe') . '='. $this->_db->quoteName('EquipeClube.id'). ')' );
						
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
																	
		if(!empty($options['id_prova']))	
			$query->where('id_prova = ' . $this->_db->quote( $options['id_prova'] ));																	//----

		$query->where($this->_db->quoteName('status_inscricao') . ' = ' . $this->_db->quote('1'));
		$query->where($this->_db->quoteName('status_modalidade') . ' = ' . $this->_db->quote('1'));	
		$query->where($this->_db->quoteName('status_campeonato') . ' = ' . $this->_db->quote('1'));
		$query->where($this->_db->quoteName('status_prova') . ' = ' . $this->_db->quote('1'));

		$query->order($this->_db->quoteName('rf') . ' DESC');
		
		for ($i = 1; $i <= $options['results_classe_prova']; $i++) { 
			$query->order($this->_db->quoteName('r' . $i) . ' DESC');
		}

		$query->order($this->_db->quoteName('id_associado') . ' ASC');

		$query->group('id_inscricao');													

		$this->_db->setQuery($query);
		return 	$this->_db->loadObjectList();
		
	}
	
	
	
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------- EQUIPES PROVA -----------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	function getEquipeProvaAntigo( $options = array() )
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
		//$queryResultsRS->select( 'RANINSC.id_equipe');																																		//----
		$queryResultsRS->select('IF(ISNULL(RANINSC.id_addequipe), RANINSC.id_equipe, CONCAT(\'A\',RANINSC.id_addequipe)) AS id_equipe');
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
		//$queryResultsRS->where('RANINSC.id_equipe <> 7617');																																//----	
		$queryResultsRS->where('(RANINSC.id_equipe <> 7617 OR RANINSC.id_equipe IS NULL)');
																																															//----
		//if( !$this->_user->authorise('torneios.diretor.geral', 'com_torneios') )																											//----
			$queryResultsRS->where($this->_db->quoteName('RANMODA.status_modalidade') . ' = ' . $this->_db->quote('1'));																	//----
																																															//----
		//if( !$this->_user->authorise('torneios.diretor.modalidade', 'com_torneios') )																										//----
		//{																																													//----
			$queryResultsRS->where($this->_db->quoteName('RANCAMP.status_campeonato') . ' = ' . $this->_db->quote('1'));																	//----
			$queryResultsRS->where($this->_db->quoteName('RANPROV.status_prova') . ' = ' . $this->_db->quote('1'));																			//----
		//}																																													//----
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
		$queryResultsRF->select('id_equipe');																																				//----
		$queryResultsRF->select('id_etapa');																																				//----
																																															//----
		$queryResultsRF->from( '(' . $queryResultsRS . ') ResultsRS' );																														//----
																																															//----
		$queryResultsRF->group('id_inscricao');																																				//----
		$queryResultsRF->group('id_equipe');																																				//----
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
		$queryResultsEtapa->select( 'id_equipe');																																			//----
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
		
		$queryResultsEtapa->having('(IF(COUNT(ResultsRF.RF) > '.($options['nr_equipe_prova']-1).
							', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultsRF.RF ORDER BY ResultsRF.RF DESC),'
							.$this->_db->quote(',').', '.$options['nr_equipe_prova'].'),'.$this->_db->quote(',').', -1), NULL)) IS NOT NULL');

		
		$queryResultsEtapa->group('id_equipe');																																				//----
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
		$queryRankPos->group('id_equipe');																																			
		$queryRankPos->order('RankRF desc');	
		
		$queryRank = $this->_db->getQuery(true);	
		$queryRank->select( 'GROUP_CONCAT( ResultadoRank.RankRF )' );							
		$queryRank->from( '(' . $queryRankPos . ') ResultadoRank' );	


		$query = $this->_db->getQuery(true);	
		$query->select( 'id_equipe');
		$query->select('IF( ISNULL(AddEquipe.id_addequipe), IF( name = 7617, \'AVULSO\', name), AddEquipe.name_addequipe) AS name');
				
		//$query->select( 'name');																					
		
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
		$query->leftJoin( '#__users ON('. $this->_db->quoteName('id'). ' = '. $this->_db->quoteName('id_equipe'). ')' );
		$query->leftJoin( '#__intranet_addequipe AddEquipe ON(CONCAT(\'A\','.  $this->_db->quoteName('id_addequipe'). ') = '. $this->_db->quoteName('id_equipe'). ')' ); 
	



		$query->group('id_equipe');																																			

		$query->order('rf desc');
			
		$this->_db->setQuery($query);
		return 	$this->_db->loadObjectList();																																							
	
	}	

	
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------- EQUIPES PROVA -----------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	function getEquipeProva( $options = array() )
	{		
	
		$queryTestAnoCampeonato = $this->_db->getQuery(true);
		$queryTestAnoCampeonato->select('ano_campeonato');
		$queryTestAnoCampeonato->from('#__ranking_prova');	
		$queryTestAnoCampeonato->innerJoin('#__ranking_campeonato RANCAMP USING( id_campeonato )');
		if(!empty($options['id_prova']))																	
			$queryTestAnoCampeonato->where('#__ranking_prova.id_prova = ' . $this->_db->quote( $options['id_prova'] ));	
	
		$this->_db->setQuery($queryTestAnoCampeonato);
		$ano_campeonato = $this->_db->loadResult();		
	
	
		if($ano_campeonato < 2019 || $ano_campeonato > 2021){
			return $this->getEquipeProvaAntigo($options);
			exit;
		}
	
	
	
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
		$queryResultsRS->select( 'RANINSC.id_equipe');																																		//----
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
		$queryResultsRS->where('RANINSC.id_equipe <> 7617');																																//----	
																																															//----
		//if( !$this->_user->authorise('torneios.diretor.geral', 'com_torneios') )																											//----
		//	$queryResultsRS->where($this->_db->quoteName('RANMODA.status_modalidade') . ' = ' . $this->_db->quote('1'));																	//----
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
		$queryResultsRF->select('id_equipe');																																				//----
		$queryResultsRF->select('id_etapa');																																				//----
																																															//----
		$queryResultsRF->from( '(' . $queryResultsRS . ') ResultsRS' );																														//----
																																															//----
		$queryResultsRF->group('id_inscricao');																																				//----
		$queryResultsRF->group('id_equipe');																																				//----
		$queryResultsRF->group('id_etapa');																																					//----
																																															//----
		$queryResultsRF->order($this->_db->quoteName('RF') . ' DESC');																														//----
		$queryResultsRF->order($this->_db->quoteName('id_etapa') . ' ASC');																													//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
																																															//----
		$queryRankPos = $this->_db->getQuery(true);																																			//----																																													
																																															//----
		$queryRankPos->select( 'id_equipe');																																				//----
		$queryFinal = '';																																									//----
		$queryConcat = '';																																									//----
		for ($i = 1; $i <= $options['nr_equipe_prova']; $i++) {  																															//----
			if(!empty($queryFinal))																																							//----
				$queryFinal .= ' + ';																																						//----
																																															//----
			if(!empty($queryConcat))																																						//----
				$queryConcat .= ',';																																						//----
																																															//----
			$queryFinal .= 	'IF(COUNT(ResultadoRankPos.RF) > '.($i-1).																														//----
							', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultadoRankPos.RF ORDER BY ResultadoRankPos.RF DESC),'														//----
							.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), 0)';																						//----
																																															//----
			$queryConcat .=	'ROUND(IF(COUNT(ResultadoRankPos.RF) > '.($i-1).																												//----
							', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultadoRankPos.RF ORDER BY ResultadoRankPos.RF DESC),'														//----
							.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), NULL))';																					//----		
		}																																													//----
																																															//----
		if(!empty($queryFinal))																																								//----
			$queryFinal = '('.$queryFinal.') + (CONCAT('.$queryConcat.')/POW(100,'.$options['nr_equipe_prova'].'))';																		//----																																											
																																															//----																			
		if(!empty($queryFinal))																																								//----
			$queryRankPos->select( '('.$queryFinal.') AS RankRF');																															//----
																																															//----																			
		$queryRankPos->select('id_etapa');																																					//----																																											//----																			
		$queryRankPos->from( '(' . $queryResultsRF . ') ResultadoRankPos' );																												//----
		$queryRankPos->group('id_equipe');																																					//----
		$queryRankPos->group('id_etapa');																																					//----
		$queryRankPos->order('RankRF DESC');																																				//----																																											
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------		
																																															//----																																										
		$queryRank = $this->_db->getQuery(true);																																			//----
		$queryRank->select( 'GROUP_CONCAT( ResultadoRank.RankRF ORDER BY  ResultadoRank.RankRF DESC) AS GroupRank' );																		//----							
		$queryRank->from( '(' . $queryRankPos . ') ResultadoRank' );																														//----
		$queryRank->select('id_etapa');																																						//----																																											//----																			
		$queryRank->group('id_etapa');																																						//----
		$queryRank->order('id_etapa');																																						//----																																											
																																															//----																																												
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------																																												
																																															//----
		$queryResultsEtapa = $this->_db->getQuery(true);																																	//----																																													
																																															//----
		$queryFinal = '';																																									//----
		$queryResultsEtapa->select( 'id_equipe');																																			//----
		$queryResultsEtapa->select( 'id_etapa');																																			//----
		$queryResultsEtapa->select( 'ETAPAORDER.ordering');																																	//----
																																															//----
		$queryFinal = '';																																									//----
		$queryConcat = '';																																									//----
		for ($i = 1; $i <= $options['nr_equipe_prova']; $i++) {  																															//----
			if(!empty($queryFinal))																																							//----
				$queryFinal .= ' + ';																																						//----
																																															//----
			if(!empty($queryConcat))																																						//----
				$queryConcat .= ',';																																						//----
																																															//----
			$queryFinal .= 	'IF(COUNT(ResultsRF.RF) > '.($i-1).																																//----
							', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultsRF.RF ORDER BY ResultsRF.RF DESC),'																	//----
							.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), 0)';																						//----	
																																															//----
			$queryConcat .=	'ROUND(IF(COUNT(ResultsRF.RF) > '.($i-1).																														//----
							', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultsRF.RF ORDER BY ResultsRF.RF DESC),'																	//----
							.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), NULL))';																					//----		
		}																																													//----
																																															//----
		if(!empty($queryFinal)):																																							//----
			$queryFinal = '('.$queryFinal.') + (CONCAT('.$queryConcat.')/POW(100,'.$options['nr_equipe_prova'].'))';																		//----
			$queryResultsEtapa->select( '('.$queryFinal.') as pontos');																														//----
		endif;																																												//----
																																															//----
		$queryResultsEtapa->select( 'IF((FIND_IN_SET( ('.$queryFinal.'), (ResultsRank.GroupRank)))>=8,'.																					//----
										'1,'.																																				//----
										'IF((FIND_IN_SET( ('.$queryFinal.'), (ResultsRank.GroupRank)))=7,'.																					//----
											'2,'.																																			//----
											'IF((FIND_IN_SET( ('.$queryFinal.'), (ResultsRank.GroupRank)))=6,'.																				//----
												'3,'.																																		//----
												'IF((FIND_IN_SET( ('.$queryFinal.'), (ResultsRank.GroupRank)))=5,'.																			//----
													'4,'.																																	//----				
													'IF((FIND_IN_SET( ('.$queryFinal.'), (ResultsRank.GroupRank)))=4,'.																		//----
														'5,'.																																//----
														'IF((FIND_IN_SET( ('.$queryFinal.'), (ResultsRank.GroupRank)))=3,'.																	//----
															'6,'.																															//----
															'IF((FIND_IN_SET( ('.$queryFinal.'), (ResultsRank.GroupRank)))=2,'.																//----
																'8,10'.																														//----
															')'.																															//----
														')'.																																//----
													')'.																																	//----
												')'.																																		//----
											')'.																																			//----
										')'.																																				//----
									') AS EtapaRF');																																		//----
																																															//----
																																															//----
		$queryResultsEtapa->from( '(' . $queryResultsRF . ') ResultsRF' );																													//----
		$queryResultsEtapa->innerJoin( '(' .$queryRank. ') ResultsRank USING('. $this->_db->quoteName('id_etapa'). ')' );																	//----
		$queryResultsEtapa->innerJoin( '#__ranking_etapa ETAPAORDER USING('. $this->_db->quoteName('id_etapa'). ')' );																		//----
																																															//----
		$queryResultsEtapa->having('(IF(COUNT(ResultsRF.RF) > '.($options['nr_equipe_prova']-1).																							//----
							', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultsRF.RF ORDER BY ResultsRF.RF DESC),'																	//----
							.$this->_db->quote(',').', '.$options['nr_equipe_prova'].'),'.$this->_db->quote(',').', -1), NULL)) IS NOT NULL');												//----
																																															//----
		$queryResultsEtapa->group('id_equipe');																																				//----
		$queryResultsEtapa->group('id_etapa');																																				//----
		$queryResultsEtapa->order('EtapaRF DESC');																																			//----	
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		
		$queryRankPos = $this->_db->getQuery(true);	
		$queryFinal = '';
		for ($i = 1; $i <= $options['numero_etapas']; $i++) { 
			if(!empty($queryFinal))
				$queryFinal .= ' + ';
		
			$queryFinal .= 	'IF(COUNT(ResultadoRankEtapa.EtapaRF) > '.($i-1).
							', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultadoRankEtapa.EtapaRF ORDER BY ResultadoRankEtapa.ordering ASC ),'
							.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), 0)';		
		}
		
		if(!empty($queryFinal))
			$queryRankPos->select( '('.$queryFinal.') as RankRF');

		$queryRankPos->from( '(' . $queryResultsEtapa . ') ResultadoRankEtapa' );	
		$queryRankPos->innerJoin( '#__users ON('. $this->_db->quoteName('id'). ' = '. $this->_db->quoteName('id_equipe'). ')' );
		$queryRankPos->group('id_equipe');																																			
		$queryRankPos->order('RankRF desc');	
		
		$queryRank = $this->_db->getQuery(true);	
		$queryRank->select( 'GROUP_CONCAT( ResultadoRank.RankRF )' );							
		$queryRank->from( '(' . $queryRankPos . ') ResultadoRank' );	


		$query = $this->_db->getQuery(true);	
		$query->select( 'id_equipe');
		$query->select( 'name');																					
		
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
		$query->innerJoin( '#__users ON('. $this->_db->quoteName('id'). ' = '. $this->_db->quoteName('id_equipe'). ')' );	
		$query->group('id_equipe');																																			

		$query->order('rf desc');
			
		$this->_db->setQuery($query);
		return 	$this->_db->loadObjectList();																																							
	
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
		$queryPartEtapas->select('IF(ISNULL(id_addequipe), id_equipe, CONCAT(\'A\',id_addequipe)) AS id_equipe');																			//----																																		//----
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
		$queryPartEtapas->where('(id_equipe <> 7617 OR id_equipe IS NULL)');																												//----
																																															//----																																														
		if(!empty($options['id_prova']))																																					//----
			$queryPartEtapas->where('id_prova = ' . $this->_db->quote( $options['id_prova'] ));																								//----
																																															//----									
		$queryPartEtapas->group('id_equipe');																																				//----
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
		$queryRankPos->innerJoin( '#__users ON('. $this->_db->quoteName('id'). ' = '. $this->_db->quoteName('id_equipe'). ')' );
		$queryRankPos->group('id_equipe');																																			
		$queryRankPos->order('RankRF desc');	
		
		$queryRank = $this->_db->getQuery(true);	
		$queryRank->select( 'GROUP_CONCAT( ResultadoRank.RankRF )' );							
		$queryRank->from( '(' . $queryRankPos . ') ResultadoRank' );
		

		$query = $this->_db->getQuery(true);	
		$query->select( 'id_equipe');
		$query->select('IF( ISNULL(AddEquipe.id_addequipe), name, AddEquipe.name_addequipe) AS name');																						
		
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
		$query->leftJoin( '#__users ON('. $this->_db->quoteName('id'). ' = '. $this->_db->quoteName('id_equipe'). ')' );	
		$query->leftJoin( '#__intranet_addequipe AddEquipe ON(CONCAT(\'A\', '. $this->_db->quoteName('id_addequipe'). ') = '. $this->_db->quoteName('id_equipe'). ')' );	

		$query->group('id_equipe');																																			

		$query->order('rf desc');
	
			
		$this->_db->setQuery($query);
		return 	$this->_db->loadObjectList();																																						
	
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
			 $valor_atleta_classe = 0;
		else:
			 $valor_atleta_classe =  $infoClass->valor_atleta_classe;
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
		$query = $this->_db->getQuery(true);
		$query->select( $this->_db->quoteName(array( '#__ranking_inscricao.id_inscricao',
													 '#__ranking_inscricao.id_user',
													 '#__ranking_genero.id_genero',
													 '#__ranking_categoria.id_categoria',
													 '#__ranking_classe.id_classe',
													// '#__intranet_cidade.name_cidade',
													 'name_genero',
													 'name_categoria',
													 'name_classe',
													 'name',
													 'image',
													 'id_associado',
												    )));
		$query->select( 'Etapa.value_resultado as rhoje');
		
		for ($i = 1; $i <= $options['results_prova']; $i++) { 
				
			$query->select( 'IF(COUNT(Resultados.value_resultados) > '.($i-1).',
								SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( Resultados.value_resultados ORDER BY Resultados.value_resultados DESC ),'.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1),
								NULL) AS r' . $i);		
		}
		
		
		$querySoma = '';
		for ($i = 1; $i <= $options['results_prova']; $i++) { 
			if(!empty($querySoma))
				$querySoma .= ' + ';
				
			$querySoma .= 	'IF(COUNT(Resultados.value_resultados) > '.($i-1).',
								SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( Resultados.value_resultados ORDER BY Resultados.value_resultados DESC ),'.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1),
								0)';		
		}
		
		
		
		$decimalMedia = '6';
		if( $options['rs_etapa_prova'] == 2 )
			$decimalMedia = '2';

		$query->select( 'COUNT(Resultados.value_resultados) as rAddColsBefore1');
		
		$queryFator = '';
		for ($i = 1; $i <= $options['results_prova']; $i++) { 
			if(!empty($queryFator))
				$queryFator .= ' + ';
		
			$queryFator .= 	'IF(COUNT(Resultados.value_resultados) > '.($i-1).',
								SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( Resultados.value_resultados ORDER BY Resultados.value_resultados DESC ),'.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1),
								0)';		
		}
		
		if(!empty($queryFator))
			$query->select( 'ROUND (( (SUM(Resultados.value_resultados)) / (COUNT(Resultados.value_resultados)) ), 3 ) as  rAddColsBefore2');
		
		$queryFinal = '';
		for ($i = 1; $i <= $options['results_prova']; $i++) { 
			if(!empty($queryFinal))
				$queryFinal .= ' + ';
		
			$queryFinal .= 	'IF(COUNT(Resultados.value_resultados) > '.($i-1).',
								SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( Resultados.value_resultados ORDER BY Resultados.value_resultados DESC ),'.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1),
								0)';		
		}
		
		if(!empty($queryFinal))
			$query->select( 'ROUND (( ('.$queryFinal.') / (' .$options['results_prova']. ') ), 3 ) as rf');

		$queryRankGroup = $this->_db->getQuery(true);
		
		$queryRankGroup->select( $this->_db->quoteName(array( 'id_inscricao',
															  'id_user',
															  'id_associado' 
													 		)));
		
		for ($i = 1; $i <= $options['results_prova']; $i++) { 

			$queryRankGroup->select( 'IF(COUNT(ResultadosRank.value_resultados_rank) > '.($i-1).',
								SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultadosRank.value_resultados_rank ORDER BY ResultadosRank.value_resultados_rank DESC ),'.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1),
								NULL) AS RankR' . $i);										
		}
		
		
		$queryFinalRank = '';
		for ($i = 1; $i <= $options['results_prova']; $i++) { 
			if(!empty($queryFinal))
				$queryFinalRank .= ' + ';
		
			$queryFinalRank .= 	'IF(COUNT(ResultadosRank.value_resultados_rank) > '.($i-1).',
								SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultadosRank.value_resultados_rank ORDER BY ResultadosRank.value_resultados_rank DESC ),'.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1),
								0)';		
		}
		
		if(!empty($queryFinalRank))
			$queryRankGroup->select( 'ROUND (( ('.$queryFinalRank.') / (' .$options['results_prova']. ') ), 2 ) as RankRf');

		$queryRankGroup->from( $this->_db->quoteName('#__ranking_inscricao') );
		
		$queryRankGroup->innerJoin( $this->_db->quoteName('#__ranking_campeonato') . ' USING('. $this->_db->quoteName('id_campeonato').')' );
		$queryRankGroup->innerJoin( $this->_db->quoteName('#__ranking_modalidade') . ' USING('. $this->_db->quoteName('id_modalidade').')' );
		$queryRankGroup->innerJoin( $this->_db->quoteName('#__ranking_prova') . ' USING('. $this->_db->quoteName('id_prova').')' );
		$queryRankGroup->innerJoin( $this->_db->quoteName('#__ranking_genero') . ' USING('. $this->_db->quoteName('id_genero').')' );
		$queryRankGroup->innerJoin( $this->_db->quoteName('#__ranking_categoria') . ' USING('. $this->_db->quoteName('id_categoria').')' );		
		$queryRankGroup->innerJoin( $this->_db->quoteName('#__ranking_classe') . ' USING('. $this->_db->quoteName('id_classe').')' );
		$queryRankGroup->innerJoin( $this->_db->quoteName('#__intranet_associado') . 'USING('. $this->_db->quoteName('id_user').')' );


		//------------------------------------------------------------------------------- CALC PERCENT GERL --------------------------------------------------------------------------------------
																																															//----
		$queryRankGeral = $this->_db->getQuery(true);																																		//----
																																															//----
		$queryRankGeral->select( 'RANRESU.id_inscricao AS RankMatricula');																													//----
																																															//----
		for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) {  																															//----
			$queryRankGeral->select( 'MAX(IF( RANRESU.ordering = ' . $i . ', RANRESU.value_resultado, 0)) AS RankS' . $i);																	//----
		}																																													//----
																																															//----
		switch( $options['rs_etapa_prova'] )																																				//----
		{																																													//----
			case '1':																																										//----
				$queryRankGeral->select( 'MAX(RANRESU.value_resultado) AS RankRF');																											//----
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
				$queryRankGeral->select( 'ROUND ( ( (' . $RFString . ') / (' . $options['ns_etapa_prova'] . ')), '. $options['decimal_prova'] .' ) AS RankRF');								//----
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
				$queryRankGeral->select( '( ' . $RFString . ' ) AS RankRF');																												//----
			break;																																											//----
		}																																													//----
																																															//----
		$queryRankGeral->select( 'RANRESU.id_inscricao');																																	//----
		$queryRankGeral->select( 'RANRESU.nr_etapa_prova');																																	//----
		$queryRankGeral->select( 'RANRESU.id_etapa');																																		//----		
																																															//----
		$queryRankGeral->from( '#__ranking_resultado RANRESU' );																															//----
																																															//----
		$queryRankGeral->innerJoin( '#__ranking_inscricao RANINSC USING( id_inscricao )' );																									//----
																																															//----
	//	$queryRankGeral->innerJoin( '#__ranking_inscricao_etapa RANIETA USING( id_inscricao,  id_etapa )' );																				//----
																																															//----
		$queryRankGeral->innerJoin( '#__ranking_campeonato RANCAMP USING( id_campeonato )' );																								//----
		$queryRankGeral->innerJoin( '#__ranking_modalidade RANMODA  USING( id_modalidade )' );																								//----
		$queryRankGeral->innerJoin( '#__ranking_prova RANPROV USING( id_prova )' );																											//----
																																															//----
		//$queryRankGeral->innerJoin( '#__ranking_profile RANPROF USING('. $this->_db->quoteName('id_user').')' );																			//----
		//$queryRankGeral->innerJoin( '#__users RANUSER ON('. $this->_db->quoteName('id'). ' = '. $this->_db->quoteName('id_user').')' );													//----
																																															//----
		//if( !$this->_user->authorise('torneios.diretor.geral', 'com_torneios') )																											//----
		//	$queryRankGeral->where($this->_db->quoteName('RANMODA.status_modalidade') . ' = ' . $this->_db->quote('1'));																	//----
																																															//----
		if( !$this->_user->authorise('torneios.diretor.modalidade', 'com_torneios') )																										//----
		{																																													//----
			$queryRankGeral->where($this->_db->quoteName('RANCAMP.status_campeonato') . ' = ' . $this->_db->quote('1'));																	//----
			$queryRankGeral->where($this->_db->quoteName('RANPROV.status_prova') . ' = ' . $this->_db->quote('1'));																			//----
	//		$queryRankGeral->where($this->_db->quoteName('RANIETA.status_inscricao_etapa') . ' = ' . $this->_db->quote('1'));																//----
		}																																													//----
																																															//----
		if(!empty($options['id_prova']))																																					//----
			$queryRankGeral->where('RANINSC.id_prova = ' . $this->_db->quote( $options['id_prova'] ));																						//----
																																															//----
		$queryRankGeral->group('RANRESU.id_etapa');																																			//----
		$queryRankGeral->group('RANRESU.id_inscricao');																																		//----
		$queryRankGeral->group('RANRESU.nr_etapa_prova');																																	//----
																																															//----
		$queryRankGeral->order('RankRF DESC');																																				//----
		for ($i = $options['ns_etapa_prova']; $i > 0; $i--) { 																																//----
			$queryRankGeral->order('RankS' . $i . ' DESC');																																	//----
		}																																													//----
																																															//----
		$queryRankGeral->order($this->_db->quoteName('RankMatricula') . ' ASC');																											//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		$queryRankGeralGroup = $this->_db->getQuery(true);																																	//----
																																															//----
		for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) {  																															//----
			$queryRankGeralGroup->select('RankS' . $i);																																		//----
		}																																													//----
																																															//----
		switch( $options['rf_etapa_prova'] )																																				//----
		{																																													//----
			case '1':																																										//----
				$queryRankGeralGroup->select( 'MAX(GrupoRankingGeral.RankRF) AS RankRF');																									//----
			break;																																											//----
																																															//----
			case '2':																																										//----
				$RFString = '';																																								//----
				for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {																														//----
					if(!empty($RFString))																																					//----
						$RFString = $RFString . ' + ';																																		//----
					$RFString = $RFString . '  MAX(IF( GrupoRankingGeral.nr_etapa_prova = '. $i .', GrupoRankingGeral.RankRF, 0))  ';														//----
				}																																											//----
				if($options['decimal_prova']==0)																																			//----
					$options['decimal_prova'] = '2';																																		//----
																																															//----
				$queryRankGeralGroup->select( 'ROUND ( ( (' . $RFString . ') / (' . $options['nr_etapa_prova'] . ')), '. $options['decimal_prova'] .' ) AS RankRF');						//----
			break;																																											//----
																																															//----
			case '3':																																										//----																				
				$RFString = '';																																								//----
				for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {																														//----
					if(!empty($RFString))																																					//----
						$RFString = $RFString . ' + ';																																		//----
					$RFString = $RFString . ' MAX(IF(GrupoRankingGeral.nr_etapa_prova = '. $i .', GrupoRankingGeral.RankRF, 0)) ';															//----
				}																																											//----
				$queryRankGeralGroup->select( '( ' . $RFString . ' ) AS RankRF');																											//----
			break;																																											//----
		}																																													//----
																																															//----
		$queryRankGeralGroup->select('id_etapa');																																			//----
		$queryRankGeralGroup->select('id_inscricao');																																		//----
		$queryRankGeralGroup->select('nr_etapa_prova');																																		//----
																																															//----
		$queryRankGeralGroup->from( '(' . $queryRankGeral . ') GrupoRankingGeral' );																										//----
																																															//----
		$queryRankGeralGroup->group('id_etapa');																																			//----
		$queryRankGeralGroup->group('id_inscricao');																																		//----
																																															//----
		$queryRankGeralGroup->order($this->_db->quoteName('RankRF') . ' DESC');																												//----
		for ($i = $options['ns_etapa_prova']; $i > 0; $i--) { 																																//----
			$queryRankGeralGroup->order('RankS' . $i . ' DESC');																															//----
		}																																													//----
																																															//----
		$queryRankGeralGroup->order($this->_db->quoteName('id_inscricao') . ' ASC');																										//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
																																															//----
		$queryRP = $this->_db->getQuery(true);																																				//----
		$queryRP->select( 'IF(ISNULL(ResultadoRankGeral.RankRF),NULL,MAX(ResultadoRankGeral.RankRF)) AS 100_percent');																		//----
		$queryRP->select('id_etapa');																																						//----
		$queryRP->from( '(' . $queryRankGeralGroup . ') ResultadoRankGeral' );																												//----
		$queryRP->group('id_etapa');																																						//----
																																															//----
																																															//----
		// ----------------------------------------------------------------------- END CALC PERCENT GERL -----------------------------------------------------------------------------------------


		//-------------------------------------------------------------- RESULTADO SÉRIE ETAPAS CADA INSCRIÇÃO (RANKING) ----------------------------------------------------------------
																																													//----
		$queryResultSerieRank = $this->_db->getQuery(true);																															//----
																																													//----
		switch( $options['rs_etapa_prova'] )																																		//----
		{																																											//----
			case '1':																																								//----
				$queryResultSerieRank->select( 'MAX(ReRaResult.value_resultado) AS SerieRF');																						//----
			break;																																									//----
																																													//----
			case '2':																																								//----
				$RFString = '';																																						//----
				for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) { 																												//----
					if(!empty($RFString))																																			//----
						$RFString = $RFString . ' + ';																																//----
					$RFString = $RFString . ' MAX(IF(ReRaResult.ordering = '. $i .', ReRaResult.value_resultado, 0)) ';																//----
				}																																									//----
				if($options['decimal_prova']==0)																																	//----
					$options['decimal_prova'] = '2';																																//----
																																													//----
				$queryResultSerieRank->select( 'ROUND ( ( (' . $RFString . ') / (' . $options['ns_etapa_prova'] . ')), '. $options['decimal_prova'] .' ) AS SerieRF');				//----
																																													//----
			break;																																									//----
																																													//----
			case '3':																																								//----
				$RFString = '';																																						//----
				for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) { 																												//----
					if(!empty($RFString))																																			//----
						$RFString = $RFString . ' + ';																																//----
					$RFString = $RFString . ' MAX(IF(ReRaResult.ordering = '. $i .', ReRaResult.value_resultado, 0)) ';																//----
				}																																									//----
																																													//----
				$queryResultSerieRank->select( '( ' . $RFString . ' ) AS SerieRF');																									//----
			break;																																									//----
		}																																											//----
																																													//----
		$queryResultSerieRank->select( 'id_inscricao');																																//----
		$queryResultSerieRank->select( 'ReRaResult.nr_etapa_prova');																												//----
		$queryResultSerieRank->select( 'id_etapa');																																	//----
		$queryResultSerieRank->select( 'ResultadoPorcentEtapa.100_percent AS 100_percent' );																						//----	
																																													//----
		$queryResultSerieRank->from( '#__ranking_resultado ReRaResult' );																											//----
		$queryResultSerieRank->innerJoin( '#__ranking_inscricao ReRaInscri USING( id_inscricao )' );																				//----
		$queryResultSerieRank->innerJoin( '#__ranking_etapa ReRaEtapa USING( id_etapa )' );																							//----
		$queryResultSerieRank->innerJoin('(' . $queryRP . ') ResultadoPorcentEtapa USING('. $this->_db->quoteName('id_etapa').')' );												//----
																																													//----
		if($options['id_classe'])																																					//----
			$queryResultSerieRank->where('ReRaInscri.id_classe = ' . $this->_db->quote( $options['id_classe'] ));																	//----
																																													//----
		if($options['id_categoria'])																																				//----
			$queryResultSerieRank->where('ReRaInscri.id_categoria = ' . $this->_db->quote( $options['id_categoria'] ));																//----
																																													//----
		if($options['id_genero'])																																					//----
			$queryResultSerieRank->where('ReRaInscri.id_genero = ' . $this->_db->quote( $options['id_genero'] ));																	//----
																																													//----
		if(!empty($options['id_prova']))																																			//----
			$queryResultSerieRank->where('ReRaInscri.id_prova = ' . $this->_db->quote( $options['id_prova'] ));																		//----
																																													//----
		$queryResultSerieRank->where('ReRaResult.status_resultado = 1');																											//----
		$queryResultSerieRank->where('ReRaInscri.status_inscricao = 1');																											//----
		$queryResultSerieRank->where('ReRaEtapa.status_etapa = 1');																													//----
		$queryResultSerieRank->where('ReRaEtapa.state_etapa = 1');																													//----
		$queryResultSerieRank->where('ReRaEtapa.special_status_etapa = 0');																											//----
																																													//----
																																													//----
		$queryResultSerieRank->group('ReRaResult.id_inscricao');																													//----
		$queryResultSerieRank->group('ReRaResult.id_etapa');																														//----
		$queryResultSerieRank->group('ReRaResult.nr_etapa_prova');																													//----
																																													//----
		//---------------------------------------------------- RESULTADO CÁLCULO DOS VALORES DE CADA INSCRIÇÃO DA ETAPA (RANKING) --------------------------------------------------//----
																																													//----
		$queryMelhorSerieRank = $this->_db->getQuery(true);																															//----
																																													//----
		switch( $options['rf_etapa_prova'] )																																		//----
		{																																											//----
			case '1':																																								//----
				$RFString = 'MAX(MelhorSerie.SerieRF)';																																//----
			break;																																									//----
																																													//----
			case '2':																																								//----
				$RFString = '';																																						//----
				for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {																												//----
					if(!empty($RFString))																																			//----
						$RFString = $RFString . ' + ';																																//----
					$RFString = $RFString . '  MAX(IF( MelhorSerie.nr_etapa_prova = '. $i .', MelhorSerie.SerieRF, NULL))  ';														//----
				}																																									//----
				if($options['decimal_prova']==0)																																	//----
					$options['decimal_prova'] = '2';																																//----
																																													//----
				$RFString = 'ROUND ( ( (' . $RFString . ') / (' . $options['nr_etapa_prova'] . ')), '. $options['decimal_prova'] .' )';												//----
			break;																																									//----
																																													//----
			case '3':																																								//----																				
				$RFString = '';																																						//----
				for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {																												//----
					if(!empty($RFString))																																			//----
						$RFString = $RFString . ' + ';																																//----
					$RFString = $RFString . '  MAX(IF( MelhorSerie.nr_etapa_prova = '. $i .', MelhorSerie.SerieRF, NULL)) ';														//----
				}																																									//----
				$RFString = '( ' . $RFString . ' )';																																//----
			break;																																									//----
		}																																											//----
																																													//----
		$queryMelhorSerieRank->select( 'ROUND(((' . $RFString . ')*100) / (100_percent), 3) AS value_resultados_rank');																//----		
							 																																						//----
							 																																						//----
		$queryMelhorSerieRank->select( 'id_inscricao');						 																										//----
		$queryMelhorSerieRank->select( 'id_etapa' );																																//----
		$queryMelhorSerieRank->from( '(' . $queryResultSerieRank . ') MelhorSerie' );				 																				//----
	 					 																																							//----
	 	$queryMelhorSerieRank->group('id_inscricao');						 																										//----
		$queryMelhorSerieRank->group('id_etapa');						 																											//----
						 																																							//----
		$queryRankGroup->innerJoin('(' . $queryMelhorSerieRank . ') ResultadosRank USING('. $this->_db->quoteName('id_inscricao').')' );											//----
		//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------		
		
		if($options['id_classe'])																																	
			$queryRankGroup->where('#__ranking_inscricao.id_classe = ' . $this->_db->quote( $options['id_classe'] ));				
												
		if($options['id_categoria'])																					
			$queryRankGroup->where('#__ranking_inscricao.id_categoria = ' . $this->_db->quote( $options['id_categoria'] ));	
																								
		if($options['id_genero'])																		
			$queryRankGroup->where('#__ranking_inscricao.id_genero = ' . $this->_db->quote( $options['id_genero'] ));					
						
		if($options['id_prova'])																			
			$queryRankGroup->where('#__ranking_inscricao.id_prova = ' . $this->_db->quote( $options['id_prova'] ));		

				
		$queryRankGroup->where( '(' 																
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
		
		$queryRankGroup->where($this->_db->quoteName('status_inscricao') . ' = ' . $this->_db->quote('1'));
		$queryRankGroup->where($this->_db->quoteName('status_modalidade') . ' = ' . $this->_db->quote('1'));	
		$queryRankGroup->where($this->_db->quoteName('status_campeonato') . ' = ' . $this->_db->quote('1'));
		$queryRankGroup->where($this->_db->quoteName('status_prova') . ' = ' . $this->_db->quote('1'));
						
	
			
		$queryRankGroup->order($this->_db->quoteName('RankRf') . ' DESC');
		//$queryRankGroup->order($this->_db->quoteName('RankFinal') . ' DESC');
		for ($i = 1; $i <= $options['results_prova']; $i++) { 
			$queryRankGroup->order($this->_db->quoteName('RankR' . $i) . ' DESC');
		}
		
		$queryRankGroup->order($this->_db->quoteName('id_associado') . ' ASC');

		$queryRankGroup->group('id_inscricao');		

		$queryRank = $this->_db->getQuery(true);																				
		$queryRank->select( 'GROUP_CONCAT( ResultadoSoma.id_associado )' );
		$queryRank->from( '(' . $queryRankGroup . ') ResultadoSoma' );
				
		$query->select( 'FIND_IN_SET( ( id_associado ), (' . $queryRank . ') ) AS rank');
		
		$query->from( $this->_db->quoteName('#__ranking_inscricao'));
		$query->innerJoin( $this->_db->quoteName('#__ranking_campeonato') . ' USING('. $this->_db->quoteName('id_campeonato').')' );
		$query->innerJoin( $this->_db->quoteName('#__ranking_modalidade') . ' USING('. $this->_db->quoteName('id_modalidade').')' );
		$query->innerJoin( $this->_db->quoteName('#__ranking_prova') . ' USING('. $this->_db->quoteName('id_prova').')' );
		$query->innerJoin( $this->_db->quoteName('#__ranking_genero') . ' USING('. $this->_db->quoteName('id_genero').')' );
		$query->innerJoin( $this->_db->quoteName('#__ranking_categoria') . ' USING('. $this->_db->quoteName('id_categoria').')' );		
		$query->innerJoin( $this->_db->quoteName('#__ranking_classe') . ' USING('. $this->_db->quoteName('id_classe').')' );
		//$query->innerJoin( $this->_db->quoteName('#__ranking_profile') . ' USING('. $this->_db->quoteName('id_user').')' );
		$query->innerJoin( $this->_db->quoteName('#__intranet_pf') . ' USING('. $this->_db->quoteName('id_user').')' );
		$query->innerJoin( $this->_db->quoteName('#__intranet_associado') . ' USING('. $this->_db->quoteName('id_user').')' );
		
		$query->innerJoin( $this->_db->quoteName('#__users') . 'ON('. $this->_db->quoteName('id'). ' = '. $this->_db->quoteName('id_user').')' );
		//$query->leftJoin( $this->_db->quoteName('#__intranet_cidade') . ' USING('. $this->_db->quoteName('id_cidade').')' );
		
		//-------------------------------------------------------------- RESULTADO SÉRIE ETAPAS CADA INSCRIÇÃO(SEM FINAL) ----------------------------------------------------------------
																																													//----
		$queryResultSerie = $this->_db->getQuery(true);																																//----
																																													//----
		switch( $options['rs_etapa_prova'] )																																		//----
		{																																											//----
			case '1':																																								//----
				$queryResultSerie->select( 'MAX(ReSeResult.value_resultado) AS SerieRF');																							//----
			break;																																									//----
																																													//----
			case '2':																																								//----
				$RFString = '';																																						//----
				for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) { 																												//----
					if(!empty($RFString))																																			//----
						$RFString = $RFString . ' + ';																																//----
					$RFString = $RFString . ' MAX(IF(ReSeResult.ordering = '. $i .', ReSeResult.value_resultado, 0)) ';																//----
				}																																									//----
				if($options['decimal_prova']==0)																																	//----
					$options['decimal_prova'] = '2';																																//----
																																													//----
				$queryResultSerie->select( 'ROUND ( ( (' . $RFString . ') / (' . $options['ns_etapa_prova'] . ')), '. $options['decimal_prova'] .' ) AS SerieRF');					//----
																																													//----
			break;																																									//----
																																													//----
			case '3':																																								//----
				$RFString = '';																																						//----
				for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) { 																												//----
					if(!empty($RFString))																																			//----
						$RFString = $RFString . ' + ';																																//----
					$RFString = $RFString . ' MAX(IF(ReSeResult.ordering = '. $i .', ReSeResult.value_resultado, 0)) ';																//----
				}																																									//----
																																													//----
				$queryResultSerie->select( '( ' . $RFString . ' ) AS SerieRF');																										//----
			break;																																									//----
		}																																											//----
		$queryResultSerie->select( 'ResultadoPorcentEtapa.100_percent AS 100_percent' );																							//----
		$queryResultSerie->select( 'ReSeResult.id_etapa AS id_etapas' );																											//----
		$queryResultSerie->select( 'ReSeResult.nr_etapa_prova AS nr_etapa_prova' );																									//----
		$queryResultSerie->select( 'ReSeEtapa.name_etapa AS name_etapas' );																											//----
		$queryResultSerie->select( 'ReSeInsEta.id_local AS id_locals' );																											//----
		$queryResultSerie->select( 'ReSeUsers.name AS names' );																														//----
																																													//----
		$queryResultSerie->select( 'id_inscricao');																																	//----
		$queryResultSerie->select( 'id_etapa');																																		//----
																																													//----
		$queryResultSerie->from( '#__ranking_resultado ReSeResult' );																												//----
		$queryResultSerie->innerJoin( '#__ranking_inscricao ReSeInscri USING( id_inscricao )' );																					//----
		$queryResultSerie->innerJoin( '#__ranking_inscricao_etapa ReSeInsEta USING( id_inscricao,  id_etapa )' );																	//----
		$queryResultSerie->innerJoin( '#__ranking_etapa ReSeEtapa USING( id_etapa )' );																								//----
		$queryResultSerie->innerJoin('(' . $queryRP . ') ResultadoPorcentEtapa USING('. $this->_db->quoteName('id_etapa').')' );													//----
		$queryResultSerie->leftJoin( '#__users ReSeUsers ON (ReSeUsers.id = ReSeInsEta.id_local)' );																				//----
																																													//----
		if($options['id_classe'])																																					//----
			$queryResultSerie->where('ReSeInscri.id_classe = ' . $this->_db->quote( $options['id_classe'] ));																		//----
																																													//----
		if($options['id_categoria'])																																				//----
			$queryResultSerie->where('ReSeInscri.id_categoria = ' . $this->_db->quote( $options['id_categoria'] ));																	//----
																																													//----
		if($options['id_genero'])																																					//----
			$queryResultSerie->where('ReSeInscri.id_genero = ' . $this->_db->quote( $options['id_genero'] ));																		//----
																																													//----		
		if(!empty($options['id_prova']))																																			//----
			$queryResultSerie->where('ReSeInscri.id_prova = ' . $this->_db->quote( $options['id_prova'] ));																			//----
																																													//----
																																													//----
		$queryResultSerie->where('ReSeResult.status_resultado = 1');																												//----
		$queryResultSerie->where('ReSeInscri.status_inscricao = 1');																												//----
		$queryResultSerie->where('ReSeInsEta.status_inscricao_etapa = 1');																											//----
		$queryResultSerie->where('ReSeEtapa.status_etapa = 1');																														//----
		$queryResultSerie->where('ReSeEtapa.state_etapa = 1');																														//----
		$queryResultSerie->where('ReSeEtapa.special_status_etapa = 0');																												//----
																																													//----
																																													//----
		$queryResultSerie->group('ReSeResult.id_inscricao');																														//----
		$queryResultSerie->group('ReSeResult.id_etapa');																															//----
		$queryResultSerie->group('ReSeResult.nr_etapa_prova');																														//----
																																													//----
		//--------------------------------------------------- RESULTADO CÁLCULO DOS VALORES DE CADA INSCRIÇÃO DA ETAPA (SEM FINAL) -------------------------------------------------//----
																																													//----
		$queryMelhorSerie = $this->_db->getQuery(true);																																//----
																																													//----
		//---------------------------------------------------- RESULTADO CÁLCULO DOS VALORES DE CADA INSCRIÇÃO DA ETAPA (RANKING) --------------------------------------------------//----
																																													//----
																																													//----
		switch( $options['rf_etapa_prova'] )																																		//----
		{																																											//----
			case '1':																																								//----
				$RFString = 'MAX(MelhorSerie.SerieRF)';																																//----
			break;																																									//----
																																													//----
			case '2':																																								//----
				$RFString = '';																																						//----
				for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {																												//----
					if(!empty($RFString))																																			//----
						$RFString = $RFString . ' + ';																																//----
					$RFString = $RFString . '  MAX(IF( MelhorSerie.nr_etapa_prova = '. $i .', MelhorSerie.SerieRF, 0))  ';															//----
				}																																									//----
				if($options['decimal_prova']==0)																																	//----
					$options['decimal_prova'] = '2';																																//----
																																													//----
				$RFString = 'ROUND ( ( (' . $RFString . ') / (' . $options['nr_etapa_prova'] . ')), '. $options['decimal_prova'] .' )';												//----
			break;																																									//----
																																													//----
			case '3':																																								//----																				
				$RFString = '';																																						//----
				for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {																												//----
					if(!empty($RFString))																																			//----
						$RFString = $RFString . ' + ';																																//----
					$RFString = $RFString . '  MAX(IF( MelhorSerie.nr_etapa_prova = '. $i .', MelhorSerie.SerieRF, 0)) ';															//----
				}																																									//----
				$RFString = '( ' . $RFString . ' )';																																//----
			break;																																									//----
		}																																											//----
																																													//----						
		$queryMelhorSerie->select( 'ROUND(((' . $RFString . ')*100) / (100_percent), 3) AS value_resultados');																		//----		
							 																																						//----
		$queryMelhorSerie->select( 'id_inscricao');						 																											//----
		$queryMelhorSerie->select( 'id_etapas' );																																	//----
		$queryMelhorSerie->select( 'name_etapas' );																																	//----
		$queryMelhorSerie->select( 'id_locals' );																																	//----
		$queryMelhorSerie->select( 'names' );																			 															//----
		$queryMelhorSerie->from( '(' . $queryResultSerie . ') MelhorSerie' );						 																				//----
	 					 																																							//----
	 	$queryMelhorSerie->group('id_inscricao');						 																											//----
		$queryMelhorSerie->group('id_etapa');						 																												//----
						 																																							//----
		$query->innerJoin('(' . $queryMelhorSerie . ') Resultados USING('. $this->_db->quoteName('id_inscricao').')' );																//----
		//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		

		// ESTA ETAPA DE FILTRO ----------------------------------------------------------------------------------------------------------------------------------------------------------
		$queryResultadoEtapa = $this->_db->getQuery(true);																															//----
		$queryResultadoEtapa->select( 'C.id_inscricao' );																															//----
																															 														//----
		switch( $options['rs_etapa_prova'] )																																		//----
		{																																											//----
			case '1':																																								//----
				$queryResultadoEtapa->select( 'MAX(C.value_resultado) AS SerieRF');																									//----
			break;																																									//----
																																													//----
			case '2':																																								//----
				$RFString = '';																																						//----
				for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) { 																												//----
					if(!empty($RFString))																																			//----
						$RFString = $RFString . ' + ';																																//----
					$RFString = $RFString . ' MAX(IF(C.ordering = '. $i .', C.value_resultado, 0)) ';																				//----
				}																																									//----
				if($options['decimal_prova']==0)																																	//----
					$options['decimal_prova'] = '2';																																//----
																																													//----
				$queryResultadoEtapa->select( 'ROUND ( ( (' . $RFString . ') / (' . $options['ns_etapa_prova'] . ')), '. $options['decimal_prova'] .' ) AS SerieRF');				//----
																																													//----
			break;																																									//----
																																													//----
			case '3':																																								//----
				$RFString = '';																																						//----
				for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) { 																												//----
					if(!empty($RFString))																																			//----
						$RFString = $RFString . ' + ';																																//----
					$RFString = $RFString . ' MAX(IF(C.ordering = '. $i .', C.value_resultado, 0)) ';																				//----
				}																																									//----
																																													//----
				$queryResultadoEtapa->select( '( ' . $RFString . ' ) AS SerieRF');																									//----
			break;																																									//----
		}																																											//----
																																													//----
		$queryResultadoEtapa->select( 'nr_etapa_prova AS nr_etapa_prova' );																											//----
																																													//----
		$queryResultadoEtapa->select( 'ResultadoPorcentEtapa.100_percent AS 100_percent' );																							//----						
		$queryResultadoEtapa->from( '#__ranking_resultado C' );																														//----
		$queryResultadoEtapa->innerJoin( '#__ranking_inscricao CC USING(id_inscricao)' );																							//----
		$queryResultadoEtapa->innerJoin( '#__ranking_etapa CCCC USING (id_etapa)' );																								//----
		$queryResultadoEtapa->innerJoin('(' . $queryRP . ') ResultadoPorcentEtapa USING('. $this->_db->quoteName('id_etapa').')' );													//----
																																													//----
		if($options['id_classe'])																																					//----
			$queryResultadoEtapa->where('CC.id_classe = ' . $this->_db->quote( $options['id_classe'] ));																			//----
																																													//----
		if($options['id_categoria'])																																				//----
			$queryResultadoEtapa->where('CC.id_categoria = ' . $this->_db->quote( $options['id_categoria'] ));																		//----
																																													//----
		if($options['id_genero'])																																					//----
			$queryResultadoEtapa->where('CC.id_genero = ' . $this->_db->quote( $options['id_genero'] ));																			//----
																																													//----
		if($options['id_prova'])																																					//----
			$queryResultadoEtapa->where('CC.id_prova = ' . $this->_db->quote( $options['id_prova'] ));																				//----
																																													//----
		if($options['id_etapa'])																																					//----
			$queryResultadoEtapa->where('CCCC.id_etapa = ' . $this->_db->quote( $options['id_etapa'] ));																			//----
																																													//----
																																													//----
		$queryResultadoEtapa->where('C.status_resultado = 1');																														//----
		$queryResultadoEtapa->where('CC.status_inscricao = 1');																														//----
		$queryResultadoEtapa->where('CCCC.status_etapa = 1');																														//----
		$queryResultadoEtapa->where('CCCC.state_etapa = 1');																														//----
		$queryResultadoEtapa->group('C.id_inscricao');																																//----
		$queryResultadoEtapa->group('nr_etapa_prova');																																//----
		//--------------------------------------------------- RESULTADO CÁLCULO DOS VALORES DE CADA INSCRIÇÃO DA ETAPA (SEM FINAL) -------------------------------------------------//----
																																													//----
		$querySerieHoje = $this->_db->getQuery(true);																																//----
																																													//----						
		switch( $options['rf_etapa_prova'] )																																		//----
		{																																											//----
			case '1':																																								//----
				$RFString = 'MAX(SerieRF)';																																			//----
			break;																																									//----
																																													//----
			case '2':																																								//----
				$RFString = '';																																						//----
				for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {																												//----
					if(!empty($RFString))																																			//----
						$RFString = $RFString . ' + ';																																//----
					$RFString = $RFString . '  MAX(IF( nr_etapa_prova = '. $i .', SerieRF, 0))  ';																				//----
				}																																									//----
				if($options['decimal_prova']==0)																																	//----
					$options['decimal_prova'] = '2';																																//----
																																													//----
				$RFString = 'ROUND ( ( (' . $RFString . ') / (' . $options['nr_etapa_prova'] . ')), '. $options['decimal_prova'] .' )';												//----
			break;																																									//----
																																													//----
			case '3':																																								//----																				
				$RFString = '';																																						//----
				for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {																												//----
					if(!empty($RFString))																																			//----
						$RFString = $RFString . ' + ';																																//----
					$RFString = $RFString . '  MAX(IF( nr_etapa_prova = '. $i .', SerieRF, 0)) ';																				//----
				}																																									//----
				$RFString = '( ' . $RFString . ' )';																																//----
			break;																																									//----
		}																																											//----
																																													//----						
		$querySerieHoje->select( 'ROUND(((' . $RFString . ')*100) / (100_percent), 3) AS value_resultado');																			//----	
																																													//----
		$querySerieHoje->select( 'id_inscricao');						 																											//----
		$querySerieHoje->from( '(' . $queryResultadoEtapa . ') HojeSerie' );						 																				//----
	 					 																																							//----
	 	$querySerieHoje->group('id_inscricao');						 																												//----
						 																																							//----
		$query->leftJoin('(' . $querySerieHoje . ') Etapa USING('. $this->_db->quoteName('id_inscricao').')' );																		//----
		//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

		if($options['id_classe'])
			$query->where($this->_db->quoteName('#__ranking_inscricao.id_classe') . ' = ' . $this->_db->quote( $options['id_classe'] ));
			
		if($options['id_categoria'])
			$query->where($this->_db->quoteName('#__ranking_inscricao.id_categoria') . ' = ' . $this->_db->quote( $options['id_categoria'] ));
			
		if($options['id_genero'])
			$query->where($this->_db->quoteName('#__ranking_inscricao.id_genero') . ' = ' . $this->_db->quote( $options['id_genero'] ));
			
		if($options['id_prova'])
			$query->where($this->_db->quoteName('#__ranking_inscricao.id_prova') . ' = ' . $this->_db->quote( $options['id_prova'] ));
			
		if($options['name'])
			$query->where( $this->_db->quoteName('name') . ' LIKE \'%' . $this->_db->escape($options['name']) . '%\'' );
		
		if($options['id_user'])
			$query->where($this->_db->quoteName('#__ranking_inscricao.id_user') . ' = ' . $this->_db->quote( $options['id_user'] ));
			
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


		$query->where($this->_db->quoteName('status_inscricao') . ' = ' . $this->_db->quote('1'));
		$query->where($this->_db->quoteName('status_modalidade') . ' = ' . $this->_db->quote('1'));	
		$query->where($this->_db->quoteName('status_campeonato') . ' = ' . $this->_db->quote('1'));
		$query->where($this->_db->quoteName('status_prova') . ' = ' . $this->_db->quote('1'));

		
		$query->order($this->_db->quoteName('rf') . ' DESC');
		
		for ($i = 1; $i <= $options['results_prova']; $i++) { 
			$query->order($this->_db->quoteName('r' . $i) . ' DESC');
		}

		$query->order($this->_db->quoteName('id_associado') . ' ASC');

		$query->group('id_inscricao');

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
	
	
		$query = $this->_db->getQuery(true);
		$query->select( $this->_db->quoteName(array( 'C.status_resultado',
											
													 'CC.id_inscricao',
													 'CC.id_prova',
													 'CC.id_user',
													 
													// 'CCC.status_inscricao_etapa',
													// 'CCC.id_inscricao_etapa',													 
													 
													 'CCCC.id_genero',
													 'CCCC.name_genero',
													 
													 'CCCCC.id_categoria',
													 'CCCCC.name_categoria',
													 
													 'CCCCCC.id_classe',
													 'CCCCCC.name_classe',
													 'U.image',
													 'UU.name',
													 //'UUU.name_cidade',
												    )));


	//	for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) { 
	//		for ($x = 1; $x <= $options['ns_etapa_prova']; $x++) { 
	//			$query->select( 'MAX( IF(C.nr_etapa_prova = ' . $i . ' AND C.ordering = ' . $x . ', C.value_resultado, NULL)) AS r' . $i . 's' . $x);
	//		}
	//	}
		
		if($options['nr_etapa_prova']>1 && $options['ns_etapa_prova']>1) :
			for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) { 
			 $query->select( 'MAX(IF(ResultadoSerie.id_inscricao = C.id_inscricao AND ResultadoSerie.nr_etapa_prova = '. $i .', ResultadoSerie.RSerie, NULL)) AS rs' . $i);
			}
		endif;
	
		switch( $options['rf_etapa_prova'] )
		{
			case '1':
				$query->select( 'MAX(IF(ResultadoSerie.id_inscricao = C.id_inscricao, ResultadoSerie.RSerie, 0)) AS rf');
			break;

			case '2':
				$RFString = '';
				for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {
					if(!empty($RFString))
						$RFString = $RFString . ' + ';
					$RFString = $RFString . ' MAX(IF(ResultadoSerie.id_inscricao = C.id_inscricao AND ResultadoSerie.nr_etapa_prova = '. $i .', ResultadoSerie.RSerie, NULL)) ';
				}
				if($options['decimal_prova']==0)
					$options['decimal_prova'] = '2';

				$query->select( 'ROUND ( ( (' . $RFString . ') / (' . $options['nr_etapa_prova'] . ')), '. $options['decimal_prova'] .' ) AS rf');

			break;

			case '3':
				$RFString = '';
				for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {
					if(!empty($RFString))
						$RFString = $RFString . ' + ';
					$RFString = $RFString . ' MAX(IF(ResultadoSerie.id_inscricao = C.id_inscricao AND ResultadoSerie.nr_etapa_prova = '. $i .', ResultadoSerie.RSerie, NULL)) ';
				}

				$query->select( '( ' . $RFString . ' ) AS rf');
			break;
		}
						
		//------------------------------------------------------------------------------------ CALC RANKING --------------------------------------------------------------------------------------
																																															//----
		$queryRankResults = $this->_db->getQuery(true);																																		//----
																																															//----
		$queryRankResults->select( 'RANRESU.id_inscricao AS RankMatricula');																												//----
																																															//----
		for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) {  																															//----
			$queryRankResults->select( 'MAX(IF( RANRESU.ordering = ' . $i . ', RANRESU.value_resultado, 0)) AS RankS' . $i);																//----
		}																																													//----
																																															//----
		switch( $options['rs_etapa_prova'] )																																				//----
		{																																													//----
			case '1':																																										//----
				$queryRankResults->select( 'MAX(RANRESU.value_resultado) AS RankRF');																										//----
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
				$queryRankResults->select( 'ROUND ( ( (' . $RFString . ') / (' . $options['ns_etapa_prova'] . ')), '. $options['decimal_prova'] .' ) AS RankRF');							//----
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
				$queryRankResults->select( '( ' . $RFString . ' ) AS RankRF');																												//----
			break;																																											//----
		}																																													//----
																																															//----
		$queryRankResults->select( 'id_inscricao');																																			//----
		$queryRankResults->select( 'RANRESU.nr_etapa_prova');																																//----
																																															//----
		$queryRankResults->from( '#__ranking_resultado RANRESU' );																															//----
																																															//----
		$queryRankResults->innerJoin( '#__ranking_inscricao RANINSC USING( id_inscricao )' );																								//----
																																															//----
		$queryRankResults->innerJoin( '#__ranking_inscricao_etapa RANIETA USING( id_inscricao,  id_etapa )' );																				//----
																																															//----
		$queryRankResults->innerJoin( '#__ranking_campeonato RANCAMP USING( id_campeonato )' );																								//----
		$queryRankResults->innerJoin( '#__ranking_modalidade RANMODA  USING( id_modalidade )' );																							//----
		$queryRankResults->innerJoin( '#__ranking_prova RANPROV USING( id_prova )' );																										//----
																																															//----
		$queryRankResults->innerJoin( '#__ranking_genero RANGENE USING( id_genero )' );																										//----
		$queryRankResults->innerJoin( '#__ranking_categoria RANCATE USING( id_categoria )' );																								//----
		$queryRankResults->innerJoin( '#__ranking_classe RANCLAS USING( id_classe )' );																										//----
																																															//----
		//$queryRankResults->innerJoin( '#__ranking_profile RANPROF USING('. $this->_db->quoteName('id_user').')' );																			//----
		//$queryRankResults->innerJoin( '#__users RANUSER ON('. $this->_db->quoteName('id'). ' = '. $this->_db->quoteName('id_user').')' );													//----
																																															//----
																																															//----
		//if( !$this->_user->authorise('torneios.diretor.geral', 'com_torneios') )																											//----
			$queryRankResults->where($this->_db->quoteName('RANMODA.status_modalidade') . ' = ' . $this->_db->quote('1'));																	//----
																																															//----
		//if( !$this->_user->authorise('torneios.diretor.modalidade', 'com_torneios') )																										//----
		//{																																													//----
			$queryRankResults->where($this->_db->quoteName('RANCAMP.status_campeonato') . ' = ' . $this->_db->quote('1'));																	//----
			$queryRankResults->where($this->_db->quoteName('RANPROV.status_prova') . ' = ' . $this->_db->quote('1'));																		//----
		//	$queryRankResults->where($this->_db->quoteName('RANIETA.status_inscricao_etapa') . ' = ' . $this->_db->quote('1'));																//----
		//}																																													//----
																																															//----
		if(!empty($options['id_classe']))																																					//----
			$queryRankResults->where('RANINSC.id_classe = ' . $this->_db->quote( $options['id_classe'] ));																					//----
																																															//----
		if(!empty($options['id_etapa']))																																					//----
			$queryRankResults->where('RANRESU.id_etapa = ' . $this->_db->quote( $options['id_etapa'] ));																					//----
																																															//----
		if(!empty($options['id_categoria']))																																				//----
			$queryRankResults->where('RANINSC.id_categoria = ' . $this->_db->quote( $options['id_categoria'] ));																			//----
																																															//----
		if(!empty($options['id_genero']))																																					//----
			$queryRankResults->where('RANINSC.id_genero = ' . $this->_db->quote( $options['id_genero'] ));																					//----
																																															//----
		if(!empty($options['id_prova']))																																					//----
			$queryRankResults->where('RANINSC.id_prova = ' . $this->_db->quote( $options['id_prova'] ));																					//----
																																															//----
		$queryRankResults->group('RANRESU.id_inscricao');																																	//----
		$queryRankResults->group('RANRESU.nr_etapa_prova');																																	//----
																																															//----
		$queryRankResults->order('RankRF DESC');																																			//----
		for ($i = $options['ns_etapa_prova']; $i > 0; $i--) { 																																//----
			$queryRankResults->order('RankS' . $i . ' DESC');																																//----
		}																																													//----
																																															//----
		$queryRankResults->order($this->_db->quoteName('RankMatricula') . ' ASC');																											//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
																																															//----
		$queryRankGroup = $this->_db->getQuery(true);																																		//----
																																															//----
																																															//----
		switch( $options['rf_etapa_prova'] )																																				//----
		{																																													//----
			case '1':																																										//----
				$queryRankGroup->select( 'MAX(GrupoRanking.RankRF) AS RankRF');																												//----
			break;																																											//----
																																															//----
			case '2':																																										//----
				$RFString = '';																																								//----
				for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {																														//----
					if(!empty($RFString))																																					//----
						$RFString = $RFString . ' + ';																																		//----
					$RFString = $RFString . '  MAX(IF( GrupoRanking.nr_etapa_prova = '. $i .', GrupoRanking.RankRF, 0))  ';																	//----
				}																																											//----
				if($options['decimal_prova']==0)																																			//----
					$options['decimal_prova'] = '2';																																		//----
																																															//----
				$queryRankGroup->select( 'ROUND ( ( (' . $RFString . ') / (' . $options['nr_etapa_prova'] . ')), '. $options['decimal_prova'] .' ) AS RankRF');								//----
			break;																																											//----
																																															//----
			case '3':																																										//----																				
				$RFString = '';																																								//----
				for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {																														//----
					if(!empty($RFString))																																					//----
						$RFString = $RFString . ' + ';																																		//----
					$RFString = $RFString . ' MAX(IF( GrupoRanking.nr_etapa_prova = '. $i .', GrupoRanking.RankRF, 0)) ';																	//----
				}																																											//----
				$queryRankGroup->select( '( ' . $RFString . ' ) AS RankRF');																												//----
			break;																																											//----
		}																																													//----
																																															//----
		$queryRankGroup->select('RankMatricula');																																			//----
		$queryRankGroup->from( '(' . $queryRankResults . ') GrupoRanking' );																												//----
																																															//----
		$queryRankGroup->group('id_inscricao');																																				//----
																																															//----
		$queryRankGroup->order($this->_db->quoteName('RankRF') . ' DESC');																													//----
		$queryRankGroup->order($this->_db->quoteName('RankMatricula') . ' ASC');																											//----
																																															//----
		$queryRank = $this->_db->getQuery(true);																																			//----
																																															//----
		switch( $options['type_prova'] )																																					//----
		{																																													//----
			case '1':																																										//----
			case '4':																																										//----
				$queryRank->select( 'GROUP_CONCAT( ResultadoRank.RankMatricula )' );																										//----								
				$queryRank->from( '(' . $queryRankGroup . ') ResultadoRank' );																												//----
				$query->select( 'FIND_IN_SET( U.id_user, (' . $queryRank . ') ) AS rank');																									//----
			break;																																											//----
																																															//----
			case '2':																																										//----
			case '3':																																										//----
			default:																																										//----
				$queryRank = $this->_db->getQuery(true);																																	//----
				$queryRank->select( 'GROUP_CONCAT( ResultadoRank.RankRF ORDER BY ResultadoRank.RankRF DESC )' );																			//----								
				$queryRank->from( '(' . $queryRankGroup . ') ResultadoRank' );																												//----
																																															//----
				switch( $options['rf_etapa_prova'] )																																		//----
				{																																											//----
					case '1':																																								//----
						$RFString = 'MAX(IF(ResultadoSerie.id_inscricao = C.id_inscricao, ResultadoSerie.RSerie, 0))';																		//----
					break;																																									//----
																																															//----
					case '2':																																								//----
						$RFString = '';																																						//----
						for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {																												//----
							if(!empty($RFString))																																			//----
								$RFString = $RFString . ' + ';																																//----
							$RFString = $RFString . ' MAX(IF(ResultadoSerie.id_inscricao = C.id_inscricao AND ResultadoSerie.nr_etapa_prova = '. $i .', ResultadoSerie.RSerie, 0)) ';		//----
						}																																									//----
						if($options['decimal_prova']==0)																																	//----
							$options['decimal_prova'] = '2';																																//----
																																															//----
						$RFString = 'ROUND ( ( (' . $RFString . ') / (' . $options['nr_etapa_prova'] . ')), '. $options['decimal_prova'] .' )';												//----
																																															//----
					break;																																									//----
																																															//----
					case '3':																																								//----
						$RFString = '';																																						//----
						for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {																												//----
							if(!empty($RFString))																																			//----
								$RFString = $RFString . ' + ';																																//----
							$RFString = $RFString . ' MAX(IF(ResultadoSerie.id_inscricao = C.id_inscricao AND ResultadoSerie.nr_etapa_prova = '. $i .', ResultadoSerie.RSerie, 0)) ';		//----
						}																																									//----
																																															//----
						$RFString = '( ' . $RFString . ' )';																																//----
					break;																																									//----
				}																																											//----
				$query->select( 'FIND_IN_SET( ('.$RFString.'), (' . $queryRank . ') ) AS rank');																							//----
			break;																																											//----
		}																																													//----
		//------------------------------------------------------------------------------ END CALC RANK -------------------------------------------------------------------------------------------


		//------------------------------------------------------------------------------- CALC PERCENT GERL --------------------------------------------------------------------------------------
																																															//----
		$queryRankGeral = $this->_db->getQuery(true);																																		//----
																																															//----
		$queryRankGeral->select( 'RANRESU.id_inscricao AS RankMatricula');																													//----
																																															//----
		for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) {  																															//----
			$queryRankGeral->select( 'MAX(IF( RANRESU.ordering = ' . $i . ', RANRESU.value_resultado, 0)) AS RankS' . $i);																	//----
		}																																													//----
																																															//----
		switch( $options['rs_etapa_prova'] )																																				//----
		{																																													//----
			case '1':																																										//----
				$queryRankGeral->select( 'MAX(RANRESU.value_resultado) AS RankRF');																											//----
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
				$queryRankGeral->select( 'ROUND ( ( (' . $RFString . ') / (' . $options['ns_etapa_prova'] . ')), '. $options['decimal_prova'] .' ) AS RankRF');								//----
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
				$queryRankGeral->select( '( ' . $RFString . ' ) AS RankRF');																												//----
			break;																																											//----
		}																																													//----
																																															//----
		$queryRankGeral->select( 'RANRESU.id_inscricao');																																	//----
		$queryRankGeral->select( 'RANRESU.nr_etapa_prova');																																	//----
																																															//----
		$queryRankGeral->from( '#__ranking_resultado RANRESU' );																															//----
																																															//----
		$queryRankGeral->innerJoin( '#__ranking_inscricao RANINSC USING( id_inscricao )' );																									//----
																																															//----
	//	$queryRankGeral->innerJoin( '#__ranking_inscricao_etapa RANIETA USING( id_inscricao,  id_etapa )' );																				//----
																																															//----
		$queryRankGeral->innerJoin( '#__ranking_campeonato RANCAMP USING( id_campeonato )' );																								//----
		$queryRankGeral->innerJoin( '#__ranking_modalidade RANMODA  USING( id_modalidade )' );																								//----
		$queryRankGeral->innerJoin( '#__ranking_prova RANPROV USING( id_prova )' );																											//----
																																															//----
		//$queryRankGeral->innerJoin( '#__ranking_profile RANPROF USING('. $this->_db->quoteName('id_user').')' );																			//----
		//$queryRankGeral->innerJoin( '#__users RANUSER ON('. $this->_db->quoteName('id'). ' = '. $this->_db->quoteName('id_user').')' );														//----
																																															//----
		//if( !$this->_user->authorise('torneios.diretor.geral', 'com_torneios') )																											//----
			$queryRankGeral->where($this->_db->quoteName('RANMODA.status_modalidade') . ' = ' . $this->_db->quote('1'));																	//----
																																															//----
		//if( !$this->_user->authorise('torneios.diretor.modalidade', 'com_torneios') )																										//----
		//{																																													//----
			$queryRankGeral->where($this->_db->quoteName('RANCAMP.status_campeonato') . ' = ' . $this->_db->quote('1'));																	//----
			$queryRankGeral->where($this->_db->quoteName('RANPROV.status_prova') . ' = ' . $this->_db->quote('1'));																			//----
	//		$queryRankGeral->where($this->_db->quoteName('RANIETA.status_inscricao_etapa') . ' = ' . $this->_db->quote('1'));																//----
		//}																																													//----
																																															//----
		if(!empty($options['id_etapa']))																																					//----
			$queryRankGeral->where('RANRESU.id_etapa = ' . $this->_db->quote( $options['id_etapa'] ));																						//----
																																															//----
		if(!empty($options['id_prova']))																																					//----
			$queryRankGeral->where('RANINSC.id_prova = ' . $this->_db->quote( $options['id_prova'] ));																						//----
																																															//----
		$queryRankGeral->group('RANRESU.id_inscricao');																																		//----
		$queryRankGeral->group('RANRESU.nr_etapa_prova');																																	//----
																																															//----
		$queryRankGeral->order('RankRF DESC');																																				//----
		for ($i = $options['ns_etapa_prova']; $i > 0; $i--) { 																																//----
			$queryRankGeral->order('RankS' . $i . ' DESC');																																	//----
		}																																													//----
																																															//----
		$queryRankGeral->order($this->_db->quoteName('RankMatricula') . ' ASC');																											//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
																																															//----
		$queryRankGeralGroup = $this->_db->getQuery(true);																																	//----
																																															//----
		for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) {  																															//----
			$queryRankGeralGroup->select('RankS' . $i);																																		//----
		}																																													//----
																																															//----
		switch( $options['rf_etapa_prova'] )																																				//----
		{																																													//----
			case '1':																																										//----
				$queryRankGeralGroup->select( 'MAX(GrupoRanking.RankRF) AS RankRF');																										//----
			break;																																											//----
																																															//----
			case '2':																																										//----
				$RFString = '';																																								//----
				for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {																														//----
					if(!empty($RFString))																																					//----
						$RFString = $RFString . ' + ';																																		//----
					$RFString = $RFString . '  MAX(IF( GrupoRanking.nr_etapa_prova = '. $i .', GrupoRanking.RankRF, 0))  ';																	//----
				}																																											//----
				if($options['decimal_prova']==0)																																			//----
					$options['decimal_prova'] = '2';																																		//----
																																															//----
				$queryRankGeralGroup->select( 'ROUND ( ( (' . $RFString . ') / (' . $options['nr_etapa_prova'] . ')), '. $options['decimal_prova'] .' ) AS RankRF');						//----
			break;																																											//----
																																															//----
			case '3':																																										//----																				
				$RFString = '';																																								//----
				for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {																														//----
					if(!empty($RFString))																																					//----
						$RFString = $RFString . ' + ';																																		//----
					$RFString = $RFString . ' MAX(IF(GrupoRanking.nr_etapa_prova = '. $i .', GrupoRanking.RankRF, 0)) ';																	//----
				}																																											//----
				$queryRankGeralGroup->select( '( ' . $RFString . ' ) AS RankRF');																											//----
			break;																																											//----
		}																																													//----
		$queryRankGeralGroup->select('RankMatricula');																																		//----
		$queryRankGeralGroup->select('nr_etapa_prova');																																		//----
		//$queryRankGeralGroup->select('id_inscricao');																																		//----
																																															//----
		$queryRankGeralGroup->from( '(' . $queryRankGeral . ') GrupoRanking' );																												//----
																																															//----
		$queryRankGeralGroup->group('id_inscricao');																																		//----
																																															//----
		$queryRankGeralGroup->order($this->_db->quoteName('RankRF') . ' DESC');																												//----
		for ($i = $options['ns_etapa_prova']; $i > 0; $i--) { 																																//----
			$queryRankGeralGroup->order('RankS' . $i . ' DESC');																															//----
		}																																													//----
																																															//----
		$queryRankGeralGroup->order($this->_db->quoteName('RankMatricula') . ' ASC');																										//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
																																															//----
		$queryRP = $this->_db->getQuery(true);																																				//----					
																																															//----
		$queryRP->select( 'IF(ISNULL(ResultadoRankGeral.RankRF),NULL,MAX(ResultadoRankGeral.RankRF)) AS rf');																																																																						
																																															//----
		$queryRP->from( '(' . $queryRankGeralGroup . ') ResultadoRankGeral' );																												//----
		switch( $options['rf_etapa_prova'] )																																				//----
		{																																													//----
			case '1':																																										//----
				$RFString = 'MAX(IF(ResultadoSerie.id_inscricao = C.id_inscricao, ResultadoSerie.RSerie, 0))';																				//----
			break;																																											//----
																																															//----
			case '2':																																										//----
				$RFString = '';																																								//----
				for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {																														//----
					if(!empty($RFString))																																					//----
						$RFString = $RFString . ' + ';																																		//----
					$RFString = $RFString . ' MAX(IF(ResultadoSerie.id_inscricao = C.id_inscricao AND ResultadoSerie.nr_etapa_prova = '. $i .', ResultadoSerie.RSerie, 0)) ';				//----
				}																																											//----
				if($options['decimal_prova']==0)																																			//----
					$options['decimal_prova'] = '2';																																		//----
																																															//----
				$RFString = 'ROUND ( ( (' . $RFString . ') / (' . $options['nr_etapa_prova'] . ')), '. $options['decimal_prova'] .' )';														//----
			break;																																											//----
																																															//----
			case '3':																																										//----
				$RFString = '';																																								//----
				for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {																														//----
					if(!empty($RFString))																																					//----
						$RFString = $RFString . ' + ';																																		//----
					$RFString = $RFString . ' MAX(IF(ResultadoSerie.id_inscricao = C.id_inscricao AND ResultadoSerie.nr_etapa_prova = '. $i .', ResultadoSerie.RSerie, 0)) ';				//----
				}																																											//----
			break;																																											//----
		}																																													//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
																																															//----
		$query->select( 'ROUND (( ((' . $RFString . ') * 100)/ (' . $queryRP . ')), 3) AS rAddColsAfter1');																					//----
																																															//----
		// ----------------------------------------------------------------------- END CALC PERCENT GERL -----------------------------------------------------------------------------------------	


		//$query->select( 'ROUND ((  (' . $queryRP . ')), 3) AS rAddColsAfter1');																					//----


		$query->from( '#__ranking_resultado C' );
		$query->innerJoin( '#__ranking_inscricao CC USING( id_inscricao )' );
	//	$query->innerJoin( '#__ranking_inscricao_etapa CCC USING( id_inscricao,  id_etapa )' );

		$query->innerJoin( '#__ranking_campeonato H USING( id_campeonato )' );
		$query->innerJoin( '#__ranking_modalidade HH  USING( id_modalidade )' );
		$query->innerJoin( '#__ranking_prova HHH USING( id_prova )' );

		$query->innerJoin( '#__ranking_genero CCCC USING( id_genero )' );
		$query->innerJoin( '#__ranking_categoria CCCCC USING( id_categoria )' );		
		$query->innerJoin( '#__ranking_classe CCCCCC USING( id_classe )' );
		
		//$query->innerJoin( '#__ranking_profile U USING('. $this->_db->quoteName('id_user').')' );
		$query->innerJoin( '#__intranet_pf UUUU USING('. $this->_db->quoteName('id_user').')' );
		$query->innerJoin( '#__users UU ON('. $this->_db->quoteName('id'). ' = '. $this->_db->quoteName('id_user'). ')' );
		//$query->leftJoin( '#__intranet_cidade UUU USING('. $this->_db->quoteName('id_cidade').')' );
	
		$query->leftJoin( '#__users UUU ON('. $this->_db->quoteName('CC.id_equipe') . '='. $this->_db->quoteName('UUU.id'). ')' );
	
	
	
	
		// ----------------------------------------------------------- Resultado Final (Série) ---------------------------------------------------------------------------------------
																																												//----
		$queryRF = $this->_db->getQuery(true);																																	//----
		switch( $options['rs_etapa_prova'] )																																	//----
		{																																										//----
			case '1':																																							//----
				$queryRF->select( 'MAX(RFRES.value_resultado) AS RSerie');																										//----
			break;																																								//----
																																												//----
			case '2':																																							//----
				$RFString = '';																																					//----
				for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) { 																											//----
					if(!empty($RFString))																																		//----
						$RFString = $RFString . ' + ';																															//----
					$RFString = $RFString . ' MAX(IF(RFRES.ordering = '. $i .', RFRES.value_resultado, NULL)) ';																	//----
				}																																								//----
				if($options['decimal_prova']==0)																																//----
					$options['decimal_prova'] = '2';																															//----
																																												//----
				$queryRF->select( 'ROUND ( ( (' . $RFString . ') / (' . $options['ns_etapa_prova'] . ')), '. $options['decimal_prova'] .' ) AS RSerie');						//----
			break;																																								//----
																																												//----
			case '3':																																							//----
				$RFString = '';																																					//----
				for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) { 																											//----
					if(!empty($RFString))																																		//----
						$RFString = $RFString . ' + ';																															//----
					$RFString = $RFString . ' MAX(IF(RFRES.ordering = '. $i .', RFRES.value_resultado, NULL)) ';																	//----
				}																																								//----
																																												//----
				$queryRF->select( '( ' . $RFString . ' ) AS RSerie');																											//----
			break;																																								//----
		}																																										//----
																																												//----
		$queryRF->select( 'RFRES.id_inscricao');																																//----								
		$queryRF->select( 'RFRES.nr_etapa_prova');																																//----
		$queryRF->select( 'RFRES.ordering');																																	//----
																																												//----
		$queryRF->from( '#__ranking_resultado RFRES' );																															//----
																																												//----
		$queryRF->innerJoin( '#__ranking_inscricao RFINS USING( id_inscricao )' );																								//----
	//	$queryRF->innerJoin( '#__ranking_inscricao_etapa RFIET USING( id_inscricao,  id_etapa )' );																				//----
																																												//----
		$queryRF->innerJoin( '#__ranking_campeonato RFCAM USING( id_campeonato )' );																							//----
		$queryRF->innerJoin( '#__ranking_modalidade RFMOD  USING( id_modalidade )' );																							//----
		$queryRF->innerJoin( '#__ranking_prova RFPRO USING( id_prova )' );																										//----
																																												//----
		$queryRF->innerJoin( '#__ranking_genero RFGEN USING( id_genero )' );																									//----
		$queryRF->innerJoin( '#__ranking_categoria RFCAT USING( id_categoria )' );																								//----
		$queryRF->innerJoin( '#__ranking_classe RFCLA USING( id_classe )' );																									//----
																																												//----
		//$queryRF->innerJoin( '#__ranking_profile RFPER USING('. $this->_db->quoteName('id_user').')' );																			//----
		//$queryRF->innerJoin( '#__users RFUSE ON('. $this->_db->quoteName('id'). ' = '. $this->_db->quoteName('id_user').')' );													//----
																																												//----
																																												//----
		if( !$this->_user->authorise('torneios.diretor.geral', 'com_torneios') )																								//----
			$queryRF->where($this->_db->quoteName('RFMOD.status_modalidade') . ' = ' . $this->_db->quote('1'));																	//----
																																												//----
		if( !$this->_user->authorise('torneios.diretor.modalidade', 'com_torneios') )																							//----
		{																																										//----
			$queryRF->where($this->_db->quoteName('RFCAM.status_campeonato') . ' = ' . $this->_db->quote('1'));																	//----
			$queryRF->where($this->_db->quoteName('RFPRO.status_prova') . ' = ' . $this->_db->quote('1'));																		//----
	//		$queryRF->where($this->_db->quoteName('RFIET.status_inscricao_etapa') . ' = ' . $this->_db->quote('1'));															//----
		}																																										//----
																																												//----
																																												//----
		if(!empty($options['id_classe']))																																		//----
			$queryRF->where('RFINS.id_classe = ' . $this->_db->quote( $options['id_classe'] ));																					//----
																																												//----
		if(!empty($options['id_etapa']))																																		//----
			$queryRF->where('RFRES.id_etapa = ' . $this->_db->quote( $options['id_etapa'] ));																					//----
																																												//----
		if(!empty($options['id_categoria']))																																	//----
			$queryRF->where('RFINS.id_categoria = ' . $this->_db->quote( $options['id_categoria'] ));																			//----
																																												//----
		if(!empty($options['id_genero']))																																		//----
			$queryRF->where('RFINS.id_genero = ' . $this->_db->quote( $options['id_genero'] ));																					//----
																																												//----
		if(!empty($options['id_prova']))																																		//----
			$queryRF->where('RFINS.id_prova = ' . $this->_db->quote( $options['id_prova'] ));																					//----
																																												//----
		$queryRF->group('RFRES.id_inscricao');																																	//----
		$queryRF->group('RFRES.nr_etapa_prova');																																//----
																																												//----
		$query->innerJoin('(' . $queryRF . ') ResultadoSerie USING('. $this->_db->quoteName('id_inscricao').')' );																//----
		// ------------------------------------------------------ Fim Resultado Fina (Série) -----------------------------------------------------------------------------------------
	


		
		if(!empty($options['name']))
			$query->where( $this->_db->quoteName('UU.name') . ' LIKE \'%' . $this->_db->escape($options['name']) . '%\'' );		
		
		if(!empty($options['id_local']))
			$query->where('CCC.id_local = ' . $this->_db->quote( $options['id_local'] ) );		
		
		if(!empty($options['id_classe']))
			$query->where('CC.id_classe = ' . $this->_db->quote( $options['id_classe'] ) );
						
		if(!empty($options['id_categoria']))
			$query->where('CC.id_categoria = ' . $this->_db->quote( $options['id_categoria'] ) );			
			
		if(!empty($options['id_genero']))
			$query->where('CC.id_genero = ' . $this->_db->quote( $options['id_genero'] ) );	
							
		if(!empty($options['id_etapa']))
			$query->where('C.id_etapa = ' . $this->_db->quote( $options['id_etapa'] ) );	

		if(!empty($options['id_prova']))
			$query->where('CC.id_prova = ' . $this->_db->quote( $options['id_prova'] ) );
		
		if( !$this->_user->authorise('torneios.diretor.geral', 'com_torneios') )
			$query->where($this->_db->quoteName('HH.status_modalidade') . ' = ' . $this->_db->quote('1'));	

		if( !$this->_user->authorise('torneios.diretor.modalidade', 'com_torneios') )
		{
			$query->where($this->_db->quoteName('H.status_campeonato') . ' = ' . $this->_db->quote('1'));
			$query->where($this->_db->quoteName('HHH.status_prova') . ' = ' . $this->_db->quote('1'));
		//	$query->where($this->_db->quoteName('CCC.status_inscricao_etapa') . ' = ' . $this->_db->quote('1'));
		}
			
		$query->group($this->_db->quoteName('C.id_inscricao'));
		
		$query->order($this->_db->quoteName('rf') . ' DESC');

		$query->order($this->_db->quoteName('UUUU.id_pf') . ' ASC');
		
		$this->_db->setQuery($query);
		return 	$this->_db->loadObjectList();		
	
	}	

	
	


	function getPosition(  $options = array() )
	{		
	
		$query = $this->_db->getQuery(true);
		/*
		$query->select( $this->_db->quoteName(array( 'C.status_resultado',
											
													 'CC.id_inscricao',
													 'CC.id_prova',
													 'CC.id_user',
													 
													 'CCC.status_inscricao_etapa',
													 'CCC.id_inscricao_etapa',													 
													 
													 'CCCC.id_genero',
													 'CCCC.name_genero',
													 
													 'CCCCC.id_categoria',
													 'CCCCC.name_categoria',
													 
													 'CCCCCC.id_classe',
													 'CCCCCC.name_classe',

													 'UU.name',
													 'UUU.name_cidade',
												    )));


		for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) { 
			for ($x = 1; $x <= $options['ns_etapa_prova']; $x++) { 
				$query->select( 'MAX( IF(C.nr_etapa_prova = ' . $i . ' AND C.ordering = ' . $x . ', C.value_resultado, NULL)) AS r' . $i . 's' . $x);
			}
		}
		
		if($options['nr_etapa_prova']>1 && $options['ns_etapa_prova']>1) :
			for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) { 
			 $query->select( 'MAX(IF(ResultadoSerie.id_inscricao = C.id_inscricao AND ResultadoSerie.nr_etapa_prova = '. $i .', ResultadoSerie.RSerie, NULL)) AS rs' . $i);
			}
		endif;

		switch( $options['rf_etapa_prova'] )
		{
			case '1':
				$query->select( 'MAX(IF(ResultadoSerie.id_inscricao = C.id_inscricao, ResultadoSerie.RSerie, 0)) AS rf');
			break;

			case '2':
				$RFString = '';
				for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {
					if(!empty($RFString))
						$RFString = $RFString . ' + ';
					$RFString = $RFString . ' MAX(IF(ResultadoSerie.id_inscricao = C.id_inscricao AND ResultadoSerie.nr_etapa_prova = '. $i .', ResultadoSerie.RSerie, NULL)) ';
				}
				if($options['decimal_prova']==0)
					$options['decimal_prova'] = '2';

				$query->select( 'ROUND ( ( (' . $RFString . ') / (' . $options['nr_etapa_prova'] . ')), '. $options['decimal_prova'] .' ) AS rf');

			break;

			case '3':
				$RFString = '';
				for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {
					if(!empty($RFString))
						$RFString = $RFString . ' + ';
					$RFString = $RFString . ' MAX(IF(ResultadoSerie.id_inscricao = C.id_inscricao AND ResultadoSerie.nr_etapa_prova = '. $i .', ResultadoSerie.RSerie, NULL)) ';
				}

				$query->select( '( ' . $RFString . ' ) AS rf');
			break;
		}
			*/				
		/*
		$queryResultSerie->select( 'ReSeInsEta.id_local AS id_locals' );																											//----
		$queryResultSerie->select( 'ReSeUsers.name AS names' );																														//----
		*/

		//------------------------------------------------------------------------------------ CALC RANKING --------------------------------------------------------------------------------------
																																															//----
		$queryRankResults = $this->_db->getQuery(true);																																		//----
																																															//----
		$queryRankResults->select( 'RANPROF.matricula AS RankMatricula');																													//----
																																															//----
		for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) {  																															//----
			$queryRankResults->select( 'MAX(IF( RANRESU.ordering = ' . $i . ', RANRESU.value_resultado, NULL)) AS RankS' . $i);																//----
		}																																													//----
																																															//----
		switch( $options['rs_etapa_prova'] )																																				//----
		{																																													//----
			case '1':																																										//----
				$queryRankResults->select( 'MAX(RANRESU.value_resultado) AS RankRF');																										//----
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
				$queryRankResults->select( 'ROUND ( ( (' . $RFString . ') / (' . $options['ns_etapa_prova'] . ')), '. $options['decimal_prova'] .' ) AS RankRF');							//----
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
				$queryRankResults->select( '( ' . $RFString . ' ) AS RankRF');																												//----
			break;																																											//----
		}																																													//----
																																															//----
		$queryRankResults->select( 'id_inscricao');																																			//----
		$queryRankResults->select( 'RANRESU.nr_etapa_prova');																																//----
																																															//----
		$queryRankResults->from( '#__ranking_resultado RANRESU' );																															//----
																																															//----
		$queryRankResults->innerJoin( '#__ranking_inscricao RANINSC USING( id_inscricao )' );																								//----
																																															//----
		$queryRankResults->innerJoin( '#__ranking_inscricao_etapa RANIETA USING( id_inscricao,  id_etapa )' );																				//----
																																															//----
		$queryRankResults->innerJoin( '#__ranking_campeonato RANCAMP USING( id_campeonato )' );																								//----
		$queryRankResults->innerJoin( '#__ranking_modalidade RANMODA  USING( id_modalidade )' );																							//----
		$queryRankResults->innerJoin( '#__ranking_prova RANPROV USING( id_prova )' );																										//----
																																															//----
		$queryRankResults->innerJoin( '#__ranking_genero RANGENE USING( id_genero )' );																										//----
		$queryRankResults->innerJoin( '#__ranking_categoria RANCATE USING( id_categoria )' );																								//----
		$queryRankResults->innerJoin( '#__ranking_classe RANCLAS USING( id_classe )' );																										//----
																																															//----
		$queryRankResults->innerJoin( '#__ranking_profile RANPROF USING('. $this->_db->quoteName('id_user').')' );																			//----
		$queryRankResults->innerJoin( '#__users RANUSER ON('. $this->_db->quoteName('id'). ' = '. $this->_db->quoteName('id_user').')' );													//----
																																															//----
		$queryRankResults->where( '(' . $this->_db->quoteName('block') . '=' . $this->_db->quote( '0' ) . ' OR ' .  $this->_db->quoteName('ano_campeonato') . ' < YEAR(CURDATE()) )' );		//----
																																															//----
		//if( !$this->_user->authorise('torneios.diretor.geral', 'com_torneios') )																											//----
			$queryRankResults->where($this->_db->quoteName('RANMODA.status_modalidade') . ' = ' . $this->_db->quote('1'));																	//----
																																															//----
		//if( !$this->_user->authorise('torneios.diretor.modalidade', 'com_torneios') )																										//----
		//{																																													//----
			$queryRankResults->where($this->_db->quoteName('RANCAMP.status_campeonato') . ' = ' . $this->_db->quote('1'));																	//----
			$queryRankResults->where($this->_db->quoteName('RANPROV.status_prova') . ' = ' . $this->_db->quote('1'));																		//----
			$queryRankResults->where($this->_db->quoteName('RANIETA.status_inscricao_etapa') . ' = ' . $this->_db->quote('1'));																//----
		//}																																													//----
																																															//----
		if(!empty($options['id_classe']))																																					//----
			$queryRankResults->where('RANINSC.id_classe = ' . $this->_db->quote( $options['id_classe'] ));																					//----
																																															//----
		if(!empty($options['id_etapa']))																																					//----
			$queryRankResults->where('RANRESU.id_etapa = ' . $this->_db->quote( $options['id_etapa'] ));																					//----
																																															//----
		if(!empty($options['id_categoria']))																																				//----
			$queryRankResults->where('RANINSC.id_categoria = ' . $this->_db->quote( $options['id_categoria'] ));																			//----
																																															//----
		if(!empty($options['id_genero']))																																					//----
			$queryRankResults->where('RANINSC.id_genero = ' . $this->_db->quote( $options['id_genero'] ));																					//----
																																															//----
		if(!empty($options['id_prova']))																																					//----
			$queryRankResults->where('RANINSC.id_prova = ' . $this->_db->quote( $options['id_prova'] ));																					//----
																																															//----
		$queryRankResults->group('RANRESU.id_inscricao');																																	//----
		$queryRankResults->group('RANRESU.nr_etapa_prova');																																	//----
																																															//----
		$queryRankResults->order('RankRF DESC');																																			//----
		for ($i = $options['ns_etapa_prova']; $i > 0; $i--) { 																																//----
			$queryRankResults->order('RankS' . $i . ' DESC');																																//----
		}																																													//----
																																															//----
		$queryRankResults->order($this->_db->quoteName('RankMatricula') . ' ASC');																											//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
																																															//----
		$queryRankGroup = $this->_db->getQuery(true);																																		//----
																																															//----
																																															//----
		switch( $options['rf_etapa_prova'] )																																				//----
		{																																													//----
			case '1':																																										//----
				$queryRankGroup->select( 'MAX(GrupoRanking.RankRF) AS RankRF');																												//----
			break;																																											//----
																																															//----
			case '2':																																										//----
				$RFString = '';																																								//----
				for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {																														//----
					if(!empty($RFString))																																					//----
						$RFString = $RFString . ' + ';																																		//----
					$RFString = $RFString . '  MAX(IF( GrupoRanking.nr_etapa_prova = '. $i .', GrupoRanking.RankRF, NULL))  ';																//----
				}																																											//----
				if($options['decimal_prova']==0)																																			//----
					$options['decimal_prova'] = '2';																																		//----
																																															//----
				$queryRankGroup->select( 'ROUND ( ( (' . $RFString . ') / (' . $options['nr_etapa_prova'] . ')), '. $options['decimal_prova'] .' ) AS RankRF');								//----
			break;																																											//----
																																															//----
			case '3':																																										//----																				
				$RFString = '';																																								//----
				for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {																														//----
					if(!empty($RFString))																																					//----
						$RFString = $RFString . ' + ';																																		//----
					$RFString = $RFString . ' MAX(IF( GrupoRanking.nr_etapa_prova = '. $i .', GrupoRanking.RankRF, NULL)) ';																//----
				}																																											//----
				$queryRankGroup->select( '( ' . $RFString . ' ) AS RankRF');																												//----
			break;																																											//----
		}																																													//----
																																															//----
		$queryRankGroup->select('RankMatricula');																																			//----
		$queryRankGroup->from( '(' . $queryRankResults . ') GrupoRanking' );																												//----
																																															//----
		$queryRankGroup->group('id_inscricao');																																				//----
																																															//----
		$queryRankGroup->order($this->_db->quoteName('RankRF') . ' DESC');																													//----
		$queryRankGroup->order($this->_db->quoteName('RankMatricula') . ' ASC');																											//----
																																															//----
		$queryRank = $this->_db->getQuery(true);																																			//----
																																															//----
		switch( $options['type_prova'] )																																					//----
		{																																													//----
			case '1':																																										//----
			case '4':																																										//----
				$queryRank->select( 'GROUP_CONCAT( ResultadoRank.RankMatricula )' );																										//----								
				$queryRank->from( '(' . $queryRankGroup . ') ResultadoRank' );																												//----
				$query->select( 'FIND_IN_SET( U.matricula, (' . $queryRank . ') ) AS rank');																								//----
			break;																																											//----
																																															//----
			case '2':																																										//----
			case '3':																																										//----
			default:																																										//----
				$queryRank = $this->_db->getQuery(true);																																	//----
				$queryRank->select( 'GROUP_CONCAT( ResultadoRank.RankRF ORDER BY ResultadoRank.RankRF DESC )' );																			//----								
				$queryRank->from( '(' . $queryRankGroup . ') ResultadoRank' );																												//----
																																															//----
				switch( $options['rf_etapa_prova'] )																																		//----
				{																																											//----
					case '1':																																								//----
						$RFString = 'MAX(IF(ResultadoSerie.id_inscricao = C.id_inscricao, ResultadoSerie.RSerie, 0))';																		//----
					break;																																									//----
																																															//----
					case '2':																																								//----
						$RFString = '';																																						//----
						for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {																												//----
							if(!empty($RFString))																																			//----
								$RFString = $RFString . ' + ';																																//----
							$RFString = $RFString . ' MAX(IF(ResultadoSerie.id_inscricao = C.id_inscricao AND ResultadoSerie.nr_etapa_prova = '. $i .', ResultadoSerie.RSerie, NULL)) ';	//----
						}																																									//----
						if($options['decimal_prova']==0)																																	//----
							$options['decimal_prova'] = '2';																																//----
																																															//----
						$RFString = 'ROUND ( ( (' . $RFString . ') / (' . $options['nr_etapa_prova'] . ')), '. $options['decimal_prova'] .' )';												//----
																																															//----
					break;																																									//----
																																															//----
					case '3':																																								//----
						$RFString = '';																																						//----
						for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {																												//----
							if(!empty($RFString))																																			//----
								$RFString = $RFString . ' + ';																																//----
							$RFString = $RFString . ' MAX(IF(ResultadoSerie.id_inscricao = C.id_inscricao AND ResultadoSerie.nr_etapa_prova = '. $i .', ResultadoSerie.RSerie, NULL)) ';	//----
						}																																									//----
																																															//----
						$RFString = '( ' . $RFString . ' )';																																//----
					break;																																									//----
				}																																											//----
				$query->select( 'FIND_IN_SET( ('.$RFString.'), (' . $queryRank . ') ) AS rank');																							//----
			break;																																											//----
		}																																													//----
		//------------------------------------------------------------------------------ END CALC RANK -------------------------------------------------------------------------------------------


		// ----------------------------------------------------------------------- END CALC PERCENT GERL -----------------------------------------------------------------------------------------	

		$query->from( '#__ranking_resultado C' );
		$query->innerJoin( '#__ranking_inscricao CC USING( id_inscricao )' );
		$query->innerJoin( '#__ranking_inscricao_etapa CCC USING( id_inscricao,  id_etapa )' );
		
		
		$query->innerJoin( '#__ranking_campeonato H USING( id_campeonato )' );
		$query->innerJoin( '#__ranking_modalidade HH  USING( id_modalidade )' );
		$query->innerJoin( '#__ranking_prova HHH USING( id_prova )' );

		$query->innerJoin( '#__ranking_genero CCCC USING( id_genero )' );
		$query->innerJoin( '#__ranking_categoria CCCCC USING( id_categoria )' );		
		$query->innerJoin( '#__ranking_classe CCCCCC USING( id_classe )' );
		
		$query->innerJoin( '#__ranking_profile U USING('. $this->_db->quoteName('id_user').')' );
		$query->innerJoin( '#__users UU ON('. $this->_db->quoteName('id'). ' = '. $this->_db->quoteName('id_user'). ')' );
		$query->leftJoin( '#__ranking_cidade UUU USING('. $this->_db->quoteName('id_cidade').')' );
	
	
	
		// ----------------------------------------------------------- Resultado Final (Série) ---------------------------------------------------------------------------------------
																																												//----
		$queryRF = $this->_db->getQuery(true);																																	//----
		switch( $options['rs_etapa_prova'] )																																	//----
		{																																										//----
			case '1':																																							//----
				$queryRF->select( 'MAX(RFRES.value_resultado) AS RSerie');																										//----
			break;																																								//----
																																												//----
			case '2':																																							//----
				$RFString = '';																																					//----
				for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) { 																											//----
					if(!empty($RFString))																																		//----
						$RFString = $RFString . ' + ';																															//----
					$RFString = $RFString . ' MAX(IF(RFRES.ordering = '. $i .', RFRES.value_resultado, 0)) ';																	//----
				}																																								//----
				if($options['decimal_prova']==0)																																//----
					$options['decimal_prova'] = '2';																															//----
																																												//----
				$queryRF->select( 'ROUND ( ( (' . $RFString . ') / (' . $options['ns_etapa_prova'] . ')), '. $options['decimal_prova'] .' ) AS RSerie');						//----
			break;																																								//----
																																												//----
			case '3':																																							//----
				$RFString = '';																																					//----
				for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) { 																											//----
					if(!empty($RFString))																																		//----
						$RFString = $RFString . ' + ';																															//----
					$RFString = $RFString . ' MAX(IF(RFRES.ordering = '. $i .', RFRES.value_resultado, 0)) ';																	//----
				}																																								//----
																																												//----
				$queryRF->select( '( ' . $RFString . ' ) AS RSerie');																											//----
			break;																																								//----
		}																																										//----
																																												//----
		$queryRF->select( 'RFRES.id_inscricao');																																//----
		$queryRF->select( 'RFRES.nr_etapa_prova');																																//----
		$queryRF->select( 'RFRES.ordering');																																	//----
																																												//----
		$queryRF->from( '#__ranking_resultado RFRES' );																															//----
																																												//----
		$queryRF->innerJoin( '#__ranking_inscricao RFINS USING( id_inscricao )' );																								//----
		$queryRF->innerJoin( '#__ranking_inscricao_etapa RFIET USING( id_inscricao,  id_etapa )' );																				//----
																																												//----
		$queryRF->innerJoin( '#__ranking_campeonato RFCAM USING( id_campeonato )' );																							//----
		$queryRF->innerJoin( '#__ranking_modalidade RFMOD  USING( id_modalidade )' );																							//----
		$queryRF->innerJoin( '#__ranking_prova RFPRO USING( id_prova )' );																										//----
																																												//----
		$queryRF->innerJoin( '#__ranking_genero RFGEN USING( id_genero )' );																									//----
		$queryRF->innerJoin( '#__ranking_categoria RFCAT USING( id_categoria )' );																								//----
		$queryRF->innerJoin( '#__ranking_classe RFCLA USING( id_classe )' );																									//----
																																												//----
		//$queryRF->innerJoin( '#__ranking_profile RFPER USING('. $this->_db->quoteName('id_user').')' );																			//----
		$queryRF->innerJoin( '#__users RFUSE ON('. $this->_db->quoteName('id'). ' = '. $this->_db->quoteName('id_user').')' );													//----
																																												//----
		$queryRF->where( '(' . $this->_db->quoteName('block') . '=' . $this->_db->quote( '0' ) . ' OR ' .  $this->_db->quoteName('ano_campeonato') . ' < YEAR(CURDATE()) )' );	//----
																																												//----
		if( !$this->_user->authorise('torneios.diretor.geral', 'com_torneios') )																								//----
			$queryRF->where($this->_db->quoteName('RFMOD.status_modalidade') . ' = ' . $this->_db->quote('1'));																	//----
																																												//----
		if( !$this->_user->authorise('torneios.diretor.modalidade', 'com_torneios') )																							//----
		{																																										//----
			$queryRF->where($this->_db->quoteName('RFCAM.status_campeonato') . ' = ' . $this->_db->quote('1'));																	//----
			$queryRF->where($this->_db->quoteName('RFPRO.status_prova') . ' = ' . $this->_db->quote('1'));																		//----
			$queryRF->where($this->_db->quoteName('RFIET.status_inscricao_etapa') . ' = ' . $this->_db->quote('1'));															//----
		}																																										//----
																																												//----
																																												//----
		if(!empty($options['id_classe']))																																		//----
			$queryRF->where('RFINS.id_classe = ' . $this->_db->quote( $options['id_classe'] ));																					//----
																																												//----
		if(!empty($options['id_etapa']))																																		//----
			$queryRF->where('RFRES.id_etapa = ' . $this->_db->quote( $options['id_etapa'] ));																					//----
																																												//----
		if(!empty($options['id_categoria']))																																	//----
			$queryRF->where('RFINS.id_categoria = ' . $this->_db->quote( $options['id_categoria'] ));																			//----
																																												//----
		if(!empty($options['id_genero']))																																		//----
			$queryRF->where('RFINS.id_genero = ' . $this->_db->quote( $options['id_genero'] ));																					//----
																																												//----
		if(!empty($options['id_prova']))																																		//----
			$queryRF->where('RFINS.id_prova = ' . $this->_db->quote( $options['id_prova'] ));																					//----
																																												//----
		$queryRF->group('RFRES.id_inscricao');																																	//----
		$queryRF->group('RFRES.nr_etapa_prova');																																//----
																																												//----
		$query->innerJoin('(' . $queryRF . ') ResultadoSerie USING('. $this->_db->quoteName('id_inscricao').')' );																//----
		// ------------------------------------------------------ Fim Resultado Fina (Série) -----------------------------------------------------------------------------------------
	

		if( $options['id_user'] )
			$query->where('CC.id_user = ' . $this->_db->quote( $options['id_user'] ) );		

		if(!empty($options['id_local']))
			$query->where('CCC.id_local = ' . $this->_db->quote( $options['id_local'] ) );		
		
		if(!empty($options['id_classe']))
			$query->where('CC.id_classe = ' . $this->_db->quote( $options['id_classe'] ) );
						
		if(!empty($options['id_categoria']))
			$query->where('CC.id_categoria = ' . $this->_db->quote( $options['id_categoria'] ) );			
			
		if(!empty($options['id_genero']))
			$query->where('CC.id_genero = ' . $this->_db->quote( $options['id_genero'] ) );	
							
		if(!empty($options['id_etapa']))
			$query->where('C.id_etapa = ' . $this->_db->quote( $options['id_etapa'] ) );	

		if(!empty($options['id_prova']))
			$query->where('CC.id_prova = ' . $this->_db->quote( $options['id_prova'] ) );
		
		$query->where( '(' . $this->_db->quoteName('block') . '=' . $this->_db->quote( '0' ) . ' OR ' .  $this->_db->quoteName('ano_campeonato') . ' < YEAR(CURDATE()) )' );

		if( !$this->_user->authorise('torneios.diretor.geral', 'com_torneios') )
			$query->where($this->_db->quoteName('HH.status_modalidade') . ' = ' . $this->_db->quote('1'));	

		if( !$this->_user->authorise('torneios.diretor.modalidade', 'com_torneios') )
		{
			$query->where($this->_db->quoteName('H.status_campeonato') . ' = ' . $this->_db->quote('1'));
			$query->where($this->_db->quoteName('HHH.status_prova') . ' = ' . $this->_db->quote('1'));
			$query->where($this->_db->quoteName('CCC.status_inscricao_etapa') . ' = ' . $this->_db->quote('1'));
		}
			
		$query->group($this->_db->quoteName('C.id_inscricao'));
		
		//$query->order($this->_db->quoteName('rf') . ' DESC');

		//$query->order($this->_db->quoteName('U.matricula') . ' ASC');
		
		$this->_db->setQuery($query);
		

		return 	$this->_db->loadResult();
	

	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
		$query = $this->_db->getQuery(true);					

		//------------------------------------------------------------------------------------ CALC RANKING --------------------------------------------------------------------------------------
																																															//----
		$queryRankResults = $this->_db->getQuery(true);																																		//----
																																															//----
		$queryRankResults->select( 'RANPROF.matricula AS RankMatricula');																													//----
																																															//----
		for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) {  																															//----
			$queryRankResults->select( 'MAX(IF( RANRESU.ordering = ' . $i . ', RANRESU.value_resultado, NULL)) AS RankS' . $i);																//----
		}																																													//----
																																															//----
		switch( $options['rs_etapa_prova'] )																																				//----
		{																																													//----
			case '1':																																										//----
				$queryRankResults->select( 'MAX(RANRESU.value_resultado) AS RankRF');																										//----
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
				$queryRankResults->select( 'ROUND ( ( (' . $RFString . ') / (' . $options['ns_etapa_prova'] . ')), '. $options['decimal_prova'] .' ) AS RankRF');							//----
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
				$queryRankResults->select( '( ' . $RFString . ' ) AS RankRF');																												//----
			break;																																											//----
		}																																													//----
																																															//----
		$queryRankResults->select( 'id_inscricao');																																			//----
																																															//----
		$queryRankResults->from( '#__ranking_resultado RANRESU' );																															//----
																																															//----
		$queryRankResults->innerJoin( '#__ranking_inscricao RANINSC USING( id_inscricao )' );																								//----
																																															//----
		$queryRankResults->innerJoin( '#__ranking_inscricao_etapa RANIETA USING( id_inscricao,  id_etapa )' );																				//----
																																															//----
		$queryRankResults->innerJoin( '#__ranking_campeonato RANCAMP USING( id_campeonato )' );																								//----
		$queryRankResults->innerJoin( '#__ranking_modalidade RANMODA  USING( id_modalidade )' );																							//----
		$queryRankResults->innerJoin( '#__ranking_prova RANPROV USING( id_prova )' );																										//----
																																															//----
		$queryRankResults->innerJoin( '#__ranking_genero RANGENE USING( id_genero )' );																										//----
		$queryRankResults->innerJoin( '#__ranking_categoria RANCATE USING( id_categoria )' );																								//----
		$queryRankResults->innerJoin( '#__ranking_classe RANCLAS USING( id_classe )' );																										//----
																																															//----
		//$queryRankResults->innerJoin( '#__ranking_profile RANPROF USING('. $this->_db->quoteName('id_user').')' );																			//----
		$queryRankResults->innerJoin( '#__users RANUSER ON('. $this->_db->quoteName('id'). ' = '. $this->_db->quoteName('id_user').')' );													//----
																																															//----
		$queryRankResults->where( '(' . $this->_db->quoteName('block') . '=' . $this->_db->quote( '0' ) . ' OR ' .  $this->_db->quoteName('ano_campeonato') . ' < YEAR(CURDATE()) )' );		//----
																																															//----
		if( !$this->_user->authorise('torneios.diretor.geral', 'com_torneios') )																											//----
			$queryRankResults->where($this->_db->quoteName('RANMODA.status_modalidade') . ' = ' . $this->_db->quote('1'));																	//----
																																															//----
		if( !$this->_user->authorise('torneios.diretor.modalidade', 'com_torneios') )																										//----
		{																																													//----
			$queryRankResults->where($this->_db->quoteName('RANCAMP.status_campeonato') . ' = ' . $this->_db->quote('1'));																	//----
			$queryRankResults->where($this->_db->quoteName('RANPROV.status_prova') . ' = ' . $this->_db->quote('1'));																		//----
			$queryRankResults->where($this->_db->quoteName('RANIETA.status_inscricao_etapa') . ' = ' . $this->_db->quote('1'));																//----
		}																																													//----
																																															//----
		if(!empty($options['id_classe']))																																					//----
			$queryRankResults->where('RANINSC.id_classe = ' . $this->_db->quote( $options['id_classe'] ));																					//----
																																															//----
		if(!empty($options['id_etapa']))																																					//----
			$queryRankResults->where('RANRESU.id_etapa = ' . $this->_db->quote( $options['id_etapa'] ));																					//----
																																															//----
		if(!empty($options['id_categoria']))																																				//----
			$queryRankResults->where('RANINSC.id_categoria = ' . $this->_db->quote( $options['id_categoria'] ));																			//----
																																															//----
		if(!empty($options['id_genero']))																																					//----
			$queryRankResults->where('RANINSC.id_genero = ' . $this->_db->quote( $options['id_genero'] ));																					//----
																																															//----
		if(!empty($options['id_prova']))																																					//----
			$queryRankResults->where('RANINSC.id_prova = ' . $this->_db->quote( $options['id_prova'] ));																					//----
																																															//----
		$queryRankResults->group('RANRESU.id_inscricao');																																	//----
		$queryRankResults->group('RANRESU.nr_etapa_prova');																																	//----
																																															//----
		$queryRankResults->order('RankRF DESC');																																			//----
		for ($i = $options['ns_etapa_prova']; $i > 0; $i--) { 																																//----
			$queryRankResults->order('RankS' . $i . ' DESC');																																//----
		}																																													//----
																																															//----
		$queryRankResults->order($this->_db->quoteName('RankMatricula') . ' ASC');																											//----
																																															//----
		$queryRankGroup = $this->_db->getQuery(true);																																		//----
																																															//----
		for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) {  																															//----
			$queryRankGroup->select('RankS' . $i);																																			//----
		}																																													//----
		$queryRankGroup->select('RankRF');																																					//----
		$queryRankGroup->select('RankMatricula');																																			//----
		$queryRankGroup->from( '(' . $queryRankResults . ') GrupoRanking' );																												//----
																																															//----
		$queryRankGroup->group('id_inscricao');																																				//----
																																															//----
		$queryRankGroup->order($this->_db->quoteName('RankRF') . ' DESC');																													//----
		for ($i = $options['ns_etapa_prova']; $i > 0; $i--) { 																																//----
			$queryRankGroup->order('RankS' . $i . ' DESC');																																	//----
		}																																													//----
																																															//----
		$queryRankGroup->order($this->_db->quoteName('RankMatricula') . ' ASC');																											//----
																																															//----
																																															//----
		$queryRank = $this->_db->getQuery(true);																																			//----
																																															//----
		switch( $options['type_prova'] )																																					//----
		{																																													//----
			case '1':																																										//----
			case '4':																																										//----
				$queryRank->select( 'GROUP_CONCAT( ResultadoRank.RankMatricula )' );																										//----								
				$queryRank->from( '(' . $queryRankGroup . ') ResultadoRank' );																												//----
				$query->select( 'FIND_IN_SET( U.matricula, (' . $queryRank . ') ) AS rank');																								//----
			break;																																											//----
																																															//----
			case '2':																																										//----
			case '3':																																										//----
				$queryRank = $this->_db->getQuery(true);																																	//----
				$queryRank->select( 'GROUP_CONCAT( ResultadoRank.RankRF ORDER BY ResultadoRank.RankRF DESC )' );																			//----								
				$queryRank->from( '(' . $queryRankGroup . ') ResultadoRank' );																												//----
				$query->select( 'FIND_IN_SET( MAX(IF(ResultadoSerie.id_inscricao = C.id_inscricao, ResultadoSerie.RSerie, 0)), (' . $queryRank . ') ) AS rank');							//----
			break;																																											//----
		}																																													//----
		//------------------------------------------------------------------------------ END CALC RANK -------------------------------------------------------------------------------------------	
			

		$query->from( '#__ranking_resultado C' );
		$query->innerJoin( '#__ranking_inscricao CC USING( id_inscricao )' );
		$query->innerJoin( '#__ranking_inscricao_etapa CCC USING( id_inscricao,  id_etapa )' );
		
		$query->innerJoin( '#__ranking_campeonato H USING( id_campeonato )' );
		$query->innerJoin( '#__ranking_modalidade HH  USING( id_modalidade )' );
		$query->innerJoin( '#__ranking_prova HHH USING( id_prova )' );

		$query->innerJoin( '#__ranking_genero CCCC USING( id_genero )' );
		$query->innerJoin( '#__ranking_categoria CCCCC USING( id_categoria )' );		
		$query->innerJoin( '#__ranking_classe CCCCCC USING( id_classe )' );
		
		//$query->innerJoin( '#__ranking_profile U USING('. $this->_db->quoteName('id_user').')' );
		$query->innerJoin( '#__users UU ON('. $this->_db->quoteName('id'). ' = '. $this->_db->quoteName('id_user').')' );
		$query->leftJoin( '#__ranking_cidade UUU USING('. $this->_db->quoteName('id_cidade').')' );		
		
		
	
		// ----------------------------------------------------------- Resultado Final (Série) ---------------------------------------------------------------------------------------
																																												//----
		$queryRF = $this->_db->getQuery(true);																																	//----
		switch( $options['rs_etapa_prova'] )																																	//----
		{																																										//----
			case '1':																																							//----
				$queryRF->select( 'MAX(RFRES.value_resultado) AS RSerie');																										//----
			break;																																								//----
																																												//----
			case '2':																																							//----
				$RFString = '';																																					//----
				for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) { 																											//----
					if(!empty($RFString))																																		//----
						$RFString = $RFString . ' + ';																															//----
					$RFString = $RFString . ' MAX(IF(RFRES.ordering = '. $i .', RFRES.value_resultado, 0)) ';																	//----
				}																																								//----
				if($options['decimal_prova']==0)																																//----
					$options['decimal_prova'] = '2';																															//----
																																												//----
				$queryRF->select( 'ROUND ( ( (' . $RFString . ') / (' . $options['ns_etapa_prova'] . ')), '. $options['decimal_prova'] .' ) AS RSerie');						//----
			break;																																								//----
																																												//----
			case '3':																																							//----
				$RFString = '';																																					//----
				for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) { 																											//----
					if(!empty($RFString))																																		//----
						$RFString = $RFString . ' + ';																															//----
					$RFString = $RFString . ' MAX(IF(RFRES.ordering = '. $i .', RFRES.value_resultado, 0)) ';																	//----
				}																																								//----
																																												//----
				$queryRF->select( '( ' . $RFString . ' ) AS RSerie');																											//----
			break;																																								//----
		}																																										//----
																																												//----
		$queryRF->select( 'RFRES.id_inscricao');																																//----
		$queryRF->select( 'RFRES.nr_etapa_prova');																																//----
		$queryRF->select( 'RFRES.ordering');																																	//----
																																												//----
		$queryRF->from( '#__ranking_resultado RFRES' );																															//----
																																												//----
		$queryRF->innerJoin( '#__ranking_inscricao RFINS USING( id_inscricao )' );																								//----
		$queryRF->innerJoin( '#__ranking_inscricao_etapa RFIET USING( id_inscricao,  id_etapa )' );																				//----
																																												//----
		$queryRF->innerJoin( '#__ranking_campeonato RFCAM USING( id_campeonato )' );																							//----
		$queryRF->innerJoin( '#__ranking_modalidade RFMOD  USING( id_modalidade )' );																							//----
		$queryRF->innerJoin( '#__ranking_prova RFPRO USING( id_prova )' );																										//----
																																												//----
		$queryRF->innerJoin( '#__ranking_genero RFGEN USING( id_genero )' );																									//----
		$queryRF->innerJoin( '#__ranking_categoria RFCAT USING( id_categoria )' );																								//----
		$queryRF->innerJoin( '#__ranking_classe RFCLA USING( id_classe )' );																									//----
																																												//----
	//	$queryRF->innerJoin( '#__ranking_profile RFPER USING('. $this->_db->quoteName('id_user').')' );																			//----
		$queryRF->innerJoin( '#__users RFUSE ON('. $this->_db->quoteName('id'). ' = '. $this->_db->quoteName('id_user').')' );													//----
																																												//----
		$queryRF->where( '(' . $this->_db->quoteName('block') . '=' . $this->_db->quote( '0' ) . ' OR ' .  $this->_db->quoteName('ano_campeonato') . ' < YEAR(CURDATE()) )' );	//----
																																												//----
		if( !$this->_user->authorise('torneios.diretor.geral', 'com_torneios') )																								//----
			$queryRF->where($this->_db->quoteName('RFMOD.status_modalidade') . ' = ' . $this->_db->quote('1'));																	//----
																																												//----
		if( !$this->_user->authorise('torneios.diretor.modalidade', 'com_torneios') )																							//----
		{																																										//----
			$queryRF->where($this->_db->quoteName('RFCAM.status_campeonato') . ' = ' . $this->_db->quote('1'));																	//----
			$queryRF->where($this->_db->quoteName('RFPRO.status_prova') . ' = ' . $this->_db->quote('1'));																		//----
			$queryRF->where($this->_db->quoteName('RFIET.status_inscricao_etapa') . ' = ' . $this->_db->quote('1'));															//----
		}																																										//----
																																												//----
																																												//----
		if(!empty($options['id_classe']))																																		//----
			$queryRF->where('RFINS.id_classe = ' . $this->_db->quote( $options['id_classe'] ));																					//----
																																												//----
		if(!empty($options['id_etapa']))																																		//----
			$queryRF->where('RFRES.id_etapa = ' . $this->_db->quote( $options['id_etapa'] ));																					//----
																																												//----
		if(!empty($options['id_categoria']))																																	//----
			$queryRF->where('RFINS.id_categoria = ' . $this->_db->quote( $options['id_categoria'] ));																			//----
																																												//----
		if(!empty($options['id_genero']))																																		//----
			$queryRF->where('RFINS.id_genero = ' . $this->_db->quote( $options['id_genero'] ));																					//----
																																												//----
		if(!empty($options['id_prova']))																																		//----
			$queryRF->where('RFINS.id_prova = ' . $this->_db->quote( $options['id_prova'] ));																					//----
																																												//----
		$queryRF->group('RFRES.id_inscricao');																																	//----
		$queryRF->group('RFRES.nr_etapa_prova');																																//----
																																												//----
		$query->innerJoin('(' . $queryRF . ') ResultadoSerie USING('. $this->_db->quoteName('id_inscricao').')' );																//----
		// ------------------------------------------------------ Fim Resultado Fina (Série) -----------------------------------------------------------------------------------------	
		
		
		if( $options['id_user'] )
			$query->where('CC.id_user = ' . $this->_db->quote( $options['id_user'] ) );		
		
		if( $options['id_classe'] )
			$query->where('CC.id_classe = ' . $this->_db->quote( $options['id_classe'] ) );			
					
		if( $options['id_etapa'] )
			$query->where('C.id_etapa = ' . $this->_db->quote( $options['id_etapa'] ) );	

		if( $options['id_categoria'] )
			$query->where('CC.id_categoria = ' . $this->_db->quote( $options['id_categoria'] ) );			
			
		if( $options['id_genero'] )
			$query->where('CC.id_genero = ' . $this->_db->quote( $options['id_genero'] ) );				
		
		if( $options['id_prova'] )
			$query->where('CC.id_prova = ' . $this->_db->quote( $options['id_prova'] ) );

		
		$query->where( '(' . $this->_db->quoteName('block') . '=' . $this->_db->quote( '0' ) . ' OR ' .  $this->_db->quoteName('ano_campeonato') . ' < YEAR(CURDATE()) )' );

		if( !$this->_user->authorise('torneios.diretor.geral', 'com_torneios') )
			$query->where($this->_db->quoteName('HH.status_modalidade') . ' = ' . $this->_db->quote('1'));	

		if( !$this->_user->authorise('torneios.diretor.modalidade', 'com_torneios') )
		{
			$query->where($this->_db->quoteName('H.status_campeonato') . ' = ' . $this->_db->quote('1'));
			$query->where($this->_db->quoteName('HHH.status_prova') . ' = ' . $this->_db->quote('1'));
			$query->where($this->_db->quoteName('CCC.status_inscricao_etapa') . ' = ' . $this->_db->quote('1'));
		}
					
		$query->group($this->_db->quoteName('C.id_inscricao'));
		
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
