<?php
/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */

defined( '_JEXEC' ) or die;

jimport( 'joomla.application.component.modellist' );


class EASistemasModelMyEnrollments extends JModelList
{
	var $_db = null;
	var $_user = null;
	var $_app = null;	
	var $_siteOffset = null;
	var $_pagination = null;
	var $_total = null;	
	var $_data = null;
	
	function __construct()
	{	
		parent::__construct();
		
		$this->_db	= JFactory::getDBO();
		$this->_user = JFactory::getUser();
		$this->_app = JFactory::getApplication();
		$this->_siteOffset = $this->_app->getCfg('offset');
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
        $this->context .'list.ordering','filter_order', 'id_arma', 'string');
		$this->setState('list.ordering', $ordering); 

 		$direction = $this->_app->getUserStateFromRequest(
        $this->context .'list.direction','filter_order_Dir', 'DESC','string');
		$this->setState('list.direction', $direction); 

	   parent::populateState($ordering, $direction );
		
    }

    protected function getListQuery() {


		//$myInfo = $this->getMyinfo();

		$query = $this->_db->getQuery(true);
		
		$query->select( $this->_db->quoteName(array( 'id_inscricao_etapa',
													 'name_campeonato',
													 'ano_campeonato',
													 'name_genero',
													 'name_categoria',
													 'name_classe',
													 'name_etapa',
													 'name_modalidade',
													 'data_beg_etapa',
													 'insc_beg_etapa',
													 'insc_end_etapa',
													 'date_register_inscricao_etapa',
													 'name_prova',
													 'state_etapa',
													 'name_especie',	
													 'name_calibre',	
													 'name_marca',	
													)));
		//$query->select( 'CONCAT (' . $this->_db->quoteName('ano_campeonato') . ', \' - \',' . $this->_db->quoteName('name_campeonato') . ') AS name_campeonato' );
		//$query->select( 'CONCAT (' . $this->_db->quoteName('name_etapa') . ', \' - \',' . $this->_db->quoteName('name_campeonato') . ', \' - \',' . $this->_db->quoteName('ano_campeonato') . ') AS name_campeonato' );
	

		$query->from( $this->_db->quoteName('#__ranking_inscricao_etapa') );
		$query->innerJoin( $this->_db->quoteName('#__ranking_inscricao') . 'USING('. $this->_db->quoteName('id_inscricao').')' );
		$query->innerJoin( $this->_db->quoteName('#__ranking_etapa') . 'USING('. $this->_db->quoteName('id_etapa').','. $this->_db->quoteName('id_campeonato').')'  );
		$query->innerJoin( $this->_db->quoteName('#__ranking_prova') . 'USING('. $this->_db->quoteName('id_prova').','. $this->_db->quoteName('id_campeonato').')'  );

		$query->innerJoin( $this->_db->quoteName('#__ranking_campeonato') . 'USING('. $this->_db->quoteName('id_campeonato').')' );
		$query->innerJoin( $this->_db->quoteName('#__ranking_modalidade') . 'USING('. $this->_db->quoteName('id_modalidade').')' );
		$query->innerJoin( $this->_db->quoteName('#__ranking_genero') . 'USING('. $this->_db->quoteName('id_genero').','. $this->_db->quoteName('id_prova').')' );
		$query->innerJoin( $this->_db->quoteName('#__ranking_categoria') . 'USING('. $this->_db->quoteName('id_genero').','. $this->_db->quoteName('id_categoria').')' );
		$query->innerJoin( $this->_db->quoteName('#__ranking_classe') . 'USING('. $this->_db->quoteName('id_categoria') .','. $this->_db->quoteName('id_classe').')' );
		$query->innerJoin( $this->_db->quoteName('#__intranet_arma') . 'USING('. $this->_db->quoteName('id_user').','. $this->_db->quoteName('id_arma').')'  );
		$query->innerJoin( $this->_db->quoteName('#__intranet_especie').' USING ('.$this->_db->quoteName('id_especie').')');
		$query->innerJoin( $this->_db->quoteName('#__intranet_calibre').' USING ('.$this->_db->quoteName('id_calibre').')');
		$query->innerJoin( $this->_db->quoteName('#__intranet_marca').' USING ('.$this->_db->quoteName('id_marca').')');


		$query->where( $this->_db->quoteName('status_modalidade') . ' = ' . $this->_db->quote('1') );	
		$query->where( $this->_db->quoteName('status_campeonato') . ' = ' . $this->_db->quote('1') );	
		$query->where( $this->_db->quoteName('status_etapa') . ' = ' . $this->_db->quote('1') );
			
		//$query->where( $this->_db->quoteName('#__ranking_etapa.insc_beg_etapa') . ' <= ' . $this->_db->quote( JFactory::getDate('now', $this->_siteOffset)->toISO8601(TRUE) ) );
		//$query->where( $this->_db->quoteName('#__ranking_etapa.insc_end_etapa') . ' >= ' . $this->_db->quote( JFactory::getDate('now', $this->_siteOffset)->toISO8601(TRUE) ) );
		$query->where( $this->_db->quoteName('id_user') . '=' . $this->_db->escape( $this->_user->get('id') ) );

		$search = $this->getState('filter.search');
        if ($search!='') {	 
			// Escape the search token.
			$token	= $this->_db->quote('%'.$this->_db->escape($search).'%');

			// Compile the different search clauses.
			$searches	= array();
			$searches[]	= 'name_campeonato LIKE '.$token;
			$searches[]	= 'name_modalidade LIKE '.$token;
			$searches[]	= 'name_etapa LIKE '.$token;
			
			$query->where('('.implode(' OR ', $searches).')');
		 
		}


/*

		if($myInfo->validate_associado <= JFactory::getDate('now', $this->_siteOffset)->format('Y-m-d',true) )	:
			$query->where( $this->_db->quoteName('convenio_prova') . ' = ' . $this->_db->quote('1') );
		elseif($myInfo->compressed_air_pf == 1):
			$query->where( $this->_db->quoteName('socioar_prova') . ' = ' . $this->_db->quote('1') );
		endif;
*/
		$query->group('id_campeonato');
        $query->group('id_etapa');
		return $query;

    }


	
	function getInscricaoPrint()
	{
		
		$query = $this->_db->getQuery(true);
		$query->select( $this->_db->quoteName(array( 'id_prova',
													 'name_prova',
													 'name_genero',
													 'name_categoria',
													 'name_classe',
													 'params_inscricao_etapa',
													 'quantidade_inscricao_etapa',
													 'id_inscricao_etapa',
													 'date_register_inscricao_etapa',
													 'inscricao_bateria_prova',
													 'inscricao_turma_prova',
													)));
		$query->select('Atleta.name AS name_atleta');
		$query->select('IF(ISNULL(id_addequipe), IF(ISNULL(id_equipe), Estado.name_estado, IF( Equipe.id = 7617, \'AVULSO\', Equipe.name)), name_addequipe) AS name_equipe');	
													
		$query->from( $this->_db->quoteName('#__ranking_inscricao') );
		$query->innerJoin( $this->_db->quoteName('#__ranking_genero') . 'USING('. $this->_db->quoteName('id_genero').','. $this->_db->quoteName('id_prova').')' );
		$query->innerJoin( $this->_db->quoteName('#__ranking_categoria') . 'USING('. $this->_db->quoteName('id_genero').','. $this->_db->quoteName('id_categoria').')' );
		$query->innerJoin( $this->_db->quoteName('#__ranking_classe') . 'USING('. $this->_db->quoteName('id_categoria') .','. $this->_db->quoteName('id_classe').')' );
		$query->innerJoin( $this->_db->quoteName('#__ranking_prova') . 'USING('. $this->_db->quoteName('id_prova') .','. $this->_db->quoteName('id_campeonato').')' );
		
		$query->leftJoin($this->_db->quoteName('#__intranet_estado') . ' AS Estado USING( ' . $this->_db->quoteName('id_estado') . ')' );		
		$query->leftJoin( $this->_db->quoteName('#__ranking_inscricao_etapa')  . 'USING('. $this->_db->quoteName('id_inscricao') . ')' );
																					   
		$query->leftJoin( $this->_db->quoteName('#__users') . ' AS Atleta ON('. $this->_db->quoteName('#__ranking_inscricao.id_user') .'='. $this->_db->quoteName('Atleta.id'). ')' );
		$query->leftJoin( $this->_db->quoteName('#__users') . ' AS Equipe ON('. $this->_db->quoteName('#__ranking_inscricao.id_equipe') .'='. $this->_db->quoteName('Equipe.id'). ')' );
		$query->leftJoin( $this->_db->quoteName('#__intranet_addequipe') . ' AS AddEquipe USING('. $this->_db->quoteName('id_addequipe') . ')' );
		$query->where($this->_db->quoteName('id_inscricao_etapa') . ' = ' . $this->_db->quote( $this->_id[3] ));
		
		$this->_db->setQuery($query);
		return $this->_db->loadObject();
		
	}
	
	function getAgendamentosPrint()
	{
		$query = $this->_db->getQuery(true);
		$query->select( $this->_db->quoteName(array( 'date_inscricao_agenda',
													 'bateria_inscricao_agenda',
													 'turma_inscricao_agenda',
													 'posto_inscricao_agenda',
													)));
	
		$query->from( $this->_db->quoteName('#__ranking_inscricao_agenda') );

		$query->where($this->_db->quoteName('id_etapa') . ' = ' . $this->_db->quote( $this->_id[1] ));
		$query->where($this->_db->quoteName('id_inscricao_etapa') . ' = ' . $this->_db->quote( $this->_id[3] ));
	
	
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();
		
	}
	





	
	function getMyInfo()
	{

		$query = $this->_db->getQuery(true);
		$query->select('*' );	
		$query->from( $this->_db->quoteName('#__users') );
		$query->innerJoin( $this->_db->quoteName('#__intranet_pf') . 'ON('. $this->_db->quoteName('id').'='. $this->_db->quoteName('id_user').')' );
		$query->leftJoin( $this->_db->quoteName('#__intranet_associado') . 'USING('. $this->_db->quoteName('id_user').')' );
		$query->leftJoin( $this->_db->quoteName('#__intranet_conveniado') . 'USING('. $this->_db->quoteName('id_user').')' );
		$query->where( $this->_db->quoteName('id') .  '=' . $this->_db->quote( $this->_user->get('id') ) );		
		$this->_db->setQuery($query);
		return $this->_db->loadObject();

	}
	

}
