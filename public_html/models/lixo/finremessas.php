<?php
/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */

defined( '_JEXEC' ) or die;

jimport( 'joomla.application.component.modellist' );

class IntranetModelFinRemessas extends JModelList
{
	var $_db = null;
	var $_user = null;
	var $_app = null;	
	var $_pagination = null;
	var $_total = null;	
	var $_data = null;
	
	function __construct()
	{	
		parent::__construct();
		
		$this->_db	= JFactory::getDBO();
		$this->_user = JFactory::getUser();
		$this->_app = JFactory::getApplication();
		
	}
	
    protected function populateState($ordering = null, $direction = null) {

		$limit		= $this->_app->getUserStateFromRequest( 
		$this->context . '.list.limit', 'limit', $this->_app->getCfg('list_limit'), 'int' );
		
		$limitstart	= $this->_app->getUserStateFromRequest( 
		$this->context . '.list.start', 'start', 0, 'int' );

		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
	   	$this->setState('list.limit', $limit); 
	  	$this->setState('list.start', $limitstart); 
		
        $metodos = $this->_app->getUserStateFromRequest(
        $this->context .'filter.metodos', 'metodos', null,'string');
        $this->setState('filter.metodos', $metodos);
		
        $search = $this->_app->getUserStateFromRequest(
        $this->context .'filter.search', 'search', null,'string');
        $this->setState('filter.search', $search);  	

		$ordering = $this->_app->getUserStateFromRequest(
        $this->context .'list.ordering','filter_order', 'id_pagamento_remessa', 'string');
		$this->setState('list.ordering', $ordering); 

 		$direction = $this->_app->getUserStateFromRequest(
        $this->context .'list.direction','filter_order_Dir', 'DESC','string');
		$this->setState('list.direction', $direction); 

	   parent::populateState($ordering, $direction );
		
    }

    protected function getListQuery() {
		
		$query = $this->_db->getQuery(true);	

		$query->select($this->_db->quoteName(  array('id_pagamento_remessa',
													 'name_pagamento_remessa',
													 'date_pagamento_remessa',
													 'name_pagamento_metodo',
													 'register_pagamento_remessa',
													 '#__intranet_pagamento_remessa.checked_out',
													 '#__intranet_pagamento_remessa.checked_out_time',
													 )));							 
		$query->select('A.name AS name_register_remessa');											 						 
		$query->select('B.name AS name_update_remessa');			
		
		$query->from($this->_db->quoteName('#__intranet_pagamento_remessa'));
		$query->innerJoin($this->_db->quoteName('#__intranet_pagamento_metodo') . ' USING(' . $this->_db->quoteName('id_pagamento_metodo') . ')');
		$query->innerJoin($this->_db->quoteName('#__users').' A ON ('.$this->_db->quoteName('A.id').'='.$this->_db->quoteName('user_register_pagamento_remessa').')');
		$query->leftJoin($this->_db->quoteName('#__users').' B ON ('.$this->_db->quoteName('B.id').'='.$this->_db->quoteName('user_update_pagamento_remessa').')');

	
			
		 
			 
		$search = $this->getState('filter.search');
        if ($search!='') {	 

			$token	= $this->_db->quote('%'.$this->_db->escape($search). '%');

			// Compile the different search clauses.
			$searches	= array();
			$searches[]	= 'name_pagamento_remessa LIKE '.$token;
			$searches[]	= 'pagamentos_pagamento_remessa LIKE '.$token;

			$query->where('('.implode(' OR ', $searches).')');
		 
		}		 
			 
			 
						
		$metodos = $this->getState('filter.metodos');
        if ($metodos!='')
			$query->where( $this->_db->quoteName('id_pagamento_metodo') . '=' . $this->_db->escape( $metodos ) );
			
			 
			 
		
		$ordering = $this->getState('list.ordering');
		$direction = $this->getState('list.direction');
		$query->order($this->_db->quoteName($ordering) . ' ' . $this->_db->escape($direction) );
		
        return $query;
    }
	
	function getMetodos()
	{
		$query = $this->_db->getQuery(true);
		$query->select('id_pagamento_metodo as value, name_pagamento_metodo as text');
		$query->from('#__intranet_pagamento_metodo');
		$query->where( $this->_db->quoteName('status_pagamento_metodo') . '=' . $this->_db->escape( '1' ));
		$query->order( $this->_db->quoteName('name_pagamento_metodo') );
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();
	}
	
