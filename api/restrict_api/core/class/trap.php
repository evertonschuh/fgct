<?php 

defined('_JEXEC') or die('Restricted access');

class EASistemasClassesTrap {

	const addColsBeforeProva = 2;
	const addColsAfterProva = 0;
	
	const addColsBeforeEtapa = 0;
	const addColsAfterEtapa = 0;
	
	const addColsBeforeClasse = 1;
	const addColsAfterClasse = 0;
	
	const addColsBeforeEquipe = 1;
	const addColsAfterEquipe = 0;

	const additionalAgenda = 1;

	const tableClassVisivle = true;
	const specialEtapa = true;
	const multiLocal = true;
	const useFinal = false;
	const tagLanguage =  'TRAP_';

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
		$document->addStyleSheet( JPATH_API .DS. 'core' .DS. 'css' .DS. 'trap.css?v=' . $token );	
		$document->addScript( JPATH_API .DS. 'core' .DS. 'js' .DS. 'trap.js?v=' . $token  );	
		

		$extension = 'com_easistemas';
		$language_tag = 'pt-BR';
		$reload = true;
		$base_dir = JPATH_SITE;	
		$base_dir_custon = JPATH_API .DS. 'core';	
		$extension_custon = $extension . '_trap';
		
		$lang = JFactory::getLanguage();
		$lang->load($extension, $base_dir, $language_tag, false);
		$lang->load($extension_custon, $base_dir_custon, $language_tag, $reload);
		
		$this->_db->setQuery('SET SESSION group_concat_max_len = 1000000');
		$this->_db->execute();
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

	function getCustomizeEtapa()
	{
		return null;
	}

	function getParamProva( $options = array() )
	{
		
 
		$registry = new JRegistry;
		$registry->loadString($options['params_inscricao_etapa']);
		$params_inscricao_etapa = $registry->toArray();	

		
		$html = '';
		$html .= '<div class="form-group">';
		$html .= '<label class="col-sm-3 control-label">' . JText::_('Participações:') . '</label>';
		$html .= '<div class="col-sm-9">';
		$html .= '<div class="row">';
		$html .= '<div class="col-sm-6" id="participacoes_1_' .  $options['id_prova'] . '">';
		//$html .= '<div class="checkbox checkbox-warning">';
		//$html .= '<input id="fgct100_' .  $options['id_prova'] . '" name="params_inscricao_etapa['.$options['id_prova'].'][]" value="fgct100" type="checkbox" checked="checked" onclick="return false;" >';
		//$html .= '<label for="fgct100_' .  $options['id_prova'] . '">' . JText::_('FGCT Trap 100'). '</label>';
		//$html .= '</div>';
		/**/
		$html .= '<div class="checkbox checkbox-success">';
		$html .= '<input di id="cbte100_' .  $options['id_prova'] . '" type="checkbox" class="check-part" '.(in_array('cbte100',$params_inscricao_etapa) ? 'checked="checked "' : '').'data-prova="'.$options['id_prova'].'" name="params_inscricao_etapa['.$options['id_prova'].'][]" value="cbte100">';
		$html .= '<label for="cbte100_' .  $options['id_prova'] . '">' . JText::_('CBTE Trap'). '</label>';
		$html .= '</div>';
		
		$html .= '<div class="checkbox checkbox-success">';
		$html .= '<input id="liga100_' .  $options['id_prova'] . '" type="checkbox" class="check-part" '.(in_array('liga100',$params_inscricao_etapa) ? 'checked="checked "' : '').'data-prova="'.$options['id_prova'].'" name="params_inscricao_etapa['.$options['id_prova'].'][]" value="liga100">';
		$html .= '<label for="liga100_' .  $options['id_prova'] . '">' . JText::_('LIGA Trap'). '</label>';
		$html .= '</div>';
		/**/
		$html .= '</div>';
		
		$html .= '<div class="col-sm-6" id="participacoes_2_' .  $options['id_prova'] . '" '.(in_array('fgct200',$params_inscricao_etapa) || in_array('cbte200',$params_inscricao_etapa) || in_array('liga200',$params_inscricao_etapa) ? '' : 'style="display:none;"').'>';
		//$html .= '<div class="checkbox checkbox-warning">';
		//$html .= '<input id="fgct200_' .  $options['id_prova'] . '" name="params_inscricao_etapa['.$options['id_prova'].'][]" value="fgct200" type="checkbox" checked="checked" onclick="return false;" '.(in_array('fgct200',$params_inscricao_etapa) || in_array('cbte200',$params_inscricao_etapa) || in_array('liga200',$params_inscricao_etapa) ? '' : 'disabled="disabled"').'>';
		//$html .= '<label for="fgct200_' .  $options['id_prova'] . '">' . JText::_('FGCT Trap 200'). '</label>';
		//$html .= '</div>';
		/* */
		$html .= '<div class="checkbox checkbox-success">';
		$html .= '<input id="cbte200_' .  $options['id_prova'] . '" type="checkbox" class="check-part" '.(in_array('cbte200',$params_inscricao_etapa) ? 'checked="checked "' : '').'data-prova="'.$options['id_prova'].'" name="params_inscricao_etapa['.$options['id_prova'].'][]" value="cbte200" '.(in_array('fgct200',$params_inscricao_etapa) || in_array('cbte200',$params_inscricao_etapa) || in_array('liga200',$params_inscricao_etapa) ? '' : 'disabled="disabled"').'>';
		$html .= '<label for="cbte200_' .  $options['id_prova'] . '">' . JText::_('CBTE Trap 200'). '</label>';
		$html .= '</div>';
		
		$html .= '<div class="checkbox checkbox-success">';
		$html .= '<input id="liga200_' .  $options['id_prova'] . '" type="checkbox" class="check-part" '.(in_array('liga200',$params_inscricao_etapa) ? 'checked="checked "' : '').'data-prova="'.$options['id_prova'].'" name="params_inscricao_etapa['.$options['id_prova'].'][]" value="liga200" '.(in_array('fgct200',$params_inscricao_etapa) || in_array('cbte200',$params_inscricao_etapa) || in_array('liga200',$params_inscricao_etapa) ? '' : 'disabled="disabled"').'>';
		$html .= '<label for="liga200_' .  $options['id_prova'] . '">' . JText::_('LIGA Trap 200'). '</label>';
		$html .= '</div>';
		/* */
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</div>';
		
		
		return $html;
	}
	
	function getAdditionalPrint( $options = array() )
	{
 		return;
	}
	
	function getTypeProva()
	{
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
	}
	
	
	function getRelatory( $options = array() )
	{
		/*
		
		SELECT `named_etapa` 
		FROM `tcgf_ranking_etapa` 
		
		
		
		SELECT COUNT(DISTINCT(`id_user`)), SUM(`inscricoes`) 
		FROM (  	
			SELECT COUNT(DISTINCT( `nr_etapa_prova`))  as `inscricoes`, `id_inscricao`
			FROM `tcgf_ranking_resultado`
			INNER JOIN `tcgf_ranking_inscricao` USING (`id_inscricao`)
			WHERE `id_etapa`= 124
            AND `id_prova`= 69
			GROUP BY `id_inscricao` 
			) T1
        INNER JOIN `tcgf_ranking_inscricao` USING (`id_inscricao`)	
		
		*/
		
	
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
				$queryResultsRS->where( '(' . $this->_db->quoteName('Associado.data_limite').'<='. $this->_db->quoteName( 'RANETAPA.data_end_etapa' ) 										//----
											.'OR' 																																			//----
											. $this->_db->quoteName('RANETAPA.id_etapa').'IN (375, 376)'																					//----	 
										. ')');																																				//----	
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
		}		
																																													//----
		$SumQuery ='';																																												//----	
		for ($i = 1; $i <=  $options['nr_etapa_prova']; $i++) { 																															//----
			for ($x = 1; $x <=  $options['ns_etapa_prova']; $x++) { 																														//----
				$queryPosition->select( 'ResultadoPosition.r' . $i . 's' . $x. ' AS RankR' . $i . 'S' . $x);																				//----
			}			
			$SumQuery .= (!empty($SumQuery) ? '+(' : '(') . 'IF(ISNULL(ResultadoPosition.rs' . $i . '), NULL, ResultadoPosition.rs' . $i . ') )';																																									//----
		}	
		//$queryPosition->select('IF()
		$queryPosition->select( 'IF(ano_campeonato > 2021, (' . $SumQuery .'), 0) AS RankingDesempate');

																																															//----
		$queryPosition->select( 'ResultadoPosition.rf  AS RankRf');																															//----
																																															//----
		$queryPosition->from( '(' . $queryResultsRF . ') ResultadoPosition' );																												//----	
		$queryPosition->innerJoin($this->_db->quoteName('#__ranking_inscricao') . ' USING('. $this->_db->quoteName('id_inscricao').')' );													//----
		$queryPosition->innerJoin( '#__ranking_campeonato USING( id_campeonato )' );																										//----
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
		$orderInit .= 'ResultadoRank.OrderGen ASC, ResultadoRank.OrderCat ASC, ResultadoRank.OrderCla ASC, ResultadoRank.RankRf DESC, ResultadoRank.RankingDesempate DESC';					//----
																																															//----
		$order='';																																											//----																																															//----
		$concatOS = array();																																								//----			
		for ($i = $options['ns_etapa_prova']; $i > 0; $i--) { 																																//----
			$order .= ', ResultadoRank.RankOS' . $i . ' DESC';																																//----
			$concatOS[] ='ROUND(ResultadoRank.RankOS' . $i . ')';	 																														//----
		}																																													//----
		$concatOS = 'CONCAT(\'0.\','.implode(',',$concatOS).')';																															//----
																																															//----
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
									. '('.$concatOS.') +	'																																//----
									. ' IF(ISNULL(ResultadoRank.shotoff_inscricao_etapa), 0,CONCAT(\'0.00000000\',ResultadoRank.shotoff_inscricao_etapa))'									//----
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

		//$query->select('IF( EquipeClube.id = 7617, \'AVULSO\', EquipeClube.name) AS name_equipe');
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
			
