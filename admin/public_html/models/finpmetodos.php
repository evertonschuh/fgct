<?php
/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */

defined( '_JEXEC' ) or die;

jimport( 'joomla.application.component.modellist' );

class IntranetModelFinPMetodos extends JModelList
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
		
        $semestre = $this->_app->getUserStateFromRequest(
        $this->context .'filter.semestre', 'semestre', null,'string');
        $this->setState('filter.semestre', $semestre);

        $search = $this->_app->getUserStateFromRequest(
        $this->context .'filter.search', 'search', null,'string');
        $this->setState('filter.search', $search);  	

		$ordering = $this->_app->getUserStateFromRequest(
        $this->context .'list.ordering','filter_order', 'id_pagamento_metodo', 'string');
		$this->setState('list.ordering', $ordering); 

 		$direction = $this->_app->getUserStateFromRequest(
        $this->context .'list.direction','filter_order_Dir', 'DESC','string');
		$this->setState('list.direction', $direction); 

	   parent::populateState($ordering, $direction );
		
    }

    protected function getListQuery() {
		
		$query = $this->_db->getQuery(true);	
		
		$query->select($this->_db->quoteName(  array('id_pagamento_metodo',
													 'status_pagamento_metodo',
													 'name_pagamento_metodo',
													 'ordering',
													 'checked_out',
													 'checked_out_time',
													 )));							 
		
		$query->from($this->_db->quoteName('#__intranet_pagamento_metodo'));
		
		$status = $this->getState('filter.status');
        if ($status!='')
			 $query->where( $this->_db->quoteName('status_pagamento_metodo') . '=' . $this->_db->escape( $status ) );
			
		$search = $this->getState('filter.search');
        if ($search!='')
			 $query->where( $this->_db->quoteName('name_pagamento_metodo') . ' LIKE \'%' . $this->_db->escape($search) . '%\'' );
		
		$ordering = $this->getState('list.ordering');
		$direction = $this->getState('list.direction');
		$query->order($this->_db->quoteName($ordering) . ' ' . $this->_db->escape($direction) );
		
        return $query;
    }
	

	function publish_pagamento_metodo()
	{
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		$cid = JRequest::getVar( 'cid' , array() , '' , 'array' );
		JArrayHelper::toInteger($cid);
		if ($this->setPublish($cid, '1'))
			return true;
		else
			return false;

	}
	
	function unpublish_pagamento_metodo()
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
			
			$fields = array( $this->_db->quoteName('status_pagamento_metodo') . ' = ' . $this->_db->quote( $status ) );

			$conditions = array( $this->_db->quoteName('id_pagamento_metodo') . ' IN (' . $cids . ')' );
			
			$query->update($this->_db->quoteName('#__intranet_pagamento_metodo'))->set($fields)->where($conditions);
			
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
			 
			$conditions = array( $this->_db->quoteName('id_pagamento_metodo') . ' IN (' . $cids . ')' );
 
			$query->update($this->_db->quoteName('#__intranet_pagamento_metodo'))->set($fields)->where($conditions);
 
			$this->_db->setQuery($query);
						
			if ( !$this->_db->query() ) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			return true;
			
		}
	}

	function move( $direction )
	{
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		$this->addTablePath(JPATH_BASE.'/tables');
	    $row =& $this->getTable('pmetodo');
		$cid = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$cid = $cid[0];
		if ( !$row->load( $cid ) ) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		if ( !$row->move( $direction) ) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		return true;
	}

	function saveOrder()
	{
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		$this->addTablePath(JPATH_BASE.'/tables');
	    $row =& $this->getTable('pmetodo');
		
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);
		$total        = count( $cid );
		$ordering        = JRequest::getVar( 'ordering', array(0), 'post', 'array' );

		array_multisort( $ordering, $cid);

		for( $i=0; $i < $total; $i++ )
		{
			$row->load( (int) $cid[$i] );
			$row->ordering = $ordering[$i];
			if (!$row->store())
			{
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}	
		$row->reorder();
		
		return true;

	}

	

}
