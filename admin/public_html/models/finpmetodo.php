<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');


class intranetModelFinPMetodo extends JModel 
{

	var $_id = null;
	var $_data = null;
	var $_db = null;
	var $_app = null;
	var $_user = null;
	var $_isRoot = null;
	var $_userLogged = null;
	var $_userAdmin = null;
	
	protected $helpURL;

	protected $_cache;
	
	
	function __construct()
	{
		parent::__construct();

		$this->_app 	= JFactory::getApplication(); 
		$this->_db	= JFactory::getDBO();
				
		$this->_user		= JFactory::getUser();
		$this->_isRoot		= $this->_user->get('isRoot');	
		$this->_userAdmin	= $this->_user->get('id'); 	
				
		$array  	= JRequest::getVar( 'cid', array(0), '', 'array');
		JRequest::setVar( 'cid', $array[0] );
		$this->setId( (int) $array[0] );



		if (!$this->isCheckedOut() )
		{
			$this->checkout();		
		}
		else
		{
			$tipo = 'alert-warning';
			$msg = JText::_( 'JGLOBAL_CONTROLLER_CHECKIN_ITEM' );
			$link = 'index.php?view=finpmetodos';
			$mainframe->redirect($link, $msg, $tipo);
		}
		
	}
	
	function setId( $id ) 
	{
		$this->_id		= $id;
		$this->_data	= null;
	}
	
	
	function getForm()
	{
		if (empty($this->_data))
			$this->getItem();
					
		$form = JForm::getInstance('', $this->_data->path,  array('control' => 'jform'));
		$form->bind($this->_data);

		return $form;
	}
	

	function getItem()
	{	
		if (empty($this->_data))
		{			
			// Initialise variables.
			if (!isset($this->_cache[$this->_id])) {
				$false	= false;
	
				// Get a row instance.
				$this->addTablePath(JPATH_BASE.'/tables');
				$table = $this->getTable('pmetodo');
				
				// Attempt to load the row.
				$return = $table->load($this->_id);
	
				// Check for a table object error.
				if ($return === false && $table->getError()) {
					$this->setError($table->getError());
					return $false;
				}
				
				
				// Convert to the JObject before adding other data.
				$properties = $table->getProperties(1);				
				$this->_cache[$this->_id] = JArrayHelper::toObject($properties, 'JObject');
	
				// Convert the params field to an array.
				$registry = new JRegistry;
				$registry->loadString($table->params_pagamento_metodo);
				$this->_cache[$this->_id]->params = $registry->toArray();	
		
				// Get the plugin XML.
				$path = JPath::clean(JPATH_MODULE.'/mod_'.$table->module_pagamento_metodo.'/mod_'.$table->module_pagamento_metodo.'.xml');

				$this->_cache[$this->_id]->path = $path;

				if (file_exists($path)) {			
					$this->_cache[$this->_id]->xml = JFactory::getXML($path);
				} else {
					$this->_cache[$this->_id]->xml = null;
				}
				
			}
			
			$this->_data = $this->_cache[$this->_id];
			
		}
		return $this->_data;
	}

	public function store()
	{
		// Load the extension plugin group.
		$data = JRequest::get( 'post' );
		
		$this->addTablePath(JPATH_BASE.'/tables');
		$row =& $this->getTable('pmetodo');
		$row->load($this->_id);
			
		//$row->enabled = $data['enabled'];
		
		if (is_array( $data['jform']['params'] )) {
			$params   = new JParameter($row->params);
			foreach ( $data['jform']['params'] as $key=>$value) {
				$params->set($key,$value);
			}
			$row->params_pagamento_metodo = $params->toString();
		}
	   
		if ( !$row->store() ) 
		{	
			$this->setError( $this->_db->getErrorMsg());
			return false;	
		}
		
		if(! $row->checkin() ) 
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		return true;
	
	}
	
	function isCheckedOut()
	{		
		$this->addTablePath(JPATH_BASE.'/tables');
	    $row = $this->getTable('pmetodo');
		$cid = JRequest::getVar( 'cid', array(0), '', 'array' );
		$cid = $cid[0];
		if ( $row->load( $cid ) ) 
		{	
			if (!$row->isCheckedOut( $this->_userAdmin ) )
			{
				return false;
			}
			return true;
		}
		return false;
	}
	
	function checkout()
	{		
		$this->addTablePath(JPATH_BASE.'/tables');
	    $row = $this->getTable('pmetodo');
		$cid = JRequest::getVar( 'cid', array(0), '', 'array' );
		$cid = $cid[0];
		if ( $row->load( $cid ) ) 
		{	
			if(! $row->checkout( $this->_userAdmin ) ) 
			{
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			return true;
		}
		return false;
	}
	   
	function checkin()
	{	
		$this->addTablePath(JPATH_BASE.'/tables');
	    $row = $this->getTable('pmetodo');
		$cid = JRequest::getVar( 'cid', array(0), '', 'array' );
		$cid = $cid[0];
		if ( $row->load( $cid ) ) 
		{	
			if(! $row->checkin( $cid ) ) 
			{
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			return true;
		}
		return false;
	}



}
