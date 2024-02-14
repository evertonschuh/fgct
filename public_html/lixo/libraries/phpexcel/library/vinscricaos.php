<?php
/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */

defined( '_JEXEC' ) or die;

jimport( 'joomla.application.component.modellist' );

class IntranetModelVInscricaos extends JModelList
{
	var $_db = null;
	var $_pagination = null;
	var $_total = null;	
	var $_user = null;


 	public function __construct($config = array()) {   

        parent::__construct($config);
		$this->_user = JFactory::getUser();
		$this->_db	= JFactory::getDBO();
	}

    protected function populateState($ordering = null, $direction = null) {

      	$mainframe 	= JFactory::getApplication();
		
		$limit		= $mainframe->getUserStateFromRequest( 
		$this->context . '.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		$limitstart	= $mainframe->getUserStateFromRequest( 
		$this->context . '.list.start', 'start', 0, 'int' );

		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
	   	$this->setState('list.limit', $limit); 
	  	$this->setState('list.start', $limitstart); 
		
        $published = $mainframe->getUserStateFromRequest(
        $this->context .'filter.published', 'published', null,'string');
        $this->setState('filter.published', $published); 
		
        $campeonato = $mainframe->getUserStateFromRequest(
        $this->context .'filter.campeonato', 'campeonato', null,'string');
        $this->setState('filter.campeonato', $campeonato); 	 
		
        $prova = $mainframe->getUserStateFromRequest(
        $this->context .'filter.prova', 'prova', null,'string');
        $this->setState('filter.prova', $prova); 	 	
		
        $clube = $mainframe->getUserStateFromRequest(
        $this->context .'filter.clube', 'clube', null,'string');
        $this->setState('filter.clube', $clube); 	 
		
        $search = $mainframe->getUserStateFromRequest(
        $this->context .'filter.search', 'search', null,'string');
        $this->setState('filter.search', $search);  	
		
		$ordering = $mainframe->getUserStateFromRequest(
        $this->context .'list.ordering','filter_order', 'id_inscricao_etapa', 'string');
		$this->setState('list.ordering', $ordering); 
		 
 		$direction = $mainframe->getUserStateFromRequest(
        $this->context .'list.direction','filter_order_Dir', 'desc','string');
		$this->setState('list.direction', $direction); 
		
		parent::populateState($ordering, $direction );
		
    }

