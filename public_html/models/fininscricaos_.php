<?php
/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */

defined( '_JEXEC' ) or die;

jimport( 'joomla.application.component.modellist' );

class IntranetModelFinInscricaos extends JModelList
{
	var $_db = null;
	var $_user = null;
	var $_app = null;	
	var $_pagination = null;
	var $_total = null;	
	var $_data = null;
	var $_trataImagem = null;
	var $_session = null;
	var $_siteOffset = null;
	
	function __construct()
	{	
		parent::__construct();
		
		$this->_db	= JFactory::getDBO();
		$this->_user = JFactory::getUser();
		$this->_app = JFactory::getApplication();
		$this->_siteOffset = $this->_app->getCfg('offset');
		
	}
		
    protected function populateState($ordering = null, $direction = null) {

		$limit		= $this->_app->getUserStateFromRequest( 
		$this->context . '.list.limit', 'limit', $this->_app->getCfg('list_limit'), 'int' );
		
		$limitstart	= $this->_app->getUserStateFromRequest( 
		$this->context . '.list.start', 'start', 0, 'int' );

		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
	   	$this->setState('list.limit', $limit); 
	  	$this->setState('list.start', $limitstart); 
		
        $status = $this->_app->getUserStateFromRequest(
        $this->context .'filter.status', 'status', null,'string');
        $this->setState('filter.status', $status);
		
        $modalidade = $this->_app->getUserStateFromRequest(
        $this->context .'filter.modalidade', 'modalidade', null,'string');
        $this->setState('filter.modalidade', $modalidade);	
		
        $situacao = $this->_app->getUserStateFromRequest(
        $this->context .'filter.situacao', 'situacao', null,'string');
        $this->setState('filter.situacao', $situacao);
			
        $registrado = $this->_app->getUserStateFromRequest(
        $this->context .'filter.registrado', 'registrado', null,'string');
        $this->setState('filter.registrado', $registrado);
		
        $atualizado = $this->_app->getUserStateFromRequest(
        $this->context .'filter.atualizado', 'atualizado', null,'string');
        $this->setState('filter.atualizado', $atualizado);
		
        $metodos = $this->_app->getUserStateFromRequest(
        $this->context .'filter.metodos', 'metodos', null,'string');
        $this->setState('filter.metodos', $metodos);
		
        $anuidades = $this->_app->getUserStateFromRequest(
        $this->context .'filter.anuidades', 'anuidades', null,'string');
        $this->setState('filter.anuidades', $anuidades);
		
        $produtos = $this->_app->getUserStateFromRequest(
        $this->context .'filter.produtos', 'produtos', null,'string');
        $this->setState('filter.produtos', $produtos);
		
		$datetype = $this->_app->getUserStateFromRequest(
        $this->context .'filter.datetype', 'datetype', '0','string');
        $this->setState('filter.datetype', $datetype);
		
        $datei = $this->_app->getUserStateFromRequest(
        $this->context .'filter.datei', 'datei', null,'string');
        $this->setState('filter.datei', $datei);
		
        $datef = $this->_app->getUserStateFromRequest(
        $this->context .'filter.datef', 'datef', null,'string');
        $this->setState('filter.datef', $datef);

        $search = $this->_app->getUserStateFromRequest(
        $this->context .'filter.search', 'search', null,'string');
        $this->setState('filter.search', $search);  	

		$ordering = $this->_app->getUserStateFromRequest(
        $this->context .'list.ordering','filter_order', 'ordering', 'string');
		$this->setState('list.ordering', $ordering); 

 		$direction = $this->_app->getUserStateFromRequest(
        $this->context .'list.direction','filter_order_Dir', 'DESC','string');
		$this->setState('list.direction', $direction); 

	   parent::populateState($ordering, $direction );
		
    }

