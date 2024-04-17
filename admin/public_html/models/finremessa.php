<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');


class IntranetModelFinRemessa extends JModel 
{

	var $_id = null;
	var $_data = null;
	var $_db = null;
	var $_app = null;
	var $_user = null;
	var $_isRoot = null;
	var $_userLogged = null;
	var $_userAdmin = null;
	
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
			$link = 'index.php?view=finremessas';
			$this->_app->redirect($link, $msg, $tipo);
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
			$query->from($this->_db->quoteName('#__intranet_pagamento_remessa'));
			$query->innerJoin($this->_db->quoteName('#__intranet_pagamento_metodo') . ' USING(' . $this->_db->quoteName('id_pagamento_metodo') . ')');
			$query->where( $this->_db->quoteName('id_pagamento_remessa') . '=' . $this->_db->quote( $this->_id ) );
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
		}
		
		return $this->_data;
	}

	function getConteudoTipos()
	{
		$query = $this->_db->getQuery(true);
		$query->select(array('id_conteudo_type', 
							 'table_conteudo_type',
							 'parent_conteudo_type',
							 ));
		$query->from('#__conteudo_type');
		$query->where( $this->_db->quoteName('status_conteudo_type') . ' = ' . $this->_db->quote( '1' ) );
		$query->order('table_conteudo_type ASC');
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();	
	}

	function getPagamentos()
	{
		if(empty($this->_data))
			$this->getItem();
			
		$registry = new JRegistry;
		$registry->loadString($this->_data->pagamentos_pagamento_remessa);
		$idsPagamentos = $registry->toArray();	
		
		
		
		if (count($idsPagamentos)>0) :

			$ids = implode( ',', $idsPagamentos );

			$query = $this->_db->getQuery(true);
			$query->select($this->_db->quoteName(  array('#__intranet_pagamento.id_pagamento',
														 'vencimento_pagamento',
														 'registrado_pagamento',
														 'baixa_pagamento',
														 'status_pagamento',
														 'resposta_pagamento',
														 'valor_pagamento',
														 'text_pagamento',
														 'valor_desconto_pagamento',
														 'valor_pago_pagamento',
														 'name',
														 'email',
														// 'name_anuidade',
														 'name_pagamento_metodo',
														 'module_pagamento_metodo',
														 'id_user',
														 /* 'icon_pagamento_metodo',
														 '#__intranet_pagamento.ordering',*/
														 '#__intranet_pagamento.checked_out',
														 '#__intranet_pagamento.checked_out_time',
														 )));	
														 
			$query->select( 'IF( ISNULL(name_anuidade), name_produto, name_anuidade)  AS produto');
			
			$query->from($this->_db->quoteName('#__intranet_pagamento'));
			$query->innerJoin($this->_db->quoteName('#__intranet_pagamento_metodo') . ' USING(' . $this->_db->quoteName('id_pagamento_metodo'). ')');
			$query->leftJoin($this->_db->quoteName('#__intranet_anuidade') . ' USING(' . $this->_db->quoteName('id_anuidade'). ')');
			$query->leftJoin($this->_db->quoteName('#__intranet_produto') . ' USING(' . $this->_db->quoteName('id_produto'). ')');
			$query->innerJoin($this->_db->quoteName('#__users') . ' ON(' . $this->_db->quoteName('id'). '=' . $this->_db->quoteName('id_user'). ')');
				
			$query->where( $this->_db->quoteName('#__intranet_pagamento.id_pagamento') .  ' IN (' . $ids . ')' );
			$this->_db->setQuery($query);
			return $this->_db->loadObjectList();
		endif;
	}

	
	function isCheckedOut()
	{		
		$this->addTablePath(JPATH_BASE.'/tables');
	    $row = $this->getTable('pagamentoremessa');
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
	    $row = $this->getTable('pagamentoremessa');
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
	    $row = $this->getTable('pagamentoremessa');
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
