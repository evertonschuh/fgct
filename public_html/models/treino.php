<?php
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class EASistemasModelTreino extends JModel
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
				$link = 'index.php?view=treinos';
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
			$query->from('#__treino');
			$query->where($this->_db->quoteName('id_treino') . '=' . $this->_db->quote($this->_id));
			$this->_db->setQuery($query);
			if (!(bool) $this->_data = $this->_db->loadObject()) {
				$this->addTablePath(JPATH_SITE . '/tables');
				$this->_data = $this->getTable('treino');
			}
		}
		return $this->_data;
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

	function getMeses()
	{
		if(empty($this->_data))
			$this->getItem();
		
		$rows = array();
		$arrayTest = array();
		$mesesDisabled = array();
		if(!empty($this->_data->id_corredor_categoria)) {
			$mesesDisabled = $this->getMesesDisable($this->_data->id_corredor_categoria);

			if(!empty($this->_data->mes_treino)){
				$rows[] = JHTML::_('select.option', $this->_data->mes_treino, JFactory::getDate($this->_data->mes_treino, $this->_siteOffset )->toFormat('%m/%Y', true), 'value', 'text' );
				$arrayTest[] =  $this->_data->mes_treino;
			}
			$mesInicio = JFactory::getDate('now - 2 month', $this->_siteOffset )->toFormat('%Y-%m', true) . '-01';
			for($x = 1; $x <= 4; $x++){
				$value = JFactory::getDate($mesInicio . ' + '. $x .' month', $this->_siteOffset )->toFormat('%Y-%m-%d', true);
				if(!in_array($value, $arrayTest)){
					$disabled = '';
					$text = JFactory::getDate($mesInicio . ' + '. $x .' month', $this->_siteOffset )->toFormat('%m/%Y', true);
					if(in_array($value, $mesesDisabled)){
						$disabled  = ' disabled="disabled"';
						$text .= ' (Treino Existente)';
					}
						
					$rows[] = JHTML::_('select.option', $value, $text, 'value', 'text', $disabled );
				}
			}
		}
			
		$date = new DateTime();
		$query_date = '2023-07-04';
		
		$source_date = strtotime($query_date);
		
		$dat_ini = new DateTime(date('Y-m-01', $source_date));
		$dat_fin = new DateTime(date('Y-m-t', $source_date));
		
		$NumeroSemanas = (int)$dat_fin->format('W') - (int)$dat_ini->format('W') + 1;
		echo $NumeroSemanas;

		return $rows;
	}


	function getMesesDisable($id_corredor_categoria = null){
		$query = $this->_db->getQuery(true);
		$query->select('mes_treino');
		$query->from('#__treino');
		$query->where($this->_db->quoteName('id_corredor_categoria') . '=' . $this->_db->quote($id_corredor_categoria));
		$query->where($this->_db->quoteName('id_treino') . '<>' . $this->_db->quote($this->_id));
		$this->_db->setQuery($query);
		return $this->_db->loadColumn();
	}




	function store()
	{

		$this->addTablePath(JPATH_SITE.'/tables');
	    $row = $this->getTable('treino');
		$post = JRequest::get( 'post' );

		if ($this->_id)
			$row->load($this->_id);

		$post['observacao_treino'] = JRequest::getVar('observacao_treino', null, 'default', 'none', JREQUEST_ALLOWHTML);

		if (!$row->bind($post)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if(!isset($post['status_treino']))
			$row->status_treino = 0;
			
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
			JLog::add($this->_user->get('id') . JText::_('		Edit Categoria Corredor -  id(' . $this->_id . ')'), JLog::INFO, 'treino');
		else :
			$this->setId($row->get('id_treino'));
			JLog::add($this->_user->get('id') . JText::_('		New Categoria Corredor -  id(' . $this->_id . ')'), JLog::INFO, 'treino');
		endif;

		JRequest::setVar('cid', $this->_id);

		return true;
	}


	function isCheckedOut()
	{
		$this->addTablePath(JPATH_SITE . '/tables');
		$row = $this->getTable('treino');
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
		$row = $this->getTable('treino');
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
		$row = $this->getTable('treino');

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