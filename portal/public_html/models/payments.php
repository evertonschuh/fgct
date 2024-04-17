<?php
/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */

defined( '_JEXEC' ) or die;

jimport( 'joomla.application.component.modellist' );


class EASistemasModelPayments extends JModelList
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
		
		$groupId = $this->getUserStateFromRequest(
		$this->context.'.filter.group', 'filter_group_id', null, 'int');
		$this->setState('filter.group_id', $groupId);
		
        $status = $this->_app->getUserStateFromRequest(
        $this->context .'filter.status', 'status', null,'string');
        $this->setState('filter.status', $status);

        $search = $this->_app->getUserStateFromRequest(
        $this->context .'filter.search', 'search', null,'string');
        $this->setState('filter.search', $search);  	

		$ordering = $this->_app->getUserStateFromRequest(
        $this->context .'list.ordering','filter_order', 'id_pagamento', 'string');
		$this->setState('list.ordering', $ordering); 

 		$direction = $this->_app->getUserStateFromRequest(
        $this->context .'list.direction','filter_order_Dir', 'DESC','string');
		$this->setState('list.direction', $direction); 

	   parent::populateState($ordering, $direction );
		
    }

    protected function getListQuery() {
		
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
		
		$query->where( $this->_db->quoteName('status_pagamento') . '>=' . $this->_db->quote( '0' ) );
		$query->where( $this->_db->quoteName('id_user') . '=' . $this->_db->quote( $this->_user->get('id') ) );


		$situacao = $this->getState('filter.situacao');
		if ($situacao!=''):
			switch($situacao)
			{
				case '1': // A Vencer Em Dia
					$query->where( $this->_db->quoteName('baixa_pagamento') . ' IS NULL'  );
					$query->where( $this->_db->quoteName('vencimento_pagamento') . '>=' . $this->_db->quote(JFactory::getDate('now',  $this->_siteOffset)->toFormat('%Y-%m-%d') ) ) ;
				break;	
				
				case '2': // Em Aberto
					$query->where( $this->_db->quoteName('baixa_pagamento') . ' IS NULL'  );
				break;	
				
				case '3': // Vencido
					$query->where( $this->_db->quoteName('baixa_pagamento') . ' IS NULL'  );
					$query->where( $this->_db->quoteName('vencimento_pagamento') . '<' . $this->_db->quote(JFactory::getDate('now',  $this->_siteOffset)->toFormat('%Y-%m-%d') ) ) ;
				break;						
				
				case '4': //Pago
					$query->where( $this->_db->quoteName('baixa_pagamento') . ' IS NOT NULL'  );
				break;	
			}
		endif;
		
		
		$query->group($this->_db->quoteName('#__intranet_pagamento.id_pagamento'));

		$search = $this->getState('filter.search');
        if ($search!='') {	 
			// Escape the search token.
			$token	= $this->_db->quote('%'.$this->_db->escape($search).'%');

			// Compile the different search clauses.
			$searches	= array();
			$searches[]	= 'id_pagamento LIKE '.$token;
			$searches[]	= 'name_produto LIKE '.$token;
			$searches[]	= 'name_anuidade LIKE '.$token;
			$searches[]	= 'name_pagamento_metodo LIKE '.$token;
			
			$query->where('('.implode(' OR ', $searches).')');
		 
		}
		
		$ordering = $this->getState('list.ordering');
		$direction = $this->getState('list.direction');
		$query->order($this->_db->quoteName($ordering) . ' ' . $this->_db->escape($direction) );
		

        return $query;

    }
	

}