    protected function getListQuery() {
		
		
		
		$query = $this->_db->getQuery(true);
		
		$query->select( $this->_db->quoteName( array('#__users.name',
													 'id_inscricao_etapa',
													 'status_inscricao_etapa',
													 'name_prova',
													 'name_etapa'
													)));
		$query->select( $this->_db->quoteName('clubes.name') . ' AS name_clube');										
		$query->select( 'CONCAT (' . $this->_db->quoteName('ano_campeonato') . ', \' - \',' . $this->_db->quoteName('name_campeonato') . ') AS name_campeonato');											
		
		$query->from( $this->_db->quoteName('#__users') );
		$query->innerJoin( $this->_db->quoteName('#__ranking_profile') . 'ON('. $this->_db->quoteName('id').'='. $this->_db->quoteName('id_user').')' );	
		$query->innerJoin( $this->_db->quoteName('#__ranking_inscricao') . 'USING('. $this->_db->quoteName('id_user').')' );
		$query->innerJoin( $this->_db->quoteName('#__ranking_inscricao_etapa') . 'USING('. $this->_db->quoteName('id_inscricao').')' );
		$query->innerJoin( $this->_db->quoteName('#__users') . ' AS clubes ON('. $this->_db->quoteName('clubes.id').'='. $this->_db->quoteName('#__ranking_inscricao_etapa.id_local').')' );	
		$query->innerJoin( $this->_db->quoteName('#__ranking_campeonato') . 'USING('. $this->_db->quoteName('id_campeonato').')' );
		$query->innerJoin( $this->_db->quoteName('#__ranking_etapa') . 'USING('. $this->_db->quoteName('id_campeonato').','.$this->_db->quoteName('id_etapa').')' );
		$query->innerJoin( $this->_db->quoteName('#__ranking_prova') . 'USING('. $this->_db->quoteName('id_campeonato').','.$this->_db->quoteName('id_prova').')' );		
			
		
		$query->where('(' . $this->_db->quoteName('#__users.block') . '=' . $this->_db->quote( '0' ) . ' OR ' .  $this->_db->quoteName('ano_campeonato') . ' < YEAR(CURDATE()) )');
		
		
		if	(   ( !$this->_user->authorise('torneios.diretor.modalidade', 'com_torneios') )
				&&
				( !$this->_user->authorise('torneios.diretor.geral', 'com_torneios') )
				&&		
				( !$this->_user->authorise('torneios.sistema.admin', 'com_torneios') ) 
				&&		
				( !$this->_user->authorise('core.admin')  ) 
			):		
				$query->where($this->_db->quoteName('clubes.id') . ' = ' . $this->_db->quote($this->_user->get('id')));
					
		else:	
		
		
			if	( 	
					( $this->_user->authorise('torneios.diretor.modalidade', 'com_torneios') )
					&&		
					( !$this->_user->authorise('core.admin')  ) 
				):	
			
				$query->innerJoin( $this->_db->quoteName('#__ranking_acess_map') . ' ON('. $this->_db->quoteName('id_modalidade'). '='. $this->_db->quoteName('id_type').
					  ' AND '.
					  $this->_db->quoteName('type').'='. $this->_db->quote( '1' ).')' );	
				$query->where($this->_db->quoteName('#__ranking_acess_map.id_user') . ' = ' . $this->_db->quote($this->_user->get('id')));
			endif;
		
				$clube = $this->getState('filter.clube');
				if ($clube!='')
					 $query->where( $this->_db->quoteName('clubes.id') . '=' . $this->_db->escape( $clube ) );
					
		endif;
		
		
		//$query->where( $this->_db->quoteName('block') . '=' . $this->_db->quote( '0' ) );
		$query->where( $this->_db->quoteName('status_inscricao') . '=' . $this->_db->quote( '1' ) );
		$query->where( $this->_db->quoteName('status_campeonato') . '=' . $this->_db->quote( '1' ) );
		$query->where( $this->_db->quoteName('finalized_campeonato') . '=' . $this->_db->quote( '0' ) );	
					

		$search = $this->getState('filter.search');
        if ($search!='')
			 $query->where( $this->_db->quoteName('#__users.name') . ' LIKE \'%' . $this->_db->escape($search) . '%\'' );
		
 		$campeonato = $this->getState('filter.campeonato');
        if ($campeonato!='')
			 $query->where( $this->_db->quoteName('id_campeonato') . '=' . $this->_db->escape( $campeonato ) );
			 
 		$prova = $this->getState('filter.prova');
        if ($prova!='')
			 $query->where( $this->_db->quoteName('id_prova') . '=' . $this->_db->escape( $prova ) );
		
		//$query->group('id_user');
		
		$ordering = $this->getState('list.ordering');
		$direction = $this->getState('list.direction');
		
		$query->order($this->_db->quoteName($ordering) . ' ' . $this->_db->escape($direction) );

        return $query;
    }
	
