<?php
/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */

defined( '_JEXEC' ) or die;

jimport( 'joomla.application.component.modellist' );


class IntranetModelConveniados extends JModelList
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
		
        $situacao = $this->_app->getUserStateFromRequest(
        $this->context .'filter.situacao', 'situacao', null,'string');
        $this->setState('filter.situacao', $situacao);
		
        $convenio = $this->_app->getUserStateFromRequest(
        $this->context .'filter.convenio', 'convenio', null,'string');
        $this->setState('filter.convenio', $convenio);
		
        $status = $this->_app->getUserStateFromRequest(
        $this->context .'filter.status', 'status', null,'string');
        $this->setState('filter.status', $status);

        $search = $this->_app->getUserStateFromRequest(
        $this->context .'filter.search', 'search', null,'string');
        $this->setState('filter.search', $search);  	

		$ordering = $this->_app->getUserStateFromRequest(
        $this->context .'list.ordering','filter_order', 'id_conveniado', 'string');
		$this->setState('list.ordering', $ordering); 

 		$direction = $this->_app->getUserStateFromRequest(
        $this->context .'list.direction','filter_order_Dir', 'DESC','string');
		$this->setState('list.direction', $direction); 

	   parent::populateState($ordering, $direction );
		
    }

    protected function getListQuery() {
		
			
		$query = $this->_db->getQuery(true);	
		
		$query->select($this->_db->quoteName(  array('id_conveniado',													 
													 'name',															 
													 'email',													 
													 'status_conveniado',
													 'observacao_pf',													 
													 'cpf_pf',	
													 'name_convenio',
													 'validate_convenio',
													 'id_user',
													 'name_cidade',
													 'sigla_estado',							 
													 '#__intranet_conveniado.checked_out',
													 '#__intranet_conveniado.checked_out_time',/**/
													 )));	

		$query->from($this->_db->quoteName('#__intranet_conveniado'));
		$query->leftJoin($this->_db->quoteName('#__intranet_pf') . ' USING ('.$this->_db->quoteName('id_user').')');
		$query->leftJoin($this->_db->quoteName('#__intranet_convenio') . ' USING ('.$this->_db->quoteName('id_convenio').')');
		$query->leftJoin($this->_db->quoteName('#__intranet_estado').' USING ('.$this->_db->quoteName('id_estado').')');
		$query->leftJoin($this->_db->quoteName('#__intranet_cidade').' USING ('.$this->_db->quoteName('id_cidade').','.$this->_db->quoteName('id_estado').')');
		$query->innerJoin($this->_db->quoteName('#__users').' ON ('.$this->_db->quoteName('id').'='.$this->_db->quoteName('id_user').')');
		


		$status = $this->getState('filter.status');
        if ($status!='')
			 $query->where( $this->_db->quoteName('status_conveniado') . '=' . $this->_db->escape( $status ) );

		$convenio = $this->getState('filter.convenio');
        if ($convenio!='')
			 $query->where( $this->_db->quoteName('id_convenio') . '=' . $this->_db->escape( $convenio ) );


		$search = $this->getState('filter.search');
        if ($search!='') {	 
			// Escape the search token.
			$token	= $this->_db->quote('%'.$this->_db->escape($search).'%');

			// Compile the different search clauses.
			$searches	= array();
			$searches[]	= 'id_conveniado LIKE '.$token;
			$searches[]	= 'name LIKE '.$token;
			$searches[]	= 'email LIKE '.$token;
			$searches[]	= 'cpf_pf LIKE '.$token;
			$searches[]	= 'name_cidade LIKE '.$token;
			$searches[]	= 'name_cidade LIKE '.$token;

			$query->where('('.implode(' OR ', $searches).')');
		 
		}
		
		$query->group($this->_db->quoteName('id_user'));	
		
		$ordering = $this->getState('list.ordering');
		$direction = $this->getState('list.direction');
		$query->order($this->_db->quoteName($ordering) . ' ' . $this->_db->escape($direction) );

        return $query;
    }
	
    function getReport() {
			
		$querylist = $this->getListQuery();
		
		$query = $this->_db->getQuery(true);
		$query->select('IF(ISNULL(Atleta.cpf_pf), Relatorio.name, SUBSTRING_INDEX(Relatorio.name, \' \', 1)) as Nome');
		$query->select('IF(ISNULL(Atleta.cpf_pf), Clube.nome_fantasia_pj, SUBSTRING_INDEX(Relatorio.name, \' \', -1)) as Sobrenome');
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
		
	}
	
	
	
	function getConvenios()
	{
		$query = $this->_db->getQuery(true);
		$query->select( $this->_db->quoteName('id_convenio') . ' AS value, ' . $this->_db->quoteName('name_convenio') . ' AS text' );	
		$query->from($this->_db->quoteName('#__intranet_convenio'));
		$query->order( $this->_db->quoteName('text') );
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList(); 
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
			$conditions = array( $this->_db->quoteName('id_conveniado') . ' IN (' . $cid . ')' );	
			
			$query->delete( $this->_db->quoteName('#__intranet_conveniado') );
			$query->where($conditions);
			$this->_db->setQuery($query);
			if ( !$this->_db->query() ) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}

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
			
			$fields = array( $this->_db->quoteName('status_conveniado') . ' = ' . $this->_db->quote( $status ) );

			$conditions = array( $this->_db->quoteName('id_conveniado') . ' IN (' . $cids . ')' );
			
			$query->update($this->_db->quoteName('#__intranet_conveniado'))->set($fields)->where($conditions);
			
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
			 
			$conditions = array( $this->_db->quoteName('id_conveniado') . ' IN (' . $cids . ')' );
 
			$query->update($this->_db->quoteName('#__intranet_conveniado'))->set($fields)->where($conditions);
 
			$this->_db->setQuery($query);
						
			if ( !$this->_db->query() ) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			return true;
			
		}
	}


}
