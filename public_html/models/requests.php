<?php
/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */

defined( '_JEXEC' ) or die;

jimport( 'joomla.application.component.modellist' );


class EASistemasModelRequests extends JModelList
{
	var $_db = null;
	var $_user = null;
	var $_app = null;	
	var $_pagination = null;
	var $_total = null;	
	var $_data = null;
	
	function __construct()
	{	
		parent::__construct();
		
		$this->_db	= JFactory::getDBO();
		$this->_user = JFactory::getUser();
		$this->_app = JFactory::getApplication();
		
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
		
        $status = $this->_app->getUserStateFromRequest(
        $this->context .'filter.status', 'status', null,'string');
        $this->setState('filter.status', $status);

        $search = $this->_app->getUserStateFromRequest(
        $this->context .'filter.search', 'search', null,'string');
        $this->setState('filter.search', $search);  	

		$ordering = $this->_app->getUserStateFromRequest(
        $this->context .'list.ordering','filter_order', 'id_service', 'string');
		$this->setState('list.ordering', $ordering); 

 		$direction = $this->_app->getUserStateFromRequest(
        $this->context .'list.direction','filter_order_Dir', 'DESC','string');
		$this->setState('list.direction', $direction); 

	   parent::populateState($ordering, $direction );
		
    }

    protected function getListQuery() {
		
		$query = $this->_db->getQuery(true);	
		
		$query->select($this->_db->quoteName(  array('id_service',													 
													 'name_service_type',
													 'status_service',
													 'create_service',	
													 'lastupdate_service',	
													 'id_user',
													 
													 //'update_arma',	
													// 'registro_arma',
													 //'sigla_estado',							 
													 '#__intranet_service.checked_out',
													 '#__intranet_service.checked_out_time',
													 )));	
									

		//$query->select('A.name AS name_register_arma');											 						 
	//	$query->select('B.name AS name_update_arma');		
		//$query->select('IF(last.user_last <> id_user, 1, 0) AS lasted_user');		
		$query->from($this->_db->quoteName('#__intranet_service'));
		$query->innerJoin($this->_db->quoteName('#__intranet_service_type').' USING ('.$this->_db->quoteName('id_service_type').')');
		//$query->innerJoin('('.$queryUser.') AS last USING ('.$this->_db->quoteName('id_service').')');
		//$query->innerJoin($this->_db->quoteName('#__intranet_marca').' USING ('.$this->_db->quoteName('id_marca').')');
		//$query->innerJoin($this->_db->quoteName('#__intranet_acervo').' USING ('.$this->_db->quoteName('id_acervo').')');
		/*
		/*
		$query->leftJoin($this->_db->quoteName('#__pj').' ON ('.$this->_db->quoteName('id_proprietario').'='.$this->_db->quoteName('id_pj')
																.'AND'
																.$this->_db->quoteName('type_proprietario_arma').'='.$this->_db->quote('1')
																.')');
		$query->leftJoin($this->_db->quoteName('#__pf').' ON ('.$this->_db->quoteName('id_proprietario').'='.$this->_db->quoteName('id_pf')
																.'AND'
																.$this->_db->quoteName('type_proprietario_arma').'='.$this->_db->quote('0')
																.')');*/
		//$query->leftJoin($this->_db->quoteName('#__users').' A ON ('.$this->_db->quoteName('A.id').'='.$this->_db->quoteName('user_register_arma').')');
		//$query->leftJoin($this->_db->quoteName('#__users').' B ON ('.$this->_db->quoteName('B.id').'='.$this->_db->quoteName('user_update_arma').')');
		//$query->where( $this->_db->quoteName('status_arma') . '>=' . $this->_db->quote( '0' ) );
		$query->where( $this->_db->quoteName('id_user') . '=' . $this->_db->quote( $this->_user->get('id') ) );



	/*
		$search = $this->getState('filter.search');
        if ($search!='') {	 
			// Escape the search token.
			$token	= $this->_db->quote('%'.$this->_db->escape($search).'%');

			// Compile the different search clauses.
			$searches	= array();
			$searches[]	= 'name_especie LIKE '.$token;
			$searches[]	= 'numero_arma LIKE '.$token;
			$searches[]	= 'name_marca LIKE '.$token;
			$searches[]	= 'name_especie LIKE '.$token;
			$searches[]	= 'name_calibre LIKE '.$token;
			
			$query->where('('.implode(' OR ', $searches).')');
		 
		}
		*/
		$ordering = $this->getState('list.ordering');
		$direction = $this->getState('list.direction');
		$query->order($this->_db->quoteName($ordering) . ' ' . $this->_db->escape($direction) );
		
        return $query;
    }