	function &getUserInfo()
	{
		return 	$this->_user;
	}
	
	
	function &getListCampeonatos()
	{

		$query = $this->_db->getQuery(true);
		$query->select( $this->_db->quoteName('id_campeonato') . ' AS value, ' . 
						'CONCAT (' . $this->_db->quoteName('ano_campeonato') . ', \' - \',' . $this->_db->quoteName('name_campeonato') . ') AS text');
		
		
		$query->from( $this->_db->quoteName('#__users') );
		$query->innerJoin( $this->_db->quoteName('#__ranking_profile') . 'ON('. $this->_db->quoteName('id').'='. $this->_db->quoteName('id_user').')' );	
		$query->innerJoin( $this->_db->quoteName('#__ranking_inscricao') . 'USING('. $this->_db->quoteName('id_user').')' );
		$query->innerJoin( $this->_db->quoteName('#__ranking_inscricao_etapa') . 'USING('. $this->_db->quoteName('id_inscricao').')' );
		$query->innerJoin( $this->_db->quoteName('#__users') . ' AS clubes ON('. $this->_db->quoteName('clubes.id').'='. $this->_db->quoteName('#__ranking_inscricao_etapa.id_local').')' );	
		$query->innerJoin( $this->_db->quoteName('#__ranking_campeonato') . 'USING('. $this->_db->quoteName('id_campeonato').')' );
		$query->innerJoin( $this->_db->quoteName('#__ranking_etapa') . 'USING('. $this->_db->quoteName('id_campeonato').','.$this->_db->quoteName('id_etapa').')' );
		$query->innerJoin( $this->_db->quoteName('#__ranking_prova') . 'USING('. $this->_db->quoteName('id_campeonato').','.$this->_db->quoteName('id_prova').')' );		

		
		if	( 
				( !$this->_user->authorise('torneios.diretor.geral', 'com_torneios') )
				&&		
				( !$this->_user->authorise('torneios.sistema.admin', 'com_torneios') ) 
				&&		
				( !$this->_user->authorise('core.admin')  ) 
			):	
			
			if	( 
					( $this->_user->authorise('torneios.diretor.prova', 'com_torneios') )
					&&		
					( $this->_user->authorise('torneios.sistema.etapa', 'com_torneios') ) 
					&&		
					( $this->_user->authorise('torneios.arbitro', 'com_torneios')  ) 
				):	
			
				$query->innerJoin( $this->_db->quoteName('#__ranking_acess_map') . ' ON('. $this->_db->quoteName('id_modalidade'). '='. $this->_db->quoteName('id_type').
					  ' AND '.
					  $this->_db->quoteName('type').'='. $this->_db->quote( '1' ).')' );	
				$query->where($this->_db->quoteName('id_user') . ' = ' . $this->_db->quote($this->_user->get('id')));
				
			elseif( (  $this->_user->authorise('torneios.clube', 'com_torneios')  ) ):	
			
				$query->where($this->_db->quoteName('clubes.id') . ' = ' . $this->_db->quote($this->_user->get('id')));
			endif;
		else:	
				$clube = $this->getState('filter.clube');
				if ($clube!='')
					 $query->where( $this->_db->quoteName('clubes.id') . '=' . $this->_db->escape( $clube ) );
		endif;
		
		$query->where('(' . $this->_db->quoteName('#__users.block') . '=' . $this->_db->quote( '0' ) . ' OR ' .  $this->_db->quoteName('ano_campeonato') . ' < YEAR(CURDATE()) )');
		
		//$query->where( $this->_db->quoteName('block') . '=' . $this->_db->quote( '0' ) );
		$query->where( $this->_db->quoteName('status_inscricao') . '=' . $this->_db->quote( '1' ) );
		$query->where( $this->_db->quoteName('status_campeonato') . '=' . $this->_db->quote( '1' ) );
		$query->where( $this->_db->quoteName('finalized_campeonato') . '=' . $this->_db->quote( '0' ) );	
		
		
		
		
		

		$query->group( $this->_db->quoteName('id_campeonato') );	
		$this->_db->setQuery($query);
		return 	$this->_db->loadObjectList();

	}
	
	
	function &getListProvas()
	{
		$query = $this->_db->getQuery(true);
		$query->select( 'DISTINCT( ' . $this->_db->quoteName('id_prova') . ') AS value, ' . 
						$this->_db->quoteName('name_prova') . ' AS text');
		
		$query->from( $this->_db->quoteName('#__users') );
		$query->innerJoin( $this->_db->quoteName('#__ranking_profile') . 'ON('. $this->_db->quoteName('id').'='. $this->_db->quoteName('id_user').')' );	
		$query->innerJoin( $this->_db->quoteName('#__ranking_inscricao') . 'USING('. $this->_db->quoteName('id_user').')' );
		$query->innerJoin( $this->_db->quoteName('#__ranking_inscricao_etapa') . 'USING('. $this->_db->quoteName('id_inscricao').')' );
		$query->innerJoin( $this->_db->quoteName('#__users') . ' AS clubes ON('. $this->_db->quoteName('clubes.id').'='. $this->_db->quoteName('#__ranking_inscricao_etapa.id_local').')' );	
		$query->innerJoin( $this->_db->quoteName('#__ranking_campeonato') . 'USING('. $this->_db->quoteName('id_campeonato').')' );
		$query->innerJoin( $this->_db->quoteName('#__ranking_etapa') . 'USING('. $this->_db->quoteName('id_campeonato').','.$this->_db->quoteName('id_etapa').')' );
		$query->innerJoin( $this->_db->quoteName('#__ranking_prova') . 'USING('. $this->_db->quoteName('id_campeonato').','.$this->_db->quoteName('id_prova').')' );		
		
		if	( 
				( !$this->_user->authorise('torneios.diretor.geral', 'com_torneios') )
				&&		
				( !$this->_user->authorise('torneios.sistema.admin', 'com_torneios') ) 
				&&		
				( !$this->_user->authorise('core.admin')  ) 
			):	
			
			if	( 
					( $this->_user->authorise('torneios.diretor.prova', 'com_torneios') )
					&&		
					( $this->_user->authorise('torneios.sistema.etapa', 'com_torneios') ) 
					&&		
					( $this->_user->authorise('torneios.arbitro', 'com_torneios')  ) 
				):	
			
				$query->innerJoin( $this->_db->quoteName('#__ranking_acess_map') . ' ON('. $this->_db->quoteName('id_modalidade'). '='. $this->_db->quoteName('id_type').
					  ' AND '.
					  $this->_db->quoteName('type').'='. $this->_db->quote( '1' ).')' );	
				$query->where($this->_db->quoteName('id_user') . ' = ' . $this->_db->quote($this->_user->get('id')));
				
			elseif( (  $this->_user->authorise('torneios.clube', 'com_torneios')  ) ):	
			
				$query->where($this->_db->quoteName('clubes.id') . ' = ' . $this->_db->quote($this->_user->get('id')));
			endif;
		else:	
				$clube = $this->getState('filter.clube');
				if ($clube!='')
					 $query->where( $this->_db->quoteName('clubes.id') . '=' . $this->_db->escape( $clube ) );
		endif;

		$query->where('(' . $this->_db->quoteName('#__users.block') . '=' . $this->_db->quote( '0' ) . ' OR ' .  $this->_db->quoteName('ano_campeonato') . ' < YEAR(CURDATE()) )');
		
		
		//$query->where( $this->_db->quoteName('block') . '=' . $this->_db->quote( '0' ) );
		$query->where( $this->_db->quoteName('status_inscricao') . '=' . $this->_db->quote( '1' ) );
		$query->where( $this->_db->quoteName('status_campeonato') . '=' . $this->_db->quote( '1' ) );
		$query->where( $this->_db->quoteName('finalized_campeonato') . '=' . $this->_db->quote( '0' ) );	

 		$campeonato = $this->getState('filter.campeonato');
        if ($campeonato!='')
			 $query->where( $this->_db->quoteName('id_campeonato') . '=' . $this->_db->escape( $campeonato ) );
		
		$query->group( $this->_db->quoteName('id_prova') );
		
		$query->order( $this->_db->quoteName('name_prova') );	

		$this->_db->setQuery($query);
		return 	$this->_db->loadObjectList();				
	}


