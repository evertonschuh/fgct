<?php

/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');


class EASistemasModelCorredors extends JModelList
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

		$status = $this->_app->getUserStateFromRequest(
			$this->context . 'filter.status',
			'status',
			null,
			'string'
		);
		$this->setState('filter.status', $status);

		$categoria = $this->getUserStateFromRequest(
			$this->context . '.filter.categoria',
			'categoria',
			null,
			'string'
		);
		$this->setState('filter.categoria', $categoria);

		$grupo = $this->getUserStateFromRequest(
			$this->context . '.filter.grupo',
			'grupo',
			null,
			'string'
		);
		$this->setState('filter.grupo', $grupo);


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
			'id_corredor',
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
			'id_corredor',
			'status_corredor',
			'name_corredor',
			'email_corredor',
			'cpf_corredor',
			'name_cidade',
			'sigla_estado',
			'name_estado',
			'image_corredor'
		)));

		$query->select('IF(ISNULL(id_corredor_categoria), 0, id_corredor_categoria) AS id_corredor_categoria');
		$query->select('IF(ISNULL(id_corredor_grupo), 0, id_corredor_grupo) AS id_corredor_grupo');

		$query->select('IF(ISNULL(id_corredor_categoria), \'Ñ\', name_corredor_categoria) AS name_corredor_categoria');
		$query->select('IF(ISNULL(id_corredor_grupo), \'Ñ\', name_corredor_grupo) AS name_corredor_grupo');

		$query->from($this->_db->quoteName('#__corredor'));
		$query->leftJoin($this->_db->quoteName('#__corredor_categoria').' USING ('.$this->_db->quoteName('id_corredor_categoria').')');
		$query->leftJoin($this->_db->quoteName('#__corredor_grupo').' USING ('.$this->_db->quoteName('id_corredor_grupo').')');
		$query->leftJoin($this->_db->quoteName('#__estado').' USING ('.$this->_db->quoteName('id_estado').')');
		$query->leftJoin($this->_db->quoteName('#__cidade').' USING ('.$this->_db->quoteName('id_estado').','.$this->_db->quoteName('id_cidade').')');

		$status = $this->getState('filter.status');
		if ($status != '')
			$query->where($this->_db->quoteName('status_corredor') . '=' . $this->_db->quote($this->_db->escape($status)));
		else
			$query->where($this->_db->quoteName('status_corredor') . '>=' . $this->_db->quote($this->_db->escape('0')));



		$categoria = $this->getState('filter.categoria');
		if ($categoria != '')
			$query->where($this->_db->quoteName('id_corredor_categoria') . '=' . $this->_db->quote($categoria));


		$grupo = $this->getState('filter.grupo');
		if ($grupo != '')
			$query->where($this->_db->quoteName('id_corredor_grupo') . '=' . $this->_db->quote($grupo));


			


		$search = $this->getState('filter.search');
		if ($search != '') {
			// Escape the search token.
			$token	= $this->_db->quote('%' . $this->_db->escape($search) . '%');

			// Compile the different search clauses.
			$searches	= array();
			$searches[]	= 'name_corredor LIKE ' . $token;
			$searches[]	= 'cpf_corredor LIKE ' . $token;
			$searches[]	= 'name_cidade LIKE ' . $token;
			$searches[]	= 'sigla_estado LIKE ' . $token;
			$searches[]	= 'name_estado LIKE ' . $token;

			$query->where('(' . implode(' OR ', $searches) . ')');
		}

		$ordering = $this->getState('list.ordering');
		$direction = $this->getState('list.direction');
		$query->order($this->_db->quoteName($ordering) . ' ' . $this->_db->escape($direction));

		return $query;
	}

	function remove()
	{
		JRequest::checkToken() or jexit('JINVALID_TOKEN');
		$cids = JRequest::getVar('cid',	array(), '', 'array');
		JArrayHelper::toInteger($cids);
		if (count($cids)) {
			$cid = implode(',', $cids);

			$query = $this->_db->getQuery(true);
			$conditions = array($this->_db->quoteName('id_corredor') . ' IN (' . $cid . ')');
			$query->delete($this->_db->quoteName('#__corredor'));
			$query->where($conditions);
			$this->_db->setQuery($query);
			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}

			return true;
		}
	}


	function getPfbyCPF($cpf_corredor=NULL){

		if(is_null($cpf_corredor))
			return false;
		$query = $this->_db->getQuery(true);
		$query->select($this->_db->quoteName('id_corredor'));
		$query->from($this->_db->quoteName('#__corredor'));
		$query->where($this->_db->quoteName('cpf_corredor') . '=' . $this->_db->quote($cpf_corredor));
		$this->_db->setQuery($query);
		if (!(boolean) $id_corredor = $this->_db->loadResult()) {
			return false;
		}
		return $id_corredor;

	}

	function getEstadoByUF($sigla_estado=NULL){

		if(is_null($sigla_estado))
			return false;

		$query = $this->_db->getQuery(true);
		$query->select($this->_db->quoteName('id_estado'));
		$query->from($this->_db->quoteName('#__estado'));
		$query->where($this->_db->quoteName('sigla_estado') . ' LIKE ' . $this->_db->quote('%' . $this->_db->escape($sigla_estado) . '%'));
		$this->_db->setQuery($query);
		if (!(boolean) $id_estado = $this->_db->loadResult()) {
			return false;
		}
		return $id_estado;

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

			$fields = array($this->_db->quoteName('status_corredor') . ' = ' . $this->_db->quote($status));

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
		$query->select('id_corredor_categoria as value, name_corredor_categoria as text');
		$query->from('#__corredor_categoria');
		$query->where( $this->_db->quoteName('status_corredor_categoria') . '=' . $this->_db->escape( '1' ));
		$query->order( $this->_db->quoteName('name_corredor_categoria') );
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();
	}

}