    protected function getListQuery() {


		$taxas = $this->getValueTaxas();

		$queryInsricao = $this->_db->getQuery(true);
		$queryInsricao->select( array('id_etapa',
							 		  'id_inscricao',
							 		  'id_local',
									));			  
		$queryInsricao->select( 'COUNT(DISTINCT( IF(rf_inscricao_prova=1,#__ranking_resultado.nr_etapa_prova, #__ranking_resultado.id_inscricao ) ) ) AS inscricoes');
		$queryInsricao->from( '#__ranking_inscricao');
		$queryInsricao->innerJoin( $this->_db->quoteName('#__ranking_inscricao_etapa') . 'USING('. $this->_db->quoteName('id_inscricao').')' );
		$queryInsricao->innerJoin( $this->_db->quoteName('#__ranking_prova') . 'USING('. $this->_db->quoteName('id_prova') .','. $this->_db->quoteName('id_campeonato') .')' );

		$queryInsricao->innerJoin( $this->_db->quoteName('#__ranking_resultado') . 'USING('. $this->_db->quoteName('id_inscricao')  .','. $this->_db->quoteName('id_etapa'). ')' );
		$queryInsricao->where( $this->_db->quoteName('contabilidade_prova') . '=' . $this->_db->quote( '1' ) );
	
		//$queryInsricao->where( $this->_db->quoteName('id_prova') . '=' . $this->_db->quote( '376' ) );
		$queryInsricao->group( $this->_db->quoteName('id_etapa') );
		$queryInsricao->group( $this->_db->quoteName('#__ranking_inscricao.id_inscricao') );


		$query = $this->_db->getQuery(true);	
		
		$query->select( array('id_etapa',
							  'id_campeonato',
							  'id_local',
							  'id_pagamento',
							  'name_etapa',
							  'name_campeonato',
							  'Clubes.name',
							  'status_pagamento',
							  'baixa_pagamento',
							  'vencimento_pagamento'
							
							
							
							));
		$query->select( 'CONCAT(id_etapa, \'-\', id_campeonato, \'-\', id_local) AS id_contabel');	
		$query->select( 'COUNT(Total.id_inscricao) AS inscricoes');				  
		
		$query->select( 'SUM(IF(Total.inscricoes > 1, (Total.inscricoes -1 ), 0 ) ) AS reinscricoes');	
		$query->select( '(COUNT(Total.id_inscricao)*'.$taxas->inscricao_inscricoes_taxas.') AS valor_inscricoes');	
		$query->select( '(SUM(IF(Total.inscricoes > 1, (Total.inscricoes -1 ), 0 ) )*'.$taxas->reinscricao_inscricoes_taxas.') AS valor_reinscricoes');	
		$query->select( '(
							(COUNT(Total.id_inscricao)*'.$taxas->inscricao_inscricoes_taxas.') 
							+
							(SUM(IF(Total.inscricoes > 1, (Total.inscricoes -1 ), 0 ) )*'.$taxas->reinscricao_inscricoes_taxas.')
						) AS valor_total');	
		


		$query->from( '(' . $queryInsricao . ') as Total');
		$query->innerJoin( $this->_db->quoteName('#__ranking_etapa') . 'USING('. $this->_db->quoteName('id_etapa').')' );
		$query->innerJoin( $this->_db->quoteName('#__ranking_campeonato') . 'USING('. $this->_db->quoteName('id_campeonato') .')' );
		$query->innerJoin( $this->_db->quoteName('#__users') . ' AS Clubes ON('. $this->_db->quoteName('Clubes.id').'='. $this->_db->quoteName('Total.id_local').')' );
	
		$query->leftJoin( $this->_db->quoteName('#__intranet_inscricoes_taxas_map') . 'USING('.$this->_db->quoteName('id_local').','.$this->_db->quoteName('id_etapa').','.$this->_db->quoteName('id_campeonato').')');

		$query->leftJoin( $this->_db->quoteName('#__intranet_pagamento') . 'USING('. $this->_db->quoteName('id_pagamento') .')' );
	



		$search = $this->getState('filter.search');
        if ($search!='') {	 
			// Escape the search token.
			$allBegin = '%';
			$allEnd = '%';
			
			if('#' == substr($search,0, 1)):
				$allBegin = '';
				$search = substr($search,1, strlen($search));
			endif;
			
			if('#' == substr($search, -1)):
				$allEnd = '';
				$search = substr($search, 0, -1);
			endif;
				
			$token	= $this->_db->quote($allBegin.$this->_db->escape($search).$allEnd);

			// Compile the different search clauses.
			$searches	= array();
			$searches[]	= 'name_etapa LIKE '.$token;
			$searches[]	= 'name_campeonato LIKE '.$token;
			//$searches[]	= 'id_pagamento LIKE '.$token;
			$searches[]	= 'name LIKE '.$token;
			
			$query->where('('.implode(' OR ', $searches).')');
		 
		}

		$situacao = $this->getState('filter.situacao');
        if ($situacao!=''):
			switch($situacao)
			{
				case '1': // A Vencer Em Dia
					$query->where( $this->_db->quoteName('#__intranet_pagamento.baixa_pagamento') . ' IS NULL'  );
					$query->where( $this->_db->quoteName('#__intranet_pagamento.vencimento_pagamento') . '>=' . $this->_db->quote(JFactory::getDate('now',  $this->_siteOffset)->toFormat('%Y-%m-%d', true) ) ) ;
				break;	
				
				case '2': // Em Aberto
					$query->where( $this->_db->quoteName('#__intranet_pagamento.baixa_pagamento') . ' IS NULL'  );
					$query->where( $this->_db->quoteName('#__intranet_pagamento.id_pagamento') . ' IS NOT NULL'  );
				break;	
				
				case '3': // Vencido
					$query->where( $this->_db->quoteName('#__intranet_pagamento.baixa_pagamento') . ' IS NULL'  );
					$query->where( $this->_db->quoteName('#__intranet_pagamento.vencimento_pagamento') . '<' . $this->_db->quote(JFactory::getDate('now',  $this->_siteOffset)->toFormat('%Y-%m-%d', true) ) ) ;
				break;						
				
				case '4': //Pago
					$query->where( $this->_db->quoteName('baixa_pagamento') . ' IS NOT NULL'  );
				break;
				case '5': // Vencido
					$query->where( $this->_db->quoteName('#__intranet_pagamento.id_pagamento') . ' IS NULL'  );
				break;	
			}
		endif;
		$query->group( $this->_db->quoteName('id_campeonato') );
		$query->group( $this->_db->quoteName('id_etapa') );
		$query->group( $this->_db->quoteName('id_local') );

		$query->order( $this->_db->quoteName('name_etapa') );
		$query->order( $this->_db->quoteName('name') );


		return $query;

    }


