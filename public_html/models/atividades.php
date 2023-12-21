<?php

/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');


class EASistemasModelAtividades extends JModelList
{
	var $_db = null;
	var $_app = null;
	var $_pagination = null;
	var $_total = null;
	var $_data = null;
	var $_isRoot = null;
	var $_user = null;
	var $_userAdmin = null;

	function __construct()
	{
		parent::__construct();

		$this->_db	= JFactory::getDBO();
		$this->_user = JFactory::getUser();
		$this->_app = JFactory::getApplication();
		$this->_user	= JFactory::getUser();

		$this->_isRoot		= $this->_user->get('isRoot');
		$this->_userAdmin	= $this->_user->get('id');
	}

	protected function populateState($ordering = null, $direction = null)
	{

		$limit		= $this->_app->getUserStateFromRequest(
			$this->context . '.list.limit',
			'limit',
			$this->_app->getCfg('list_limit'),
			'int'
		);

		$limitstart	= $this->_app->getUserStateFromRequest(
			$this->context . '.list.start',
			'start',
			0,
			'int'
		);

		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		$this->setState('list.limit', $limit);
		$this->setState('list.start', $limitstart);

		$groupId = $this->getUserStateFromRequest(
			$this->context . '.filter.group',
			'filter_group_id',
			null,
			'int'
		);
		$this->setState('filter.group_id', $groupId);

		$status = $this->_app->getUserStateFromRequest(
			$this->context . 'filter.status',
			'status',
			null,
			'string'
		);
		$this->setState('filter.status', $status);

		$search = $this->_app->getUserStateFromRequest(
			$this->context . 'filter.search',
			'search',
			null,
			'string'
		);
		$this->setState('filter.search', $search);

		$ordering = $this->_app->getUserStateFromRequest(
			$this->context . 'list.ordering',
			'filter_order',
			'id_atividade',
			'string'
		);
		$this->setState('list.ordering', $ordering);

		$direction = $this->_app->getUserStateFromRequest(
			$this->context . 'list.direction',
			'filter_order_Dir',
			'DESC',
			'string'
		);
		$this->setState('list.direction', $direction);

		parent::populateState($ordering, $direction);
	}

	protected function getListQuery()
	{

		$query = $this->_db->getQuery(true);
		$query->select($this->_db->quoteName(array(
			'id_atividade',
			'status_atividade',
			'name_atividade',
			'checked_out',
			'checked_out_time',
		)));

		$query->from($this->_db->quoteName('#__atividade'));

		$status = $this->getState('filter.status');
		if ($status != '')
			$query->where($this->_db->quoteName('status_atividade') . '=' . $this->_db->quote($this->_db->escape($status)));
		else
			$query->where($this->_db->quoteName('status_atividade') . '>=' . $this->_db->quote($this->_db->escape('0')));


		$search = $this->getState('filter.search');
		if ($search != '') {
			$token	= $this->_db->quote('%' . $this->_db->escape($search) . '%');
			$searches	= array();
			$searches[]	= 'name_atividade LIKE ' . $token;

			$query->where('(' . implode(' OR ', $searches) . ')');
		}

		$ordering = $this->getState('list.ordering');
		$direction = $this->getState('list.direction');
	//$query->order($this->_db->quoteName($ordering) . ' ' . $this->_db->quote($direction));
		
		return $query;
	}

	function _getTotalCorredores($id_atividade)
	{
		$query = $this->_db->getQuery(true);	
		$query->select( 'COUNT(DISTINCT(' . $this->_db->quoteName('id_corredor') . '))' );	
		$query->from($this->_db->quoteName('#__corredor'));
		$query->where($this->_db->quoteName('id_atividade') . ' = ' .  $this->_db->quote( $id_atividade ));
		$this->_db->setQuery($query);
		return $this->_db->loadResult();
	}

	function remove()
	{
		JRequest::checkToken() or jexit('JINVALID_TOKEN');
		$cids = JRequest::getVar('cid',	array(), '', 'array');
		JArrayHelper::toInteger($cids);
		if (count($cids)) {
			$cid = implode(',', $cids);

			$query = $this->_db->getQuery(true);
			$conditions = array($this->_db->quoteName('id_atividade') . ' IN (' . $cid . ')');
			$query->delete($this->_db->quoteName('#__atividade'));
			$query->where($conditions);
			$this->_db->setQuery($query);
			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}

			return true;
		}
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
		JRequest::checkToken() or jexit('JINVALID_TOKEN');
		$cid = JRequest::getVar('cid', array(), '', 'array');
		JArrayHelper::toInteger($cid);
		if ($this->setPublish($cid, '1'))
			return true;
		else
			return false;
	}

	function unpublish()
	{
		JRequest::checkToken() or jexit('JINVALID_TOKEN');
		$cid = JRequest::getVar('cid', array(), '', 'array');
		JArrayHelper::toInteger($cid);
		if ($this->setPublish($cid, '0'))
			return true;
		else
			return false;
	}

	function setPublish($cid, $status)
	{
		if (count($cid)) {
			$cids = implode(',', $cid);
			$query = $this->_db->getQuery(true);

			$fields = array($this->_db->quoteName('status_atividade') . ' = ' . $this->_db->quote($status));

			$conditions = array($this->_db->quoteName('id_atividade') . ' IN (' . $cids . ')');

			$query->update($this->_db->quoteName('#__atividade'))->set($fields)->where($conditions);

			$this->_db->setQuery($query);

			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			return true;
		}
	}


	function checkin()
	{

		JRequest::checkToken() or jexit('JINVALID_TOKEN');
		$cid = JRequest::getVar('cid', array(), '', 'array');
		JArrayHelper::toInteger($cid);
		if (count($cid)) {
			$cids = implode(',', $cid);
			$query = $this->_db->getQuery(true);

			$fields = array(
				$this->_db->quoteName('checked_out') . ' = NULL',
				$this->_db->quoteName('checked_out_time') . ' = NULL'
			);

			$conditions = array($this->_db->quoteName('id_corredor') . ' IN (' . $cids . ')');

			$query->update($this->_db->quoteName('#__corredor'))->set($fields)->where($conditions);

			$this->_db->setQuery($query);

			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			return true;
		}
	}

	
	function getGrupos()
	{
		$query = $this->_db->getQuery(true);
		$query->select('id_corredor_grupo as value, name_corredor_grupo as text');
		$query->from('#__corredor_grupo');
		$query->where( $this->_db->quoteName('status_corredor_grupo') . '=' . $this->_db->escape( '1' ));
		$query->order( $this->_db->quoteName('name_corredor_grupo') );
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();
	}

	function getCategorias()
	{
		$query = $this->_db->getQuery(true);
		$query->select('id_atividade as value, name_atividade as text');
		$query->from('#__atividade');
		$query->where( $this->_db->quoteName('status_atividade') . '=' . $this->_db->escape( '1' ));
		$query->order( $this->_db->quoteName('name_atividade') );
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();
	}

}