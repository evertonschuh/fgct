<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');


class IntranetModelFinProduto extends JModel 
{

	var $_id = null;
	var $_data = null;
	var $_db = null;
	var $_app = null;
	var $_user = null;
	var $_isRoot = null;
	var $_userLogged = null;
	var $_userAdmin = null;
	var $_siteOffset = null;
	
	protected $helpURL;

	protected $_cache;
	
	
	function __construct()
	{
		parent::__construct();

		$this->_app 		= JFactory::getApplication(); 
		$this->_db			= JFactory::getDBO();
		$this->_user		= JFactory::getUser();
		
		$this->_siteOffset 	= $this->_app->getCfg('offset');
		$this->_isRoot		= $this->_user->get('isRoot');	
		$this->_userAdmin	= $this->_user->get('id'); 	
			

		if( JRequest::getVar( 'task' )  != 'add')
		{
			$array  	= JRequest::getVar( 'cid', array(0), '', 'array');
			JRequest::setVar( 'cid', $array[0] );
			$this->setId( (int) $array[0] );
		
			if (!$this->isCheckedOut() ) {
				$this->checkout();		
			}
			else {
				$tipo = 'alert-warning';
				$msg = JText::_( 'JGLOBAL_CONTROLLER_CHECKIN_ITEM' );
				$link = 'index.php?view=finprodutos';
				$this->_app->redirect($link, $msg, $tipo);
			}
		}
	}
	
	function setId( $id ) 
	{
		$this->_id		= $id;
		$this->_data	= null;
	}
	
	function getItem()
	{	
		if (empty($this->_data))
		{			

			$query = $this->_db->getQuery(true);
			$query->select('*');
			$query->from('#__intranet_produto');
			$query->where($this->_db->quoteName('id_produto').'='.$this->_db->quote( $this->_id ) );
			$this->_db->setQuery($query);
			
			if( !(boolean) $this->_data = $this->_db->loadObject()) {;
			
				$this->addTablePath(JPATH_BASE.'/tables');
				$this->_data = $this->getTable('produto');
			}
			
		}
		return $this->_data;
	}
	

	function store()
	{

		$this->addTablePath(JPATH_BASE.'/tables');
	    $row = $this->getTable('produto');
		$data = JRequest::get( 'post' );
		
		if($this->_id)
			$row->load($this->_id);
			
		//cria a parta aula se ainda nao existe
		if ( !$row->bind($data)) 
		{
			$this->setError($this->_db->getErrorMsg());
			return false;	
		}
			
		if ( !$row->check($data)) 
		{
			$this->setError($this->_db->getErrorMsg());
			return false;	
		}	
			
		if ( !$row->store(TRUE) ) 
		{	
			$this->setError( $this->_db->getErrorMsg());
			return false;	
		}

		jimport('joomla.log.log');
		JLog::addLogger(array( 'text_file' => 'log.finprodutos.php'));
		

		if($this->_id):
			$row->checkin($this->_id);
			JLog::add($this->_user->get('id') . JText::_('		Produto Editada -  idProduto('.$this->_id.')'), JLog::INFO, 'finprodutos');
		else:
			$this->setId( $row->get('id_produto') ); 	
			JLog::add($this->_user->get('id') . JText::_('		Produto Cadastrada -  idProduto('.$this->_id.')'), JLog::INFO, 'finprodutos');
		endif;
		
		JRequest::setVar( 'cid', $this->_id );
		
		return true;
	
	}
	
	
	function isCheckedOut()
	{		
		$this->addTablePath(JPATH_BASE.'/tables');
	    $row = $this->getTable('produto');
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
	    $row = $this->getTable('produto');
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
	    $row = $this->getTable('produto');
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