	function &getListClubes()
	{
		$query = $this->_db->getQuery(true);
		$query->select( 'DISTINCT( ' . $this->_db->quoteName('clubes.id') . ') AS value, ' . 
						$this->_db->quoteName('clubes.name') . ' AS text');
		
		$query->from( $this->_db->quoteName('#__users') );
		$query->innerJoin( $this->_db->quoteName('#__ranking_profile') . 'ON('. $this->_db->quoteName('id').'='. $this->_db->quoteName('id_user').')' );	
		$query->innerJoin( $this->_db->quoteName('#__ranking_inscricao') . 'USING('. $this->_db->quoteName('id_user').')' );
		$query->innerJoin( $this->_db->quoteName('#__ranking_inscricao_etapa') . 'USING('. $this->_db->quoteName('id_inscricao').')' );
		$query->innerJoin( $this->_db->quoteName('#__users') . ' AS clubes ON('. $this->_db->quoteName('clubes.id').'='. $this->_db->quoteName('#__ranking_inscricao_etapa.id_local').')' );	
		$query->innerJoin( $this->_db->quoteName('#__ranking_campeonato') . 'USING('. $this->_db->quoteName('id_campeonato').')' );
		$query->innerJoin( $this->_db->quoteName('#__ranking_etapa') . 'USING('. $this->_db->quoteName('id_campeonato').','.$this->_db->quoteName('id_etapa').')' );
		$query->innerJoin( $this->_db->quoteName('#__ranking_prova') . 'USING('. $this->_db->quoteName('id_campeonato').','.$this->_db->quoteName('id_prova').')' );	
		
		if	( 
				( !$this->_user->authorise('torneios.diretor.geral', 'com_torneios') )
				&&		
				( !$this->_user->authorise('torneios.sistema.admin', 'com_torneios') ) 
				&&		
				( !$this->_user->authorise('core.admin')  ) 
			):	
			
			if	( 
					( $this->_user->authorise('torneios.diretor.prova', 'com_torneios') )
					&&		
					( $this->_user->authorise('torneios.sistema.etapa', 'com_torneios') ) 
					&&		
					( $this->_user->authorise('torneios.arbitro', 'com_torneios')  ) 
				):	
			
				$query->innerJoin( $this->_db->quoteName('#__ranking_acess_map') . ' ON('. $this->_db->quoteName('id_modalidade'). '='. $this->_db->quoteName('id_type').
					  ' AND '.
					  $this->_db->quoteName('type').'='. $this->_db->quote( '1' ).')' );	
				$query->where($this->_db->quoteName('id_user') . ' = ' . $this->_db->quote($this->_user->get('id')));
				
			elseif( (  $this->_user->authorise('torneios.clube', 'com_torneios')  ) ):	
			
				$query->where($this->_db->quoteName('clubes.id') . ' = ' . $this->_db->quote($this->_user->get('id')));
			endif;
		endif;
		
		
		
		$query->where('(' . $this->_db->quoteName('#__users.block') . '=' . $this->_db->quote( '0' ) . ' OR ' .  $this->_db->quoteName('ano_campeonato') . ' < YEAR(CURDATE()) )');
		
		
		//$query->where( $this->_db->quoteName('block') . '=' . $this->_db->quote( '0' ) );
		$query->where( $this->_db->quoteName('status_inscricao') . '=' . $this->_db->quote( '1' ) );
		$query->where( $this->_db->quoteName('status_campeonato') . '=' . $this->_db->quote( '1' ) );
		$query->where( $this->_db->quoteName('finalized_campeonato') . '=' . $this->_db->quote( '0' ) );	
		
 		$prova = $this->getState('filter.prova');
        if ($prova!='')
			 $query->where( $this->_db->quoteName('id_prova') . '=' . $this->_db->escape( $prova ) );

 		$campeonato = $this->getState('filter.campeonato');
        if ($campeonato!='')
			 $query->where( $this->_db->quoteName('id_campeonato') . '=' . $this->_db->escape( $campeonato ) );
		
		$query->order( $this->_db->quoteName('clubes.name') );	

		$query->group( $this->_db->quoteName('clubes.id') );
		
		$this->_db->setQuery($query);
		return 	$this->_db->loadObjectList();				
	}



	
	function remove()
	{	
		JRequest::checkToken() or jexit( 'INTRANET_TOKEN_INVALID' );
		$cid = JRequest::getVar( 'cid' , array() , '' , 'array' );
		JArrayHelper::toInteger($cid);
		if (count( $cid )) 
		{
			$cids = implode( ',', $cid );
			
			$query = $this->_db->getQuery(true);
			$query->select( 'DISTINCT( ' . $this->_db->quoteName('id_categoria') . ')' );
			$query->from( $this->_db->quoteName('#__ranking_classe') );
			$query->where($this->_db->quoteName('id_classe') . ' IN (' . $cids . ')' );
			$this->_db->setQuery($query);
			$ids_modalidades = $this->_db->loadResultArray();	
			
			
				
			$query = $this->_db->getQuery(true);
			$query->delete($this->_db->quoteName('#__ranking_classe'));
			$query->where($this->_db->quoteName('id_classe') . ' IN (' . $cids . ')' );
			$this->_db->setQuery($query);
			$this->_db->execute();				
			if ( !$this->_db->query() ) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			
			$this->addTablePath(JPATH_BASE.'/tables');
			$row = $this->getTable('classe');
			foreach ($ids_modalidades as $id_genero) {
				$row->reorder('id_categoria = ' . $id_categoria);
			}
			
			
			return true;
		}
	}
	
	
	function export()
	{
		
		$query = $this->_db->getQuery(true);
		$query->select( $this->_db->quoteName( array('id_inscricao_etapa',
													 'name_prova',
													 'name_etapa',
													 'name_campeonato',
													 'params_inscricao_etapa',
													 'date_inscricao_agenda',
													 'turma_inscricao_agenda',
													 'posto_inscricao_agenda',
													)));
		
		$query->select( $this->_db->quoteName('Atleta.name') . ' AS name_atleta');	
		$query->select( $this->_db->quoteName('Equipe.name') . ' AS name_equipe');	
		$query->select( 'IF(name_classe="Única", name_categoria, name_classe) AS name_classe');

		/*									
		$query->select( 'CONCAT (' . $this->_db->quoteName('ano_campeonato') . ', \' - \',' . $this->_db->quoteName('name_campeonato') . ') AS name_campeonato');											
		*/
		
		$query->from( $this->_db->quoteName('#__ranking_inscricao') );
		$query->innerJoin( $this->_db->quoteName('#__ranking_genero') . 'USING('. $this->_db->quoteName('id_genero').','. $this->_db->quoteName('id_prova').')' );
		$query->innerJoin( $this->_db->quoteName('#__ranking_categoria') . 'USING('. $this->_db->quoteName('id_genero').','. $this->_db->quoteName('id_categoria').')' );
		$query->innerJoin( $this->_db->quoteName('#__ranking_classe') . 'USING('. $this->_db->quoteName('id_categoria') .','. $this->_db->quoteName('id_classe').')' );
		
		
		$query->innerJoin( $this->_db->quoteName('#__ranking_inscricao_etapa') . 'USING('. $this->_db->quoteName('id_inscricao').')' );
		
		$query->innerJoin( $this->_db->quoteName('#__ranking_inscricao_agenda') . 'USING('	. $this->_db->quoteName('id_inscricao_etapa') . ',' 
																							. $this->_db->quoteName('id_prova') . ','
																							. $this->_db->quoteName('id_etapa') . ','
																							. $this->_db->quoteName('id_local') . ')');
		$query->innerJoin( $this->_db->quoteName('#__ranking_campeonato') . 'USING('. $this->_db->quoteName('id_campeonato').')' );
		$query->innerJoin( $this->_db->quoteName('#__ranking_etapa') . 'USING('. $this->_db->quoteName('id_campeonato').','.$this->_db->quoteName('id_etapa').')' );
		$query->innerJoin( $this->_db->quoteName('#__ranking_prova') . 'USING('. $this->_db->quoteName('id_campeonato').','.$this->_db->quoteName('id_prova').')' );			


		$query->innerJoin( $this->_db->quoteName('#__users') . ' AS Atleta ON('. $this->_db->quoteName('Atleta.id').'='. $this->_db->quoteName('id_user').')' );	
		$query->innerJoin( $this->_db->quoteName('#__users') . ' AS Clube ON('. $this->_db->quoteName('Clube.id').'='. $this->_db->quoteName('#__ranking_inscricao_etapa.id_local').')' );
		$query->innerJoin( $this->_db->quoteName('#__users') . ' AS Equipe ON('. $this->_db->quoteName('Equipe.id').'='. $this->_db->quoteName('#__ranking_inscricao.id_equipe').')' );
		
		
		//$query->where('(' . $this->_db->quoteName('#__users.block') . '=' . $this->_db->quote( '0' ) . ' OR ' .  $this->_db->quoteName('ano_campeonato') . ' < YEAR(CURDATE()) )');
		
		//$query->where($this->_db->quoteName('id_campeonato') . '=' . $this->_db->quote( '0' ));
		//$query->where($this->_db->quoteName('id_prova') . '=' . $this->_db->quote( '0' ));
		//$query->where($this->_db->quoteName('id_etapa') . '=' . $this->_db->quote( '0' ));
		
	//	$query->where($this->_db->quoteName('date_inscricao_agenda') . '=' . $this->_db->quote( '2018-05-05' ));
		
		
		
		
		$query->order($this->_db->quoteName('turma_inscricao_agenda') . ' ASC');
		$query->order($this->_db->quoteName('posto_inscricao_agenda') . ' ASC');
			
				
		$this->_db->setQuery($query);
		$agendamentos = $this->_db->loadObjectList();		



		if(count($agendamentos) >0 ):
		
			require_once(JPATH_LIBRARIES .DS. 'phpexcel' . DS. 'library' . DS. 'PHPExcel' . DS. 'IOFactory.php');
			require_once(JPATH_LIBRARIES .DS. 'phpexcel' . DS. 'library' . DS. 'PHPExcel.php');
				
			$inputFileName 		= JPATH_MEDIA .DS. 'files' .DS. 'sumula_trap.xlsx';
	//	$inputFileNameNew 	= JPATH_MEDIA .DS. 'files' .DS. 'sumula_trap.xlsb';

			$inputFileType 		= PHPExcel_IOFactory::identify($inputFileName);
		
		
		//$inputFileTypeNew 	= PHPExcel_IOFactory::identify($inputFileNameNew);
	
			$objReader = PHPExcel_IOFactory::createReader('excel2007');
			$objPHPExcel = $objReader->load($inputFileName);
		
		//$objReaderNew = PHPExcel_IOFactory::createReader($inputFileTypeNew);
	//	$objPHPExcelNew = $objReaderNew->load($inputFileNameNew);
		
/*
		foreach($agendamentos as $i => $agendamento):

			$turma = $agendamento->turma_inscricao_agenda;
			$posto = $agendamento->posto_inscricao_agenda;
			$linha = ( 3 + ($posto-1) + ( ($turma - 1) *8) ) ;
			$objPHPExcel->setActiveSheetIndex(0);
			$objPHPExcel->getActiveSheet()->setCellValue('C' . $linha, $agendamento->name_atleta)
				->setCellValue('D' . $linha, $agendamento->name_equipe)
				->setCellValue('E' . $linha, $agendamento->name_classe)       
				->setCellValue('F' . $linha,' LIGA');
			
		
			endforeach;
		
		*/
/*
	
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="sumula.xlsx"');;
			header('Cache-Control: max-age=0');
			header('Cache-Control: max-age=1');
			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
			header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
			header ('Cache-Control: cache, must-revalidate');
			header ('Pragma: public');
			*/
			
			
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'excel2007');
			$objWriter->save('php://output');
			
