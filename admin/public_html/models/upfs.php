<?php
/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */

defined( '_JEXEC' ) or die;

jimport( 'joomla.application.component.modellist' );


class IntranetModelUPFs extends JModelList
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
        $this->context .'list.ordering','filter_order', 'id_pf', 'string');
		$this->setState('list.ordering', $ordering); 

 		$direction = $this->_app->getUserStateFromRequest(
        $this->context .'list.direction','filter_order_Dir', 'DESC','string');
		$this->setState('list.direction', $direction); 

	   parent::populateState($ordering, $direction );
		
    }

    protected function getListQuery() {
		
		$query = $this->_db->getQuery(true);	
		
		$query->select($this->_db->quoteName(  array('id_pf',													 
													 'U.name',
													 'block_pf',													 
													 'status_pf',
													 'register_pf',													 
													 'update_pf',	
													 'cpf_pf',
													 'name_cidade',
													 'sigla_estado',							 
													 '#__intranet_pf.checked_out',
													 '#__intranet_pf.checked_out_time',
													 )));	
									

		$query->select('A.name AS name_register_pj');											 						 
		$query->select('B.name AS name_update_pj');		
				
		$query->from($this->_db->quoteName('#__intranet_pf'));
		$query->leftJoin($this->_db->quoteName('#__intranet_estado').' USING ('.$this->_db->quoteName('id_estado').')');
		$query->leftJoin($this->_db->quoteName('#__intranet_cidade').' USING ('.$this->_db->quoteName('id_estado').','.$this->_db->quoteName('id_cidade').')');
		$query->innerJoin($this->_db->quoteName('#__users').' U ON ('.$this->_db->quoteName('U.id').'='.$this->_db->quoteName('id_user').')');
		
		$query->leftJoin($this->_db->quoteName('#__users').' A ON ('.$this->_db->quoteName('A.id').'='.$this->_db->quoteName('user_register_pf').')');
		$query->leftJoin($this->_db->quoteName('#__users').' B ON ('.$this->_db->quoteName('B.id').'='.$this->_db->quoteName('user_update_pf').')');
		

		$status = $this->getState('filter.status');
        if ($status!='')
			 $query->where( $this->_db->quoteName('status_pf') . '=' . $this->_db->escape( $status ) );
			 
			 
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
			$searches[]	= 'cpf_pf LIKE '.$token;
			$searches[]	= 'U.email LIKE '.$token;
			$searches[]	= 'name_cidade LIKE '.$token;
			
			$query->where('('.implode(' OR ', $searches).')');
		 
		}
		
		$ordering = $this->getState('list.ordering');
		$direction = $this->getState('list.direction');
		$query->order($this->_db->quoteName($ordering) . ' ' . $this->_db->escape($direction) );
		
        return $query;
    }
	
	function remove_pf()
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

			$conditions = array( $this->_db->quoteName('id_pf') . ' IN (' . $cid . ')' );	
			
			//$query->clear();
			$query->delete( $this->_db->quoteName('#__intranet_pf') );
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


	function publish_pf2()
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

	function publish_pf()
	{
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		$cid = JRequest::getVar( 'cid' , array() , '' , 'array' );
		JArrayHelper::toInteger($cid);
		if ($this->setPublish($cid, '1'))
			return true;
		else
			return false;

	}
	
	function unpublish_pf()
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
			
			$fields = array( $this->_db->quoteName('status_pf') . ' = ' . $this->_db->quote( $status ) );

			$conditions = array( $this->_db->quoteName('id_pf') . ' IN (' . $cids . ')' );
			
			$query->update($this->_db->quoteName('#__intranet_pf'))->set($fields)->where($conditions);
			
			$this->_db->setQuery($query);
						
			if ( !$this->_db->query() ) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			return true;
		}	
	}

	/*
	function renovClube()
	{
		$query = $this->_db->getQuery(true);
		$query->select('PessoaFisicaReal.id_clube AS id_clube');
		$query->select('PessoaFisicaReal.id_pf AS id_pf');
		$query->select('PessoaFisicaBackup.id_clube AS id_clube_backup');

		$query->from($this->_db->quoteName('#__intranet_pf') . ' AS PessoaFisicaReal');
		$query->innerJoin($this->_db->quoteName('#__intranet_save_clube') . ' AS PessoaFisicaBackup USING ('.$this->_db->quoteName('id_pf').')');
		$query->where( $this->_db->quoteName('PessoaFisicaReal.id_clube') . ' IS NULL' );
		$query->where( $this->_db->quoteName('PessoaFisicaBackup.id_clube') . ' IS NOT NULL' );
		
		$this->_db->setQuery($query);
 		$atualizars = $this->_db->loadObjectList();

		foreach($atualizars as $i => $atualizar):
		

			$query->clear();
			$fields = array( $this->_db->quoteName('id_clube') . ' = ' . $this->_db->quote( $atualizar->id_clube_backup ) );
			$conditions = array( $this->_db->quoteName('id_pf') . '=' . $atualizar->id_pf );
			$query->update($this->_db->quoteName('#__intranet_pf'))->set($fields)->where($conditions);
			$this->_db->setQuery($query);
			if ( !$this->_db->query() ) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		endforeach;
		
		return true;
		
	}
	*/

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
			 
			$conditions = array( $this->_db->quoteName('id_pf') . ' IN (' . $cids . ')' );
 
			$query->update($this->_db->quoteName('#__intranet_pf'))->set($fields)->where($conditions);
 
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
	    $row =& $this->getTable('pf');
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
	    $row =& $this->getTable('pf');
		
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
