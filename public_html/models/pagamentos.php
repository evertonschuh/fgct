<?php
/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */

defined( '_JEXEC' ) or die;

jimport( 'joomla.application.component.modellist' );

class EASistemasModelPagamentos extends JModelList
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
		
        $typesocio = $this->_app->getUserStateFromRequest(
		$this->context .'filter.typesocio', 'typesocio', null,'string');
		$this->setState('filter.typesocio', $typesocio);

        $datef = $this->_app->getUserStateFromRequest(
        $this->context .'filter.datef', 'datef', null,'string');
        $this->setState('filter.datef', $datef);

        $search = $this->_app->getUserStateFromRequest(
        $this->context .'filter.search', 'search', null,'string');
        $this->setState('filter.search', $search);  	

		$ordering = $this->_app->getUserStateFromRequest(
        $this->context .'list.ordering','filter_order', '#__pagamento.id_pagamento', 'string');
		$this->setState('list.ordering', $ordering); 

 		$direction = $this->_app->getUserStateFromRequest(
        $this->context .'list.direction','filter_order_Dir', 'DESC','string');
		$this->setState('list.direction', $direction); 

	   parent::populateState($ordering, $direction );
		
    }

    protected function getListQuery() {

		$query = $this->_db->getQuery(true);		
		
		$query->select($this->_db->quoteName(  array('#__pagamento.id_pagamento',
													 'vencimento_pagamento',
													 'registrado_pagamento',
													 'baixa_pagamento',
													 'status_pagamento',
													 'resposta_pagamento',
													 'valor_pagamento',
													 'text_pagamento',
													 'valor_desconto_pagamento',
													 'valor_pago_pagamento',
													 //'name',
													 //'email',
													 'name_anuidade',
													 'name_pagamento_metodo',
													 'module_pagamento_metodo',
													// 'id_user',
													 /* 'icon_pagamento_metodo',
													 '#__pagamento.ordering',*/
													 '#__pagamento.checked_out',
													 '#__pagamento.checked_out_time',
													 )));	
													 
		$query->select( 'IF( ISNULL(name_anuidade), name_produto, name_anuidade)  AS produto');

		$query->select( 'IF( ISNULL(name_pf), (IF( ISNULL(razao_social_pj), \'Sacado Removido\', razao_social_pj)), name_pf) AS sacado');
		$query->from($this->_db->quoteName('#__pagamento'));
		$query->innerJoin($this->_db->quoteName('#__pagamento_metodo') . ' USING(' . $this->_db->quoteName('id_pagamento_metodo'). ')');

		$query->leftJoin($this->_db->quoteName('#__anuidade') . ' USING(' . $this->_db->quoteName('id_anuidade'). ')');
		$query->leftJoin($this->_db->quoteName('#__produto') . ' USING(' . $this->_db->quoteName('id_produto'). ')');
		$query->leftJoin($this->_db->quoteName('#__pf') . '  USING(' . $this->_db->quoteName('id_pf'). ')');
		$query->leftJoin($this->_db->quoteName('#__pj') . '  USING(' . $this->_db->quoteName('id_pj'). ')');
		


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
			$searches[]	= '#__pagamento.id_pagamento LIKE '.$token;
			$searches[]	= 'name_pj LIKE '.$token;
			$searches[]	= 'name_pf LIKE '.$token;
			
			$query->where('('.implode(' OR ', $searches).')');
		 
		}
		

		$typesocio = $this->getState('filter.typesocio');
        if ($typesocio!='')
			switch($typesocio){
				case '1':
					$query->where( $this->_db->quoteName('#__pf.id_pf') . 'IS NOT NULL');
				break;
				case '2':
					$query->where( $this->_db->quoteName('#__pj.id_pj') . 'IS NOT NULL');
				break;

			}

		$registrado = $this->getState('filter.registrado');
        if ($registrado!='')
			$query->where( $this->_db->quoteName('registrado_pagamento') . '=' . $this->_db->escape( $registrado ) );
		
		$metodos = $this->getState('filter.metodos');
        if ($metodos!='')
			$query->where( $this->_db->quoteName('id_pagamento_metodo') . '=' . $this->_db->escape( $metodos ) );
		
		$anuidades = $this->getState('filter.anuidades');
        if ($anuidades!='')
			$query->where( $this->_db->quoteName('id_anuidade') . '=' . $this->_db->escape( $anuidades ) );
			
		$produtos = $this->getState('filter.produtos');
        if ($produtos!='')
			$query->where( $this->_db->quoteName('id_produto') . '=' . $this->_db->escape( $produtos ) );
					
		$datetype = $this->getState('filter.datetype');	
		if ($datetype=='1')
			$dataFiltro = 'baixa_pagamento';
		else
			$dataFiltro = 'vencimento_pagamento';
			
		$datei = $this->getState('filter.datei');
        if ($datei!='')
			$query->where( $this->_db->quoteName($dataFiltro) . '>=' . $this->_db->quote(JFactory::getDate(implode("-",array_reverse(explode("/", $datei))),  $this->_siteOffset)->toFormat('%Y-%m-%d') ) ) ;
		
		$datef = $this->getState('filter.datef');
        if ($datef!='')
			$query->where( $this->_db->quoteName($dataFiltro) . '<=' . $this->_db->quote(JFactory::getDate(implode("-",array_reverse(explode("/", $datef))),  $this->_siteOffset)->toFormat('%Y-%m-%d') ) ) ;

		$status = $this->getState('filter.status');
		if ($status!='')
			$query->where( $this->_db->quoteName('status_pagamento') . '=' . $this->_db->escape( $status ) );
		
		$situacao = $this->getState('filter.situacao');
        if ($situacao!=''):
			switch($situacao)
			{
				case '1': // A Vencer Em Dia
					$query->where( $this->_db->quoteName('baixa_pagamento') . ' IS NULL'  );
					$query->where( $this->_db->quoteName('vencimento_pagamento') . '>=' . $this->_db->quote(JFactory::getDate('now',  $this->_siteOffset)->toFormat('%Y-%m-%d') ) ) ;
				break;	
				
				case '2': // Em Aberto
					$query->where( $this->_db->quoteName('baixa_pagamento') . ' IS NULL'  );
				break;	
				
				case '3': // Vencido
					$query->where( $this->_db->quoteName('baixa_pagamento') . ' IS NULL'  );
					$query->where( $this->_db->quoteName('vencimento_pagamento') . '<' . $this->_db->quote(JFactory::getDate('now',  $this->_siteOffset)->toFormat('%Y-%m-%d') ) ) ;
				break;						
				
				case '4': //Pago
					$query->where( $this->_db->quoteName('baixa_pagamento') . ' IS NOT NULL'  );
				break;	
			}
		endif;
		
		
		$query->group($this->_db->quoteName('#__pagamento.id_pagamento'));
		
		
		$ordering = $this->getState('list.ordering');
		$direction = $this->getState('list.direction');

		//if ($ordering!='ordering')
		//	$query->order($this->_db->quoteName($ordering) . ' ' . $this->_db->escape($direction) );
		//else {
			//$query->order($this->_db->quoteName('id_pagamento_produto') . ' ' . $this->_db->escape($direction) );
		$query->order($this->_db->quoteName($ordering) . ' ' . $this->_db->escape($direction) ); 
		//}
	
		
        return $query;

    }
	
    function getReportMail() {
			
		$querylist = $this->getListQuery();
		
		$query = $this->_db->getQuery(true);
		$query->select('IF(ISNULL(Atleta.cpf_pf), Relatorio.name, SUBSTRING_INDEX(Relatorio.name, \' \', 1)) as Nome');
		$query->select('IF(ISNULL(Atleta.cpf_pf), NULL, SUBSTRING_INDEX(Relatorio.name, \' \', -1)) as Sobrenome');
		$query->select('Relatorio.email AS Email');
		//somente com o PHP 7
		//$query->select('CONCAT(\'55-\', REGEXP_REPLACE(IF(ISNULL(Atleta.cpf_pf), Clube.celular_pj, Atleta.tel_celular_pf), \'[^0-9]\', \'\')) AS Celular');
		//$query->select('REGEXP_REPLACE(IF(ISNULL(Atleta.cpf_pf), Clube.cnpj_pj, Atleta.cpf_pf), \'[^0-9]\', \'\') AS Documento');
		
		$query->select('IF(ISNULL(Atleta.cpf_pf) AND ISNULL(Atleta.cpf_pf), NULL, CONCAT(\'55-\', REPLACE(REPLACE(REPLACE(REPLACE(IF(ISNULL(Atleta.cpf_pf), Clube.celular_pj, Atleta.tel_celular_pf), \'-\', \'\'), \'(\', \'\'), \')\', \'\'), \' \', \'\'))) AS Celular');
		$query->select('REPLACE(REPLACE(REPLACE(IF(ISNULL(Atleta.cpf_pf), Clube.cnpj_pj, Atleta.cpf_pf), \'.\', \'\'), \'-\', \'\'), \'/\', \'\') AS Documento');
		
		$query->select('Relatorio.module_pagamento_metodo AS module_pagamento_metodo');
		$query->select('Relatorio.id_pagamento  AS id_pagamento');
		$query->select('Relatorio.vencimento_pagamento  AS Vencimento');
		$query->select('Relatorio.valor_pagamento  AS Valor');
		//$query->select('IF(ISNULL(id_pj),0,1) AS types_id');
		
		$query->from('(' . $querylist . ') AS Relatorio');
		
		$query->leftJoin($this->_db->quoteName('#__pf') . ' AS Atleta USING ('.$this->_db->quoteName('id_user').')');
		$query->leftJoin($this->_db->quoteName('#__pj') . ' AS Clube USING ('.$this->_db->quoteName('id_user').')');
		
		$this->_db->setQuery($query);

		$listExports = $this->_db->loadObjectList(); 

		//$config   = JFactory::getConfig();
		//$siteOffset = $config->getValue('offset');


		if(empty($this->_config))
			$this->_config = $this->getConfig($listExports[0]->module_pagamento_metodo);
			


		require_once(JPATH_LIBRARIES . DS . 'boleto' . DS . 'Carbon' . DS . 'Carbon.php');
		require_once(JPATH_LIBRARIES . DS . 'boleto' . DS . 'Pessoa.php');
		require_once(JPATH_LIBRARIES . DS . 'boleto' . DS . 'Boleto'. DS . 'Banco'. DS . 'Banrisul.php');
		require_once(JPATH_LIBRARIES . DS . 'boleto' . DS . 'Contracts' . DS . 'Boleto'. DS . 'Boleto.php');
		require_once(JPATH_LIBRARIES . DS . 'boleto' . DS . 'Contracts' . DS . 'Cnab' . DS . 'Remessa.php');
			
		$beneficiario = new Pessoa(
			array(
				'nome'      => $this->_config['identificacao'],
				'endereco'  => $this->_config['endereco'],
				'cep'       => $this->_config['cep'],
				'uf'        => $this->_config['uf'],
				'cidade'    => $this->_config['cidade'],
				'documento' => $this->_config['cpf_cnpj'],
			)
		);

		if(isset($this->_config['sacador']) && !empty($this->_config['sacador']) )
			$sacador = new Pessoa(
				array(
					'nome'      => $this->_config['sacador'],
					//'endereco'  => $listExport->logradouro'] . ', ' . $listExport->numero'],
					//'bairro'    => $listExport->bairro'],
					//'cep'       => $listExport->cep'],
					//'uf'        => $listExport->sigla_estado'],
					//'cidade'    => $listExport->name_cidade'],
					'documento' => $this->_config['cpf_cnpj_sacador'],
				)
			);
		else
			$sacador = array();

		$demonstrativo = array();
		//if($this->_config['demonstrativo1']) $demonstrativo[] = $this->_config['demonstrativo1'];
		//if($this->_config['demonstrativo2']) $demonstrativo[] = $this->_config['demonstrativo2'];		 
		//if($this->_config['demonstrativo3']) $demonstrativo[] = $this->_config['demonstrativo3'];
	
		$instrucoes = array();
	
		//$instrucoes[] = str_replace('{{TAXA}}', 'R$ ' . number_format($this->_config['taxa'], 2, ',','.'), $this->_config['instrucoes1']);
		//$instrucoes[] = str_replace('{{TAXA}}', 'R$ ' . number_format($this->_config['taxa'], 2, ',','.'), $this->_config['instrucoes2']);
		//$instrucoes[] = str_replace('{{TAXA}}', 'R$ ' . number_format($this->_config['taxa'], 2, ',','.'), $this->_config['instrucoes3']);
		//$instrucoes[] = str_replace('{{TAXA}}', 'R$ ' . number_format($this->_config['taxa'], 2, ',','.'), $this->_config['instrucoes4']);
		
		foreach($listExports as $i =>  $listExport):
		
			$pagador = new Pessoa(
				array(
					'nome'      => $listExport->Nome . ' ' . $listExport->Sobrenome,
					//'endereco'  => ' ',
					//'bairro'    => ' ',
					//'cep'       => ' ',
					//'uf'        => ' ',
					//'cidade'    => ' ',
					'documento' => $listExport->Documento,
				)
			);
		
			$boleto = new BanrisulBoleto(
								array(
									'logo'                   => '/libraries/boleto/Logos/041.png',
									'dataVencimento'         => new Carbon($listExport->Vencimento),
									//'data_processamento' 	 => $this->getDataProcessamento(),
									//'data_desconto' 		 => $listExport->vencimento_desconto_pagamento > 0 ? new Carbon($listExport->vencimento_desconto_pagamento) : NULL,
									'valor'                  => $listExport->Valor,
									//'desconto' 				 => $listExport->vencimento_desconto_pagamento > 0 ? $valor_desconto_pagamento : NULL,
									//'multa'                  => 2,
									//'juros'                  => 2,
									'numero'                 => $listExport->id_pagamento,
									'numeroDocumento'        => $listExport->id_pagamento,
									'numeroControle'         => $listExport->id_pagamento,
									'pagador'                => $pagador,
									'beneficiario'           => $beneficiario,
									'sacadorAvalista'        => $sacador,
									'diasBaixaAutomatica'    => -1,
									'carteira'               => $this->_config['carteira'],
									'agencia'                => $this->_config['agencia'],
									'conta'                  => $this->_config['conta'],
									'codigoCliente' 		 => $this->_config['conta_cedente'],
									'descricaoDemonstrativo' => $demonstrativo,
									'instrucoes'             => $instrucoes,
									'aceite'                 => 'N',
									'especieDoc'             => 'DM',
								)
							);

			$listExports[$i]->Codigo = 	$boleto->getCodigoBarras();			
			$listExports[$i]->Digitavel = 	$boleto->getLinhaDigitavel();
		endforeach;
		
		return $listExports;
		
	}
	/*
			$query = $this->_db->getQuery(true);
			$query->select($this->_db->quoteName(  array('#__pagamento.id_pagamento',
														 'vencimento_pagamento',
														 'registrado_pagamento',
														 'baixa_pagamento',
														 'status_pagamento',
														 'resposta_pagamento',
														 'valor_pagamento',
														 'text_pagamento',
														 'valor_pago_pagamento',
														 'name',
														 'name_anuidade',
														 'name_pagamento_metodo',
													 	 'sigla_estado',
														 'name_cidade',
														 'transacao_pagamento',
														 'taxa_pagamento',
														 'vencimento_desconto_pagamento',
														 'valor_desconto_pagamento',
														 '#__pagamento.checked_out',
														 '#__pagamento.checked_out_time',
														 )));	
														 
			$query->select( 'IF( ISNULL(#__pf.cpf_pf), #__pj.cnpj_pj, #__pf.cpf_pf) AS doc');
			$query->select( 'IF( ISNULL(#__pf.cep_pf), #__pj.cep_pj, #__pf.cep_pf) AS cep');
			$query->select( 'IF( ISNULL(#__pf.logradouro_pf), #__pj.logradouro_pj, #__pf.logradouro_pf) AS logradouro');
			$query->select( 'IF( ISNULL(#__pf.numero_pf), #__pj.numero_pj, #__pf.numero_pf) AS numero');
			$query->select( 'IF( ISNULL(#__pf.complemento_pf), #__pj.complemento_pj, #__pf.complemento_pf) AS complemento');
			
			$query->select( 'IF( ISNULL(#__pf.bairro_pf), #__pj.bairro_pj, #__pf.bairro_pf) AS bairro');
			$query->select( 'IF( ISNULL(#__pf.tel_celular_pf), #__pj.telefone_pj, #__pf.tel_celular_pf) AS telefone');		
			$query->select( 'name AS sacado');
			$query->select( 'name_anuidade AS produto');
			$query->select('IF(ISNULL(id_pj),0,1) AS types_id');
			
		
			$query->from($this->_db->quoteName('#__pagamento'));
			$query->innerJoin($this->_db->quoteName('#__pagamento_metodo') . ' USING(' . $this->_db->quoteName('id_pagamento_metodo'). ')');
			$query->innerJoin($this->_db->quoteName('#__anuidade') . ' USING(' . $this->_db->quoteName('id_anuidade'). ')');
			//$query->innerJoin($this->_db->quoteName('#__ranking_profile') . ' USING(' . $this->_db->quoteName('id_user'). ')');	
			
			$query->leftJoin($this->_db->quoteName('#__pf') . ' USING(' . $this->_db->quoteName('id_user'). ')');
			$query->leftJoin($this->_db->quoteName('#__pj') . ' USING(' . $this->_db->quoteName('id_user'). ')');;	
			$query->innerJoin($this->_db->quoteName('#__users') . ' ON(' . $this->_db->quoteName('id'). '=' . $this->_db->quoteName('id_user'). ')');
			$query->leftJoin($this->_db->quoteName('#__estado') . ' ON(' 
																		 . $this->_db->quoteName('#__estado.id_estado'). '=' . $this->_db->quoteName('#__pf.id_estado')
																		 . 'OR '
																		 . $this->_db->quoteName('#__estado.id_estado'). '=' . $this->_db->quoteName('#__pj.id_estado')
																		 . ')');	
			$query->leftJoin($this->_db->quoteName('#__cidade') . ' ON(' 
																		 . $this->_db->quoteName('#__cidade.id_cidade'). '=' . $this->_db->quoteName('#__pf.id_cidade')
																		 . 'OR '
																		 . $this->_db->quoteName('#__cidade.id_cidade'). '=' . $this->_db->quoteName('#__pj.id_cidade')
																		 . ')');
			
			
			$query->where( $this->_db->quoteName('#__pagamento.id_pagamento') .  ' IN (' . $cids . ')' );
			$this->_db->setQuery($query);
			$pagamentos = $this->_db->loadObjectList();
	
*/

	function getConfig($module_pagamento_metodo = null)
	{
			
		if(empty($this->_config))
		{
			//$this->_config = new stdClass();
			$query = $this->_db->getQuery(true);
			$query->select($this->_db->quoteName('params_pagamento_metodo'));				
			$query->from($this->_db->quoteName('#__pagamento_metodo'));
			$query->where($this->_db->quoteName('module_pagamento_metodo') . '=' . $this->_db->quote($module_pagamento_metodo));
			$this->_db->setQuery($query);
			$params_pagamento_metodo = $this->_db->loadResult();
							
			$registry = new JRegistry;
			$registry->loadString($params_pagamento_metodo);
			$this->_config = $registry->toArray();
		
			
			// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
			

			if(empty($this->_config['conta_cedente'])):
				$this->_config['conta_cedente'] = $this->_config['conta'];
				$this->_config['conta_cedente_dv'] = $this->_config['conta_dv'];	
			endif;
			$this->_config['codigobanco'] = '033';
		

			$this->_config['moeda'] = '9';
			$this->_config['especie'] = "R$";
			$this->_config['especie_doc'] = "DS";
			$this->_config['quantidade'] = "";
			$this->_config['valor_unitario'] = "";
			$this->_config['aceite'] = "N";		
					
			// INFORMACOES PARA O CLIENTE
			if(empty($this->_config['demonstrativo1']) && isset($this->_data['text_pagamento']))
				$this->_config['demonstrativo1'] = $this->_data['text_pagamento'];
			elseif(empty($this->_config['demonstrativo2'])&& isset($this->_data['text_pagamento']))
				$this->_config['demonstrativo2'] = $this->_data['text_pagamento'];
			elseif(empty($this->_config['demonstrativo3'])&& isset($this->_data['text_pagamento']))
				$this->_config['demonstrativo3'] = $this->_data['text_pagamento'];		

			$this->_config['taxa'] =  (float) str_replace(',', '.', str_replace('.', '', str_replace('R$ ', '', $this->_config['taxa'])));


		}
		return $this->_config;

	}

    function getReport() {
			
		$querylist = $this->getListQuery();
		$query = $this->_db->getQuery(true);
		
		$query->select('Relatorio.name AS name_associado');
		$query->select('Atleta.cpf_pf AS documanto_associado');
		$query->select('Associado.id_associado AS matricula_associado');
		
		$query->select('Atleta.numcr_pf AS numcr_associado');
		$query->select('Clube.name AS subfiliacao_associado');
		
		$query->from('(' . $querylist . ') AS Relatorio');
		$query->innerJoin($this->_db->quoteName('#__associado') . ' AS Associado USING ('.$this->_db->quoteName('id_user').')');
		$query->leftJoin($this->_db->quoteName('#__pf') . ' AS Atleta USING ('.$this->_db->quoteName('id_user').')');
		
		$query->leftJoin($this->_db->quoteName('#__users') . ' AS Clube ON ('.$this->_db->quoteName('Clube.id').'='.$this->_db->quoteName('Atleta.id_clube').')');

		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();
		
	}
	
	
	
	
	
	
	
	
	

    function getEtiqueta() {
			
		$datetype = $this->getState('filter.datetype');

		$querylist = $this->getListQuery();
		$query = $this->_db->getQuery(true);
			
			/*
		$query->select('COUNT(RelatXsl.id_pagamento) AS total_dia');
		$query->select('SUM(IF(ISNULL(RelatXsl.valor_pagamento), 0, RelatXsl.valor_pagamento)) AS valor_pagamento');
		$query->select('SUM(IF(ISNULL(RelatXsl.valor_desconto_pagamento), 0, RelatXsl.valor_desconto_pagamento)) AS valor_desconto_pagamento');
		$query->select('SUM(IF(ISNULL(RelatXsl.valorpago_pagamento), 0, RelatXsl.valorpago_pagamento)) AS valorpago_pagamento');
		$query->select('SUM(IF(ISNULL(RelatXsl.valor_desconto_pagamento), RelatXsl.valor_pagamento, RelatXsl.valor_desconto_pagamento)) AS valor_cheio_desconto_pagamento');
		
		*/
		
		$query->select('Relatorio.name AS name');	
		
		

		$query->select( 'IF( ISNULL(Atleta.add_cep_pf), Clube.add_cep_pj, Atleta.add_cep_pf) AS cep');
		$query->select( 'IF( ISNULL(Atleta.add_logradouro_pf), Clube.add_logradouro_pj, Atleta.add_logradouro_pf) AS logradouro');
		$query->select( 'IF( ISNULL(Atleta.add_numero_pf), Clube.add_numero_pj, Atleta.add_numero_pf) AS numero');
		$query->select( 'IF( ISNULL(Atleta.add_complemento_pf), Clube.add_complemento_pj, Atleta.add_complemento_pf) AS complemento');
		$query->select( 'IF( ISNULL(Atleta.add_bairro_pf), Clube.add_bairro_pj, Atleta.add_bairro_pf) AS bairro');
		$query->select('IF(ISNULL(ClubeCidade.name_cidade), AtletaCidade.name_cidade, ClubeCidade.name_cidade) AS name_cidade');	
		$query->select('IF(ISNULL(ClubeEstado.sigla_estado), AtletaEstado.sigla_estado, ClubeEstado.sigla_estado) AS sigla_estado');
		
		
		
		$query->from('(' . $querylist . ') AS Relatorio');
		$query->innerJoin($this->_db->quoteName('#__associado') . ' AS Associado USING ('.$this->_db->quoteName('id_user').')');
		$query->leftJoin($this->_db->quoteName('#__pf') . ' AS Atleta USING ('.$this->_db->quoteName('id_user').')');
		$query->leftJoin($this->_db->quoteName('#__pj') . ' AS Clube USING ('.$this->_db->quoteName('id_user').')');
		
		$query->leftJoin($this->_db->quoteName('#__estado').' AS AtletaEstado ON ('.$this->_db->quoteName('AtletaEstado.id_estado').'='.$this->_db->quoteName('Atleta.add_id_estado').')');
		$query->leftJoin($this->_db->quoteName('#__cidade').' AS AtletaCidade ON ('.$this->_db->quoteName('AtletaCidade.id_cidade').'='.$this->_db->quoteName('Atleta.add_id_cidade').')');
		
		$query->leftJoin($this->_db->quoteName('#__estado').' AS ClubeEstado ON ('.$this->_db->quoteName('ClubeEstado.id_estado').'='.$this->_db->quoteName('Clube.add_id_estado').')');
		$query->leftJoin($this->_db->quoteName('#__cidade').' AS ClubeCidade ON ('.$this->_db->quoteName('ClubeCidade.id_cidade').'='.$this->_db->quoteName('Clube.add_id_cidade').')');
		
		/*
		$ordering = $this->getState('list.ordering');
		$direction = $this->getState('list.direction');

		if ($ordering!='ordering')
			$query->order($this->_db->quoteName($ordering) . ' ' . $this->_db->escape($direction) );
		else {
			$query->order($this->_db->quoteName('id_pagamento_produto') . ' ' . $this->_db->escape($direction) );
			$query->order($this->_db->quoteName($ordering) . ' ' . $this->_db->escape($direction) ); 
		}
		*/
		
		$query->order($this->_db->quoteName('name') . ' ASC' );

		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();
		
	}

	
	function getVTotal()
	{
			// Get a storage key.
		$querylist = $this->getListQuery();
		
		$query = $this->_db->getQuery(true);	
		$query->select('SUM(ListaPagamentos.valor_pagamento)');
		$query->from('(' . $querylist . ') AS ListaPagamentos');
		$this->_db->setQuery($query);
		return $this->_db->loadResult();
	}
	
	function getVTotalP()
	{
			// Get a storage key.
		$querylist = $this->getListQuery();
		
		$query = $this->_db->getQuery(true);	
		$query->select('SUM(ListaPagamentos.valorpago_pagamento)');
		$query->from('(' . $querylist . ') AS ListaPagamentos');
		$this->_db->setQuery($query);
		return $this->_db->loadResult();
	}

	function getVTotalD()
	{
			// Get a storage key.
		$querylist = $this->getListQuery();
		
		$query = $this->_db->getQuery(true);	
		$query->select('SUM(IF(ListaPagamentos.valor_desconto_pagamento > 0, ListaPagamentos.valor_desconto_pagamento, ListaPagamentos.valor_pagamento) )');
		$query->from('(' . $querylist . ') AS ListaPagamentos');
		$this->_db->setQuery($query);
		return $this->_db->loadResult();
	}
	

	function getConteudoTipos()
	{
		$query = $this->_db->getQuery(true);
		$query->select(array('id_conteudo_type', 
							 'table_conteudo_type',
							 'parent_conteudo_type',
							 ));
		$query->from('#__conteudo_type');
		$query->where( $this->_db->quoteName('status_conteudo_type') . ' = ' . $this->_db->quote( '1' ) );
		$query->order('table_conteudo_type ASC');
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();	
	}
	
	function getMetodos()
	{
		$query = $this->_db->getQuery(true);
		$query->select('id_pagamento_metodo as value, name_pagamento_metodo as text');
		$query->from('#__pagamento_metodo');
		$query->where( $this->_db->quoteName('status_pagamento_metodo') . '=' . $this->_db->escape( '1' ));
		$query->order( $this->_db->quoteName('name_pagamento_metodo') );
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();
	}
	
	function getAnuidades()
	{
		$query = $this->_db->getQuery(true);
		$query->select('id_anuidade as value, name_anuidade as text');
		$query->from('#__anuidade');
		$query->where( $this->_db->quoteName('status_anuidade') . '=' . $this->_db->escape( '1' ));
		$query->order( $this->_db->quoteName('name_anuidade') . ' DESC' );
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();
	}
	
	function getProdutos()
	{
		$query = $this->_db->getQuery(true);
		$query->select('id_produto as value, name_produto as text');
		$query->from('#__produto');
		$query->where( $this->_db->quoteName('status_produto') . '=' . $this->_db->escape( '1' ));
		$query->order( $this->_db->quoteName('name_produto') . ' DESC' );
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();
	}
	function remove_pagamento()
	{	
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		$cids = JRequest::getVar( 'cid',	array(), '', 'array' );
		
		JArrayHelper::toInteger($cids);
		if (count( $cids )) 
		{				
			$cid = implode( ',', $cids );
			
			$query = $this->_db->getQuery(true);
			$query->select($this->_db->quoteName(array( 'ano_anuidade','id_user')));	
			$query->from( $this->_db->quoteName('#__pagamento') );
			$query->innerJoin( $this->_db->quoteName('#__anuidade') . ' USING(' . $this->_db->quoteName('id_anuidade') . ')' );
			$query->where( $this->_db->quoteName('id_pagamento') . ' IN (' . $cid . ')' );
			$this->_db->setQuery($query);
			$users = $this->_db->loadObjectList();
						
			$query->clear();
			$conditions = array( $this->_db->quoteName('id_pagamento') . ' IN (' . $cid . ')' );		
			$query->delete( $this->_db->quoteName('#__pagamento') );
			$query->where($conditions);
			$this->_db->setQuery($query);
			if ( !$this->_db->query() ) 
				return false;
			
			jimport('joomla.log.log');
			JLog::addLogger(array( 'text_file' => 'log.pagamento.php'));
			foreach($cids as $item)
				JLog::add($this->_user->get('id') . JText::_('		Pagamento Removido -  idpagamento('.$item.')'), JLog::INFO, 'pagamentos');
			
			
			foreach($users as $user):
				$tentativas = 0;
				$ano_anuidade = $user->ano_anuidade;
				$anuidade = false;
				while($anuidade == false && $tentativas < 10):
					$query->clear();
					$query->select($this->_db->quoteName(array('validate_anuidade','id_anuidade', 'baixa_pagamento')));
					$query->from( $this->_db->quoteName('#__pagamento') );
					$query->innerJoin( $this->_db->quoteName('#__anuidade') . ' USING(' . $this->_db->quoteName('id_anuidade') . ')' );
					$query->where( $this->_db->quoteName('id_user') . '=' . $this->_db->quote( $user->id_user ) );
					$query->where( $this->_db->quoteName('ano_anuidade') . '=' . $this->_db->quote( $ano_anuidade ) );
					$query->where( $this->_db->quoteName('baixa_pagamento') . '>' . $this->_db->quote( '0' ) );
					$this->_db->setQuery($query);
					if(!(boolean) $anuidade = $this->_db->loadObject()):
						$ano_anuidade = $ano_anuidade - 1;
						$tentativas++;
					endif;
					if($tentativas == 12):
						print_r($tentativas);
						exit;
						exit;
					endif;	
				endwhile;
				
				$query->clear();	
				$query->select($this->_db->quoteName(array( 'id_associado',
															'status_associado',
															'cadastro_associado',
															'validate_associado')));
				$query->from('#__associado');	
				$query->where( $this->_db->quoteName('id_user') . '=' . $this->_db->quote( $user->id_user ) );
				$this->_db->setQuery($query);
				$associado = $this->_db->loadObject();
				
				if($tentativas < 10 && $anuidade):		
					if( JFactory::getDate($anuidade->validate_anuidade, $this->_siteOffset)->toFormat('%Y-%m-%d', true) != JFactory::getDate($associado->validate_associado, $this->_siteOffset)->toFormat('%Y-%m-%d', true)):
					
						$query->clear();
						$fields = array(
							$this->_db->quoteName('confirmado_associado') . ' = ' . $this->_db->quote( JFactory::getDate($anuidade->baixa_pagamento, $this->_siteOffset)->toFormat('%Y-%m-%d', true) ),
							$this->_db->quoteName('validate_associado') . ' = ' . $this->_db->quote( JFactory::getDate($anuidade->validate_anuidade, $this->_siteOffset)->toFormat('%Y-%m-%d', true) ),
						);
						$conditions = array(
							$this->_db->quoteName('id_user') . '=' . $this->_db->quote( $user->id_user )			 
						);
						$query->update($this->_db->quoteName('#__associado'))->set($fields)->where($conditions);
						$this->_db->setQuery($query);
						if ( !((boolean) $this->_db->query()))
							return false;	
					
						if( JFactory::getDate($anuidade->validate_anuidade, $this->_siteOffset)->toFormat('%Y-%m-%d', true) < JFactory::getDate('now', $this->_siteOffset)->toFormat('%Y-%m-%d', true)): 
					
							$query->clear();
							$fields = array( $this->_db->quoteName('block') . ' = ' . $this->_db->quote( '1' ) );
							$conditions = array( $this->_db->quoteName('id') . '=' . $this->_db->quote( $user->id_user ));	
							$query->update($this->_db->quoteName('#__users'))->set($fields)->where($conditions);
							$this->_db->setQuery($query);
							if ( !$this->_db->query())	
								return false;
						endif;
					endif;
				elseif($tentativas == 10):
					$query->clear();
					$fields = array(
						$this->_db->quoteName('confirmado_associado') . ' = NULL',
						$this->_db->quoteName('validate_associado') . ' = NULL',
					);
					$conditions = array(
						$this->_db->quoteName('id_user') . '=' . $this->_db->quote( $user->id_user )			 
					);
					$query->update($this->_db->quoteName('#__associado'))->set($fields)->where($conditions);
					$this->_db->setQuery($query);
					if ( !((boolean) $this->_db->query()))
						return false;	
						
					$query->clear();
					$fields = array( $this->_db->quoteName('block') . ' = ' . $this->_db->quote( '1' ) );
					$conditions = array( $this->_db->quoteName('id') . '=' . $this->_db->quote( $user->id_user ));	
					$query->update($this->_db->quoteName('#__users'))->set($fields)->where($conditions);
					$this->_db->setQuery($query);
					if ( !$this->_db->query())	
						return false;	

				endif;
			
			endforeach;
			
			return true;
		}
	}
	
	
	function publish_pagamento()
	{
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		$cid = JRequest::getVar( 'cid' , array() , '' , 'array' );
		JArrayHelper::toInteger($cid);
		if ($this->setPublish($cid, '1'))
			return true;
		else
			return false;

	}
	
	function unpublish_pagamento()
	{	
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		$cid = JRequest::getVar( 'cid' , array() , '' , 'array' );
		JArrayHelper::toInteger($cid);
		if ($this->setPublish($cid, '0'))
			return true;
		else
			return false;
	}
	
	function setPublish(  $cid, $status ){
		if (count( $cid )) 
		{
			$cids = implode( ',', $cid );
			$query = $this->_db->getQuery(true);
			
			$fields = array( $this->_db->quoteName('status_pagamento') . ' = ' . $this->_db->quote( $status ) );

			$conditions = array( $this->_db->quoteName('id_pagamento') . ' IN (' . $cids . ')' );
			
			$query->update($this->_db->quoteName('#__pagamento'))->set($fields)->where($conditions);
			
			$this->_db->setQuery($query);
						
			if ( !$this->_db->query() ) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			return true;
		}	
	}


	function checkin()
	{	
	
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		$cid = JRequest::getVar( 'cid' , array() , '' , 'array' );
		JArrayHelper::toInteger($cid);
		if (count( $cid )) 
		{
			$cids = implode( ',', $cid );
			$query = $this->_db->getQuery(true);
 
			$fields = array( $this->_db->quoteName('checked_out') . ' = NULL',
			 				 $this->_db->quoteName('checked_out_time') . ' = NULL');
			 
			$conditions = array( $this->_db->quoteName('id_pagamento') . ' IN (' . $cids . ')' );
 
			$query->update($this->_db->quoteName('#__pagamento'))->set($fields)->where($conditions);
 
			$this->_db->setQuery($query);
						
			if ( !$this->_db->query() ) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			return true;
			
		}
	}

	function ProcessLote()
	{
		
		$lote = JRequest::getVar( 'lote', '', 'post' );
		return $this->$lote(); 
		//switch ($lote)
		//{
			//case '': return true; break; 
		//}
		//
	}

	function updateperiod()
	{
		$valor_periodo = JRequest::getVar( 'valor_periodo', '', 'post' );
		$tipo_periodo = JRequest::getVar( 'tipo_periodo', '', 'post' );
		
		$cids = JRequest::getVar( 'cid', array(), 'post', 'array' );
		$query = $this->_db->getQuery(true);
		if(count($cids)>0)
		{
			foreach($cids as $id_pagamento)
			{
				$query->clear();
				$query->select($this->_db->quoteName(array('vencimento_pagamento')));
				$query->from($this->_db->quoteName('#__pagamento'));
				$query->where( $this->_db->quoteName('id_pagamento') . '=' . $this->_db->quote( $id_pagamento ) );
				$this->_db->setQuery($query);
				if( !(boolean) $vencimento_pagamento = $this->_db->loadResult())
					return false;
				
				switch ($tipo_periodo)
				{
					case '1': $period = 'day'; break;
					case '2': $period = 'month'; break;
					case '3': $period = 'year'; break;
				}
				
				$new_vencimento_pagamento =  JFactory::getDate($vencimento_pagamento . ' + ' . $valor_periodo . ' ' . $period,  $this->_siteOffset)->toFormat('%Y-%m-%d', true);

				$query->clear();		
				$fields = array( $this->_db->quoteName('vencimento_pagamento') . '=' . $this->_db->quote( $new_vencimento_pagamento ) );
				$conditions = array( $this->_db->quoteName('id_pagamento') .  '=' . $this->_db->quote( $id_pagamento ) );
				$query->update($this->_db->quoteName('#__pagamento'))->set($fields)->where($conditions);
				$this->_db->setQuery($query);
				if ( !$this->_db->query() ) 
					return false;
			
		
			}
			return true; 
		}
	}

	function updateday()
	{
		$dia_vencimento = JRequest::getVar( 'dia_vencimento', '', 'post' );
		$cids = JRequest::getVar( 'cid', array(), 'post', 'array' );
		$query = $this->_db->getQuery(true);
		if(count($cids)>0)
		{
			foreach($cids as $id_pagamento)
			{
				$query->clear();
				$query->select($this->_db->quoteName(array('vencimento_pagamento')));
				$query->from($this->_db->quoteName('#__pagamento'));
				$query->where( $this->_db->quoteName('id_pagamento') . '=' . $this->_db->quote( $id_pagamento ) );
				$this->_db->setQuery($query);
				if( !(boolean) $vencimento_pagamento = $this->_db->loadResult())
					return false;
				
	
				$new_vencimento_pagamento = JFactory::getDate(JFactory::getDate($vencimento_pagamento,  $this->_siteOffset)->toFormat('%Y', true) . '-' . JFactory::getDate($vencimento_pagamento,  $this->_siteOffset)->toFormat('%m', true) . '-'.$dia_vencimento,  $this->_siteOffset)->toFormat('%Y-%m-%d', true);
				$i = 0;
				while(JFactory::getDate($new_vencimento_pagamento,  $this->_siteOffset)->toFormat('%m', true) != JFactory::getDate($vencimento_pagamento,  $this->_siteOffset)->toFormat('%m', true))
				{
					$i++;
					$new_vencimento_pagamento = JFactory::getDate(JFactory::getDate($vencimento_pagamento,  $this->_siteOffset)->toFormat('%Y', true) . '-' . JFactory::getDate($vencimento_pagamento,  $this->_siteOffset)->toFormat('%m', true) . '-'.($dia_vencimento-$i),  $this->_siteOffset)->toFormat('%Y-%m-%d', true);
				}

				$query->clear();		
				$fields = array( $this->_db->quoteName('vencimento_pagamento') . '=' . $this->_db->quote( $new_vencimento_pagamento ) );
				$conditions = array( $this->_db->quoteName('id_pagamento') .  '=' . $this->_db->quote( $id_pagamento ) );
				$query->update($this->_db->quoteName('#__pagamento'))->set($fields)->where($conditions);
				$this->_db->setQuery($query);
				if ( !$this->_db->query() ) 
					return false;
			
			
			}
			return true; 
		}
	}
	
	
	function updatevalue()
	{

		$cids = JRequest::getVar( 'cid', array(), 'post', 'array' );
		$new_valor = JRequest::getVar( 'new_valor', '', 'post' );
		
		JArrayHelper::toInteger($cids);
		$new_valor =  (float) str_replace(',', '.', str_replace('.', '', str_replace('R$ ', '', $new_valor)));
		
		if (count( $cids )) 
		{
			$cid = implode( ',', $cids );
			
			$query = $this->_db->getQuery(true);
			$fields = array( $this->_db->quoteName('valor_pagamento') . ' = ' . $this->_db->quote( $new_valor ) );
			$conditions = array( $this->_db->quoteName('id_pagamento') . ' IN (' . $cid . ')' );
			$query->update($this->_db->quoteName('#__pagamento'))->set($fields)->where($conditions);
			$this->_db->setQuery($query);
						
			if ( !$this->_db->query() )
				return false;

		}		
		return true; 
	}
	
	function updatemetodo()
	{
		//$this->_app->redirect(JRoute::_('index.php?view=pagamentos&format=raw', false));
		$id_metodo_novo = JRequest::getVar( 'id_metodo_novo', '', 'post' );
		$registro = JRequest::getVar( 'registro', '', 'post' );
		$cnab400 = JRequest::getVar( 'cnab400', '', 'post' );
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);
		if (count( $cid )) 
		{
			$cids = implode( ',', $cid );
			$query = $this->_db->getQuery(true);
 
			$fields = array( $this->_db->quoteName('id_pagamento_metodo') . ' = ' . $this->_db->quote( $id_metodo_novo ) );
			
			if($registro==1)
				$fields[] = $this->_db->quoteName('registrado_pagamento') . ' = ' . $this->_db->quote( '1' ) ;

			$conditions = array( $this->_db->quoteName('id_pagamento') . ' IN (' . $cids . ')' );
			$query->update($this->_db->quoteName('#__pagamento'))->set($fields)->where($conditions);
			$this->_db->setQuery($query);
						
			if ( !$this->_db->query() ) 
				return false;


			if($registro==1):
			
					
				$hoje = JFactory::getDate('now', $this->_siteOffset)->toFormat('%Y-%m-%d',true);	
				$dia = JFactory::getDate('now', $this->_siteOffset)->toFormat('%d',true);	
				$mes = JFactory::getDate('now', $this->_siteOffset)->toFormat('%m',true);	
				$query->clear();
				$query->select($this->_db->quoteName(array(	'id_pagamento_remessa')));
				$query->from($this->_db->quoteName('#__pagamento_remessa'));
				$query->where( $this->_db->quoteName('date_pagamento_remessa') . '=' . $this->_db->quote( $hoje ) );
				$this->_db->setQuery($query);
							
				$pagamentosRemessa = $this->_db->loadObjectList();
				$arquivos = count($pagamentosRemessa) + 1; 

				$data = array();
				$nameFile = 'CB' . sprintf('%02d', $dia) . sprintf('%02d', $mes) . sprintf('%02d', $arquivos);
				$data['name_pagamento_remessa'] = $nameFile;
				$data['id_pagamento_metodo'] = $id_metodo_novo;
				$data['id_pagamento_produto_tipo'] = 1;
				
				$params   = new JParameter();
				foreach( $cid as $i => $value ):
					$params->set($i, $value);
				endforeach;
				$data['pagamentos_pagamento_remessa'] = $params->toString();
				
				$data['date_pagamento_remessa'] = JFactory::getDate('now', $this->_siteOffset)->toFormat('%Y-%m-%d',true);
				$data['register_pagamento_remessa'] = JFactory::getDate('now', $siteOffset)->toISO8601(true);
				$data['user_register_pagamento_remessa'] = $this->_user->get('id');
				
				
				$this->addTablePath(JPATH_SITE.'/tables');
				$row = $this->getTable('pagamentoremessa');
				if ( !$row->bind($data)) 
					return false;	
				
				if ( !$row->check($data)) 
					return false;	
				
				if ( !$row->store($data) ) 
					return false;
				
				//sistema antigo //
				$id_pagamento_remessa = $row->get('id_pagamento_remessa');
				$this->_app->setUserState( 'id_pagamento_remessa', $id_pagamento_remessa );
				$this->_app->setUserState( 'name_pagamento_remessa', $nameFile );
			endif;		
					
					
					
					
			/*		
			$query->clear();
			$query->select($this->_db->quoteName(array('name_pagamento_metodo')));
			$query->from($this->_db->quoteName('#__pagamento_metodo'));
			$query->where( $this->_db->quoteName('id_pagamento_metodo') . '=' . $this->_db->quote( $id_metodo_novo ) );
			$this->_db->setQuery($query);
			$name_pagamento_metodo = $this->_db->loadResult();
				
			$query->clear();		
			$fields = array( $this->_db->quoteName('cb_destino') . '=' . $this->_db->quote( $name_pagamento_metodo ) );
			
			if($registro==1)
			{
				$fields[] = $this->_db->quoteName('cb_remessa') . '=' . $this->_db->quote( '1' ) ;
				$fields[] = $this->_db->quoteName('cb_data_registrar') . '=' . $this->_db->quote( JFactory::getDate('now', $this->_siteOffset)->toFormat('%Y-%m-%d',true) ) ;
			}
			$conditions = array( $this->_db->quoteName('cb_id') . ' IN (' . $cids . ')' );
			$query->update($this->_db->quoteName('cobranca'))->set($fields)->where($conditions);
			$this->_db->setQuery($query);
						
			if ( !$this->_db->query() )
				return false;
			*/
			if($cnab400==1)
			{
				$this->_app->setUserState( 'id_pagamentos_cnab400', $cid );
				$this->_app->setUserState( 'id_pagamento_metodo', $id_metodo_novo );
				$this->_app->setUserState( 'openModal', $cnab400 );
				
			}
		}		
		return true; 
	}
	
	
	function expotremessa()
	{
		//$this->_app->redirect(JRoute::_('index.php?view=pagamentos&format=raw', false));
		$id_pagamento_metodo = JRequest::getVar( 'id_pagamento_metodo', '', 'post' );
		$cnba_type = JRequest::getVar( 'cnba_type', '', 'post' );
		$registro = JRequest::getVar( 'registro', '', 'post' );
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid )) 
		{
			$cids = implode( ',', $cid );
			$query = $this->_db->getQuery(true);
			
			if($registro==1):
				
				$fields[] = $this->_db->quoteName('registrado_pagamento') . ' = ' . $this->_db->quote( '1' ) ;
				$conditions = array( $this->_db->quoteName('id_pagamento') . ' IN (' . $cids . ')' );
				$query->update($this->_db->quoteName('#__pagamento'))->set($fields)->where($conditions);
				$this->_db->setQuery($query);
							
				if ( !$this->_db->query() ) 
					return false;

			
					
				$hoje = JFactory::getDate('now', $this->_siteOffset)->toFormat('%Y-%m-%d',true);	
				$dia = JFactory::getDate('now', $this->_siteOffset)->toFormat('%d',true);	
				$mes = JFactory::getDate('now', $this->_siteOffset)->toFormat('%m',true);	
				$query->clear();
				$query->select($this->_db->quoteName(array(	'id_pagamento_remessa')));
				$query->from($this->_db->quoteName('#__pagamento_remessa'));
				$query->where( $this->_db->quoteName('date_pagamento_remessa') . '=' . $this->_db->quote( $hoje ) );
				$this->_db->setQuery($query);
							
				$pagamentosRemessa = $this->_db->loadObjectList();
				$arquivos = count($pagamentosRemessa) + 1; 

				$data = array();
				$nameFile = 'CB' . sprintf('%02d', $dia) . sprintf('%02d', $mes) . sprintf('%02d', $arquivos);
				$data['name_pagamento_remessa'] = $nameFile;
				$data['id_pagamento_metodo'] = $id_pagamento_metodo;
				$data['id_pagamento_produto_tipo'] = 1;
				
				$params   = new JParameter();
				foreach( $cid as $i => $value ):
					$params->set($i, $value);
				endforeach;
				$data['pagamentos_pagamento_remessa'] = $params->toString();
				
				$data['date_pagamento_remessa'] = JFactory::getDate('now', $this->_siteOffset)->toFormat('%Y-%m-%d',true);
				$data['register_pagamento_remessa'] = JFactory::getDate('now', $siteOffset)->toISO8601(true);
				$data['user_register_pagamento_remessa'] = $this->_user->get('id');
				
				
				$this->addTablePath(JPATH_SITE.'/tables');
				$row =& $this->getTable('pagamentoremessa');
				if ( !$row->bind($data)) 
					return false;	
				
				if ( !$row->check($data)) 
					return false;	
				
				if ( !$row->store($data) ) 
					return false;
				
				//sistema antigo //
				$id_pagamento_remessa = $row->get('id_pagamento_remessa');
				$this->_app->setUserState( 'id_pagamento_remessa', $id_pagamento_remessa );
				$this->_app->setUserState( 'name_pagamento_remessa', $nameFile );
			endif;		
							
			$this->_app->setUserState( 'cnba_type', $cnba_type );
			$this->_app->setUserState( 'id_pagamentos', $cid );
			$this->_app->setUserState( 'id_pagamento_metodo', $id_pagamento_metodo );
			$this->_app->setUserState( 'openModal', true );

		}		
		return true; 
	}
	
	function getCnabNameFile()
	{
		$response = $this->_app->getUserStateFromRequest( 'name_pagamento_remessa' );
		$cnba_type = $this->_app->getUserStateFromRequest( 'cnba_type' );
		
		if(empty($response));
			$response= 'Ccnba' . (($cnba_type==0) ? '240' : '400') . '_' . JFactory::getDate('now', $this->_siteOffset)->toFormat('%d-%m-%y', true);	
		
			
		return $response;
	}
	
	function getCnab()
	{
				
		$cid = $this->_app->getUserStateFromRequest( 'id_pagamentos' );
		$cnba_type = $this->_app->getUserStateFromRequest( 'cnba_type' );
		$id_pagamento_metodo = $this->_app->getUserStateFromRequest( 'id_pagamento_metodo' );
		$id_pagamento_remessa = $this->_app->getUserStateFromRequest( 'id_pagamento_remessa' );
		//$cid = array(1);
		//$id_pagamento_metodo = 1;
		if (count( $cid )) :

			$cids = implode( ',', $cid );

			$query = $this->_db->getQuery(true);
			$query->select($this->_db->quoteName(  array('#__pagamento.id_pagamento',
														 'vencimento_pagamento',
														 'registrado_pagamento',
														 'baixa_pagamento',
														 'status_pagamento',
														 'resposta_pagamento',
														 'valor_pagamento',
														 'text_pagamento',
														 'valor_pago_pagamento',
														 'name',
														 'name_anuidade',
														 'name_pagamento_metodo',
													 	 'sigla_estado',
														 'name_cidade',
														 'transacao_pagamento',
														 'taxa_pagamento',
														 'vencimento_desconto_pagamento',
														 'valor_desconto_pagamento',
														 '#__pagamento.checked_out',
														 '#__pagamento.checked_out_time',
														 )));	
														 
			$query->select( 'IF( ISNULL(#__pf.cpf_pf), #__pj.cnpj_pj, #__pf.cpf_pf) AS doc');
			$query->select( 'IF( ISNULL(#__pf.cep_pf), #__pj.cep_pj, #__pf.cep_pf) AS cep');
			$query->select( 'IF( ISNULL(#__pf.logradouro_pf), #__pj.logradouro_pj, #__pf.logradouro_pf) AS logradouro');
			$query->select( 'IF( ISNULL(#__pf.numero_pf), #__pj.numero_pj, #__pf.numero_pf) AS numero');
			$query->select( 'IF( ISNULL(#__pf.complemento_pf), #__pj.complemento_pj, #__pf.complemento_pf) AS complemento');
			
			$query->select( 'IF( ISNULL(#__pf.bairro_pf), #__pj.bairro_pj, #__pf.bairro_pf) AS bairro');
			$query->select( 'IF( ISNULL(#__pf.tel_celular_pf), #__pj.telefone_pj, #__pf.tel_celular_pf) AS telefone');		
			$query->select( 'name AS sacado');
			$query->select( 'IF( ISNULL(name_anuidade), name_produto, name_anuidade)  AS produto');
			$query->select('IF(ISNULL(id_pj),0,1) AS types_id');
			
		
			$query->from($this->_db->quoteName('#__pagamento'));
			$query->innerJoin($this->_db->quoteName('#__pagamento_metodo') . ' USING(' . $this->_db->quoteName('id_pagamento_metodo'). ')');
			$query->leftJoin($this->_db->quoteName('#__anuidade') . ' USING(' . $this->_db->quoteName('id_anuidade'). ')');
			$query->leftJoin($this->_db->quoteName('#__produto') . ' USING(' . $this->_db->quoteName('id_produto'). ')');
			
			$query->leftJoin($this->_db->quoteName('#__pf') . ' USING(' . $this->_db->quoteName('id_user'). ')');
			$query->leftJoin($this->_db->quoteName('#__pj') . ' USING(' . $this->_db->quoteName('id_user'). ')');;	
			$query->innerJoin($this->_db->quoteName('#__users') . ' ON(' . $this->_db->quoteName('id'). '=' . $this->_db->quoteName('id_user'). ')');
			$query->leftJoin($this->_db->quoteName('#__estado') . ' ON(' 
																		 . $this->_db->quoteName('#__estado.id_estado'). '=' . $this->_db->quoteName('#__pf.id_estado')
																		 . 'OR '
																		 . $this->_db->quoteName('#__estado.id_estado'). '=' . $this->_db->quoteName('#__pj.id_estado')
																		 . ')');	
			$query->leftJoin($this->_db->quoteName('#__cidade') . ' ON(' 
																		 . $this->_db->quoteName('#__cidade.id_cidade'). '=' . $this->_db->quoteName('#__pf.id_cidade')
																		 . 'OR '
																		 . $this->_db->quoteName('#__cidade.id_cidade'). '=' . $this->_db->quoteName('#__pj.id_cidade')
																		 . ')');
			
			
			$query->where( $this->_db->quoteName('#__pagamento.id_pagamento') .  ' IN (' . $cids . ')' );
			$this->_db->setQuery($query);
			$pagamentos = $this->_db->loadObjectList();
			
			//print_R()
			//break;
			if(count($pagamentos)>0):	
				$options = array();
				$options['pagamentos'] = $pagamentos;
				/*
				04 –Cobrança Direta:O Banco imprime o bloqueto e envia para a Agência do Beneficiário, 
					para que seja encaminhado ao Pagador.Para Tipo de Carteira D (posição 108), 
					o bloqueto terá formato carnê.
				
				06 –Cobrança Escritural:O Banco emite o bloqueto e envia ao Pagador pelo Correio.
				
				08 –Cobrança Credenciada Banrisul – CCB:O Banco não emite o bloqueto. O bloqueto é impresso e expedido pelo Beneficiário. Vide item 5 (Notas).
				O Banco poderá fornecer formulários pré-impressos.
				
				09 –Títulos de Terceiros:O Banco emite o bloqueto e envia ao Pagadorpelo Correio.Obrigatório o preenchimento das posições 073-104 
					como CNPJ/CPF e o NOME DO SACADOR e a inclusão do registro tipo 1 – Dados do Sacador no arquivo remessa.
				*/

				$options['tipo_cobranca'] = '08';
				if( $id_pagamento_remessa):
					
					$query->clear();
					$query->select($this->_db->quoteName(array(	'id_pagamento_remessa',
																'name_pagamento_remessa',
																'date_pagamento_remessa',)));
					$query->from($this->_db->quoteName('#__pagamento_remessa'));
					$query->where( $this->_db->quoteName('id_pagamento_remessa') . '=' . $this->_db->quote( $id_pagamento_remessa ) );
					$this->_db->setQuery($query);
							
					$remessa = $this->_db->loadObject(); 
					
					$options['numero_sequencia'] = $remessa->id_pagamento_remessa;
					$options['data_geracao'] = JFactory::getDate($remessa->date_pagamento_remessa, $this->_siteOffset)->toFormat('%d%m%y', true);	
					$options['filename'] = $remessa->name_pagamento_remessa;  
				else:
					$options['numero_sequencia'] = '0';
					$options['data_geracao'] = JFactory::getDate('now', $this->_siteOffset)->toFormat('%d%m%y', true);
					$options['filename'] = 'CB' . sprintf('%02d', JFactory::getDate('now', $this->_siteOffset)->toFormat('%d', true)) . sprintf('%02d', JFactory::getDate('now', $this->_siteOffset)->toFormat('%m', true)) . '01'; 
					
				endif;

				$query->clear();
				$query->select($this->_db->quoteName(array('module_pagamento_metodo')));
				$query->from($this->_db->quoteName('#__pagamento_metodo'));
				$query->where( $this->_db->quoteName('id_pagamento_metodo') . '=' . $this->_db->quote( $id_pagamento_metodo ) );
				$this->_db->setQuery($query);
			
				if( (boolean) $module_pagamento_metodo = $this->_db->loadResult()):
	
					require_once(JPATH_MODULE .DS. 'mod_' . $module_pagamento_metodo. DS. 'mod_' . $module_pagamento_metodo . '.php');
					
					$prefix  = 'Intranet';
					$type = $module_pagamento_metodo. 'Module';
					$type = preg_replace('/[^A-Z0-9_\.-]/i', '', $type);
					$modelClass = $prefix . ucfirst($type);
					
					if (!class_exists($modelClass))
						return false;
					
					$_module_pagament = new $modelClass();

					$this->_app->setUserState( 'id_pagamentos', NULL );
					$this->_app->setUserState( 'id_pagamento_metodo', NULL );
					$this->_app->setUserState( 'id_pagamento_remessa', NULL );
					
					if($cnba_type==1)
						return $_module_pagament->setCnab400( $options );
					else
						return $_module_pagament->setCnab240( $options );
				endif;
			endif;
		endif;
		
	}

	function verificarEmail( $email = null )  
	{  
	   $syntaxe='#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#';  
	   if(preg_match($syntaxe, $email))  
		  return true;  
	   else  
		 return false;  
	}

	function validarCPF( $cpf = '') { 
	
		$cpf = str_pad(preg_replace('/[^0-9]/', '', $cpf), 11, '0', STR_PAD_LEFT);
		// Verifica se nenhuma das sequências abaixo foi digitada, caso seja, retorna falso
		if ( strlen($cpf) != 11 || $cpf == '00000000000' || $cpf == '11111111111' || $cpf == '22222222222' || $cpf == '33333333333' || $cpf == '44444444444' || $cpf == '55555555555' || $cpf == '66666666666' || $cpf == '77777777777' || $cpf == '88888888888' || $cpf == '99999999999') {
			return FALSE;
		} else { 
					// Calcula os números para verificar se o CPF é verdadeiro
			for ($t = 9; $t < 11; $t++) {
				for ($d = 0, $c = 0; $c < $t; $c++) {
					$d += $cpf{$c} * (($t + 1) - $c);
				}
				$d = ((10 * $d) % 11) % 10;
				if ($cpf{$c} != $d) {
					return FALSE;
				}
			}
			return TRUE;
		}
	}
	
	
	
	function tirarAcentos($string){
		
		$string = strtolower($string);
		// Código ASCII das vogais
		$ascii['a'] = range(224, 230);
		$ascii['e'] = range(232, 235);
		$ascii['i'] = range(236, 239);
		$ascii['o'] = array_merge(range(242, 246), array(240, 248));
		$ascii['u'] = range(249, 252);
		// Código ASCII dos outros caracteres
		$ascii['b'] = array(223);
		$ascii['c'] = array(231);
		$ascii['d'] = array(208);
		$ascii['n'] = array(241);
		$ascii['y'] = array(253, 255);
		foreach ($ascii as $key=>$item) {
			$acentos = '';
			foreach ($item AS $codigo) $acentos .= chr($codigo);
				$troca[$key] = '/['.$acentos.']/i';
		}
		$string = preg_replace(array_values($troca), array_keys($troca), $string);
		// Slug?
		if ($slug) {
		// Troca tudo que não for letra ou número por um caractere ($slug)
			$string = preg_replace('/[^a-z0-9]/i', $slug, $string);
			// Tira os caracteres ($slug) repetidos
			$string = preg_replace('/' . $slug . '{2,}/i', $slug, $string);
			$string = trim($string, $slug);
		}
		return $string;
		//return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$string);
	
	}
	
	
	
	
	
	
	
}