			echo 'Aqui';
			break;			
			
			
			
			break;
			//exit;
		
		endif;
		
		return true;
		
	}
	
	function criaclasses()
	{
	
		$query = $this->_db->getQuery(true);
		$query->select( $this->_db->quoteName('id_categoria') );
		$query->from( $this->_db->quoteName('#__ranking_categoria') );
		$this->_db->setQuery($query);
		$ids_categorias = $this->_db->loadObjectList();	
		
		foreach($ids_categorias as $id_categoria) 
		{
			$i++;
			echo $i . ' - ' . $id_categoria->id_categoria;
			$query = $this->_db->getQuery(true);
			$query->select( $this->_db->quoteName('id_classe') );
			$query->from( $this->_db->quoteName('#__ranking_classe') );
			$query->where( $this->_db->quoteName('id_categoria') . ' = ' . $id_categoria->id_categoria  );
			$this->_db->setQuery($query);
			if ( !(boolean) $result = $this->_db->loadObject() )
			{
				$columns = array('status_classe',
								 'id_categoria',
								 'name_classe',
								 'ponto_min_classe',
								 'ponto_max_classe',
								 'ordering'					 
								 );
				
				$query = $this->_db->getQuery(true);
				$query->insert( $this->_db->quoteName('#__ranking_classe') );
				$query->columns($this->_db->quoteName($columns));	

				$values = array($this->_db->quote('1'),
								$this->_db->quote( $id_categoria->id_categoria ), 
								$this->_db->quote('Classe A'), 
								$this->_db->quote('0'), 
								$this->_db->quote('0'), 
								$this->_db->quote('1')
								);
				$query->values(implode(',', $values));

				$this->_db->setQuery($query);
				$this->_db->query();			 
				 
			}		
		}		
	}
	
	function publish()
	{
		JRequest::checkToken() or jexit( 'INTRANET_TOKEN_INVALID' );
		$cid = JRequest::getVar( 'cid' , array() , '' , 'array' );
		JArrayHelper::toInteger($cid);
		if (count( $cid )) 
		{
			$cids = implode( ',', $cid );
			
			$query = $this->_db->getQuery(true);
			$fields = array(
				$this->_db->quoteName('status_classe') . ' = ' . $this->_db->quote('1'),
			);
			$conditions = array(
				$this->_db->quoteName('id_classe') . ' IN (' . $cids . ')'		 
			);
			
			$query->update($this->_db->quoteName('#__ranking_classe'))->set($fields)->where($conditions);

			$this->_db->setQuery( $query );
			if ( !$this->_db->query() ) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			return true;
		}
	}
	
	function unpublish()
	{	
		JRequest::checkToken() or jexit( 'INTRANET_TOKEN_INVALID' );
		$cid = JRequest::getVar( 'cid' , array() , '' , 'array' );
		JArrayHelper::toInteger($cid);
		if (count( $cid )) 
		{
			$cids = implode( ',', $cid );
			
			$query = $this->_db->getQuery(true);
			$fields = array(
				$this->_db->quoteName('status_classe') . ' = ' . $this->_db->quote('0'),
			);
			$conditions = array(
				$this->_db->quoteName('id_classe') . ' IN (' . $cids . ')'		 
			);
			
			$query->update($this->_db->quoteName('#__ranking_classe'))->set($fields)->where($conditions);

			$this->_db->setQuery( $query );
			if ( !$this->_db->query() ) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			return true;
		}
	}

	function checkin()
	{	
	
		JRequest::checkToken() or jexit( 'INTRANET_TOKEN_INVALID' );
		$cid = JRequest::getVar( 'cid' , array() , '' , 'array' );
		JArrayHelper::toInteger($cid);
		if (count( $cid )) 
		{
			$cids = implode( ',', $cid );
			$query = $this->_db->getQuery(true);
 
			$fields = array( $this->_db->quoteName('checked_out') . ' = NULL',
			 				 $this->_db->quoteName('checked_out_time') . ' = NULL');
			 
			$conditions = array( $this->_db->quoteName('id_classe') . ' IN (' . $cids . ')' );
 
			$query->update($this->_db->quoteName('#__ranking_classe'))->set($fields)->where($conditions);
 
			$this->_db->setQuery($query);
						
			if ( !$this->_db->query() ) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			return true;
			
		}
	}	
	
	function move( $direction )
	{
		JRequest::checkToken() or jexit( 'INTRANET_TOKEN_INVALID' );
		
		$this->addTablePath(JPATH_BASE.'/tables');
	    $row = $this->getTable('classe');
		$cid = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$cid = $cid[0];
		if ( !$row->load( $cid ) ) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		if ( !$row->move( $direction, ' id_categoria=' . $row->id_categoria ) ) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		return true;
	}

	function saveOrder($cid)
	{
		JRequest::checkToken() or jexit( 'INTRANET_TOKEN_INVALID' );
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);
		$total        = count( $cid );
		$ordering        = JRequest::getVar( 'ordering', array(0), 'post', 'array' );
		$id_categoria   = JRequest::getVar( 'id_categoria', array(0), 'post', 'array' );
		
		$this->addTablePath(JPATH_BASE.'/tables');
		$row = $this->getTable('classe');
		array_multisort($id_categoria, $ordering, $cid);
		$n=0;
		$userOrder ='';
		for( $i=0; $i < $total; $i++ )
		{
			if ( $userOrder !=  $id_categoria[$i] )
			{
				if ( $n!=0 )
				{
					$row->reorder( 'id_categoria=' .  $id_categoria[$i]);
				}
				$userOrder =  $id_categoria[$i];
				$n=1;
			}
			$row->load( (int) $cid[$i] );
			$row->ordering = $n;
			if (!$row->store())
			{
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			if($i == $total-1)
			{
				$row->reorder( 'id_categoria=' . $id_categoria[$i]);
			}
			$n++;
		}	
		return true;
	}

}



