<?php
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.modellist' );

class IntranetModelUPJs extends JModelList
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
        $this->context .'list.ordering','filter_order', 'id_pj', 'string');
		$this->setState('list.ordering', $ordering); 

 		$direction = $this->_app->getUserStateFromRequest(
        $this->context .'list.direction','filter_order_Dir', 'DESC','string');
		$this->setState('list.direction', $direction); 

	   parent::populateState($ordering, $direction );
		
    }


    protected function getListQuery() {
		
		$query = $this->_db->getQuery(true);	
		
		$query->select($this->_db->quoteName(  array('id_pj',													 
													 'U.name',
													 'block_pj',													 
													 'status_pj',
													 'register_pj',													 
													 'update_pj',	
													 'cnpj_pj',
													 'name_cidade',
													 'sigla_estado',							 
													 '#__intranet_pj.checked_out',
													 '#__intranet_pj.checked_out_time',
													 )));	
									
		$query->select('A.name AS name_register_pj');											 						 
		$query->select('B.name AS name_update_pj');		
				
		$query->from($this->_db->quoteName('#__intranet_pj'));
		$query->innerJoin($this->_db->quoteName('#__intranet_estado').' USING ('.$this->_db->quoteName('id_estado').')');
		$query->innerJoin($this->_db->quoteName('#__intranet_cidade').' USING ('.$this->_db->quoteName('id_estado').','.$this->_db->quoteName('id_cidade').')');
		$query->innerJoin($this->_db->quoteName('#__users').' U ON ('.$this->_db->quoteName('U.id').'='.$this->_db->quoteName('id_user').')');
		
		$query->leftJoin($this->_db->quoteName('#__users').' A ON ('.$this->_db->quoteName('A.id').'='.$this->_db->quoteName('user_register_pj').')');
		$query->leftJoin($this->_db->quoteName('#__users').' B ON ('.$this->_db->quoteName('B.id').'='.$this->_db->quoteName('user_update_pj').')');


		$status = $this->getState('filter.status');
        if ($status!='')
			 $query->where( $this->_db->quoteName('status_pj') . '=' . $this->_db->escape( $status ) );
		
		/* 
		$groupId = $this->getState('filter.group_id');
		$groups = $this->getState('filter.groups');

		if ($groupId || isset($groups))
		{
			$query->join('LEFT', '#__user_usergroup_map AS map2 ON map2.user_id = U.id');
			//$query->group('id,name,username,password,usertype,block,sendEmail,registerDate,lastvisitDate,activation,params,email');

			if ($groupId)
			{
				$query->where('map2.group_id = '.(int) $groupId);
			}

			if (isset($groups))
			{
				$query->where('map2.group_id IN ('.implode(',', $groups).')');
			}
		} 
		*/
		
		$search = $this->getState('filter.search');
        if ($search!='') {	 
			// Escape the search token.
			$token	= $this->_db->quote('%'.$this->_db->escape($search).'%');

			// Compile the different search clauses.
			$searches	= array();
			$searches[]	= 'U.name LIKE '.$token;
			$searches[]	= 'cnpj_pj LIKE '.$token;
			$searches[]	= 'U.email LIKE '.$token;
			$searches[]	= 'name_cidade LIKE '.$token;
			
			$query->where('('.implode(' OR ', $searches).')');
		 
		}
		
		$ordering = $this->getState('list.ordering');
		$direction = $this->getState('list.direction');
		$query->order($this->_db->quoteName($ordering) . ' ' . $this->_db->escape($direction) );
		
        return $query;
    }

	function remove_pj()
	{	
		$cid = JRequest::getVar( 'cid' , array() , '' , 'array' );
		JArrayHelper::toInteger($cid);
		if (count( $cid )) 
		{		
			$cids = implode( ',', $cid );
			
			$query = $this->_db->getQuery(true);
			$conditions = array( $this->_db->quoteName('id_pj') . ' IN (' . $cids . ')' );
			$query->delete( $this->_db->quoteName('#__intranet_pj') );
			$query->where($conditions);
			$this->_db->setQuery($query);
			if ( !$this->_db->query() ) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}

			return true;
		}
	}
	
	function publish_pj()
	{
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		$cid = JRequest::getVar( 'cid' , array() , '' , 'array' );
		JArrayHelper::toInteger($cid);
		if ($this->setPublish($cid, '1'))
			return true;
		else
			return false;

	}
	
	function unpublish_pj()
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
			
			$fields = array( $this->_db->quoteName('status_pj') . ' = ' . $this->_db->quote( $status ) );

			$conditions = array( $this->_db->quoteName('id_pj') . ' IN (' . $cids . ')' );
			
			$query->update($this->_db->quoteName('#__intranet_pj'))->set($fields)->where($conditions);
			
			$this->_db->setQuery($query);
						
			if ( !$this->_db->query() ) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			return true;
		}	
	}

	function block_pj()
	{
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		$cid = JRequest::getVar( 'cid' , array() , '' , 'array' );
		JArrayHelper::toInteger($cid);
		if ($this->setBlock($cid, '1'))
			return true;
		else
			return false;

	}
	
	function unblock_pj()
	{	
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		$cid = JRequest::getVar( 'cid' , array() , '' , 'array' );
		JArrayHelper::toInteger($cid);
		if ($this->setBlock($cid, '0'))
			return true;
		else
			return false;
	}
	
	function setBlock( $cid, $status ){
		if (count( $cid )) 
		{
			$cids = implode( ',', $cid );
			$query = $this->_db->getQuery(true);
			
			$fields = array( $this->_db->quoteName('block_pj') . ' = ' . $this->_db->quote( $status ) );

			$conditions = array( $this->_db->quoteName('id_pj') . ' IN (' . $cids . ')' );
			
			$query->update($this->_db->quoteName('#__intranet_pj'))->set($fields)->where($conditions);
			
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
			 
			$conditions = array( $this->_db->quoteName('id_pj') . ' IN (' . $cids . ')' );
 
			$query->update($this->_db->quoteName('#__intranet_pj'))->set($fields)->where($conditions);
 
			$this->_db->setQuery($query);
						
			if ( !$this->_db->query() ) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			return true;
			
		}
	}


}
