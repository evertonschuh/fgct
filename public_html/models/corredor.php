<?php
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class EASistemasModelCorredor extends JModel
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
				$link = 'index.php?view=corredors';
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
			$query->from('#__corredor');
			$query->where($this->_db->quoteName('id_corredor') . '=' . $this->_db->quote($this->_id));
			$this->_db->setQuery($query);
			if (!(bool) $this->_data = $this->_db->loadObject()) {
				$this->addTablePath(JPATH_SITE . '/tables');
				$this->_data = $this->getTable('corredor');
			}
		}
		return $this->_data;
	}

	function getEstados()
	{
		$query = $this->_db->getQuery(true);
		$query->select('id_estado as value, name_estado as text');
		$query->from('#__estado');
		$query->where('status_estado = 1');
		$query->order($this->_db->quoteName('text'));
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();
	}

	function getCidades()
	{
		if(empty($this->_data))
			$this->getItem();
		if (isset($this->_data->id_estado)) {
			$query = $this->_db->getQuery(true);
			$query->select('id_cidade as value, name_cidade as text');
			$query->from('#__cidade');
			$query->where('id_estado = ' . $this->_db->quote($this->_data->id_estado));
			$query->where('status_cidade = 1');
			$query->order($this->_db->quoteName('text'));
			$this->_db->setQuery($query);
			return $this->_db->loadObjectList();
		}
	}

	function testDOC() {

		$post = JRequest::get( 'post' );

		$query = $this->_db->getQuery(true);
		$query->select($this->_db->quoteName('id_corredor'));
		$query->from($this->_db->quoteName('#__corredor'));
		$query->where($this->_db->quoteName('cpf_corredor') . '=' . $this->_db->quote($post['doc']));

		$query->where($this->_db->quoteName('id_corredor') . '<>' . $this->_db->quote($this->_id));		
		$this->_db->setQuery($query);
		
		return !$this->_db->loadResult();	
	}

	function store()
	{

		$this->addTablePath(JPATH_SITE.'/tables');

	    $row = $this->getTable('corredor');

		$post = JRequest::get( 'post' );
		$image = JRequest::getVar( 'image_corredor_new', '', 'files', 'array' );
		$_path = JPATH_MEDIA.DS.'images'.DS.'avatar';

		if (!JFolder::exists($_path)) {
			$_folder_permissions = "0755";
			$_folder_permissions = octdec((int)$_folder_permissions);
			JFolder::create($_path, $_folder_permissions);
			$buffer = '<html><body bgcolor="#FFFFFF"></body></html>';
			JFile::write($_path . DS . 'index.html', $buffer);
		}

		if ($this->_id)
			$row->load($this->_id);
		else
			$post['cadastro_corredor'] = JFactory::getDate('now', $this->_siteOffset )->toISO8601(true);

		if ($post['remove_image_corredor']) {
			if (JFile::exists($_path . DS . $post['image_corredor']))
				JFile::delete($_path . DS . $post['image_corredor']);
			$post['image_corredor'] = NULL;
		} elseif (isset($image['name']) && $image['name'] != '') {
			$ext = strtolower(JFile::getExt($image['name']));
			$name =  md5(uniqid());
			$thumb = $name . '.' . $ext;

			if (JFile::exists($_path . DS . $thumb))
				JFile::delete($_path . DS . $thumb);

			if (!JFile::upload($image['tmp_name'], $_path . DS . $thumb))
				return false;

			if (JFile::exists($_path . DS . $post['image_corredor']))
				JFile::delete($_path . DS . $post['image_corredor']);

			$post['image_corredor'] = $thumb;
		}

		if (!empty($post['data_nascimento_corredor'])) {
			$post['data_nascimento_corredor'] = implode("-", array_reverse(explode("/", $post['data_nascimento_corredor'])));
			$post['data_nascimento_corredor'] = JFactory::getDate($post['data_nascimento_corredor'], $this->_siteOffset )->toFormat('%Y-%m-%d', true);
		} else
			$post['data_nascimento_corredor'] = NULL;

		if (!$row->bind($post)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		if(is_null($post['image_corredor']))
			$row->image_corredor = null;

		if (!$row->check($post)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (!$row->store($post)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		jimport('joomla.log.log');
		JLog::addLogger(array('text_file' => 'log.corredor.php'));

		if ($this->_id) :
			$row->checkin($this->_id);
			JLog::add($this->_user->get('id') . JText::_('		Edit Corredor -  id(' . $this->_id . ')'), JLog::INFO, 'corredor');
		else :
			$this->setId($row->get('id_corredor'));
			JLog::add($this->_user->get('id') . JText::_('		New Corredor -  id(' . $this->_id . ')'), JLog::INFO, 'corredor');
		endif;

		JRequest::setVar('cid', $this->_id);

		return true;
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

	function isCheckedOut()
	{
		$this->addTablePath(JPATH_SITE . '/tables');
		$row = $this->getTable('corredor');
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
		$row = $this->getTable('corredor');
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
		$row = $this->getTable('corredor');

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