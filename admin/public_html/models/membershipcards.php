<?php
/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */

defined( '_JEXEC' ) or die;

jimport( 'joomla.application.component.modellist' );


class EASistemasModelMembershipCards extends JModelList
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
        $this->context .'list.ordering','filter_order', 'id_carteira_digital', 'string');
		$this->setState('list.ordering', $ordering); 

 		$direction = $this->_app->getUserStateFromRequest(
        $this->context .'list.direction','filter_order_Dir', 'DESC','string');
		$this->setState('list.direction', $direction); 

	   parent::populateState($ordering, $direction );
		
    }

    protected function getListQuery() {
		
		$query = $this->_db->getQuery(true);	
		
		$query->select($this->_db->quoteName(  array('id_carteira_digital',			
													 'validate_anuidade',
                                                     'name_anuidade',
                                                     'name_associado_tipo',
													 'status_carteira_digital',					 
													 '#__intranet_carteira_digital.checked_out',
													 '#__intranet_carteira_digital.checked_out_time',
													 )));	


		$query->select('CONCAT(name_anuidade,\' - \', IF(name_associado_tipo = -1, \'Todos\', name_associado_tipo)) AS name_carteira_digital');				
		$query->from($this->_db->quoteName('#__intranet_carteira_digital'));
		$query->innerJoin($this->_db->quoteName('#__intranet_anuidade').' USING ('.$this->_db->quoteName('id_anuidade').')');
		$query->leftJoin($this->_db->quoteName('#__intranet_associado_tipo').' USING ('.$this->_db->quoteName('id_associado_tipo').')');
		$status = $this->getState('filter.status');
        if ($status!='')		
			 $query->where($this->_db->quoteName('status_carteira_digital') . '=' . $this->_db->quote($this->_db->escape($status)));
		else
			$query->where($this->_db->quoteName('status_carteira_digital') . '>=' . $this->_db->quote($this->_db->escape('0')));
				
				 
			 
	
		$search = $this->getState('filter.search');
        if ($search!='') {	 
			// Escape the search token.
			$token	= $this->_db->quote('%'.$this->_db->escape($search).'%');

			// Compile the different search clauses.
			$searches	= array();
			$searches[]	= 'name_carteira_digital LIKE '.$token;
			$searches[]	= 'id_carteira_digital LIKE '.$token;
			//$searches[]	= 'name_situacao LIKE '.$token;
			$searches[]	= 'text_carteira_digital LIKE '.$token;
			
			$query->where('('.implode(' OR ', $searches).')');
		 
		}
		
		$ordering = $this->getState('list.ordering');
		$direction = $this->getState('list.direction');
		$query->order($this->_db->quoteName($ordering) . ' ' . $this->_db->escape($direction) );
		
        return $query;
    }
	

	function setPublish(  $cid, $status ){
		if (count( $cid )) 
		{
			$cids = implode( ',', $cid );
			$query = $this->_db->getQuery(true);
			
			$fields = array( $this->_db->quoteName('status_carteira_digital') . ' = ' . $this->_db->quote( $status ) );

			$conditions = array( $this->_db->quoteName('id_carteira_digital') . ' IN (' . $cids . ')' );
			
			$query->update($this->_db->quoteName('#__intranet_carteira_digital'))->set($fields)->where($conditions);
			
			$this->_db->setQuery($query);
						
			if ( !$this->_db->query() ) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			
			jimport('joomla.log.log');
			JLog::addLogger(array( 'text_file' => 'log.carteira_digital.php'));

			switch($status)
			{
				case '1': $text = 'activated item';
				case '0': $text = 'item disabled';
				case '-1': $text = 'item sent to recycle bin';
				case '-2': $text = 'item removed';
				default: $text = 'unknown action on item';
			}

			foreach($cid as $item)
				JLog::add($this->_user->get('id') . ' - ' . $text.' -  id item ('.$item.')',JLog::INFO, 'carteira_digital');
				
			return true;
		}	
	}

		
	function remove()			
	{
		JRequest::checkToken() or jexit('JINVALID_TOKEN');
		$cid = JRequest::getVar('cid', array(), '', 'array');
		JArrayHelper::toInteger($cid);
		if ($this->setPublish($cid, '-2'))
			return true;
		else
			return false;
	}

	function trash()
	{
		JRequest::checkToken() or jexit('JINVALID_TOKEN');
		$cid = JRequest::getVar('cid', array(), '', 'array');
		JArrayHelper::toInteger($cid);
		if ($this->setPublish($cid, '-1'))
			return true;
		else
			return false;
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

	

}
