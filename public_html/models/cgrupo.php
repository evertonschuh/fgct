<?php
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class EASistemasModelCGrupo extends JModel
{

	var $_id = null;
	var $_data = null;
	var $_db = null;
	var $_app = null;
	var $_isRoot = null;
	var $_user = null;
	var $_userAdmin = null;
	var $_pagination = null;
	var $_siteOffset = null;

	function __construct()
	{
		parent::__construct();

		$this->_app 	= JFactory::getApplication();
		$this->_db		= JFactory::getDBO();
		$this->_user	= JFactory::getUser();

		$this->_isRoot		= $this->_user->get('isRoot');
		$this->_userAdmin	= $this->_user->get('id');
		$this->_siteOffset 	= $this->_app->getCfg('offset'); 

		if (JRequest::getVar('task')  != 'add') {
			$array  	= JRequest::getVar('cid', array(0), '', 'array');
			JRequest::setVar('cid', $array[0]);
			$this->setId((int) $array[0]);

			if (!$this->isCheckedOut()) {
				$this->checkout();
			} else {
				$tipo = 'warning';
				$msg = JText::_('JGLOBAL_CONTROLLER_CHECKIN_ITEM');
				$link = 'index.php?view=cgrupos';
				$this->_app->redirect($link, $msg, $tipo);
			}
		}
	}

	function setId($id)
	{
		$this->_id		= $id;
		$this->_data	= null;
	}

	function getItem()
	{
		if (empty($this->_data)) {
			$query = $this->_db->getQuery(true);
			$query->select('*');
			$query->from('#__corredor_grupo');
			$query->where($this->_db->quoteName('id_corredor_grupo') . '=' . $this->_db->quote($this->_id));
			$this->_db->setQuery($query);
			if (!(bool) $this->_data = $this->_db->loadObject()) {
				$this->addTablePath(JPATH_SITE . '/tables');
				$this->_data = $this->getTable('cgrupo');
			}
		}
		return $this->_data;
	}


	function store()
	{

		$this->addTablePath(JPATH_SITE.'/tables');
	    $row = $this->getTable('cgrupo');
		$post = JRequest::get( 'post' );

		if ($this->_id)
			$row->load($this->_id);

		$post['observacao_corredor_grupo'] = JRequest::getVar('observacao_corredor_grupo', null, 'default', 'none', JREQUEST_ALLOWHTML);

		if (!$row->bind($post)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if(!isset($post['status_corredor_grupo']))
			$row->status_corredor_grupo = 0;
			
		if (!$row->check($post)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (!$row->store($post)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		jimport('joomla.log.log');
		JLog::addLogger(array('text_file' => 'log.c.php'));

		if ($this->_id) :
			$row->checkin($this->_id);
			JLog::add($this->_user->get('id') . JText::_('		Edit Categoria Corredor -  id(' . $this->_id . ')'), JLog::INFO, 'cgrupo');
		else :
			$this->setId($row->get('id_corredor_grupo'));
			JLog::add($this->_user->get('id') . JText::_('		New Categoria Corredor -  id(' . $this->_id . ')'), JLog::INFO, 'cgrupo');
		endif;

		JRequest::setVar('cid', $this->_id);

		return true;
	}

	function getAtividades()
	{
		$query = $this->_db->getQuery(true);
		$query->select('id_atividade as value, name_atividade as text');
		$query->from('#__atividade');
		$query->order($this->_db->quoteName('text'));

		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();
	}

	function isCheckedOut()
	{
		$this->addTablePath(JPATH_SITE . '/tables');
		$row = $this->getTable('cgrupo');
		$cid = JRequest::getVar('cid', array(0), '', 'array');
		$cid = $cid[0];
		if ($row->load($cid)) {
			if (!$row->isCheckedOut($this->_userAdmin)) {
				return false;
			}
			return true;
		}
		return false;
	}

	function checkout()
	{
		$this->addTablePath(JPATH_SITE . '/tables');
		$row = $this->getTable('cgrupo');
		$cid = JRequest::getVar('cid', array(0), '', 'array');
		$cid = $cid[0];
		if ($row->load($cid)) {
			if (!$row->checkout($this->_userAdmin)) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			return true;
		}
		return false;
	}

	function checkin()
	{
		$this->addTablePath(JPATH_SITE . '/tables');
		$row = $this->getTable('cgrupo');

		$cid = JRequest::getVar('cid', array(0), '', 'array');
		$cid = $cid[0];
		if ($row->load($cid)) {
			if (!$row->checkin($cid)) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			return true;
		}
		return false;
	}
}