	function checkin()
	{	
	
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		$cid = JRequest::getVar( 'cid' , array() , '' , 'array' );
		JArrayHelper::toInteger($cid);
		if (count( $cid )) 
		{
			$cids = implode( ',', $cid );
			$query = $this->_db->getQuery(true);
 
			$fields = array( $this->_db->quoteName('checked_out') . ' = NULL',
			 				 $this->_db->quoteName('checked_out_time') . ' = NULL');
			 
			$conditions = array( $this->_db->quoteName('id_pagamento_remessa') . ' IN (' . $cids . ')' );
 
			$query->update($this->_db->quoteName('#__intranet_pagamento_remessa'))->set($fields)->where($conditions);
 
			$this->_db->setQuery($query);
						
			if ( !$this->_db->query() ) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			return true;
			
		}
	}

	function getCnab400NameFile()
	{
		$array = JRequest::getVar( 'cid' , array() , '' , 'array' );
		$cid = $array[0];
		if ($cid) :
			$query = $this->_db->getQuery(true);	
			$query->select($this->_db->quoteName(array(	'name_pagamento_remessa')));
			$query->from($this->_db->quoteName('#__intranet_pagamento_remessa'));
			$query->where( $this->_db->quoteName('id_pagamento_remessa') . '=' . $this->_db->quote( $cid ) );
			$this->_db->setQuery($query);
			return $this->_db->loadResult();	
		endif;
	}
	
