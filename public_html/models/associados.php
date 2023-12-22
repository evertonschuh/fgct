<?php
/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */

defined( '_JEXEC' ) or die;

jimport( 'joomla.application.component.modellist' );


class EASistemasModelAssociados extends JModelList
{
	var $_db = null;
	var $_user = null;
	var $_app = null;	
	var $_pagination = null;
	var $_total = null;	
	var $_data = null;
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
		
		$groupId = $this->getUserStateFromRequest(
		$this->context.'.filter.group', 'filter_group_id', null, 'int');
		$this->setState('filter.group_id', $groupId);
		
        $tipo = $this->_app->getUserStateFromRequest(
        $this->context .'filter.tipo', 'tipo', null,'string');
		$this->setState('filter.tipo', $tipo);
		
		$para = $this->_app->getUserStateFromRequest(
		$this->context .'filter.para', 'para', null,'string');
		$this->setState('filter.para', $para);	
				
        $situacao = $this->_app->getUserStateFromRequest(
        $this->context .'filter.situacao', 'situacao', null,'string');
        $this->setState('filter.situacao', $situacao);
		
        $clube = $this->_app->getUserStateFromRequest(
        $this->context .'filter.clube', 'clube', null,'string');
        $this->setState('filter.clube', $clube);
		
        $cidade = $this->_app->getUserStateFromRequest(
		$this->context .'filter.cidade', 'cidade', array(),'array');
		$this->setState('filter.cidade', $cidade);

        $status = $this->_app->getUserStateFromRequest(
        $this->context .'filter.status', 'status', null,'string');
        $this->setState('filter.status', $status);

        $search = $this->_app->getUserStateFromRequest(
        $this->context .'filter.search', 'search', null,'string');
        $this->setState('filter.search', $search);  	

		$ordering = $this->_app->getUserStateFromRequest(
        $this->context .'list.ordering','filter_order', 'id_associado', 'string');
		$this->setState('list.ordering', $ordering); 

 		$direction = $this->_app->getUserStateFromRequest(
        $this->context .'list.direction','filter_order_Dir', 'DESC','string');
		$this->setState('list.direction', $direction); 

