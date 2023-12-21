<?php
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class EASistemasModelCCategoria extends JModel
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
				$link = 'index.php?view=ccategorias';
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
			$query->from('#__corredor_categoria');
			$query->where($this->_db->quoteName('id_corredor_categoria') . '=' . $this->_db->quote($this->_id));
			$this->_db->setQuery($query);
			if (!(bool) $this->_data = $this->_db->loadObject()) {
				$this->addTablePath(JPATH_SITE . '/tables');
				$this->_data = $this->getTable('ccategoria');
			}
		}
		return $this->_data;
	}


	function store()
	{

		$this->addTablePath(JPATH_SITE.'/tables');
	    $row = $this->getTable('ccategoria');
		$post = JRequest::get( 'post' );

		if ($this->_id)
			$row->load($this->_id);

		$post['observacao_corredor_categoria'] = JRequest::getVar('observacao_corredor_categoria', null, 'default', 'none', JREQUEST_ALLOWHTML);

		if (!$row->bind($post)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if(!isset($post['status_corredor_categoria']))
			$row->status_corredor_categoria = 0;
			
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
			JLog::add($this->_user->get('id') . JText::_('		Edit Categoria Corredor -  id(' . $this->_id . ')'), JLog::INFO, 'ccategoria');
		else :
			$this->setId($row->get('id_corredor_categoria'));
			JLog::add($this->_user->get('id') . JText::_('		New Categoria Corredor -  id(' . $this->_id . ')'), JLog::INFO, 'ccategoria');
		endif;

		JRequest::setVar('cid', $this->_id);

		return true;
	}


	function isCheckedOut()
	{
		$this->addTablePath(JPATH_SITE . '/tables');
		$row = $this->getTable('ccategoria');
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
		$row = $this->getTable('ccategoria');
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
		$row = $this->getTable('ccategoria');

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