	function getCnab400()
	{
		$array = JRequest::getVar( 'cid' , array() , '' , 'array' );
		$cid = $array[0];
		if ($cid) :
			$query = $this->_db->getQuery(true);	
			$query->select($this->_db->quoteName(array(	'id_pagamento_remessa',
														'date_pagamento_remessa',
														'id_pagamento_metodo',
														'pagamentos_pagamento_remessa'
														)));
			$query->from($this->_db->quoteName('#__intranet_pagamento_remessa'));
			$query->where( $this->_db->quoteName('id_pagamento_remessa') . '=' . $this->_db->quote( $cid ) );
			$this->_db->setQuery($query);
			$remessa = $this->_db->loadObject();

			$registry = new JRegistry;
			$registry->loadString($remessa->pagamentos_pagamento_remessa);
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
															 'valor_pago_pagamento',
															 'name',
															 'name_anuidade',
															 'name_pagamento_metodo',
															 'id_pagamento_metodo',
															 'sigla_estado',
															 'name_cidade',
															 'transacao_pagamento',
															 'taxa_pagamento',
															 'vencimento_desconto_pagamento',
															 'valor_desconto_pagamento',
															 '#__intranet_pagamento.checked_out',
															 '#__intranet_pagamento.checked_out_time',
															 )));	
															 
				$query->select( 'IF( ISNULL(#__intranet_pf.cpf_pf), #__intranet_pj.cnpj_pj, #__intranet_pf.cpf_pf) AS doc');
				$query->select( 'IF( ISNULL(#__intranet_pf.cep_pf), #__intranet_pj.cep_pj, #__intranet_pf.cep_pf) AS cep');
				$query->select( 'IF( ISNULL(#__intranet_pf.logradouro_pf), #__intranet_pj.logradouro_pj, #__intranet_pf.logradouro_pf) AS logradouro');
				$query->select( 'IF( ISNULL(#__intranet_pf.numero_pf), #__intranet_pj.numero_pj, #__intranet_pf.numero_pf) AS numero');
				$query->select( 'IF( ISNULL(#__intranet_pf.complemento_pf), #__intranet_pj.complemento_pj, #__intranet_pf.complemento_pf) AS complemento');
				
				$query->select( 'IF( ISNULL(#__intranet_pf.bairro_pf), #__intranet_pj.bairro_pj, #__intranet_pf.bairro_pf) AS bairro');
				$query->select( 'IF( ISNULL(#__intranet_pf.tel_celular_pf), #__intranet_pj.telefone_pj, #__intranet_pf.tel_celular_pf) AS telefone');		
				$query->select( 'name AS sacado');
				$query->select( 'IF( ISNULL(name_anuidade), name_produto, name_anuidade)  AS produto');
				$query->select('IF(ISNULL(id_pj),0,1) AS types_id');
				
			
				$query->from($this->_db->quoteName('#__intranet_pagamento'));
				$query->innerJoin($this->_db->quoteName('#__intranet_pagamento_metodo') . ' USING(' . $this->_db->quoteName('id_pagamento_metodo'). ')');
				$query->leftJoin($this->_db->quoteName('#__intranet_anuidade') . ' USING(' . $this->_db->quoteName('id_anuidade'). ')');
				$query->leftJoin($this->_db->quoteName('#__intranet_produto') . ' USING(' . $this->_db->quoteName('id_produto'). ')');
				
				$query->leftJoin($this->_db->quoteName('#__intranet_pf') . ' USING(' . $this->_db->quoteName('id_user'). ')');
				$query->leftJoin($this->_db->quoteName('#__intranet_pj') . ' USING(' . $this->_db->quoteName('id_user'). ')');;	
				$query->innerJoin($this->_db->quoteName('#__users') . ' ON(' . $this->_db->quoteName('id'). '=' . $this->_db->quoteName('id_user'). ')');
				$query->leftJoin($this->_db->quoteName('#__intranet_estado') . ' ON(' 
																			 . $this->_db->quoteName('#__intranet_estado.id_estado'). '=' . $this->_db->quoteName('#__intranet_pf.id_estado')
																			 . 'OR '
																			 . $this->_db->quoteName('#__intranet_estado.id_estado'). '=' . $this->_db->quoteName('#__intranet_pj.id_estado')
																			 . ')');	
				$query->leftJoin($this->_db->quoteName('#__intranet_cidade') . ' ON(' 
																			 . $this->_db->quoteName('#__intranet_cidade.id_cidade'). '=' . $this->_db->quoteName('#__intranet_pf.id_cidade')
																			 . 'OR '
																			 . $this->_db->quoteName('#__intranet_cidade.id_cidade'). '=' . $this->_db->quoteName('#__intranet_pj.id_cidade')
																			 . ')');
				
				
				$query->where( $this->_db->quoteName('#__intranet_pagamento.id_pagamento') .  ' IN (' . $ids . ')' );
				$this->_db->setQuery($query);
				$pagamentos = $this->_db->loadObjectList();
				

				//break;
				if(count($pagamentos)>0):	
					$options = array();
					$options['pagamentos'] = $pagamentos;			
						
					$options['numero_sequencia'] = $remessa->id_pagamento_remessa;
					$options['data_geracao'] = JFactory::getDate($remessa->date_pagamento_remessa, $this->_siteOffset)->toFormat('%d%m%y', true);	
					$options['filename'] = $remessa->name_pagamento_remessa;  
	
					$query->clear();
					$query->select($this->_db->quoteName(array('module_pagamento_metodo')));
					$query->from($this->_db->quoteName('#__intranet_pagamento_metodo'));
					$query->where( $this->_db->quoteName('id_pagamento_metodo') . '=' . $this->_db->quote( $remessa->id_pagamento_metodo ) );
					$this->_db->setQuery($query);
				
					if( (boolean) $module_pagamento_metodo = $this->_db->loadResult()):
		
						require_once(JPATH_MODULE .DS. 'mod_' . $module_pagamento_metodo. DS. 'mod_' . $module_pagamento_metodo . '.php');
						
						$prefix  = 'Intranet';
						$type = $module_pagamento_metodo. 'Module';
						$type = preg_replace('/[^A-Z0-9_\.-]/i', '', $type);
						$modelClass = $prefix . ucfirst($type);
						
						if (!class_exists($modelClass))
							return false;
						
						$_module_pagament = new $modelClass();
	
						//$this->_app->setUserState( 'id_pagamentos', NULL );
						//$this->_app->setUserState( 'id_pagamento_metodo', NULL );
						//$this->_app->setUserState( 'id_pagamento_remessa', NULL );
						
						$cnba_type = 1;
						
						if($cnba_type==1)
							return $_module_pagament->setCnab400( $options );
						else
							return $_module_pagament->setCnab240( $options );
					endif;
				endif;
			endif;
		endif;
		
	}


}