	   parent::populateState($ordering, $direction );
		
    }

    protected function getListQuery() {
		
		
		$config = JFactory::getConfig();
		$offset = $config->getValue('offset');	
		
		
		$query = $this->_db->getQuery(true);	
		
		$query->select($this->_db->quoteName(  array('id_associado',													 
													 'U.name',															 
													 'U.email',													 
													 'status_associado',
													 'validate_associado',
													 'cadastro_associado',
													 //'register_pf',													 
													 'image_pf',	
													 'id_user',
													 //'compressed_air_pf',
													// 'sigla_estado',							 
													 '#__intranet_associado.checked_out',
													 '#__intranet_associado.checked_out_time',
													 )));	
									

		//$query->select('A.name AS name_register_pj');											 						 
		//$query->select('B.name AS name_update_pj');
			
		$query->select('IF(ISNULL(Atleta.id_pf), 1, 0) AS tipo');	
		$query->select('IF(ISNULL(Atleta.observacao_pf), Clube.observacao_pj, Atleta.observacao_pf) AS obs');
		$query->select('IF(ISNULL(Atleta.cpf_pf), Clube.cnpj_pj, Atleta.cpf_pf) AS doc');	
		$query->select('IF(ISNULL(Atleta.sexo_pf), NULL, Atleta.sexo_pf) AS sexo_pf');	
		$query->select('IF(ISNULL(Atleta.compressed_air_pf), NULL, Atleta.compressed_air_pf) AS compressed_air_pf');	
		$query->select('IF(ISNULL(Atleta.copa_brasil_pf), NULL, Atleta.copa_brasil_pf) AS copa_brasil_pf');	
		//$query->select('IF(ISNULL(Atleta.tel_celular_pf), Clube.celular_pj, Atleta.tel_celular_pf) AS celular');	
		$query->select('IF(ISNULL(ClubeCidade.name_cidade), AtletaCidade.name_cidade, ClubeCidade.name_cidade) AS name_cidade');	
		$query->select('IF(ISNULL(ClubeEstado.sigla_estado), AtletaEstado.sigla_estado, ClubeEstado.sigla_estado) AS sigla_estado');
			
		$query->from($this->_db->quoteName('#__intranet_associado'));
		$query->innerJoin($this->_db->quoteName('#__users').' U ON ('.$this->_db->quoteName('U.id').'='.$this->_db->quoteName('id_user').')');
		
		$query->leftJoin($this->_db->quoteName('#__intranet_pf') . ' AS Atleta USING ('.$this->_db->quoteName('id_user').')');
		$query->leftJoin($this->_db->quoteName('#__intranet_pj') . ' AS Clube USING ('.$this->_db->quoteName('id_user').')');
		
		$query->leftJoin($this->_db->quoteName('#__intranet_estado').' AS AtletaEstado ON ('.$this->_db->quoteName('AtletaEstado.id_estado').'='.$this->_db->quoteName('Atleta.id_estado').')');
		$query->leftJoin($this->_db->quoteName('#__intranet_cidade').' AS AtletaCidade ON ('.$this->_db->quoteName('AtletaCidade.id_cidade').'='.$this->_db->quoteName('Atleta.id_cidade').')');
		
		$query->leftJoin($this->_db->quoteName('#__intranet_estado').' AS ClubeEstado ON ('.$this->_db->quoteName('ClubeEstado.id_estado').'='.$this->_db->quoteName('Clube.id_estado').')');
		$query->leftJoin($this->_db->quoteName('#__intranet_cidade').' AS ClubeCidade ON ('.$this->_db->quoteName('ClubeCidade.id_cidade').'='.$this->_db->quoteName('Clube.id_cidade').')');

		$status = $this->getState('filter.status');
        if ($status!='')
			 $query->where( $this->_db->quoteName('status_associado') . '=' . $this->_db->escape( $status ) );

		$cidade = $this->getState('filter.cidade');
		if (count($cidade)>'0')
			$query->where('(' . $this->_db->quoteName('AtletaCidade.id_cidade') . ' IN (' . implode(',', $cidade).')' 
							. 'OR'
							. $this->_db->quoteName('ClubeCidade.id_cidade') . ' IN (' . implode(',', $cidade).')' 
							.')');

	 
		$tipo = $this->getState('filter.tipo');
        if ($tipo!=''):
			switch($tipo) {
				case '0': 
					$query->where( $this->_db->quoteName('cnpj_pj') . ' IS NULL');
				break;
				case '1': 
					$query->where( $this->_db->quoteName('cnpj_pj') . ' IS NULL');
					$query->where( $this->_db->quoteName('compressed_air_pf') . '=' . $this->_db->quote( '0' ) );
					$query->where( $this->_db->quoteName('copa_brasil_pf') . '=' . $this->_db->quote( '0' ) );
				break;
				case '2': 
					$query->where( $this->_db->quoteName('cnpj_pj') . ' IS NULL');
					$query->where( $this->_db->quoteName('compressed_air_pf') . '=' . $this->_db->quote( '1' ) );
					$query->where( $this->_db->quoteName('copa_brasil_pf') . '=' . $this->_db->quote( '0' ) );
				break;				
				case '3': 
					$query->where( $this->_db->quoteName('cnpj_pj') . ' IS NULL');
					$query->where( $this->_db->quoteName('compressed_air_pf') . '=' . $this->_db->quote( '0' ) );
					$query->where( $this->_db->quoteName('copa_brasil_pf') . '=' . $this->_db->quote( '1' ) );
				break;
				case '4': 
					$query->where( $this->_db->quoteName('cpf_pf') . ' IS NULL');
				break;
			}
		endif;
		
		$para = $this->getState('filter.para');
		if ($para!='')
			$query->where( $this->_db->quoteName('pcd_pf') . '=' . $this->_db->quote( $para ) );
		

		$situacao = $this->getState('filter.situacao');
        if ($situacao!=''):
			switch ($situacao):
				case '0':
						$query->where( $this->_db->quoteName('validate_associado') . ' IS NULL');
				break;
				case '1':
						$query->where( $this->_db->quoteName('validate_associado') . '>=' . $this->_db->quote(JFactory::getDate('now', $this->_siteOffset)->toFormat('%Y-%m-%d', true)));
				break;
				case '2':
						$query->where( $this->_db->quoteName('validate_associado') . '<' . $this->_db->quote(JFactory::getDate('now', $this->_siteOffset)->toFormat('%Y-%m-%d', true)));
						$query->where( $this->_db->quoteName('validate_associado') . '>=' . $this->_db->quote(JFactory::getDate('now -1 year', $this->_siteOffset)->toFormat('%Y-%m-%d', true)));
				break;
				case '3':
						$query->where( $this->_db->quoteName('validate_associado') . '<' . $this->_db->quote(JFactory::getDate('now', $this->_siteOffset)->toFormat('%Y-%m-%d', true)));
				break;
			endswitch;	
		endif;



		$clube = $this->getState('filter.clube');
        if ($clube!=''):
			if ($clube!='-1'):
				$query->where( $this->_db->quoteName('id_clube') . '=' . $this->_db->escape( $clube ) );
			else:
				$query->where( $this->_db->quoteName('id_clube') . ' IS NULL');
				$query->where( $this->_db->quoteName('cnpj_pj') . ' IS NULL');
			endif;
		endif;

		$search = $this->getState('filter.search');
        if ($search!='') {	 
			// Escape the search token.
			$token	= $this->_db->quote('%'.$this->_db->escape($search).'%');

			// Compile the different search clauses.
			$searches	= array();
			$searches[]	= 'id_associado LIKE '.$token;
			$searches[]	= 'U.name LIKE '.$token;
			$searches[]	= 'U.email LIKE '.$token;
			$searches[]	= 'Atleta.cpf_pf LIKE '.$token;
			$searches[]	= 'Clube.cnpj_pj LIKE '.$token;
			$searches[]	= 'AtletaCidade.name_cidade LIKE '.$token;
			$searches[]	= 'ClubeCidade.name_cidade LIKE '.$token;

			$query->where('('.implode(' OR ', $searches).')');
		 
		}
		
		$query->group($this->_db->quoteName('id_user'));	
		
		$ordering = $this->getState('list.ordering');
		$direction = $this->getState('list.direction');
		$query->order($this->_db->quoteName($ordering) . ' ' . $this->_db->escape($direction) );
		
        return $query;
    }
	
	function getCidades()
	{
		$query = $this->_db->getQuery(true);
		$query->select( $this->_db->quoteName('id_cidade') . ' AS value, CONCAT(' . $this->_db->quoteName('name_cidade') . ',"/",' . $this->_db->quoteName('sigla_estado') . ') AS text' );	
		
		$query->from($this->_db->quoteName('#__intranet_cidade'));
		$query->innerJoin($this->_db->quoteName('#__intranet_estado').' USING ('.$this->_db->quoteName('id_estado').')');

		$query->where( $this->_db->quoteName('sigla_estado') .'='. $this->_db->quote('RS') );
		$query->order( $this->_db->quoteName('name_cidade') );
		$this->_db->setQuery($query);

		return $this->_db->loadObjectList(); 
	}
	
    function getReportBoletos()   {
			
		$querylist = $this->getListQuery();
		//$querylist->where( $this->_db->quoteName('Atleta.sexo_pf') . '=' . $this->_db->escape( '0' ) ); //dia dos pais
		$query = $this->_db->getQuery(true);
		
		//$query->select('IF(ISNULL(Atleta.cpf_pf), TRIM(Relatorio.name), SUBSTRING_INDEX(TRIM(Relatorio.name), \' \', 1)) as Nome');
        $query->select('Relatorio.name as Nome');
		$query->select('IF(ISNULL(Atleta.cpf_pf), TRIM(Clube.nome_fantasia_pj), SUBSTRING_INDEX(TRIM(Relatorio.name), \' \', -1)) as Sobrenome');
		$query->select('Relatorio.email AS Email');
		//somente com o PHP 7
		//$query->select('CONCAT(\'55-\', REGEXP_REPLACE(IF(ISNULL(Atleta.cpf_pf), Clube.celular_pj, Atleta.tel_celular_pf), \'[^0-9]\', \'\')) AS Celular');
		//$query->select('REGEXP_REPLACE(IF(ISNULL(Atleta.cpf_pf), Clube.cnpj_pj, Atleta.cpf_pf), \'[^0-9]\', \'\') AS Documento');
		$query->select('IF(ISNULL(Atleta.cpf_pf) AND ISNULL(Clube.cnpj_pj), NULL, CONCAT(\'55-\', REPLACE(REPLACE(REPLACE(REPLACE(IF(ISNULL(Atleta.cpf_pf), Clube.telefone_pj, Atleta.tel_celular_pf), \'-\', \'\'), \'(\', \'\'), \')\', \'\'), \' \', \'\'))) AS Telefone');
		$query->select('REPLACE(REPLACE(REPLACE(IF(ISNULL(Atleta.cpf_pf), Clube.cnpj_pj, Atleta.cpf_pf), \'.\', \'\'), \'-\', \'\'), \'/\', \'\') AS Documento');
		$query->select('IF(ISNULL(Atleta.cpf_pf), Clube.fundacao_pj, Atleta.data_nascimento_pf) AS Nascimento');
		$query->from('(' . $querylist . ') AS Relatorio');
		$query->leftJoin($this->_db->quoteName('#__intranet_pf') . ' AS Atleta USING ('.$this->_db->quoteName('id_user').')');
		$query->leftJoin($this->_db->quoteName('#__intranet_pj') . ' AS Clube USING ('.$this->_db->quoteName('id_user').')');
		
		$this->_db->setQuery($query);

        return $this->_db->loadObjectList();
		$list =  $this->_db->loadObjectList();
		/*
		print_r($list);
		exit;
		foreach($list as  $i => $item):
		
			$this->getValues($item->id);
		
		endforeach;
		*/
		
		
	}
	
	//Este Aqui é para os Boleto no final do Ano
	function  getReport() 
	{
		
		$query = $this->_db->getQuery(true);
		$query->select($this->_db->quoteName(array( '#__intranet_pagamento.id_pagamento',
													'text_pagamento',
													'taxa_pagamento',
													'baixa_pagamento',
													'vencimento_pagamento',
													'valor_pagamento',
													'vencimento_desconto_pagamento',
													'valor_desconto_pagamento',
													'email',
													'sigla_estado',
													'name_cidade',
													)));	
		
		$query->select('REPLACE(REPLACE(REPLACE(IF(ISNULL(#__intranet_pf.cpf_pf), #__intranet_pj.cnpj_pj, #__intranet_pf.cpf_pf), \'.\', \'\'), \'-\', \'\'), \'/\', \'\') AS Documento');
		$query->select( 'IF( ISNULL(#__intranet_pf.cpf_pf), #__intranet_pj.cnpj_pj, #__intranet_pf.cpf_pf) AS doc');
		$query->select( 'IF( ISNULL(#__intranet_pf.cep_pf), #__intranet_pj.cep_pj, #__intranet_pf.cep_pf) AS cep');
		$query->select( 'IF( ISNULL(#__intranet_pf.logradouro_pf), #__intranet_pj.logradouro_pj, #__intranet_pf.logradouro_pf) AS logradouro');
		$query->select( 'IF( ISNULL(#__intranet_pf.numero_pf), #__intranet_pj.numero_pj, #__intranet_pf.numero_pf) AS numero');
		$query->select( 'IF( ISNULL(#__intranet_pf.complemento_pf), #__intranet_pj.complemento_pj, #__intranet_pf.complemento_pf) AS complemento');
		
		$query->select( 'IF( ISNULL(#__intranet_pf.bairro_pf), #__intranet_pj.bairro_pj, #__intranet_pf.bairro_pf) AS bairro');
		$query->select( 'IF( ISNULL(#__intranet_pf.tel_celular_pf), #__intranet_pj.telefone_pj, #__intranet_pf.tel_celular_pf) AS telefone');		
		$query->select( 'name AS sacado');
		$query->select( 'IF( ISNULL(name_anuidade), name_produto, name_anuidade)  AS produto');
		$query->select('IF(ISNULL(id_pj),0,1) AS types_id');

		$query->select('IF(ISNULL(#__intranet_pf.cpf_pf), name, SUBSTRING_INDEX(name, \' \', 1)) as Nome');
		$query->select('email AS Email');

		$query->from($this->_db->quoteName('#__intranet_pagamento'));
		$query->leftJoin($this->_db->quoteName('#__intranet_anuidade') . ' USING(' . $this->_db->quoteName('id_anuidade'). ')');
		$query->leftJoin($this->_db->quoteName('#__intranet_produto') . ' USING(' . $this->_db->quoteName('id_produto'). ')');	
		
		$query->leftJoin($this->_db->quoteName('#__intranet_pf') . ' USING(' . $this->_db->quoteName('id_user'). ')');
		$query->leftJoin($this->_db->quoteName('#__intranet_pj') . ' USING(' . $this->_db->quoteName('id_user'). ')');;	
		$query->innerJoin($this->_db->quoteName('#__users') . ' ON(' . $this->_db->quoteName('id'). '=' . $this->_db->quoteName('id_user'). ')');
		$query->leftJoin($this->_db->quoteName('#__intranet_estado') . ' ON(' 
																	 . $this->_db->quoteName('#__intranet_estado.id_estado'). '=' . $this->_db->quoteName('#__intranet_pf.id_estado')
																	 . 'OR '
																	 . $this->_db->quoteName('#__intranet_estado.id_estado'). '=' . $this->_db->quoteName('#__intranet_pj.id_estado')
																	 . ')');	
		$query->leftJoin($this->_db->quoteName('#__intranet_cidade') . ' ON(' 
																	 . $this->_db->quoteName('#__intranet_cidade.id_cidade'). '=' . $this->_db->quoteName('#__intranet_pf.id_cidade')
																 	 . 'OR '
																 	 . $this->_db->quoteName('#__intranet_cidade.id_cidade'). '=' . $this->_db->quoteName('#__intranet_pj.id_cidade')
																	 . ')');			

		$query->where( $this->_db->quoteName('id_anuidade') . '=' . $this->_db->quote( '15' ) );
		$query->where( $this->_db->quoteName('status_pagamento') . '=' . $this->_db->quote( '1' ) );
		$query->where( '(' . $this->_db->quoteName('cadastro_pagamento') . '=' . $this->_db->quote('2023-11-13') . ')');
		$query->where( $this->_db->quoteName('baixa_pagamento') . ' IS NULL'  );
		
		$this->_db->setQuery($query);

		$list =  $this->_db->loadAssocList();

		$query = $this->_db->getQuery(true);	
		$query->select($this->_db->quoteName(array('module_pagamento_metodo')));
		$query->from($this->_db->quoteName('#__intranet_pagamento_metodo'));
		$query->where( $this->_db->quoteName('id_pagamento_metodo').'='.$this->_db->quote('1'));
		$this->_db->setQuery($query);
		$_data = $this->_db->loadObject();
		
		require_once(JPATH_MODULE .DS. 'mod_' . $_data->module_pagamento_metodo. DS. 'mod_' . $_data->module_pagamento_metodo . '.php');

		$prefix  = 'Intranet';
		$type = $_data->module_pagamento_metodo. 'Module';
		$type = preg_replace('/[^A-Z0-9_\.-]/i', '', $type);
		$modelClass = $prefix . ucfirst($type);
		
		if (!class_exists($modelClass))
			return false;

		$_module_pagament = new $modelClass();
		foreach($list as  $i => $item):
			$_module_pagament->setData($item);

			$boleto = $_module_pagament->getBoleto();
			$list[$i]['linha_digitavel'] = $boleto['linha_digitavel'];

		endforeach;

		return $list;
		
	}
	
	
	function getClubes()
	{
		$query = $this->_db->getQuery(true);
		$query->select( $this->_db->quoteName('id') . ' AS value, ' . $this->_db->quoteName('name') . ' AS text' );	
		
		$query->from($this->_db->quoteName('#__intranet_associado'));
		$query->innerJoin($this->_db->quoteName('#__users').' U ON ('.$this->_db->quoteName('U.id').'='.$this->_db->quoteName('id_user').')');
		$query->innerJoin($this->_db->quoteName('#__intranet_pj') . ' AS Clube USING ('.$this->_db->quoteName('id_user').')');
		$query->order( $this->_db->quoteName('name') );
		
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList(); 
	}
	
	function renomeia()
	{	
		$_path = JPATH_MEDIA .DS. 'digitalfiles';

		$_forders_array = JFolder::folders($_path);

		JArrayHelper::toString($_forders_array);
		
		foreach($_forders_array as $key => $value) {
			$dest = ((int) $value);
			
			JFolder::move($_path .DS.  $value, $_path .DS. $dest );
			
			echo 'Passar esse => ' . $_path .DS.  $value . ' para esse => ' . $_path .DS. $dest . '<br/>' ;
			/*
			if ( empty($_forders) )
				$_forders .= $this->_db->quote($this->_db->escape($value));
			else
				$_forders .= ',' . $this->_db->quote($this->_db->escape($value));
				*/
		}
		exit;
		/*
		$query = $this->_db->getQuery(true);
		$query->select($this->_db->quoteName('session_id') . ' AS '. $this->_db->quoteName('folder_name') );
		$query->from($this->_db->quoteName('#__session'));
		$query->where($this->_db->quoteName('session_id') . ' IN (' . $_forders . ')');
		
		$this->_db->setQuery($query);
		
		$result = $this->_db->loadResultArray();
		foreach ( $_forders_array as $_forder) 
		{
			if ( !in_array( $_forder , $result ) && JFolder::exists( JPATH_BASE .DS. 'cache' .DS. $_forder ) )
				JFolder::delete( JPATH_BASE .DS. 'cache' .DS. $_forder );
		}
		*/
		
	}
	
	function remove()
	{	
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		$cids = JRequest::getVar( 'cid',	array(), '', 'array' );
		
		JArrayHelper::toInteger($cids);
		if (count( $cids )) 
		{				
			$cid = implode( ',', $cids );
			
			$query = $this->_db->getQuery(true);	
			/*$query->select($this->_db->quoteName('id_pf'));							 
			$query->from($this->_db->quoteName('#__pf'));
			$query->where( $this->_db->quoteName('id_pf') . ' IN (' . $cid . ')' );

			$this->_db->setQuery($query);
			$pfs = $this->_db->loadObjectList();
*/

			$conditions = array( $this->_db->quoteName('id_associado') . ' IN (' . $cid . ')' );	
			
			//$query->clear();
			$query->delete( $this->_db->quoteName('#__intranet_associado') );
			$query->where($conditions);
			$this->_db->setQuery($query);
			if ( !$this->_db->query() ) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
/*
			$query->clear();
			$query->delete( $this->_db->quoteName('#__pf_material_map') );
			$query->where($conditions);
			$this->_db->setQuery($query);
			if ( !$this->_db->query() ) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
*/
			return true;
			
		}
	}
	
	static function getGroups()
	{
		$db = JFactory::getDbo();
		$db->setQuery(
			'SELECT a.id AS value, a.title AS text, COUNT(DISTINCT b.id) AS level' .
			' FROM #__usergroups AS a' .
			' LEFT JOIN '.$db->quoteName('#__usergroups').' AS b ON a.lft > b.lft AND a.rgt < b.rgt' .
			' GROUP BY a.id, a.title, a.lft, a.rgt' .
			' ORDER BY a.lft ASC'
		);
		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum())
		{
			JError::raiseNotice(500, $db->getErrorMsg());
			return null;
		}

		foreach ($options as &$option)
		{
			$option->text = str_repeat('- ', $option->level).$option->text;
		}

		return $options;
	}

