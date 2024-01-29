<?php
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.filesystem.file' );
require_once(JPATH_LIBRARIES .DS. 'qrcode' .DS. 'qrlib.php');

jimport( 'joomla.application.component.model' );

class IntranetModelDocAssociado extends JModel {

	var $_id = null;
	var $_data = null;
	var $_db = null;
	var $_app = null;	
	var $_isRoot = null;
	var $_user = null;
	
	function __construct()
	{
        parent::__construct();
		
		$this->_db	= JFactory::getDBO();		
		$this->_app	 = JFactory::getApplication(); 
		$this->_user = JFactory::getUser();
		$this->_id_user = $this->_user->get('id');
		//$this->_trataImagem = new IntranetIncludesImages();

		$this->_siteOffset = $this->_app->getCfg('offset');

		$array  	= JRequest::getVar( 'cid', array(0), '', 'array');

		JRequest::setVar( 'cid', $array[0] );
		$this->setId( (int) $array[0] );
	

		
	}
	
	function setId( $id) 
	{
		$this->_id		= $id;
		$this->_data	= null;
	}

	function getItem()
	{
		if (empty($this->_data))
		{			
		
			$query = $this->_db->getQuery(true);
			$query->select( $this->_db->quoteName(array('id_documento_numero',
														'id_documento',
														'texto_documento_numero',
														'register_documento_numero',
														'name',
														'cpf_pf',
														'skin_documento',
														'id_user',
														'skin_documento'
														)));
			$query->select('CONCAT(ano_documento_numero, \'/\', numero_documento_numero) AS numero_documento_numero');		
			$query->from($this->_db->quoteName('#__intranet_documento_numero'));
			$query->innerJoin($this->_db->quoteName('#__intranet_documento') . 'USING(' . $this->_db->quoteName('id_documento') . ')');
			$query->innerJoin($this->_db->quoteName('#__intranet_pf') . 'USING(' . $this->_db->quoteName('id_user') . ')');
			$query->innerJoin($this->_db->quoteName('#__users') . 'ON(' . $this->_db->quoteName('id_user') .  '=' . $this->_db->quoteName('id') .  ')');		
			
			$query->where($this->_db->quoteName('id_documento_numero') . ' = ' . $this->_db->quote($this->_id));	

			$this->_db->setQuery($query);

			$this->_data =  $this->_db->loadObject();

			$this->_data->skin_documento_numero = '/media/images/ptimbrado/' . $this->_data->skin_documento. '.jpg';

		}
		return $this->_data;
	}

	
}