	public function getItems()
	{

		// Get a storage key.
		$store = $this->getStoreId();

		// Try to load the data from internal storage.
		if (empty($this->cache[$store]))
		{
			
			$items = parent::getItems();

			if (empty($items))
			{
				$this->cache[$store] = $items;
				return $items;
			}

			$id_service = array();
			foreach ($items as $item)
			{
				$id_service[] = (int) $item->id_service;
			}
	
			//print_r(  $id_users  );

			$query = $this->_db->getQuery(true);

			$query->select('map.id_service, max(map.update_service) AS update_service')
				->from('#__intranet_service_map AS map')
				->where('map.id_service IN ('.implode(',', $id_service ).')')
				->group('id_service');
				// Join over the user groups table.
				//->join('LEFT', '#__usergroups AS g2 ON g2.id = map.group_id');

			$this->_db->setQuery($query);

			$services = $this->_db->loadObjectList('id_service');
			$error = $this->_db->getErrorMsg();

			if ($error)
			{
				$this->setError($error);
				return false;
			}

			foreach ($items as &$item)
			{
				if (isset($services[$item->id_service]))
				{
					//$item->group_count = $comissoesGroups[$item->id_prova]->id_clube;
					$item->lasted_user = $this->_getLastedUser($services[$item->id_service]->update_service, $item->id_service);
					
				}
			}
			$this->cache[$store] = $items;
		}

		return $this->cache[$store];
	}


	function _getLastedUser($update_service = null, $id_service = null)
	{

		$query = $this->_db->getQuery(true);
		$query->select('id_user');
		$query->from($this->_db->quoteName('#__intranet_service_map'));
		$query->where( $this->_db->quoteName('update_service') . '='. $this->_db->quote($update_service));
		$query->where( $this->_db->quoteName('id_service') . '='. $this->_db->quote($id_service) );
		$this->_db->setQuery($query);
		return $this->_db->loadResult();
	}

	function remove()
	{	
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		$cid = JRequest::getVar( 'cid' , array() , '' , 'array' );
		JArrayHelper::toInteger($cid);
		if ($this->setPublish($cid, '-2'))
			return true;
		else
			return false;
	}
	
	function publish_arma()
	{
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		$cid = JRequest::getVar( 'cid' , array() , '' , 'array' );
		JArrayHelper::toInteger($cid);
		if ($this->setPublish($cid, '1'))
			return true;
		else
			return false;

	}
	
	function unpublish_arma()
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
			
			$fields = array( $this->_db->quoteName('status_arma') . ' = ' . $this->_db->quote( $status ) );

			$conditions = array( $this->_db->quoteName('id_arma') . ' IN (' . $cids . ')' );
			
			$query->update($this->_db->quoteName('#__intranet_arma'))->set($fields)->where($conditions);
			
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
			 
			$conditions = array( $this->_db->quoteName('id_arma') . ' IN (' . $cids . ')' );
 
			$query->update($this->_db->quoteName('#__arma'))->set($fields)->where($conditions);
 
			$this->_db->setQuery($query);
						
			if ( !$this->_db->query() ) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			return true;
			
		}
	}

	

	

	

}
