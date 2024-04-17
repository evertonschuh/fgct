<?php
/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */

defined( '_JEXEC' ) or die;

jimport( 'joomla.application.component.modellist' );


class EASistemasModelDocAssociados extends JModelList
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
        $this->context .'list.ordering','filter_order', 'id_documento_numero', 'string');
		$this->setState('list.ordering', $ordering); 

 		$direction = $this->_app->getUserStateFromRequest(
        $this->context .'list.direction','filter_order_Dir', 'DESC','string');
		$this->setState('list.direction', $direction); 

	   parent::populateState($ordering, $direction );
		
    }

    protected function getListQuery() {
		
		$query = $this->_db->getQuery(true);	
		
		$query->select($this->_db->quoteName(  array('id_documento_numero',													 
													 'name',
													 //'numero_documento_numero',
													 'ano_documento_numero',
                                                     'name_documento',
													// 'status_documento',
													 //'situacao_documento',													 
													 //'update_documento',	
													 'register_documento_numero',						
													 )));	
		$query->select('CONCAT(ano_documento_numero, "/", numero_documento_numero) AS numero_documento_numero');

		$query->from($this->_db->quoteName('#__intranet_documento_numero'));
		//$query->innerJoin($this->_db->quoteName('#__situacao').' USING ('.$this->_db->quoteName('id_situacao').')');
		$query->innerJoin($this->_db->quoteName('#__users').' A ON ('.$this->_db->quoteName('id').'='.$this->_db->quoteName('id_user').')');
		//$query->leftJoin($this->_db->quoteName('#__users').' B ON ('.$this->_db->quoteName('B.id').'='.$this->_db->quoteName('user_update_documento').')');
		

		//$status = $this->getState('filter.status');
        //if ($status!='')
		//	 $query->where( $this->_db->quoteName('status_documento') . '=' . $this->_db->escape( $status ) );
		
			 
			 
	
		$search = $this->getState('filter.search');
        if ($search!='') {	 
			// Escape the search token.
			
			$token	= $this->_db->quote('%'.$this->_db->escape($search).'%');

			// Compile the different search clauses.
			$searches	= array();
			$searches[]	= 'name_documento LIKE '.$token;
			$searches[]	= 'ano_documento_numero LIKE '.$token;
			$searches[]	= 'numero_documento_numero LIKE '.$token;
			$searches[]	= 'name LIKE '.$token;
			$searches[]	= 'texto_documento_numero LIKE '.$token;
			
			$query->where('('.implode(' OR ', $searches).')');
		 
		}
		
		$ordering = $this->getState('list.ordering');
		$direction = $this->getState('list.direction');
		$query->order($this->_db->quoteName($ordering) . ' ' . $this->_db->escape($direction) );
		
        return $query;
    }
	
	//function getSituacao() 
	//{
		//$query = $this->_db->getQuery(true);
		//$query->select('id_situacao as value, name_situacao as text');
		//$query->from('#__situacao');
		//$query->where('status_situacao = 1');
		//$query->order($this->_db->quoteName('text'));
		//$this->_db->setQuery($query);
		//return $this->_db->loadObjectList();	
	//}
	
	function remove_documento()
	{	
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		$cids = JRequest::getVar( 'cid',	array(), '', 'array' );
		
		JArrayHelper::toInteger($cids);
		if (count( $cids )) 
		{				
			$cid = implode( ',', $cids );
			
			$query = $this->_db->getQuery(true);	
			/*$query->select($this->_db->quoteName('id_documento'));							 
			$query->from($this->_db->quoteName('#__documento'));
			$query->where( $this->_db->quoteName('id_documento') . ' IN (' . $cid . ')' );

			$this->_db->setQuery($query);
			$documentos = $this->_db->loadObjectList();
*/

			$conditions = array( $this->_db->quoteName('id_documento') . ' IN (' . $cid . ')' );	
			
			//$query->clear();
			$query->delete( $this->_db->quoteName('#__intranet_documento') );
			$query->where($conditions);
			$this->_db->setQuery($query);
			if ( !$this->_db->query() ) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
/*
			$query->clear();
			$query->delete( $this->_db->quoteName('#__documento_material_map') );
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
	
	function publish_documento()
	{
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		$cid = JRequest::getVar( 'cid' , array() , '' , 'array' );
		JArrayHelper::toInteger($cid);
		if ($this->setPublish($cid, '1'))
			return true;
		else
			return false;

	}
	
	function unpublish_documento()
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
			
			$fields = array( $this->_db->quoteName('status_documento') . ' = ' . $this->_db->quote( $status ) );

			$conditions = array( $this->_db->quoteName('id_documento') . ' IN (' . $cids . ')' );
			
			$query->update($this->_db->quoteName('#__intranet_documento'))->set($fields)->where($conditions);
			
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
			 
			$conditions = array( $this->_db->quoteName('id_documento') . ' IN (' . $cids . ')' );
 
			$query->update($this->_db->quoteName('#__intranet_documento'))->set($fields)->where($conditions);
 
			$this->_db->setQuery($query);
						
			if ( !$this->_db->query() ) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			return true;
			
		}
	}

	

	

	

}