	function getValueTaxas(){

		$query = $this->_db->getQuery(true);	
		$query->select( $this->_db->quoteName(array('inscricao_inscricoes_taxas',
							  						'reinscricao_inscricoes_taxas')));
		$query->from($this->_db->quoteName('#__intranet_inscricoes_taxas'));
		$query->where( $this->_db->quoteName('id_inscricoes_taxas') .'='.$this->_db->quote('1'));
		$this->_db->setQuery($query);
		return $this->_db->loadObject(); 
	}	



	
	function getVTotal()
	{
			// Get a storage key.
		$querylist = $this->getListQuery();
		
		$query = $this->_db->getQuery(true);	
		$query->select('SUM(ListaInscricoes.inscricoes) AS inscricoes');
		$query->select('SUM(ListaInscricoes.reinscricoes) AS reinscricoes');
		$query->select('SUM(ListaInscricoes.valor_inscricoes) AS valor_inscricoes');
		$query->select('SUM(ListaInscricoes.valor_reinscricoes) AS valor_reinscricoes');
		$query->select('SUM(ListaInscricoes.valor_total) AS valor_total');
		$query->from('(' . $querylist . ') AS ListaInscricoes');
		$this->_db->setQuery($query);

		return $this->_db->loadObject(); 
	}
	