/*
	function publish_pf()
	{

		$query = $this->_db->getQuery(true);
		$query->select($this->_db->quoteName(  array('id_pf',													 
													 'image',
													 )));	
				
		$query->from($this->_db->quoteName('#__intranet_pf'));
		$query->innerJoin($this->_db->quoteName('#__ranking_profile').' USING ('.$this->_db->quoteName('id_user').')');
			
		$this->_db->setQuery($query);
		$pfs = $this->_db->loadObjectList();
				
		foreach($pfs as $pf) {
			if(!empty($pf->image)) {
				$query->clear();
				$fields = array( $this->_db->quoteName('image_pf') . ' = ' . $this->_db->quote( $pf->image ) );
				$conditions = array( $this->_db->quoteName('id_pf') . ' IN (' . $pf->id_pf  . ')' );
				$query->update($this->_db->quoteName('#__intranet_pf'))->set($fields)->where($conditions);
				$this->_db->setQuery($query);
				if ( !$this->_db->query())	{
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
			}
		}
				
		return true;
	}
	*/

	function publish()
	{
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		$cid = JRequest::getVar( 'cid' , array() , '' , 'array' );
		JArrayHelper::toInteger($cid);
		if ($this->setPublish($cid, '1'))
			return true;
		else
			return false;

	}
	
	function unpublish()
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
			
			$fields = array( $this->_db->quoteName('status_associado') . ' = ' . $this->_db->quote( $status ) );

			$conditions = array( $this->_db->quoteName('id_associado') . ' IN (' . $cids . ')' );
			
			$query->update($this->_db->quoteName('#__intranet_associado'))->set($fields)->where($conditions);
			
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
			 
			$conditions = array( $this->_db->quoteName('id_associado') . ' IN (' . $cids . ')' );
 
			$query->update($this->_db->quoteName('#__intranet_associado'))->set($fields)->where($conditions);
 
			$this->_db->setQuery($query);
						
			if ( !$this->_db->query() ) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			return true;
			
		}
	}


}