		$SumQuery = '';
		for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) { 
			for ($x = 1; $x <= $options['ns_etapa_prova']; $x++) { 
				$query->select( 'IF(ISNULL(Resultados.r' . $i . 's' . $x . '), NULL, Resultados.r' . $i . 's' . $x . ') AS r' . $i . 's' . $x);
			}
			$query->select( 'IF(ISNULL(Resultados.rs' . $i . '), NULL, Resultados.rs' . $i . ') AS rs' . $i );
			$SumQuery .= (!empty($SumQuery) ? '+(' : '(') . 'IF(ISNULL(Resultados.rs' . $i . '), NULL, Resultados.rs' . $i . ') )';
		}

		$query->select( 'IF(ISNULL(Resultados.rf), NULL, Resultados.rf) AS rf');

		$query->select( 'IF(ano_campeonato > 2021, (' . $SumQuery .'), 0) AS NewDesempate');

		
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
		$query->order('NewDesempate DESC');

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
				if($options['shot_off_prova']==1)																																			//----
					$queryResultsRF->select( '(MAX(ResultsRS.RS) + IF(ISNULL(shotoff_inscricao_etapa), 0, shotoff_inscricao_etapa/1000 ) ) AS RF');											//----
				else																																										//----
					$queryResultsRF->select( 'MAX(ResultsRS.RS) AS rf');																													//----
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
				if($options['shot_off_prova']==1)																																			//----
					$queryResultsRF->select('(ROUND ( ( (' . $RFString . ') / (' . $options['nr_etapa_prova'] . ')), '. $options['decimal_prova'] .' )' .									//----
					 						' + IF(ISNULL(shotoff_inscricao_etapa), 0, shotoff_inscricao_etapa/1000 ) ) AS RF');															//----
				else																																										//----
					$queryResultsRF->select( 'ROUND ( ( (' . $RFString . ') / (' . $options['nr_etapa_prova'] . ')), '. $options['decimal_prova'] .' ) AS RF');								//----
			break;																																											//----
																																															//----
			case '3':																																										//----																				
				$RFString = '';																																								//----
				for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {																														//----
					if(!empty($RFString))																																					//----
						$RFString = $RFString . ' + ';																																		//----
					$RFString = $RFString . ' MAX(IF( ResultsRS.nr_etapa_prova = '. $i .', ResultsRS.RS, 0)) ';																				//----
				}																																											//----
																																															//----
				if($options['shot_off_prova']==1)																																			//----
					$queryResultsRF->select('(( ' . $RFString . ' ) + IF(ISNULL(shotoff_inscricao_etapa), 0, shotoff_inscricao_etapa/1000 ) ) AS RF');										//----
				else																																										//----
					$queryResultsRF->select('( ' . $RFString . ' ) AS RF');																													//----
			break;																																											//----
		}																																													//----
																																															//----
		for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {																																//----
			for ($x = 1; $x <= $options['ns_etapa_prova']; $x++) {  																														//----
				$queryResultsRF->select( 'MAX(IF( (ResultsRS.nr_etapa_prova = '. $i .' ), ResultsRS.S'.  $x . ', NULL)) AS r' . $i . 's' . $x);												//----
			}																																												//----
																																															//----
			$queryResultsRF->select( 'MAX(IF( ResultsRS.nr_etapa_prova = '. $i .', ResultsRS.RS, NULL)) AS rs' . $i);																		//----
		}																																													//----
																																															//----
		for ($x = 1; $x <= $options['ns_etapa_prova']; $x++) { 																																//----
			$queryResultsRF->select( 'IF(MAX(ResultsRS.RS), ResultsRS.S'.  $x . ', NULL) AS os'. $x);																						//----
		}																																													//----
																																															//----
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
	function getEquipeEtapa( $options = array() )
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
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
																																															//----
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
		$queryPagamento->group($this->_db->quoteName('id_user'));																															//----
																																															//----
		//----------------------------------------------------------------- RESULTADO SÉRIE ETAPAS CADA INSCRIÇÃO(SEM FINAL) ---------------------------------------------------------------------
		$queryResultsRS = $this->_db->getQuery(true);																																		//----
																																															//----
		for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) {  																															//----
			$queryResultsRS->select( 'MAX(IF( RANRESU.ordering = ' . $i . ', RANRESU.value_resultado, NULL)) AS S' . $i);																	//----
		}																																													//----
																																															//----
		switch( $options['rs_etapa_prova'] )																																			 	//----
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
		$queryResultsRS->innerJoin( '#__ranking_campeonato USING( id_campeonato, ano_campeonato )' );																						//----
		$queryResultsRS->innerJoin( '#__ranking_modalidade USING( id_modalidade )' );																										//----
		$queryResultsRS->innerJoin( '#__ranking_prova USING( id_prova, id_campeonato )' );																									//----
		$queryResultsRS->innerJoin( '#__ranking_etapa USING( id_etapa, id_campeonato )' );																									//----
																																															//----
		//$queryResultsRS->where( $this->_db->quoteName('baixa_pagamento').'<='. $this->_db->quoteName( 'data_end_etapa' ) );																//----
		$queryResultsRS->where( '(' . $this->_db->quoteName('baixa_pagamento').'<='. $this->_db->quoteName( 'data_end_etapa' ) 																//----
									.'OR' 																																					//----
									. $this->_db->quoteName('id_etapa').'IN (375, 376)'																										//----	 
									. ')');																																					//----
																																															//----
																																															//----
		$queryResultsRS->where('RANINSC.id_equipe <> 7617');																																//----																																														
																																															//----	
		if(!empty($options['id_prova']))																																					//----
			$queryResultsRS->where('id_prova = ' . $this->_db->quote( $options['id_prova'] ));																								//----
																																															//----
		if(!empty($options['id_etapa']))																																					//----
			$queryResultsRS->where('id_etapa = ' . $this->_db->quote( $options['id_etapa'] ));																								//----
																																															//----																																													
		if( !$this->_user->authorise('torneios.diretor.geral', 'com_torneios') )																											//----
			$queryResultsRS->where($this->_db->quoteName('status_modalidade') . ' = ' . $this->_db->quote('1'));																			//----
																																															//----
		if( !$this->_user->authorise('torneios.diretor.modalidade', 'com_torneios') )																										//----
		{																																													//----
			$queryResultsRS->where($this->_db->quoteName('status_campeonato') . ' = ' . $this->_db->quote('1'));																			//----
			$queryResultsRS->where($this->_db->quoteName('status_prova') . ' = ' . $this->_db->quote('1'));																					//----
		}																																													//----
																																															//----
		$queryResultsRS->group('id_etapa');																																					//----
		$queryResultsRS->group('id_inscricao');																																				//----
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
		$OSString = '';																																										//----
		for ($x = 1; $x <= $options['ns_etapa_prova']; $x++) { 																																//----
			if(!empty($OSString))																																							//----
				$OSString = $OSString . ', ';																																				//----
			$OSString = $OSString . ' (IF(MAX(ResultsRS.RS), ROUND(ResultsRS.S'.  $x . ', 0), NULL)) ';																						//----
		}																																													//----
																																															//----
		switch( $options['rf_etapa_prova'] )																																				//----
		{																																													//----
			case '1':																																										//----
				$queryResultsRF->select( 'MAX(ResultsRS.RS) AS RF');																														//----
																																															//----
				if($options['shot_off_prova']==1):																																			//----
					$queryResultsRF->select( 'CONCAT(ROUND(MAX(ResultsRS.RS),0), '.$OSString . ', IF(ISNULL(shotoff_inscricao_etapa), 0, (10 - shotoff_inscricao_etapa) ) ) AS FIND');		//----
				else:																																										//----
					$queryResultsRF->select( 'CONCAT(MAX(ResultsRS.RS), '.$OSString . ') AS FIND');																							//----
				endif;																																										//----
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
																																															//----
				if($options['shot_off_prova']==1)																																			//----
					$queryResultsRF->select('(ROUND ( ( (' . $RFString . ') / (' . $options['nr_etapa_prova'] . ')), '. $options['decimal_prova'] .' )' .									//----
					 						' + IF(ISNULL(shotoff_inscricao_etapa), 0, shotoff_inscricao_etapa/1000 ) ) AS FIND');															//----
				else																																										//----
					$queryResultsRF->select( 'ROUND ( ( (' . $RFString . ') / (' . $options['nr_etapa_prova'] . ')), '. $options['decimal_prova'] .' ) AS FIND');							//----
			break;																																											//----
																																															//----
			case '3':																																										//----																				
				$RFString = '';																																								//----
				for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {																														//----
					if(!empty($RFString))																																					//----
						$RFString = $RFString . ' + ';																																		//----
					$RFString = $RFString . ' MAX(IF( ResultsRS.nr_etapa_prova = '. $i .', ResultsRS.RS, 0)) ';																				//----
				}																																											//----
				$queryResultsRF->select('( ' . $RFString . ' ) AS RF');																														//----
																																															//----
				if($options['shot_off_prova']==1)																																			//----
					$queryResultsRF->select('(( ' . $RFString . ' ) + IF(ISNULL(shotoff_inscricao_etapa), 0, shotoff_inscricao_etapa/1000 ) ) AS FIND');									//----
				else																																										//----
					$queryResultsRF->select('( ' . $RFString . ' ) AS FIND');																												//----
			break;																																											//----
		}																																													//----
																																															//----
		$ROString = '';																																										//----
		for ($x = 1; $x <= $options['ns_etapa_prova']; $x++) { 																																//----
			if(!empty($ROString))																																							//----
				$ROString = $ROString . ' + ';																																				//----
			$ROString .= 'IF(MAX(ResultsRS.RS), ResultsRS.S'.  $x . ', 0)';																													//----	
			$queryResultsRF->select( 'IF(MAX(ResultsRS.RS), ResultsRS.S'.  $x . ', NULL) AS os'. $x);																						//----
		}																																													//----
																																															//----
		$queryResultsRF->select('( ' . $ROString . ' ) AS pratos');																															//----
																																															//----																																																
		for ($i = 1; $i <= $options['nr_etapa_prova']; $i++) {																																//----
			for ($x = 1; $x <= $options['ns_etapa_prova']; $x++) {  																														//----
				$queryResultsRF->select( 'MAX(IF( (ResultsRS.nr_etapa_prova = '. $i .' ), ResultsRS.S'.  $x . ', NULL)) AS r' . $i . 's' . $x);												//----
			}																																												//----
																																															//----
			$queryResultsRF->select( 'MAX(IF( ResultsRS.nr_etapa_prova = '. $i .', ResultsRS.RS, NULL)) AS rs' . $i);																		//----
		}																																													//----
																																															//----
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
		$queryResultsRF->order($this->_db->quoteName('rf') . ' DESC');																														//----
																																															//----
		for ($x = $options['ns_etapa_prova']; $x > 0; $x--) {  																																//----
			$queryResultsRF->order('os' . $x . ' DESC');																																	//----
		}																																													//----
																																															//----
		$queryResultsRF->order('FIND DESC');																																				//----	
																																															//----
		$queryResultsRF->order('id_associado ASC');																																			//----	
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
																																															//----
		if(!empty($options['id_prova']) && $options['id_prova'] == 194)	: 																													//----
																																															//----
			$resultAdd = '';																																								//----
																																															//----
			switch($options['id_etapa']):																																					//----
																																															//----
				case '264'	:																																								//----
					$resultAdd = ' 90.000000  AS RF, 90171921220 AS FIND, 17.000000 AS os1, 19.000000 AS os2, 21.000000 AS os3, 22.000000 AS os4,'. 				//----
							     ' 79.000000 AS pratos, 20.000000 AS r1s1, 24.000000 AS r1s2, 22.000000 AS r1s3, 24.000000 AS r1s4, 90.000000 AS rs1,'.				//---- 
								 ' 17.000000 AS r2s1, 19.000000 AS r2s2, 21.000000 AS r2s3, 22.000000 AS r2s4, 79.000000 AS rs2, 8210 AS id_inscricao,'. 			//----
								 ' 31 AS id_campeonato, 194 AS id_prova, 1645 AS id_user, 397 AS id_genero, 941 AS id_categoria, 1030 AS id_classe,' .				//----
								 ' 264 AS id_etapa, 2559 AS id_equipe, \'\' AS shotoff_inscricao_etapa, 2153 AS id_associado, \'Masculino\' AS name_genero,'. 					//----
								 ' \'Master\' AS name_categoria, \'Única\' AS name_classe, \'Miguel Angelo de Menezes Fumagalli\' AS atleta';												//----
				break;																																										//----
				case '265'	:																																								//----
					$resultAdd = ' 93.000000  AS RF, 93222323220 AS FIND, 22.000000 AS os1, 23.000000 AS os2, 23.000000 AS os3, 22.000000 AS os4,'. 				//----
							     ' 90.000000 AS pratos, 21.000000 AS r1s1, 25.000000 AS r1s2, 25.000000 AS r1s3, 22.000000 AS r1s4, 93.000000 AS rs1,'.				//---- 
								 ' 22.000000 AS r2s1, 23.000000 AS r2s2, 23.000000 AS r2s3, 22.000000 AS r2s4, 90.000000 AS rs2, 8210 AS id_inscricao,'. 			//----
								 ' 31 AS id_campeonato, 194 AS id_prova, 1645 AS id_user, 397 AS id_genero, 941 AS id_categoria, 1030 AS id_classe,' .				//----
								 ' 265 AS id_etapa, 2559 AS id_equipe, \'\' AS shotoff_inscricao_etapa, 2153 AS id_associado, \'Masculino\' AS name_genero,'. 					//----
								 ' \'Master\' AS name_categoria, \'Única\' AS name_classe, \'Miguel Angelo de Menezes Fumagalli\' AS atleta';												//----
				break;																																										//----
				case '296'	:																																								//----
					$resultAdd = ' 96.000000  AS RF, 96232324210 AS FIND, 23.000000 AS os1, 23.000000 AS os2, 24.000000 AS os3, 21.000000 AS os4,'. 				//----
							     ' 91.000000 AS pratos, 24.000000 AS r1s1, 25.000000 AS r1s2, 24.000000 AS r1s3, 23.000000 AS r1s4, 96.000000 AS rs1,'.				//---- 
								 ' 23.000000 AS r2s1, 23.000000 AS r2s2, 24.000000 AS r2s3, 21.000000 AS r2s4, 91.000000 AS rs2, 8210 AS id_inscricao,'. 			//----
								 ' 31 AS id_campeonato, 194 AS id_prova, 1645 AS id_user, 397 AS id_genero, 941 AS id_categoria, 1030 AS id_classe,' .				//----
								 ' 296 AS id_etapa, 2559 AS id_equipe, \'\' AS shotoff_inscricao_etapa, 2153 AS id_associado, \'Masculino\' AS name_genero,'. 					//----
								 ' \'Master\' AS name_categoria, \'Única\' AS name_classe, \'Miguel Angelo de Menezes Fumagalli\' AS atleta';												//----
				break;																																										//----
				case '357'	:																																								//----
					$resultAdd = ' 94.000000  AS RF, 94232222230 AS FIND, 23.000000 AS os1, 22.000000 AS os2, 22.000000 AS os3, 23.000000 AS os4,'. 				//----
							     ' 90.000000 AS pratos, 23.000000 AS r1s1, 22.000000 AS r1s2, 22.000000 AS r1s3, 23.000000 AS r1s4, 90.000000 AS rs1,'.				//---- 
								 ' 23.000000 AS r2s1, 24.000000 AS r2s2, 22.000000 AS r2s3, 25.000000 AS r2s4, 94.000000 AS rs2, 8210 AS id_inscricao,'. 			//----
								 ' 31 AS id_campeonato, 194 AS id_prova, 1645 AS id_user, 397 AS id_genero, 941 AS id_categoria, 1030 AS id_classe,' .				//----
								 ' 357 AS id_etapa, 2559 AS id_equipe, \'\' AS shotoff_inscricao_etapa, 2153 AS id_associado, \'Masculino\' AS name_genero,'. 					//----
								 ' \'Master\' AS name_categoria, \'Única\' AS name_classe, \'Miguel Angelo de Menezes Fumagalli\' AS atleta';												//----
				break;																																										//----
				case '365'	:																																								//----
					$resultAdd = ' 89.000000  AS RF, 89252120190 AS FIND, 25.000000 AS os1, 21.000000 AS os2, 20.000000 AS os3, 19.000000 AS os4,'. 				//----
							     ' 85.000000 AS pratos, 22.000000 AS r1s1, 24.000000 AS r1s2, 19.000000 AS r1s3, 24.000000 AS r1s4, 89.000000 AS rs1,'.				//---- 
								 ' 25.000000 AS r2s1, 21.000000 AS r2s2, 20.000000 AS r2s3, 19.000000 AS r2s4, 85.000000 AS rs2, 8210 AS id_inscricao,'. 			//----
								 ' 31 AS id_campeonato, 194 AS id_prova, 1645 AS id_user, 397 AS id_genero, 941 AS id_categoria, 1030 AS id_classe,' .				//----
								 ' 365 AS id_etapa, 2559 AS id_equipe, \'\' AS shotoff_inscricao_etapa, 2153 AS id_associado, \'Masculino\' AS name_genero,'. 					//----
								 ' \'Master\' AS name_categoria, \'Única\' AS name_classe, \'Miguel Angelo de Menezes Fumagalli\' AS atleta';												//----
				break;																																										//----
				case '366'	:																																								//----
					$resultAdd = ' 98.000000  AS RF, 98222523230 AS FIND, 22.000000 AS os1, 25.000000 AS os2, 23.000000 AS os3, 23.000000 AS os4,'. 				//----
							     ' 93.000000 AS pratos, 22.000000 AS r1s1, 25.000000 AS r1s2, 23.000000 AS r1s3, 23.000000 AS r1s4, 93.000000 AS rs1,'.				//---- 
								 ' 24.000000 AS r2s1, 25.000000 AS r2s2, 25.000000 AS r2s3, 24.000000 AS r2s4, 98.000000 AS rs2, 8210 AS id_inscricao,'. 			//----
								 ' 31 AS id_campeonato, 194 AS id_prova, 1645 AS id_user, 397 AS id_genero, 941 AS id_categoria, 1030 AS id_classe,' .				//----
								 ' 366 AS id_etapa, 2559 AS id_equipe, \'\' AS shotoff_inscricao_etapa, 2153 AS id_associado, \'Masculino\' AS name_genero,'. 					//----
								 ' \'Master\' AS name_categoria, \'Única\' AS name_classe, \'Miguel Angelo de Menezes Fumagalli\' AS atleta';												//----
				break;																																										//----
																																															//----
			endswitch;																																										//----
																																															//----
			if(!empty($resultAdd))	: 																																						//----
				$queryResultAdd = $this->_db->getQuery(true);																																//----
				$queryResultAdd->select($resultAdd);																																		//----
				$queryResultsRF->unionAll($queryResultAdd);																																	//----
			endif;																																											//----
																																															//----
		endif;																																												//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

		
		// [RF] => 90.000000 [FIND] => 90171921220 [os1] => 17.000000 [os2] => 19.000000 [os3] => 21.000000 [os4] => 22.000000 [pratos] => 79.000000 [r1s1] => 20.000000 [r1s2] => 24.000000 [r1s3] => 22.000000 [r1s4] => 24.000000 [rs1] => 90.000000 [r2s1] => 17.000000 [r2s2] => 19.000000 [r2s3] => 21.000000 [r2s4] => 22.000000 [rs2] => 79.000000 [id_inscricao] => 8210 [id_campeonato] => 31 [id_prova] => 194 [id_user] => 1645 [id_genero] => 397 [id_categoria] => 941 [id_classe] => 1030 [id_etapa] => 264 [id_equipe] => 7617 [shotoff_inscricao_etapa] => [id_associado] => 2153 [name_genero] => Masculino [name_categoria] => Master [name_classe] => Única [atleta] => Miguel Angelo de Menezes Fumagalli
		
		// [RF] => 93.000000 [FIND] => 93222323220 [os1] => 22.000000 [os2] => 23.000000 [os3] => 23.000000 [os4] => 22.000000 [pratos] => 90.000000 [r1s1] => 21.000000 [r1s2] => 25.000000 [r1s3] => 25.000000 [r1s4] => 22.000000 [rs1] => 93.000000 [r2s1] => 22.000000 [r2s2] => 23.000000 [r2s3] => 23.000000 [r2s4] => 22.000000 [rs2] => 90.000000 [id_inscricao] => 8210 [id_campeonato] => 31 [id_prova] => 194 [id_user] => 1645 [id_genero] => 397 [id_categoria] => 941 [id_classe] => 1030 [id_etapa] => 265 [id_equipe] => 7617 [shotoff_inscricao_etapa] => [id_associado] => 2153 [name_genero] => Masculino [name_categoria] => Master [name_classe] => Única [atleta] => Miguel Angelo de Menezes Fumagalli
				
		// [RF] => 96.000000 [FIND] => 96232324210 [os1] => 23.000000 [os2] => 23.000000 [os3] => 24.000000 [os4] => 21.000000 [pratos] => 91.000000 [r1s1] => 24.000000 [r1s2] => 25.000000 [r1s3] => 24.000000 [r1s4] => 23.000000 [rs1] => 96.000000 [r2s1] => 23.000000 [r2s2] => 23.000000 [r2s3] => 24.000000 [r2s4] => 21.000000 [rs2] => 91.000000 [id_inscricao] => 8210 [id_campeonato] => 31 [id_prova] => 194 [id_user] => 1645 [id_genero] => 397 [id_categoria] => 941 [id_classe] => 1030 [id_etapa] => 296 [id_equipe] => 7617 [shotoff_inscricao_etapa] => [id_associado] => 2153 [name_genero] => Masculino [name_categoria] => Master [name_classe] => Única [atleta] => Miguel Angelo de Menezes Fumagalli
		
		// [RF] => 94.000000 [FIND] => 94232222230 [os1] => 23.000000 [os2] => 22.000000 [os3] => 22.000000 [os4] => 23.000000 [pratos] => 90.000000 [r1s1] => 23.000000 [r1s2] => 22.000000 [r1s3] => 22.000000 [r1s4] => 23.000000 [rs1] => 90.000000 [r2s1] => 23.000000 [r2s2] => 24.000000 [r2s3] => 22.000000 [r2s4] => 25.000000 [rs2] => 94.000000 [id_inscricao] => 8210 [id_campeonato] => 31 [id_prova] => 194 [id_user] => 1645 [id_genero] => 397 [id_categoria] => 941 [id_classe] => 1030 [id_etapa] => 357 [id_equipe] => 7617 [shotoff_inscricao_etapa] => [id_associado] => 2153 [name_genero] => Masculino [name_categoria] => Master [name_classe] => Única [atleta] => Miguel Angelo de Menezes Fumagalli
		
		// [RF] => 89.000000 [FIND] => 89252120190 [os1] => 25.000000 [os2] => 21.000000 [os3] => 20.000000 [os4] => 19.000000 [pratos] => 85.000000 [r1s1] => 22.000000 [r1s2] => 24.000000 [r1s3] => 19.000000 [r1s4] => 24.000000 [rs1] => 89.000000 [r2s1] => 25.000000 [r2s2] => 21.000000 [r2s3] => 20.000000 [r2s4] => 19.000000 [rs2] => 85.000000 [id_inscricao] => 8210 [id_campeonato] => 31 [id_prova] => 194 [id_user] => 1645 [id_genero] => 397 [id_categoria] => 941 [id_classe] => 1030 [id_etapa] => 365 [id_equipe] => 7617 [shotoff_inscricao_etapa] => [id_associado] => 2153 [name_genero] => Masculino [name_categoria] => Master [name_classe] => Única [atleta] => Miguel Angelo de Menezes Fumagalli 
		
		// [RF] => 98.000000 [FIND] => 98222523230 [os1] => 22.000000 [os2] => 25.000000 [os3] => 23.000000 [os4] => 23.000000 [pratos] => 93.000000 [r1s1] => 22.000000 [r1s2] => 25.000000 [r1s3] => 23.000000 [r1s4] => 23.000000 [rs1] => 93.000000 [r2s1] => 24.000000 [r2s2] => 25.000000 [r2s3] => 25.000000 [r2s4] => 24.000000 [rs2] => 98.000000 [id_inscricao] => 8210 [id_campeonato] => 31 [id_prova] => 194 [id_user] => 1645 [id_genero] => 397 [id_categoria] => 941 [id_classe] => 1030 [id_etapa] => 366 [id_equipe] => 7617 [shotoff_inscricao_etapa] => [id_associado] => 2153 [name_genero] => Masculino [name_categoria] => Master [name_classe] => Única [atleta] => Miguel Angelo de Menezes Fumagalli 
		
																																															//----																																										
		$queryRankPos = $this->_db->getQuery(true);																																			//----																																													
																																															//----
		$queryRankPos->select( 'id_equipe');																																				//----
																																															//----			
		$order='';																																											//----
		for ($x = $options['ns_etapa_prova']; $x > 0; $x--) {  																																//----
			$order .= ', ResultadoRankPos.os' . $x. ' DESC';																																//----
		}																																													//----
																																															//----			
		$order .= ', ResultadoRankPos.FIND DESC';																																			//----
																																															//----			
		$queryFinal = '';																																									//----
		for ($i = 1; $i <= $options['nr_equipe_prova']; $i++) { 																															//----
			if(!empty($queryFinal))																																							//----
				$queryFinal .= ' + ';																																						//----
																																															//----
			$queryFinal .= 	'IF(COUNT(ResultadoRankPos.RF) > '.($i-1).																														//----
							', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultadoRankPos.RF ORDER BY ResultadoRankPos.RF DESC' . $order . '),'										//----
							.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), 0)';																						//----		
		}																																													//----
																																															//----
		$queryRankPos->select('ResultadoRankPos.pratos AS pratos');																															//----
		$queryRankPos->select('COUNT(DISTINCT( ResultadoRankPos.id_inscricao ) ) AS atletas');																								//----																																											
																																															//----
		if(!empty($queryFinal))																																								//----
			$queryRankPos->select( '('.$queryFinal.') AS RankRF');																															//----
																																															//----
		$queryRankPos->from( '(' . $queryResultsRF . ') ResultadoRankPos' );																												//----
		$queryRankPos->group('id_equipe');																																					//----
		$queryRankPos->order('RankRF DESC');																																				//----
		$queryRankPos->order('atletas DESC');																																				//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------		
																																															//----	
		$queryRank = $this->_db->getQuery(true);																																			//----
		$queryRank->select( 'GROUP_CONCAT( ResultadoRank.id_equipe ORDER BY ResultadoRank.RankRF DESC, ResultadoRank.atletas DESC, ResultadoRank.pratos DESC)' );							//----							
		$queryRank->from( '(' . $queryRankPos . ') ResultadoRank' );																														//----																																									
																																															//----																																												
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------																																												
						
		$query = $this->_db->getQuery(true);	

		if($options['equipe_prova']==1 || $options['equipe_prova']==4):
			//$query->select( 'name');	
			$query->select('IF( ISNULL(AddEquipe.id_addequipe), IF( id = 7617, \'AVULSO\', name), AddEquipe.name_addequipe) AS name');

		elseif($options['equipe_prova']==1 || $options['equipe_prova']==5)	:
			$query->select( 'CONCAT (\'Equipe \','. $this->_db->quoteName('id_equipe') . ') AS name');
		endif;																			
		
		$order='';
		for ($x = $options['ns_etapa_prova']; $x > 0; $x--) { 
			$order .= ', ResultsEtapa.os' . $x. ' DESC';
		}
		
		$order .= ', ResultsEtapa.pratos DESC';
		
		for ($i = 1; $i <= $options['nr_equipe_prova']; $i++) {
			
			$query->select( 'IF(COUNT(ResultsEtapa.RF) > '.($i-1).
							', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultsEtapa.RF ORDER BY ResultsEtapa.RF DESC' . $order . '),'
							.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), NULL) AS r' . $i);	
		
			$query->select( 'IF(COUNT(ResultsEtapa.RF) > '.($i-1).
							', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultsEtapa.atleta ORDER BY ResultsEtapa.RF DESC' . $order . '),'
							.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), NULL) AS n' . $i);	
		
			$query->select( 'IF(COUNT(ResultsEtapa.RF) > '.($i-1).
							', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultsEtapa.name_genero ORDER BY ResultsEtapa.RF DESC' . $order . '),'
							.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), NULL) AS g' . $i);	
							
			$query->select( 'IF(COUNT(ResultsEtapa.RF) > '.($i-1).
							', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultsEtapa.name_categoria ORDER BY ResultsEtapa.RF DESC' . $order . '),'
							.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), NULL) AS c' . $i);	
							
			$query->select( 'IF(COUNT(ResultsEtapa.RF) > '.($i-1).
							', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultsEtapa.name_classe ORDER BY ResultsEtapa.RF DESC' . $order . '),'
							.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), NULL) AS l' . $i);	
		}
		
		$query->select('SUM( ResultsEtapa.pratos ) AS pratos');			
		
		$query->select('COUNT(DISTINCT( ResultsEtapa.id_inscricao ) ) AS atletas');																																																																		
		
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
		
		$query->select( 'FIND_IN_SET( (id_equipe), (' . $queryRank . ')) AS rank');
			
		$query->from( '(' . $queryResultsRF . ') ResultsEtapa' );
		$query->leftJoin( '#__users ON('. $this->_db->quoteName('id'). ' = '. $this->_db->quoteName('id_equipe'). ')' );	
		//$query->leftJoin( '#__intranet_addequipe AddEquipe ON('. $this->_db->quoteName('id_addequipe'). ' = '. $this->_db->quoteName('id_equipe'). ')' );	

		//$query->select('IF( ISNULL(AddEquipe.id_addequipe), name, AddEquipe.name_addequipe) AS name');
		$query->leftJoin( '#__intranet_addequipe AddEquipe ON(CONCAT(\'A\', '. $this->_db->quoteName('id_addequipe'). ') = '. $this->_db->quoteName('id_equipe'). ')' );	
		
																																													
																																															


		$query->group('id_equipe');																																			

		$query->order('rf desc');	
		$query->order('atletas desc');
		$query->order('pratos desc');	
	
		$this->_db->setQuery($query);	
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
		$queryRanking->select('#__ranking_etapa.ordering');																																	//----
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
		$queryRanking->group('id_inscricao');																																				//----
		$queryRanking->group('id_etapa');																																					//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
																																															//----																																										
		$queryPosition = $this->_db->getQuery(true);																																		//----
																																															//----
		$queryPosition->select('id_inscricao AS RankInscricao');																															//----
		$queryPosition->select('id_associado AS RankAssociado');																															//----
		$queryPosition->select('shotoff_inscricao AS RankSrotOff');																															//----
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
		$queryPosition->select( 'SUM(ResultadoPosition.rf) as rAddColsBefore1' );																											//---
																																															//----
		if(!empty($RF))																																										//----
			$queryPosition->select( 'ROUND (( '.$RF.')) AS RankRf');																														//----
																																															//----
		$queryPosition->from( '(' . $queryRanking . ') ResultadoPosition' );																												//----	
		$queryPosition->innerJoin($this->_db->quoteName('#__ranking_inscricao') . ' USING('. $this->_db->quoteName('id_inscricao').','. $this->_db->quoteName('id_classe').')' );			//----
																																															//----
		$queryPosition->order($this->_db->quoteName('RankRf') . ' DESC');																													//----
		$queryPosition->order($this->_db->quoteName('RankSrotOff') . ' DESC');																												//----
		$queryPosition->order($this->_db->quoteName('rAddColsBefore1') . ' DESC');																											//----
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
		$queryRank->select( 'GROUP_CONCAT( ResultadoRank.Rankinscricao ORDER BY ResultadoRank.Rankrf DESC, ResultadoRank.RankSrotOff DESC, ResultadoRank.rAddColsBefore1 DESC' . $order . ' )' );						//----
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
		
		$query->select( 'SUM(Resultados.rf) as rAddColsBefore1' );
		$query->select( 'ROUND (( (SUM(Resultados.rf)) / (COUNT(Resultados.rf)) ), 3 ) as rAddColsBefore2');


		$SomaAddColsBefore3 = '';
		for ($i = 1; $i <= 6; $i++) { 
			if(!empty($SomaAddColsBefore3))
				$SomaAddColsBefore3 .= ' + ';
		
			$SomaAddColsBefore3 .= 	'IF(COUNT(Resultados.rf) > '.($i-1)
									. ', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( Resultados.rf ORDER BY Resultados.rf DESC ), '
									. $this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), 0)';	
		}
		
		if(!empty($SomaAddColsBefore3))
			$query->select( 'ROUND ( ('.$SomaAddColsBefore3.') ) AS rAddColsBefore3');

		
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
			$query->select( 'ROUND ( ('.$queryFinal.') ) AS rf');

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
		$query->order($this->_db->quoteName('shotoff_inscricao') . ' DESC');
		$query->order($this->_db->quoteName('rAddColsBefore1') . ' DESC');
		
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
		$queryRanking = $this->_db->getQuery(true);																																			//----
		$queryRanking->select('id_inscricao');																																				//----
		$queryRanking->select('id_associado');																																				//----
		$queryRanking->select('id_etapa');																																					//----
		$queryRanking->select('name_etapa');																																				//----
		$queryRanking->select('id_classe');																																					//----	
		$queryRanking->select('id_local as id_locals');																																		//----
		$queryRanking->select('name AS names');																																				//----
																																															//----
		$queryRanking->select( 'MAX((ResultsRF.RF)) AS rf');																																//----
		$queryRanking->from( '(' . $queryResultsRF . ') ResultsRF' );																														//----
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
		$queryPosition->select('shotoff_inscricao AS RankSrotOff');																															//----								//----
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
		$queryPosition->select( 'SUM(ResultadoPosition.rf) as rAddColsBefore1' );																											//---
																																															//----
		if(!empty($RF))																																										//----
			$queryPosition->select( 'ROUND ( ('.$RF.') ) AS RankRf');																														//----
																																															//----
		$queryPosition->from( '(' . $queryRanking . ') ResultadoPosition' );																												//----	
		$queryPosition->innerJoin($this->_db->quoteName('#__ranking_inscricao') . ' USING('. $this->_db->quoteName('id_inscricao').','. $this->_db->quoteName('id_classe').')' );			//----
		$queryPosition->innerJoin( '#__ranking_genero USING( id_genero, id_prova )' );																										//----
		$queryPosition->innerJoin( '#__ranking_categoria USING( id_categoria, id_genero )' );																								//----	
		$queryPosition->innerJoin( '#__ranking_classe USING( id_classe, id_categoria )' );																									//----
																																															//----
		$queryPosition->order($this->_db->quoteName('RankRf') . ' DESC');																													//----
		$queryPosition->order($this->_db->quoteName('RankSrotOff') . ' DESC');																												//----
		$queryPosition->order($this->_db->quoteName('rAddColsBefore1') . ' DESC');																											//----
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
		$orderInit .= 'ResultadoRank.OrderGen ASC, ResultadoRank.OrderCat ASC, ResultadoRank.OrderCla ASC, ResultadoRank.Rankrf DESC, ResultadoRank.RankSrotOff DESC, ResultadoRank.rAddColsBefore1 DESC';						//----
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

		//$query->select('EquipeClube.name AS name_equipe');
		$query->select('IF(ISNULL(AddEquipe.id_addequipe), IF( EquipeClube.id = 7617, \'AVULSO\', EquipeClube.name), AddEquipe.name_addequipe) AS name_equipe');
		
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
		
		$query->select( 'SUM(Resultados.rf) as rAddColsBefore1' );
		$query->select( 'ROUND (( (SUM(Resultados.rf)) / (COUNT(Resultados.rf)) ), 3 ) as rAddColsBefore2');

		$SomaAddColsBefore3 = '';
		for ($i = 1; $i <= 6; $i++) { 
			if(!empty($SomaAddColsBefore3))
				$SomaAddColsBefore3 .= ' + ';
		
			$SomaAddColsBefore3 .= 	'IF(COUNT(Resultados.rf) > '.($i-1)
									. ', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( Resultados.rf ORDER BY Resultados.rf DESC ), '
									. $this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), 0)';	
		}
		
		if(!empty($SomaAddColsBefore3))
			$query->select( 'ROUND ( ('.$SomaAddColsBefore3.') ) AS rAddColsBefore3');
			
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
			$query->select( 'ROUND ( ('.$queryFinal.') ) AS rf');

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
		$query->order($this->_db->quoteName('shotoff_inscricao') . ' DESC');
		$query->order($this->_db->quoteName('rAddColsBefore1') . ' DESC');
	

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
	//------------------------------------------------------------------------------------- EQUIPES PROVA ----------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	function getEquipeProva( $options = array() )
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
		//$queryResultsRS->select('IF(ISNULL(RANINSC.id_addequipe), RANINSC.id_equipe, RANINSC.id_addequipe) AS id_equipe');																//----
																																															//----
		$queryResultsRS->select('IF(ISNULL(RANINSC.id_addequipe), RANINSC.id_equipe, CONCAT(\'A\',RANINSC.id_addequipe)) AS id_equipe');													//----
																																															//----
		$queryResultsRS->from( '#__ranking_resultado RANRESU' );																															//----
		$queryResultsRS->innerJoin( '#__ranking_inscricao RANINSC USING( id_inscricao )' );																									//----
		$queryResultsRS->innerJoin( '#__ranking_inscricao_etapa RANIETA USING( id_inscricao,  id_etapa )' );																				//----
		$queryResultsRS->innerJoin( '#__ranking_campeonato RANCAMP USING( id_campeonato )' );																								//----
		$queryResultsRS->innerJoin( '#__ranking_etapa RANETAPA USING( id_campeonato, id_etapa )' );																							//----
		$queryResultsRS->innerJoin( '#__ranking_modalidade RANMODA  USING( id_modalidade )' );																								//----
		$queryResultsRS->innerJoin( '#__ranking_prova RANPROV USING( id_prova )' );																											//----
																																															//----
		$queryResultsRS->where('(RANINSC.id_equipe <> 7617 OR RANINSC.id_equipe IS NULL)');																									//----	
																																															//----
		//if( !$this->_user->authorise('torneios.diretor.geral', 'com_torneios') )																											//----
		$queryResultsRS->where($this->_db->quoteName('RANMODA.status_modalidade') . ' = ' . $this->_db->quote('1'));																		//----
																																															//----
		//if( !$this->_user->authorise('torneios.diretor.modalidade', 'com_torneios') )																										//----
		//{																																													//----
		$queryResultsRS->where($this->_db->quoteName('RANCAMP.status_campeonato') . ' = ' . $this->_db->quote('1'));																		//----
		$queryResultsRS->where($this->_db->quoteName('RANPROV.status_prova') . ' = ' . $this->_db->quote('1'));																				//----
		//}																																													//----
																																															//----	
		$queryResultsRS->where($this->_db->quoteName('RANETAPA.status_etapa') . ' = ' . $this->_db->quote('1'));																			//----
																																															//----	
																																															//----
		if(!empty($options['id_prova']))																																					//----
			$queryResultsRS->where('RANINSC.id_prova = ' . $this->_db->quote( $options['id_prova'] ));																						//----
																																															//----
		$queryResultsRS->group('RANRESU.id_etapa');																																			//----
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
		$queryResultsRF->select('id_equipe');																																				//----
		$queryResultsRF->select('id_etapa');																																				//----
																																															//----
		$queryResultsRF->from( '(' . $queryResultsRS . ') ResultsRS' );																														//----
																																															//----
		$queryResultsRF->group('id_inscricao');																																				//----
		$queryResultsRF->group('id_equipe');																																				//----
		$queryResultsRF->group('id_etapa');																																					//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
																																															//----
		if(!empty($options['id_prova']) && $options['id_prova'] == 194)	: 																													//----
				/*																																											//----
			$queryEtapa1 = $this->_db->getQuery(true);																																		//----
			$queryEtapa1->select('90.000000 AS RF, 8210 AS id_inscricao, 2559 AS id_equipe, 264 AS id_etapa');																				//----
			$queryResultsRF->unionAll($queryEtapa1);																																		//----
																																															//----
			$queryEtapa2 = $this->_db->getQuery(true);																																		//----
			$queryEtapa2->select('93.000000 AS RF, 8210 AS id_inscricao, 2559 AS id_equipe, 265 AS id_etapa');																				//----
			$queryResultsRF->unionAll($queryEtapa2);																																		//----
			*/																																												//----
			$queryEtapa3 = $this->_db->getQuery(true);																																		//----
			$queryEtapa3->select('96.000000 AS RF, 8210 AS id_inscricao, 2559 AS id_equipe, 296 AS id_etapa');																				//----
			/*$queryResultsRF->unionAll($queryEtapa3);																																		//----
																																															//----
			$queryEtapa4 = $this->_db->getQuery(true);																																		//----
			$queryEtapa4->select('94.000000 AS RF, 8210 AS id_inscricao, 2559 AS id_equipe, 357 AS id_etapa');																				//----
			$queryResultsRF->unionAll($queryEtapa4);																																		//----
																																															//----
			$queryEtapa5 = $this->_db->getQuery(true);																																		//----
			$queryEtapa5->select('89.000000 AS RF, 8210 AS id_inscricao, 2559 AS id_equipe, 365 AS id_etapa');																				//----
			$queryResultsRF->unionAll($queryEtapa5);																																		//----
			*/																																												//----
			$queryEtapa6 = $this->_db->getQuery(true);																																		//----
			$queryEtapa6->select('98.000000 AS RF, 8210 AS id_inscricao, 2559 AS id_equipe, 366 AS id_etapa');																				//----
																																															//----
			$queryEtapa6->unionAll($queryEtapa3);																																			//----
																																															//----
			$queryEtapasValues = $this->_db->getQuery(true);																																//----
			$queryEtapasValues->select('RF, id_inscricao, id_equipe, id_etapa');																											//----
			$queryEtapasValues->from('(' . $queryEtapa6 .') AS Teste');																														//----
			$queryResultsRF->unionAll('('. $queryEtapasValues . ')');																														//----
																																															//----
		endif;																																												//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
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
		$queryResultsEtapa->where($this->_db->quoteName('ETAPAORDER.status_etapa') . ' = ' . $this->_db->quote('1'));																		//----
		$queryResultsEtapa->group('id_equipe');																																				//----
		$queryResultsEtapa->group('id_etapa');																																				//----
		$queryResultsEtapa->order('EtapaRF DESC');																																			//----																																											
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

		$queryRankPos = $this->_db->getQuery(true);	
		$queryFinal = '';
		//for ($i = 1; $i <= $options['numero_etapas']; $i++) { 
		if(!empty($options['id_prova']) && $options['id_prova'] >= 258 && $options['id_prova'] < 507)	{
			for ($i = 1; $i <= 9; $i++) { 
				if(!empty($queryFinal))
					$queryFinal .= ' + ';
			
				$queryFinal .= 	'IF(COUNT(ResultadoRankPos.EtapaRF) > '.($i-1).
								', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultadoRankPos.EtapaRF ORDER BY ResultadoRankPos.EtapaRF DESC ),'
								.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), 0)';		
			}
			
			if(!empty($queryFinal))
				$queryRankPos->select( '('.$queryFinal.') as RankRF');
		}
		else{

			for ($i = 1; $i <= $options['numero_etapas']; $i++) { 
				if(!empty($queryFinal))
					$queryFinal .= ' + ';
			
				$queryFinal .= 	'IF(COUNT(ResultadoRankPos.EtapaRF) > '.($i-1).
								', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultadoRankPos.EtapaRF ORDER BY ResultadoRankPos.ordering ASC ),'
								.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), 0)';		
			}
			
			if(!empty($queryFinal))
				$queryRankPos->select( '('.$queryFinal.') as RankRF');

		}
		$queryFinal = '';
		for ($i = 1; $i <= $options['numero_etapas']; $i++) { 
			if(!empty($queryFinal))
				$queryFinal .= ' + ';
		
			$queryFinal .= 	'IF(COUNT(ResultadoRankPos.EtapaRF) > '.($i-1).
							', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultadoRankPos.EtapaRF ORDER BY ResultadoRankPos.ordering ASC ),'
							.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), 0)';		
		}
		
		if(!empty($queryFinal))
			$queryRankPos->select( '('.$queryFinal.') as Total');


		$queryRankPos->from( '(' . $queryResultsEtapa . ') ResultadoRankPos' );	
		//$queryRankPos->innerJoin( '#__users ON('. $this->_db->quoteName('id'). ' = '. $this->_db->quoteName('id_equipe'). ')' );
		$queryRankPos->group('id_equipe');																																			
		$queryRankPos->order('RankRF desc');																																		
		$queryRankPos->order('Total desc');	
		
		$queryRank = $this->_db->getQuery(true);	
		$queryRank->select( 'GROUP_CONCAT( ResultadoRank.RankRF )' );							
		$queryRank->from( '(' . $queryRankPos . ') ResultadoRank' );	


		$query = $this->_db->getQuery(true);	
		$query->select( 'id_equipe');
		//$query->select( 'name');	
		$query->select('IF( ISNULL(AddEquipe.id_addequipe), IF( name = 7617, \'AVULSO\', name), AddEquipe.name_addequipe) AS name');
																				
		
		for ($i = 1; $i <= $options['numero_etapas']; $i++) { 
			$query->select( 'IF(COUNT(ResultsEtapa.EtapaRF) > '.($i-1).
							', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultsEtapa.EtapaRF ORDER BY ResultsEtapa.ordering ASC ),'
							.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), NULL) AS r' . $i);	
							
			$query->select( 'IF(COUNT(ResultsEtapa.EtapaRF) > '.($i-1).
							', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultsEtapa.ordering ORDER BY ResultsEtapa.ordering ASC ),'
							.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), NULL) AS o' . $i);	
											
							
		}
		
		$queryFinal = '';
		if(!empty($options['id_prova']) && $options['id_prova'] >= 258 && $options['id_prova'] < 507)	{



			$queryFinal = '';
			for ($i = 1; $i <= $options['numero_etapas']; $i++) { 
				if(!empty($queryFinal))
					$queryFinal .= ' + ';
			
				$queryFinal .= 	'IF(COUNT(ResultsEtapa.EtapaRF) > '.($i-1).
								', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultsEtapa.EtapaRF ORDER BY ResultsEtapa.ordering ASC ),'
								.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), 0)';
			}	

			if(!empty($queryFinal))
				$query->select( '(('.$queryFinal.')+0) as rAddColsBefore1');


			$queryFinal = '';
			for ($i = 1; $i <= 9; $i++) { 
				if(!empty($queryFinal))
					$queryFinal .= ' + ';
			
				$queryFinal .= 	'IF(COUNT(ResultsEtapa.EtapaRF) > '.($i-1).
								', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultsEtapa.EtapaRF ORDER BY ResultsEtapa.EtapaRF DESC ),'
								.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), 0)';	
			}
			
			if(!empty($queryFinal))
				$query->select( '(('.$queryFinal.')+0) as rf');


		}
		else{

			for ($i = 1; $i <= $options['numero_etapas']; $i++) { 
				if(!empty($queryFinal))
					$queryFinal .= ' + ';
			
				$queryFinal .= 	'IF(COUNT(ResultsEtapa.EtapaRF) > '.($i-1).
								', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( ResultsEtapa.EtapaRF ORDER BY ResultsEtapa.ordering ASC ),'
								.$this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), 0)';
			}	
			
			
			if(!empty($queryFinal))
				$query->select( '(('.$queryFinal.')+0) as rf');		

			if(!empty($queryFinal))
				$query->select( '(('.$queryFinal.')+0) as rAddColsBefore1');
		}
		

				
		
		$query->select( 'FIND_IN_SET( ('.$queryFinal.'), (' . $queryRank . ')) AS rank');
			
		$query->from( '(' . $queryResultsEtapa . ') ResultsEtapa' );
		$query->leftJoin( '#__users ON('. $this->_db->quoteName('id'). ' = '. $this->_db->quoteName('id_equipe'). ')' );
		$query->leftJoin( '#__intranet_addequipe AddEquipe ON(CONCAT(\'A\','.  $this->_db->quoteName('id_addequipe'). ') = '. $this->_db->quoteName('id_equipe'). ')' ); 
	
	
	
		$query->group('id_equipe');																																			

		$query->order('rf desc');
		$query->order('rAddColsBefore1 desc');	
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
		$queryPartEtapas = $this->_db->getQuery(true);																																		//----
																																															//----
		$queryPartEtapas->select('id_inscricao');																																			//----
		$queryPartEtapas->select('IF(ISNULL(id_addequipe), id_equipe, CONCAT(\'A\',id_addequipe)) AS id_equipe');																			//----
		$queryPartEtapas->select('id_etapa');																																				//----
		$queryPartEtapas->select('#__ranking_etapa.ordering');																																//----	
																																															//----
		$queryPartEtapas->from( '#__ranking_resultado' );																																	//----																										
		$queryPartEtapas->innerJoin( '#__ranking_inscricao USING( id_inscricao )' );																										//----
		$queryPartEtapas->innerJoin( '#__ranking_etapa USING('. $this->_db->quoteName('id_etapa'). ')' );																					//----	
		$queryPartEtapas->where($this->_db->quoteName('status_etapa') . ' = ' . $this->_db->quote('1'));																					//----																		
																																															//----
		$queryPartEtapas->where('(id_equipe <> 7617 OR id_equipe IS NULL)');																												//----
																																															//----																																														
		if(!empty($options['id_prova']))																																					//----
			$queryPartEtapas->where('id_prova = ' . $this->_db->quote( $options['id_prova'] ));																								//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
																																															//----
		$queryResultados = $this->_db->getQuery(true);																																		//----
																																															//----
		$queryResultados->select( 'COUNT(DISTINCT( Resultados.id_inscricao )) AS totalAtletas');																							//----
		$queryResultados->select('Resultados.id_equipe');																																	//----
		$queryResultados->select('Resultados.id_etapa');																																	//----
		$queryResultados->select('Resultados.ordering');																																	//----	
																																															//----
		$queryResultados->from('('.$queryPartEtapas.') as Resultados' );																													//----																										
																																															//----									
		$queryResultados->group('Resultados.id_equipe');																																	//----
		$queryResultados->group('Resultados.id_etapa');																																		//----									
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

		$queryRankPos->from( '(' . $queryResultados . ') ResultadoRankPos' );	
		$queryRankPos->group('id_equipe');																																			
		$queryRankPos->order('RankRF desc');	
		
		$queryRank = $this->_db->getQuery(true);	
		$queryRank->select( 'GROUP_CONCAT( ResultadoRank.RankRF )' );							
		$queryRank->from( '(' . $queryRankPos . ') ResultadoRank' );
		

		$query = $this->_db->getQuery(true);	
		$query->select( 'id_equipe');
		//$query->select( 'name');
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
			
		$query->from( '(' . $queryResultados . ') ResultsEtapa' );
		$query->leftJoin( '#__users ON('. $this->_db->quoteName('id'). ' = '. $this->_db->quoteName('id_equipe'). ')' );	
		$query->leftJoin( '#__intranet_addequipe AddEquipe ON(CONCAT(\'A\', '. $this->_db->quoteName('id_addequipe'). ') = '. $this->_db->quoteName('id_equipe'). ')' );	
		
			
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

	function getCustomizeProva( $options = array() )
	{		

		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
																																															//----
		$queryPartEtapas = $this->_db->getQuery(true);																																		//----
																																															//----
		//$queryPartEtapas->select('id_inscricao');	
		$queryPartEtapas->select('id_user');																																		//----
		$queryPartEtapas->select('IF(ISNULL(id_addequipe), id_equipe, CONCAT(\'A\',id_addequipe)) AS id_equipe');																			//----
		$queryPartEtapas->select('id_etapa');																				//----
		$queryPartEtapas->select('name_etapa');																																																				//----
		$queryPartEtapas->select('#__ranking_etapa.ordering');																																//----	
																																															//----
		$queryPartEtapas->from( '#__ranking_resultado' );																																	//----																										
		$queryPartEtapas->innerJoin( '#__ranking_inscricao USING( id_inscricao )' );																										//----
		$queryPartEtapas->innerJoin( '#__ranking_etapa USING('. $this->_db->quoteName('id_etapa'). ')' );																					//----	
		$queryPartEtapas->where($this->_db->quoteName('status_etapa') . ' = ' . $this->_db->quote('1'));																					//----																		
																																															//----
		$queryPartEtapas->where('(id_equipe <> 7617 OR id_equipe IS NULL)');																												//----
																																															//----									
		$queryPartEtapas->group('id_etapa');																																				//----
																																															//----									
		$queryPartEtapas->group('id_inscricao');																																			//----
																																															//----																																														
		if(!empty($options['id_prova']))																																					//----
			$queryPartEtapas->where('id_prova = ' . $this->_db->quote( $options['id_prova'] ));																								//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
																																															//----
		$queryResultados = $this->_db->getQuery(true);																																		//----
																																															//----
		$queryResultados->select( 'COUNT(DISTINCT( Resultados.id_etapa )) AS participacoes');																								//----
		$queryResultados->select( 'GROUP_CONCAT(Resultados.name_etapa ORDER BY Resultados.ordering SEPARATOR \'@@#@##\') AS etapas');														//----
		$queryResultados->select('Resultados.id_equipe');																																	//----
		$queryResultados->select('Resultados.id_user AS id_user');																															//----
		$queryResultados->select('#__users.name AS name_atleta');																															//----
																																															//----
		$queryResultados->from('('.$queryPartEtapas.') as Resultados' );																													//----																										
		$queryResultados->innerJoin( '#__users ON( id_user  = id)' );																														//----																										
																																															//----
		$queryResultados->group('Resultados.id_equipe');																																	//----
		$queryResultados->group('Resultados.id_user');																																		//----									
																																															//----
		$queryResultados->order('participacoes DESC');																											//----
		$queryResultados->having('COUNT(DISTINCT( Resultados.id_etapa )) >= 6');																											//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
																																															//----
		$queryRankPos = $this->_db->getQuery(true);																																			//----
		$queryRankPos->select( 'SUM(ResultadoRankPos.participacoes) as RankRF');																											//----
		$queryRankPos->from( '(' . $queryResultados . ') ResultadoRankPos' );																												//----
																																															//----
		$queryRankPos->group('id_equipe');																																					//----																																	
		$queryRankPos->order('RankRF desc');																																				//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
																																															//----	
		$queryRank = $this->_db->getQuery(true);																																			//----	
		$queryRank->select( 'GROUP_CONCAT( ResultadoRank.RankRF )' );																														//----						
		$queryRank->from( '(' . $queryRankPos . ') ResultadoRank' );																														//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

		$query = $this->_db->getQuery(true);

		$query->select('IF( ISNULL(AddEquipe.id_addequipe), name, AddEquipe.name_addequipe) AS name');
		$query->select( 'SUM(ResultsEtapa.participacoes) as rf');
		$query->select( 'FIND_IN_SET( (SUM(ResultsEtapa.participacoes)), (' . $queryRank . ')) AS rank');
		
		$query->select( 'GROUP_CONCAT( ResultsEtapa.name_atleta ) Col0' );	
		$query->select( 'GROUP_CONCAT( ResultsEtapa.participacoes ) Col1' );
		$query->select( 'GROUP_CONCAT( ResultsEtapa.etapas ) popoverCol1' );
			
		$query->from( '(' . $queryResultados . ') ResultsEtapa' );
		$query->leftJoin( '#__users ON('. $this->_db->quoteName('id'). ' = '. $this->_db->quoteName('id_equipe'). ')' );	
		$query->leftJoin( '#__intranet_addequipe AddEquipe ON(CONCAT(\'A\', '. $this->_db->quoteName('id_addequipe'). ') = '. $this->_db->quoteName('id_equipe'). ')' );	

		$query->group('id_equipe');			
		
		$query->order('rank ASC');
		$query->order('rf desc');
	
		$this->_db->setQuery($query);
		return 	$this->_db->loadObjectList();																																						
	
	}	
	
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------- CLASSES PROXIMO ANO ------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- 
	function getClasses( $options = array() )
	{
		
																																												
		
		$queryResultsRF = $this->getQueryRF( $options );
		
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
		$queryRanking = $this->_db->getQuery(true);																																			//----
		$queryRanking->select('id_inscricao');																																				//----
		$queryRanking->select('id_associado');																																				//----
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
															'sexo_pf',																														//----
															'pcd_pf',																														//----
															)));																															//----
																																															//----
		$queryResults->select( 'TIMESTAMPDIFF(YEAR, CONCAT( YEAR(data_nascimento_pf), "-01-01" ), CONCAT( (ano_campeonato+1), "-12-31" ) ) AS idadeClass');									//----													
																																															//----
		$queryResults->from( $this->_db->quoteName('#__ranking_inscricao') );																												//----
		$queryResults->innerJoin('(' . $queryRanking . ') Results USING('. $this->_db->quoteName('id_inscricao').','. $this->_db->quoteName('id_classe').')' );								//----
		$queryResults->innerJoin( '#__ranking_campeonato USING( id_campeonato )' );																											//----
		$queryResults->innerJoin( '#__intranet_pf USING('. $this->_db->quoteName('id_user').')' );																							//----
		$queryResults->group($this->_db->quoteName('id_inscricao'));																														//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
																																															//----
		$queryNewClass = $this->_db->getQuery(true);																																		//----
																																															//----
		$queryNewClass->select( $this->_db->quoteName('id_inscricao'));																														//----
		$queryNewClass->select( 'IF(ISNULL(ClassePremium.name_categoria), Classes.name_categoria, ClassePremium.name_categoria) AS name_categoria');										//----
		$queryNewClass->select( 'IF(ISNULL(ClassePremium.name_classe), Classes.name_classe, ClassePremium.name_classe) AS name_classe');													//----
																																															//----		
																																															//----
		$queryNewClass->from( $this->_db->quoteName('#__ranking_inscricao') );																												//----
		$queryNewClass->innerJoin('(' . $queryResults . ') ResultClass USING('. $this->_db->quoteName('id_inscricao').','. $this->_db->quoteName('id_classe').')' );						//----
		$queryNewClass->leftJoin('(' . $queryClasses . ') ClassePremium ON('																												//----
																			. '('																											//----
																				. $this->_db->quoteName('ClassePremium.sexo_genero').'='. $this->_db->quoteName('ResultClass.sexo_pf')		//----
																				. ' OR'																										//----
																				. $this->_db->quoteName('ClassePremium.sexo_genero') . '=' . $this->_db->quote('2')							//----
																			. ')'																											//----
																			. ' AND '																										//----
																			. '('																											//----
																				. $this->_db->quoteName('ClassePremium.idade_min_categoria').'='. $this->_db->quote('0')					//----
																				. ' OR '																									//----
																				. $this->_db->quoteName('ClassePremium.idade_min_categoria').'<= ResultClass.idadeClass'					//----
																			. ')'																											//----
																			. ' AND '																										//----
																			. '('																											//----
																				. $this->_db->quoteName('ClassePremium.idade_max_categoria').'='. $this->_db->quote('0')					//----
																				. ' OR '																									//----
																				. $this->_db->quoteName('ClassePremium.idade_max_categoria').'>= ResultClass.idadeClass'					//----
																			. ')'																											//----
																			. ' AND '																										//----
																			. '('																											//----
																				. $this->_db->quoteName('ClassePremium.ponto_min_classe').'='. $this->_db->quote('0')						//----
																				. ' OR '																									//----
																				. $this->_db->quoteName('ClassePremium.ponto_min_classe').' <= ResultClass.rfClass'							//----
																			. ')'																											//----
																			. ' AND '																										//----
																			. '('																											//----
																				. $this->_db->quoteName('ClassePremium.ponto_max_classe').'='. $this->_db->quote('0')						//----
																				. ' OR '																									//----
																				. $this->_db->quoteName('ClassePremium.ponto_max_classe').'>= ResultClass.rfClass'							//----
																			. ')'																											//----
																			. ' AND'																										//----
																			. $this->_db->quoteName('ClassePremium.pcd_categoria').'='. $this->_db->quoteName('ResultClass.pcd_pf')			//----
																			. ' AND'																										//----
																			. $this->_db->quoteName('ClassePremium.parent_prova').'='. $this->_db->quoteName('id_prova')					//----
																			. ' AND'																										//----
																			. $this->_db->quoteName('ClassePremium.name_categoria') . ' LIKE ' . $this->_db->quote('%premium%')				//----
																		.')' );																												//----
																																															//----
		$queryNewClass->leftJoin('(' . $queryClasses . ') Classes ON('																														//----
																	. '('																													//----
																	. $this->_db->quoteName('Classes.sexo_genero').'='. $this->_db->quoteName('ResultClass.sexo_pf')						//----
																	. ' OR'																													//----
																	. $this->_db->quoteName('Classes.sexo_genero') . '=' . $this->_db->quote('2')											//----
																	. ')'																													//----
																	. ' AND '																												//----
																	. '('																													//----
																		. $this->_db->quoteName('Classes.idade_min_categoria').'='. $this->_db->quote('0')									//----
																		. ' OR '																											//----
																		. $this->_db->quoteName('Classes.idade_min_categoria').'<= ResultClass.idadeClass'									//----
																	. ')'																													//----
																	. ' AND '																												//----
																	. '('																													//----
																		. $this->_db->quoteName('Classes.idade_max_categoria').'='. $this->_db->quote('0')									//----
																		. ' OR '																											//----
																		. $this->_db->quoteName('Classes.idade_max_categoria').'>= ResultClass.idadeClass'									//----
																	. ')'																													//----
																	. ' AND '																												//----
																	. '('																													//----
																		. $this->_db->quoteName('Classes.ponto_min_classe').'='. $this->_db->quote('0')										//----
																		. ' OR '																											//----	
																		. $this->_db->quoteName('Classes.ponto_min_classe').' <= ResultClass.rfClass'										//----
																	. ')'																													//----
																	. ' AND '																												//----
																	. '('																													//----
																		. $this->_db->quoteName('Classes.ponto_max_classe').'='. $this->_db->quote('0')										//----
																		. ' OR '																											//----
																		. $this->_db->quoteName('Classes.ponto_max_classe').'>= ResultClass.rfClass'										//----
																	. ')'																													//----
																	. ' AND'																												//----
																	. $this->_db->quoteName('Classes.pcd_categoria').'='. $this->_db->quoteName('ResultClass.pcd_pf')						//----
																	. ' AND'																												//----
																	. $this->_db->quoteName('Classes.parent_prova').'='. $this->_db->quoteName('id_prova')									//----
																	. ' AND'																												//----
																	. $this->_db->quoteName('ClassePremium.name_categoria') . ' IS NULL '													//----
																.')' );																														//----
																																															//----
		$queryNewClass->group($this->_db->quoteName('id_inscricao'));																														//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------		
		

		$query = $this->_db->getQuery(true);
		$query->select( $this->_db->quoteName(array( 'id_inscricao',
													 'id_prova',
													 'id_user',
													 'AtletaDados.name',
													 'NewClass.name_categoria',
													 'NewClass.name_classe',													 
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
			$query->select( 'ROUND ( ('.$queryFinal.') ) AS rAddColsBefore1');
			$query->select( 'ROUND ( ( (' . $queryFinal . ')/( IF(COUNT(Resultados.rf)> ' . $options['results_classe_prova'] .', '. $options['results_classe_prova'] .', COUNT(Resultados.rf)) )), 3 ) AS rf');		
			
		}
			

		$query->from( $this->_db->quoteName('#__ranking_inscricao') );
		$query->innerJoin('(' . $queryRanking . ') Resultados USING('. $this->_db->quoteName('id_inscricao').','. $this->_db->quoteName('id_classe').')' );	
		$query->innerJoin('(' . $queryNewClass . ') NewClass USING('. $this->_db->quoteName('id_inscricao').')' );	

		$query->innerJoin( '#__ranking_campeonato USING( id_campeonato )' );
		$query->innerJoin( '#__ranking_modalidade USING( id_modalidade )' );
		$query->innerJoin( '#__ranking_prova USING( id_prova, id_campeonato )' );
	
		$query->innerJoin( '#__intranet_pf USING('. $this->_db->quoteName('id_user').')' );
		$query->innerJoin( $this->_db->quoteName('#__intranet_associado') . ' USING('. $this->_db->quoteName('id_user').')' );
		
		$query->innerJoin( '#__users AtletaDados ON('. $this->_db->quoteName('AtletaDados.id'). ' = '. $this->_db->quoteName('id_user'). ')' );
		$query->leftJoin( '#__users EquipeClube ON('. $this->_db->quoteName('id_equipe') . '='. $this->_db->quoteName('EquipeClube.id'). ')' );
																	
		if(!empty($options['id_prova']))	
			$query->where('id_prova = ' . $this->_db->quote( $options['id_prova'] ));

																
		$query->group($this->_db->quoteName('id_inscricao'));
		
		$query->order($this->_db->quoteName('name'));

		$this->_db->setQuery($query);
		return 	$this->_db->loadObjectList();	
		
			
	}



		
	function getSpecialEtapaView( $type, $special_status_etapa, $special_value_etapa, $special_text_etapa	)
	{
		$returnView = '';
		$returnView = '<label id="special_status_etapa-lbl" for="special_status_etapa" class="hasTip required">';
		$returnView .= JText::_( 'TORNEIOS_VIEWS_ETAPA_EDIT_TIROABALA_SPECIAL_ETAPA_STATUS'  );  
		$returnView .= ':<span class="star">&nbsp;*</span></label>';   
		$returnView .= '<fieldset class="radio inputbox">';   
		$returnView .=  JHTML::_('select.booleanlist', 'special_status_etapa', 'class="inputbox" ', $special_status_etapa);
		$returnView .= '</fieldset>';   
		$returnView .= '<input type="hidden" name="special_value_etapa" id="special_value_etapa" />';
		$returnView .= '<input type="hidden" name="special_text_etapa" id="special_text_etapa" />';
		$returnView .=$base_dir;
		return  $returnView;		
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
		$queryRanking->select( 'MAX((ResultsRF.RF)) AS rf');																																//----
		$queryRanking->from( '(' . $queryResultsRF . ') ResultsRF' );																														//----
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
		$queryPosition = $this->_db->getQuery(true);																																		//----
																																															//----
		$queryPosition->select('id_inscricao AS RankInscricao');																															//----
		$queryPosition->select('id_associado AS RankAssociado');																															//----
		$queryPosition->select('shotoff_inscricao AS RankSrotOff');																															//----								//----
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
		$queryPosition->select( 'SUM(ResultadoPosition.rf) as rAddColsBefore1' );																											//---
																																															//----
		if(!empty($RF))																																										//----
			$queryPosition->select( 'ROUND ( ('.$RF.') ) AS RankRf');																														//----
																																															//----
		$queryPosition->from( '(' . $queryRanking . ') ResultadoPosition' );																												//----	
		$queryPosition->innerJoin($this->_db->quoteName('#__ranking_inscricao') . ' USING('. $this->_db->quoteName('id_inscricao').','. $this->_db->quoteName('id_classe').')' );			//----
																																															//----
		$queryPosition->order($this->_db->quoteName('RankRf') . ' DESC');																													//----	
		$queryPosition->order($this->_db->quoteName('RankSrotOff') . ' DESC');																												//----
		$queryPosition->order($this->_db->quoteName('rAddColsBefore1') . ' DESC');																											//----
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
		$queryRank->select( 'GROUP_CONCAT( ResultadoRank.Rankinscricao ORDER BY ResultadoRank.Rankrf DESC, ResultadoRank.RankSrotOff DESC, ResultadoRank.rAddColsBefore1 DESC' 				//----
		. $order . ' )' );																																									//----
																																															//----
		$queryRank->from( '(' . $queryPosition . ') ResultadoRank' );																														//----	
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------																																												
																																															//----
		$queryHoje = $this->_db->getQuery(true);																																			//----
		$queryHoje->select('id_inscricao');																																					//----
		$queryHoje->select( 'MAX((ResultsRH.RF)) AS rh');																																	//----
		$queryHoje->from( '(' . $queryResultsRF . ') ResultsRH' );																															//----
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

													 '#__intranet_associado.id_associado',
												    )));
		$query->select('image_pf AS image');
		
		$query->select( 'FIND_IN_SET( Resultados.id_inscricao, (' . $queryRank . ') ) AS rank');
		$query->select( 'ROUND( ResultadoHoje.rh ) AS rhoje');
		
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
		
		$query->select( 'SUM(Resultados.rf) as rAddColsBefore1' );
		$query->select( 'ROUND (( (SUM(Resultados.rf)) / (COUNT(Resultados.rf)) ), 3 ) as rAddColsBefore2');

		$SomaAddColsBefore3 = '';
		for ($i = 1; $i <= 6; $i++) { 
			if(!empty($SomaAddColsBefore3))
				$SomaAddColsBefore3 .= ' + ';
		
			$SomaAddColsBefore3 .= 	'IF(COUNT(Resultados.rf) > '.($i-1)
									. ', SUBSTRING_INDEX( SUBSTRING_INDEX( GROUP_CONCAT( Resultados.rf ORDER BY Resultados.rf DESC ), '
									. $this->_db->quote(',').', '.$i.'),'.$this->_db->quote(',').', -1), 0)';	
		}
		
		if(!empty($SomaAddColsBefore3))
			$query->select( 'ROUND ( ('.$SomaAddColsBefore3.') ) AS rAddColsBefore3');
			
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
			$query->select( 'ROUND ( ('.$queryFinal.') ) AS rf');


		$query->from( $this->_db->quoteName('#__ranking_inscricao') );
		$query->innerJoin('(' . $queryRanking . ') Resultados USING('. $this->_db->quoteName('id_inscricao').','. $this->_db->quoteName('id_classe').')' );	
		$query->leftJoin('(' . $queryHoje . ') ResultadoHoje USING('. $this->_db->quoteName('id_inscricao').')' );
		
		//echo 'asdad';
		//exit;
		
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
			
		//if($options['name'])
		//	$query->where( $this->_db->quoteName('AtletaDados.name') . ' LIKE \'%' . $this->_db->escape($options['name']) . '%\'' );
		
		//if(isset($options['id_user']) && $options['id_user'])
		//	$query->where($this->_db->quoteName('#__ranking_inscricao.id_user') . ' = ' . $this->_db->quote( $options['id_user'] ));
			
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
		
		$query->order($this->_db->quoteName('rf') . ' DESC');
		
		$query->order($this->_db->quoteName('shotoff_inscricao') . ' DESC');

		$query->order($this->_db->quoteName('rAddColsBefore1') . ' DESC');


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
		
		//for ($i = self::addColsAfterEtapa; $i >= 1; $i--) { 
		//	$title[] = '	<span style="width:10%; float:right; top: 5px; text-align:center">' .mb_strtoupper( JText::sprintf('TORNEIOS_VIEWS_RESULT_ETAPA_NAME_ADDCOLSAFTER_TH_' . self::tagLanguage . $options['type_prova'] , $i) ) . '</span>';		 
		//}
		
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
		$concatOS = 'CONCAT(\'0.\','.implode(',',$concatOS).')';																															//----
			
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
									. '('.$concatOS.') +	'																																//----
									. ' IF(ISNULL(ResultadoRank.shotoff_inscricao_etapa), 0,CONCAT(\'0.00000000\',ResultadoRank.shotoff_inscricao_etapa))'									//----
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
		//$query->select('IF( EquipeClube.id = 7617, \'AVULSO\', EquipeClube.name) AS name_equipe');
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
		//	for ($x = 1; $x <= $options['ns_etapa_prova']; $x++) { 
	//			$query->select( 'IF(ISNULL(Resultados.r' . $i . 's' . $x . '), NULL, Resultados.r' . $i . 's' . $x . ') AS r' . $i . 's' . $x);
	//		}
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

	function getGenCatClass( $options = array())
	{
		
		$query = $this->_db->getQuery(true);
		$query->select( $this->_db->quoteName(array('sexo_pf','pcd_pf')) );
		$query->select( 'TIMESTAMPDIFF(YEAR,  CONCAT( YEAR(data_nascimento_pf), "-01-01" ), NOW()) AS idade_pf');
		$query->from( $this->_db->quoteName('#__intranet_pf') );
		$query->where($this->_db->quoteName('id_user') . ' = ' . $this->_db->quote(   $options['id_user'] ) );
		$this->_db->setQuery($query);
		$infoUser = $this->_db->loadObject();
		
		if(!(boolean) $infoUser = $this->_db->loadObject()):	
			$infoUser = new stdClass();
			$infoUser->sexo_pf = NULL;
			$infoUser->idade_pf = NULL;
			$infoUser->pcd_pf = NULL;
		endif;
		
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
			$valor_atleta_classe = $infoClass->valor_atleta_classe; 
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


	function getPosition(  $options = array() )
	{		
	
		$query = $this->_db->getQuery(true);
		/*
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


		$query->select( 'MAX(IF(ResultadoSerie.id_inscricao = C.id_inscricao, ResultadoSerie.RSerie, 0)) AS rf');
		
		
		for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) {  
			$query->select('OrderRanking.OrderS' . $i . ' AS OrderS' . $i);
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
			
											
											
		$queryRankResults->from( '#__ranking_campeonato RANCAMP' );									
		$queryRankResults->innerJoin( '#__ranking_modalidade RANMODA  USING( id_modalidade )' );																							//----
		$queryRankResults->innerJoin( '#__ranking_prova RANPROV USING( id_campeonato )' );																										//----
																																															//----
																								//----									
											
		$queryRankResults->innerJoin( '#__ranking_inscricao RANINSC USING( id_campeonato, id_prova )' );												//----
		
		$queryRankResults->innerJoin( '#__ranking_genero RANGENE USING( id_genero )' );																										//----
		$queryRankResults->innerJoin( '#__ranking_categoria RANCATE USING( id_categoria )' );																								//----
		$queryRankResults->innerJoin( '#__ranking_classe RANCLAS USING( id_classe )' );				
		
		
		$queryRankResults->innerJoin( '#__ranking_resultado RANRESU USING( id_inscricao )' );						
											
							/*																																								//----
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
		*/																																													
																																															
																																															
		$queryRankResults->innerJoin( '#__ranking_profile RANPROF USING('. $this->_db->quoteName('id_user').')' );																			//----
		$queryRankResults->innerJoin( '#__users RANUSER ON('. $this->_db->quoteName('id'). ' = '. $this->_db->quoteName('id_user').')' );													//----
																																															//----
		if(!empty($options['id_etapa']))																																					//----
			$queryRankResults->where('RANRESU.id_etapa = ' . $this->_db->quote( $options['id_etapa'] ));																					//----
																																															//----
		if(!empty($options['id_prova']))																																					//----
			$queryRankResults->where('RANINSC.id_prova = ' . $this->_db->quote( $options['id_prova'] ));																					//----
			
		$queryRankResults->where( '(' . $this->_db->quoteName('block') . '=' . $this->_db->quote( '0' ) . ' OR ' .  $this->_db->quoteName('ano_campeonato') . ' < YEAR(CURDATE()) )' );		//----
																																															//----
		if( !$this->_user->authorise('torneios.diretor.geral', 'com_torneios') )																											//----
			$queryRankResults->where($this->_db->quoteName('RANMODA.status_modalidade') . ' = ' . $this->_db->quote('1'));																	//----
																																															//----
		if( !$this->_user->authorise('torneios.diretor.modalidade', 'com_torneios') )																										//----
		{																																													//----
			$queryRankResults->where($this->_db->quoteName('RANCAMP.status_campeonato') . ' = ' . $this->_db->quote('1'));																	//----
			$queryRankResults->where($this->_db->quoteName('RANPROV.status_prova') . ' = ' . $this->_db->quote('1'));																		//----
			//$queryRankResults->where($this->_db->quoteName('RANIETA.status_inscricao_etapa') . ' = ' . $this->_db->quote('1'));																//----
		}																																													//----			
																																															//----
		if(!empty($options['id_classe']))																																					//----
			$queryRankResults->where('RANINSC.id_classe = ' . $this->_db->quote( $options['id_classe'] ));																					//----
																																															//----
		if(!empty($options['id_categoria']))																																				//----
			$queryRankResults->where('RANINSC.id_categoria = ' . $this->_db->quote( $options['id_categoria'] ));																			//----
																																															//----
		if(!empty($options['id_genero']))																																					//----
			$queryRankResults->where('RANINSC.id_genero = ' . $this->_db->quote( $options['id_genero'] ));																					//----
																																															//----
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


		$query->from( '#__ranking_campeonato H' );
		$query->innerJoin( '#__ranking_modalidade HH  USING( id_modalidade )' );
		$query->innerJoin( '#__ranking_prova HHH USING( id_campeonato )' );

		$query->innerJoin( '#__ranking_inscricao CC USING( id_campeonato, id_prova )' );
		
		$query->innerJoin( '#__ranking_genero CCCC USING( id_genero )' );
		$query->innerJoin( '#__ranking_categoria CCCCC USING( id_categoria )' );		
		$query->innerJoin( '#__ranking_classe CCCCCC USING( id_classe )' );

		$query->innerJoin( '#__ranking_resultado C USING( id_inscricao )' );

/*
		
		$query->from( '#__ranking_resultado C' );
		$query->innerJoin( '#__ranking_inscricao CC USING( id_inscricao )' );
		$query->innerJoin( '#__ranking_inscricao_etapa CCC USING( id_inscricao,  id_etapa )' );
		
		
		$query->innerJoin( '#__ranking_campeonato H USING( id_campeonato )' );
		$query->innerJoin( '#__ranking_modalidade HH  USING( id_modalidade )' );
		$query->innerJoin( '#__ranking_prova HHH USING( id_prova )' );

		$query->innerJoin( '#__ranking_genero CCCC USING( id_genero )' );
		$query->innerJoin( '#__ranking_categoria CCCCC USING( id_categoria )' );		
		$query->innerJoin( '#__ranking_classe CCCCCC USING( id_classe )' );
		
*/		
		$query->innerJoin( '#__ranking_profile U USING('. $this->_db->quoteName('id_user').')' );
		$query->innerJoin( '#__users UU ON('. $this->_db->quoteName('id'). ' = '. $this->_db->quoteName('id_user'). ')' );
		$query->leftJoin( '#__intranet_cidade UUU USING('. $this->_db->quoteName('id_cidade').')' );
	
	
	
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
		$queryRF->select( 'RFRES.id_inscricao');																																//----																												//----
		$queryRF->select( 'RFRES.nr_etapa_prova');																																//----
		$queryRF->select( 'RFRES.ordering');																																	//----
		
		
		
		
		$queryRF->from( '#__ranking_campeonato RFCAM' );	
		
		$queryRF->innerJoin( '#__ranking_modalidade RFMOD  USING( id_modalidade )' );																							//----
		$queryRF->innerJoin( '#__ranking_prova RFPRO USING( id_campeonato )' );																										//----
												
																																												//----
		$queryRF->innerJoin( '#__ranking_inscricao RFINS USING( id_campeonato, id_prova )' );																								//----
																																												//----
		$queryRF->innerJoin( '#__ranking_genero RFGEN USING( id_genero )' );																									//----
		$queryRF->innerJoin( '#__ranking_categoria RFCAT USING( id_categoria )' );																								//----
		$queryRF->innerJoin( '#__ranking_classe RFCLA USING( id_classe )' );																									//----
		$queryRF->innerJoin( '#__ranking_resultado RFRES USING( id_inscricao )' );																										//----
		
		
		
		/*
		
		
		
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
		
		
		*/
																																												//----
		$queryRF->innerJoin( '#__ranking_profile RFPER USING('. $this->_db->quoteName('id_user').')' );																			//----
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
		//	$queryRF->where($this->_db->quoteName('RFIET.status_inscricao_etapa') . ' = ' . $this->_db->quote('1'));															//----
		}																																										//----
		
		if(!empty($options['id_etapa']))																																		//----
			$queryRF->where('RFRES.id_etapa = ' . $this->_db->quote( $options['id_etapa'] ));																					//----
																																												//----
		if(!empty($options['id_prova']))																																		//----
			$queryRF->where('RFINS.id_prova = ' . $this->_db->quote( $options['id_prova'] ));																					//----
		
																																												//----
																																												//----
		if(!empty($options['id_classe']))																																		//----
			$queryRF->where('RFINS.id_classe = ' . $this->_db->quote( $options['id_classe'] ));																					//----
																																												//----
		if(!empty($options['id_categoria']))																																	//----
			$queryRF->where('RFINS.id_categoria = ' . $this->_db->quote( $options['id_categoria'] ));																			//----
																																												//----
		if(!empty($options['id_genero']))																																		//----
			$queryRF->where('RFINS.id_genero = ' . $this->_db->quote( $options['id_genero'] ));																					//----
																																												//----
																																												//----
		$queryRF->group('RFRES.id_inscricao');																																	//----
		$queryRF->group('RFRES.nr_etapa_prova');																																//----
																																												//----
		$query->innerJoin('(' . $queryRF . ') ResultadoSerie USING('. $this->_db->quoteName('id_inscricao').')' );																//----
		// ------------------------------------------------------ Fim Resultado Fina (Série) -----------------------------------------------------------------------------------------
	

		//------------------------------------------------------------------------------------ MELHOR SÉRIE --------------------------------------------------------------------------------------
																																															//----
		$queryOrderResult = $this->_db->getQuery(true);																																		//----
																																															//----
		$queryOrderResult->select( 'ORDPROF.matricula AS OrderMatricula');																													//----
																																															//----
		for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) {  																															//----
			$queryOrderResult->select( 'MAX(IF( ORDRESU.ordering = ' . $i . ', ORDRESU.value_resultado, NULL)) AS OrderS' . $i);															//----
		}																																													//----
																																															//----
		switch( $options['rs_etapa_prova'] )																																				//----
		{																																													//----
			case '1':																																										//----
				$queryOrderResult->select( 'MAX(ORDRESU.value_resultado) AS OrderRF');																										//----
			break;																																											//----
																																															//----
			case '2':																																										//----
				$RFString = '';																																								//----
				for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) { 																														//----
					if(!empty($RFString))																																					//----
						$RFString = $RFString . ' + ';																																		//----
					$RFString = $RFString . ' MAX(IF(ORDRESU.ordering = '. $i .', ORDRESU.value_resultado, 0)) ';																			//----
				}																																											//----
				if($options['decimal_prova']==0)																																			//----
					$options['decimal_prova'] = '2';																																		//----
																																															//----
				$queryOrderResult->select( 'ROUND ( ( (' . $RFString . ') / (' . $options['ns_etapa_prova'] . ')), '. $options['decimal_prova'] .' ) AS OrderRF');							//----
																																															//----
			break;																																											//----
																																															//----
			case '3':																																										//----
				$RFString = '';																																								//----
				for ($i = 1; $i <= $options['ns_etapa_prova']; $i++) { 																														//----
					if(!empty($RFString))																																					//----
						$RFString = $RFString . ' + ';																																		//----
					$RFString = $RFString . ' MAX(IF(ORDRESU.ordering = '. $i .', ORDRESU.value_resultado, 0)) ';																			//----
				}																																											//----
																																															//----
				$queryOrderResult->select( '( ' . $RFString . ' ) AS OrderRF');																												//----
			break;																																											//----
		}																																													//----
																																															//----
		$queryOrderResult->select( 'id_inscricao');																																			//----
								
		$queryOrderResult->from( '#__ranking_campeonato ORDCAMP' );							
		$queryOrderResult->innerJoin( '#__ranking_modalidade ORDMODA  USING( id_modalidade )' );																							//----
		$queryOrderResult->innerJoin( '#__ranking_prova ORDPROV USING( id_campeonato )' );																									//----
																																															//----
		$queryOrderResult->innerJoin( '#__ranking_inscricao ORDINSC USING( id_campeonato, id_prova )' );																					//----
		
		$queryOrderResult->innerJoin( '#__ranking_genero ORDGENE USING( id_genero )' );																										//----
		$queryOrderResult->innerJoin( '#__ranking_categoria ORDCATE USING( id_categoria )' );																								//----
		$queryOrderResult->innerJoin( '#__ranking_classe ORDCLAS USING( id_classe )' );																										//----						
								
																																															//----
		$queryOrderResult->innerJoin( '#__ranking_resultado ORDRESU USING( id_inscricao )' );																					//----
								
			/*																																												//----
		$queryOrderResult->from( '#__ranking_resultado ORDRESU' );																															//----
																																															//----
		$queryOrderResult->innerJoin( '#__ranking_inscricao ORDINSC USING( id_inscricao )' );																								//----
																																															//----
		$queryOrderResult->innerJoin( '#__ranking_inscricao_etapa ORDIETA USING( id_inscricao,  id_etapa )' );																				//----
																																															//----
		$queryOrderResult->innerJoin( '#__ranking_campeonato ORDCAMP USING( id_campeonato )' );																								//----
		$queryOrderResult->innerJoin( '#__ranking_modalidade ORDMODA  USING( id_modalidade )' );																							//----
		$queryOrderResult->innerJoin( '#__ranking_prova ORDPROV USING( id_prova )' );																										//----
																																															//----
		$queryOrderResult->innerJoin( '#__ranking_genero ORDGENE USING( id_genero )' );																										//----
		$queryOrderResult->innerJoin( '#__ranking_categoria ORDCATE USING( id_categoria )' );																								//----
		$queryOrderResult->innerJoin( '#__ranking_classe ORDCLAS USING( id_classe )' );																										//----
																																															//----
		
		*/
		
		$queryOrderResult->innerJoin( '#__ranking_profile ORDPROF USING('. $this->_db->quoteName('id_user').')' );																			//----
		$queryOrderResult->innerJoin( '#__users ORDUSER ON('. $this->_db->quoteName('id'). ' = '. $this->_db->quoteName('id_user').')' );													//----
																																															//----
																																															
		if(!empty($options['id_etapa']))																																					//----
			$queryOrderResult->where('ORDRESU.id_etapa = ' . $this->_db->quote( $options['id_etapa'] ));																					//----
																																															//----
		if(!empty($options['id_prova']))																																					//----
			$queryOrderResult->where('ORDINSC.id_prova = ' . $this->_db->quote( $options['id_prova'] ));																					//----
																																															//----
		$queryOrderResult->where( '(' . $this->_db->quoteName('block') . '=' . $this->_db->quote( '0' ) . ' OR ' .  $this->_db->quoteName('ano_campeonato') . ' < YEAR(CURDATE()) )' );		//----
																																															//----
		if( !$this->_user->authorise('torneios.diretor.geral', 'com_torneios') )																											//----
			$queryOrderResult->where($this->_db->quoteName('ORDMODA.status_modalidade') . ' = ' . $this->_db->quote('1'));																	//----
																																															//----
		if( !$this->_user->authorise('torneios.diretor.modalidade', 'com_torneios') )																										//----
		{																																													//----
			$queryOrderResult->where($this->_db->quoteName('ORDCAMP.status_campeonato') . ' = ' . $this->_db->quote('1'));																	//----
			$queryOrderResult->where($this->_db->quoteName('ORDPROV.status_prova') . ' = ' . $this->_db->quote('1'));																		//----
			//$queryOrderResult->where($this->_db->quoteName('ORDIETA.status_inscricao_etapa') . ' = ' . $this->_db->quote('1'));																//----
		}																																													//----
																																															//----
		if(!empty($options['id_classe']))																																					//----
			$queryRankResults->where('ORDINSC.id_classe = ' . $this->_db->quote( $options['id_classe'] ));																					//----
																																															//----
		if(!empty($options['id_categoria']))																																				//----
			$queryOrderResult->where('ORDINSC.id_categoria = ' . $this->_db->quote( $options['id_categoria'] ));																			//----
																																															//----
		if(!empty($options['id_genero']))																																					//----
			$queryOrderResult->where('ORDINSC.id_genero = ' . $this->_db->quote( $options['id_genero'] ));																					//----
																																															//----
		$queryOrderResult->group('ORDRESU.id_inscricao');																																	//----
		$queryOrderResult->group('ORDRESU.nr_etapa_prova');																																	//----
																																															//----
		$queryOrderResult->order('OrderRF DESC');																																			//----
		for ($i = $options['ns_etapa_prova']; $i > 0; $i--) { 																																//----
			$queryOrderResult->order('OrderS' . $i . ' DESC');																																//----
		}																																													//----
																																															//----
		$query->innerJoin('(' . $queryOrderResult . ') OrderRanking USING('. $this->_db->quoteName('id_inscricao').')' );																	//----
																																															//----
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		
		if( $options['id_user'] )
			$query->where('CC.id_user = ' . $this->_db->quote( $options['id_user'] ) );			
									
		if(!empty($options['id_etapa']))
			$query->where('C.id_etapa = ' . $this->_db->quote( $options['id_etapa'] ) );	

		if(!empty($options['id_prova']))
			$query->where('CC.id_prova = ' . $this->_db->quote( $options['id_prova'] ) );
					
		if(!empty($options['id_classe']))
			$query->where('CC.id_classe = ' . $this->_db->quote( $options['id_classe'] ) );
						
		if(!empty($options['id_categoria']))
			$query->where('CC.id_categoria = ' . $this->_db->quote( $options['id_categoria'] ) );			
			
		if(!empty($options['id_genero']))
			$query->where('CC.id_genero = ' . $this->_db->quote( $options['id_genero'] ) );	

		$query->where( '(' . $this->_db->quoteName('block') . '=' . $this->_db->quote( '0' ) . ' OR ' .  $this->_db->quoteName('ano_campeonato') . ' < YEAR(CURDATE()) )' );

		if( !$this->_user->authorise('torneios.diretor.geral', 'com_torneios') )
			$query->where($this->_db->quoteName('HH.status_modalidade') . ' = ' . $this->_db->quote('1'));	

		if( !$this->_user->authorise('torneios.diretor.modalidade', 'com_torneios') )
		{
			$query->where($this->_db->quoteName('H.status_campeonato') . ' = ' . $this->_db->quote('1'));
			$query->where($this->_db->quoteName('HHH.status_prova') . ' = ' . $this->_db->quote('1'));
		//	$query->where($this->_db->quoteName('CCC.status_inscricao_etapa') . ' = ' . $this->_db->quote('1'));
		}
			
		$query->group($this->_db->quoteName('C.id_inscricao'));
		
	//	$query->order($this->_db->quoteName('rf') . ' DESC');
		
	//	for ($i = $options['ns_etapa_prova']; $i > 0; $i--) {  		
	//		$query->order($this->_db->quoteName('OrderS'.$i) . ' DESC');		
	//	}

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
		$queryRankResults->innerJoin( '#__ranking_profile RANPROF USING('. $this->_db->quoteName('id_user').')' );																			//----
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
		
		$query->innerJoin( '#__ranking_profile U USING('. $this->_db->quoteName('id_user').')' );
		$query->innerJoin( '#__users UU ON('. $this->_db->quoteName('id'). ' = '. $this->_db->quoteName('id_user').')' );
		$query->leftJoin( '#__intranet_cidade UUU USING('. $this->_db->quoteName('id_cidade').')' );		
		
		
	
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
		$queryRF->select( 'RFRES.id_inscricao');																																//----																												//----
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
		$queryRF->innerJoin( '#__ranking_profile RFPER USING('. $this->_db->quoteName('id_user').')' );																			//----
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