	function getTotalPagamento($id_contabel = null)
	{
		$querylist = $this->getListQuery();
		$query = $this->_db->getQuery(true);	
		$query->select('SUM(ListaInscricoes.valor_total) AS valor_total');		
		$query->select('SUM(ListaInscricoes.inscricoes) AS inscricoes');
		$query->select('SUM(ListaInscricoes.reinscricoes) AS reinscricoes');
		$query->select('SUM(ListaInscricoes.id_local) AS id_local');
		$query->select('SUM(ListaInscricoes.id_campeonato) AS id_campeonato');
		$query->select('SUM(ListaInscricoes.id_etapa) AS id_etapa');
		$query->from('(' . $querylist . ') AS ListaInscricoes');
		$query->where( $this->_db->quoteName('ListaInscricoes.id_contabel') .'='. $this->_db->quote($id_contabel)  );
		$this->_db->setQuery($query);

		return $this->_db->loadObject(); 

	}


	function setPagamentos() 
	{

		$vencimento = JRequest::getVar( 'vencimento', '', 'post' );
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );

		if(empty($vencimento))
			return false;

		if (count( $cid )) 
		{

			$this->addTablePath(JPATH_ADMINISTRATOR.'/tables');
			foreach($cid as $i => $value)
			{

				$pagamento = $this->getTotalPagamento($value);

				$row = $this->getTable('pagamento');

				$data['text_pagamento'] =  ' Inscrições: ' . $pagamento->inscricoes . ($pagamento->reinscricoes>0 ? ' + Reinscrições: ' . $pagamento->reinscricoes  : '');

				$data['cadastro_pagamento'] = JFactory::getDate('now', $this->_siteOffset)->toFormat('%Y-%m-%d', true);
				$data['valor_pago_pagamento'] = NULL;

				$data['valor_pagamento'] =  $pagamento->valor_total;
				$data['vencimento_pagamento'] = JFactory::getDate(implode("-",array_reverse(explode("/", $vencimento))), $this->_siteOffset)->toFormat('%Y-%m-%d', true);
				
				$data['valor_desconto_pagamento'] = NULL;
				$data['vencimento_desconto_pagamento'] = NULL;
				$data['baixa_pagamento'] = NULL;
				$data['id_anuidade'] = NULL;
				$data['id_produto'] = '2';
				$data['type_pagamento'] = '1';
				$data['status_pagamento'] = '1';
				$data['id_pagamento_metodo'] = '1';
				$data['id_user'] = $pagamento->id_local;

				if ( !$row->bind($data)) 
					return false;	

				if(!$data['baixa_pagamento'])
					$row->baixa_pagamento= NULL;
				
				if(!$data['valor_pago_pagamento'])
					$row->valor_pago_pagamento= NULL;
					
				if(!$data['vencimento_desconto_pagamento'])
					$row->vencimento_desconto_pagamento= NULL;
					
				if(!$data['valor_desconto_pagamento'])
					$row->valor_desconto_pagamento= NULL;
				
				if(!$data['id_anuidade'])
					$row->id_anuidade= NULL;
							
				if ( !$row->check($data)) 
					return false;	
					
				if ( !$row->store(TRUE) ) 
					return false;	
				
				$id_pagamento = $row->get('id_pagamento');
				
				jimport('joomla.log.log');
				JLog::addLogger(array( 'text_file' => 'log.pagamento.php'));
				JLog::add($id_pagamento . JText::_('		Pagamento Cadastrado -  idPg('.$id_pagamento.')'), JLog::INFO, 'finpagamento');

				$rowTaxa = $this->getTable('taxainscricaomap');
				$options = array();
				$options['id_local'] = $pagamento->id_local;
				$options['id_campeonato'] =$pagamento->id_campeonato;
				$options['id_etapa'] = $pagamento->id_etapa;
				$options['id_pagamento'] = $id_pagamento;

				if ( !$rowTaxa->bind($options)) 
					return false;	
							
				if ( !$rowTaxa->check($options)) 
					return false;	
					
				if ( !$rowTaxa->store(TRUE) ) 
					return false;	

			}
		}	


		return true;

	}

}
