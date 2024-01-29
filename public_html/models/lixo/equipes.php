<?php
/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */

defined( '_JEXEC' ) or die;

jimport( 'joomla.application.component.modellist' );

class IntranetModelEquipes extends JModelList
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
		
        $status = $this->_app->getUserStateFromRequest(
        $this->context .'filter.status', 'status', null,'string');
        $this->setState('filter.status', $status);
		
        $search = $this->_app->getUserStateFromRequest(
        $this->context .'filter.search', 'search', null,'string');
        $this->setState('filter.search', $search);  	

		$ordering = $this->_app->getUserStateFromRequest(
        $this->context .'list.ordering','filter_order', 'id_addequipe', 'string');
		$this->setState('list.ordering', $ordering); 

 		$direction = $this->_app->getUserStateFromRequest(
        $this->context .'list.direction','filter_order_Dir', 'DESC','string');
		$this->setState('list.direction', $direction); 

	   parent::populateState($ordering, $direction );
		
    }

    protected function getListQuery() {
		
		$query = $this->_db->getQuery(true);	
		
		$query->select($this->_db->quoteName(  array('id_addequipe',
													 'status_addequipe',
													 'name_addequipe',
													 'checked_out',
													 'checked_out_time',
													 )));							 
		
		$query->from($this->_db->quoteName('#__intranet_addequipe'));
		
		$status = $this->getState('filter.status');
        if ($status!='')
			 $query->where( $this->_db->quoteName('status_addequipe') . '=' . $this->_db->escape( $status ) );
			
		$search = $this->getState('filter.search');
        if ($search!='')
			 $query->where( $this->_db->quoteName('name_addequipe') . ' LIKE \'%' . $this->_db->escape($search) . '%\'' );
		
		$ordering = $this->getState('list.ordering');
		$direction = $this->getState('list.direction');
		$query->order($this->_db->quoteName($ordering) . ' ' . $this->_db->escape($direction) );
		
        return $query;
    }
	
	
	protected function getStoreId($id = '')
	{
		return parent::getStoreId($id);
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

			$id_users = array();
			foreach ($items as $item)
			{
				$id_addequipe[] = (int) $item->id_addequipe;
				$item->group_count = 0;
			}
	
			//print_r(  $id_users  );

			$query = $this->_db->getQuery(true);

			$query->select('map.id_addequipe, COUNT(map.id_modalidade) AS group_count')
				->from('#__intranet_addequipe_map AS map')
				->where('map.id_addequipe IN ('.implode(',', $id_addequipe ).')')
				->group('id_addequipe');

			$this->_db->setQuery($query);

			$modalidadesGroups = $this->_db->loadObjectList('id_addequipe');

			$error = $this->_db->getErrorMsg();
			if ($error)
			{
				$this->setError($error);
				return false;
			}

			foreach ($items as &$item)
			{
				if (isset($modalidadesGroups[$item->id_addequipe]))
				{
					$item->group_count = $modalidadesGroups[$item->id_addequipe]->group_count;
					$item->modalidades_groups = $this->_getModalidadesGroup($item->id_addequipe);
					
				}
			}
			$this->cache[$store] = $items;
		}

		return $this->cache[$store];
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
			$query->select($this->_db->quoteName('id_addequipe'));	
			$query->from($this->_db->quoteName('#__ranking_inscricao'));
			$query->where($this->_db->quoteName('id_addequipe') . ' IN (' . $cid . ')' );
			$this->_db->setQuery($query);
			if((boolean) $result =  $this->_db->loadObjectList())
				return false;

				
			$conditions = array( $this->_db->quoteName('id_addequipe') . ' IN (' . $cid . ')' );	

			$query->clear();			
			$query->delete( $this->_db->quoteName('#__intranet_addequipe') );
			$query->where($conditions);
			$this->_db->setQuery($query);
			if ( !$this->_db->query() ) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			
			$query->clear();			
			$query->delete( $this->_db->quoteName('#__intranet_addequipe_map') );
			$query->where($conditions);
			$this->_db->setQuery($query);
			if ( !$this->_db->query() ) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}

			return true;
			
		}
	}

	


	function _getModalidadesGroup($id_addequipe = null)
	{
		$query = $this->_db->getQuery(true);	
		//$query->select($this->_db->quoteName('value_parceiro_comissao'));
		$query->select($this->_db->quoteName('name_modalidade'));	
		$query->from($this->_db->quoteName('#__ranking_modalidade'));
		$query->innerJoin($this->_db->quoteName('#__intranet_addequipe_map') . ' USING('.$this->_db->quoteName('id_modalidade').')');
		$query->where($this->_db->quoteName('id_addequipe') . ' = ' .  $this->_db->quote( $id_addequipe ));
		$this->_db->setQuery($query);
		$result =  $this->_db->loadColumn();
		return implode(' | ',  $result);
	}


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
			
			$fields = array( $this->_db->quoteName('status_addequipe') . ' = ' . $this->_db->quote( $status ) );

			$conditions = array( $this->_db->quoteName('id_addequipe') . ' IN (' . $cids . ')' );
			
			$query->update($this->_db->quoteName('#__intranet_addequipe'))->set($fields)->where($conditions);
			
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
			 
			$conditions = array( $this->_db->quoteName('id_addequipe') . ' IN (' . $cids . ')' );
 
			$query->update($this->_db->quoteName('#__intranet_addequipe'))->set($fields)->where($conditions);
 
			$this->_db->setQuery($query);
						
			if ( !$this->_db->query() ) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			return true;
			
		}
	}

}
