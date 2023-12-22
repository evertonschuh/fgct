<?php
/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */

defined( '_JEXEC' ) or die;

jimport( 'joomla.application.component.modellist' );


class IntranetModelUpdates extends JModelList
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
		
        $clube = $this->_app->getUserStateFromRequest(
        $this->context .'filter.clube', 'clube', null,'string');
        $this->setState('filter.clube', $clube);
		
        $status = $this->_app->getUserStateFromRequest(
        $this->context .'filter.status', 'status', null,'string');
        $this->setState('filter.status', $status);

        $search = $this->_app->getUserStateFromRequest(
        $this->context .'filter.search', 'search', null,'string');
        $this->setState('filter.search', $search);  	

		$ordering = $this->_app->getUserStateFromRequest(
        $this->context .'list.ordering','filter_order', 'id_pf_update', 'string');
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

		$query->select($this->_db->quoteName(  array('id_pf_update',													 
													 'register_pf_update',															 
					 								 'cpf_pf',
													 '#__intranet_pf_update.checked_out',
													 '#__intranet_pf_update.checked_out_time',
													 )));	
									
		$query->select('Atual.id_clube AS id_clube');
		$query->select('#__intranet_pf_update.id_clube AS id_clube_update');
		$query->select('IF(ISNULL(Atual.cpf_pf), \'Cadastro Novo\', \'Atualização Cadastral\') AS tipo_executa_pf_update');
		$query->select('IF(ISNULL(Atual.cpf_pf), name_pf_update, User.name) AS name_pf_update');	
			
		$query->from($this->_db->quoteName('#__intranet_pf_update'));
		$query->leftJoin($this->_db->quoteName('#__intranet_pf') . ' AS Atual ON ('.$this->_db->quoteName('cpf_pf').'='.$this->_db->quoteName('cpf_pf_update').')');
		$query->leftJoin($this->_db->quoteName('#__users').' AS User ON ('.$this->_db->quoteName('User.id').'='.$this->_db->quoteName('Atual.id_user').')');


		$search = $this->getState('filter.search');
        if ($search!='') {	 
			// Escape the search token.
			$token	= $this->_db->quote('%'.$this->_db->escape($search).'%');

			// Compile the different search clauses.
			$searches	= array();
			$searches[]	= 'name_pf_update LIKE '.$token;
			$searches[]	= 'User.name LIKE '.$token;
			$searches[]	= 'User.email LIKE '.$token;
			$searches[]	= 'Atual.cpf_pf LIKE '.$token;
			$searches[]	= 'cpf_pf_update LIKE '.$token;

			$query->where('('.implode(' OR ', $searches).')');
		 
		}

		//$query->group($this->_db->quoteName('cpf_pf_update'));	
		
		$ordering = $this->getState('list.ordering');
		$direction = $this->getState('list.direction');
		$query->order($this->_db->quoteName($ordering) . ' ' . $this->_db->escape($direction) );
		
        return $query;
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
	
	
	function remove()
	{	
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		$cids = JRequest::getVar( 'cid',	array(), '', 'array' );
		
		JArrayHelper::toInteger($cids);
		if (count( $cids )) 
		{				
			$cid = implode( ',', $cids );
			
			$query = $this->_db->getQuery(true);	

			$conditions = array( $this->_db->quoteName('id_pf_update') . ' IN (' . $cid . ')' );	
			//$query->clear();
			$query->delete( $this->_db->quoteName('#__intranet_pf_update') );
			$query->where($conditions);
			$this->_db->setQuery($query);
			if ( !$this->_db->query() ) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			
			$query->clear();
			$query->delete( $this->_db->quoteName('#__intranet_pf_update_atividade_map') );
			$query->where($conditions);
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
			 
			$conditions = array( $this->_db->quoteName('id_pf_update') . ' IN (' . $cids . ')' );
 
			$query->update($this->_db->quoteName('#__intranet_pf_update'))->set($fields)->where($conditions);
 
			$this->_db->setQuery($query);
						
			if ( !$this->_db->query() ) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			return true;
			
		}
	}


}
