<?php
/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */

defined( '_JEXEC' ) or die;

jimport( 'joomla.application.component.modellist' );


class EASistemasModelEnrollmentOpens extends JModelList
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






		$myInfo = $this->getMyinfo();

		$query = $this->_db->getQuery(true);
		
		$query->select( $this->_db->quoteName(array( 'id_campeonato',
													 'name_campeonato',
													 'ano_campeonato',
													 'id_etapa',
													 'name_etapa',
													 'name_modalidade',
													 'data_beg_etapa',
													 'insc_beg_etapa',
													 'insc_end_etapa',
													 'id_prova',
													 'name_prova',
													 'state_etapa'
													
													)));
		//$query->select( 'CONCAT (' . $this->_db->quoteName('ano_campeonato') . ', \' - \',' . $this->_db->quoteName('name_campeonato') . ') AS name_campeonato' );
		//$query->select( 'CONCAT (' . $this->_db->quoteName('name_etapa') . ', \' - \',' . $this->_db->quoteName('name_campeonato') . ', \' - \',' . $this->_db->quoteName('ano_campeonato') . ') AS name_campeonato' );
	
		$query->from( $this->_db->quoteName('#__ranking_etapa') );
		$query->innerJoin( $this->_db->quoteName('#__ranking_campeonato') . 'USING('. $this->_db->quoteName('id_campeonato').')' );
		$query->innerJoin( $this->_db->quoteName('#__ranking_prova') . 'USING('. $this->_db->quoteName('id_campeonato').')' );
		//$query->innerJoin( $this->_db->quoteName('#__ranking_prova') . 'USING('. $this->_db->quoteName('id_prova').','. $this->_db->quoteName('id_campeonato').')' );
		$query->innerJoin( $this->_db->quoteName('#__ranking_modalidade') . 'USING('. $this->_db->quoteName('id_modalidade').')' );



		$query->where( $this->_db->quoteName('status_modalidade') . ' = ' . $this->_db->quote('1') );	
		$query->where( $this->_db->quoteName('status_campeonato') . ' = ' . $this->_db->quote('1') );	
		$query->where( $this->_db->quoteName('status_etapa') . ' = ' . $this->_db->quote('1') );
			
		$query->where( $this->_db->quoteName('#__ranking_etapa.insc_beg_etapa') . ' <= ' . $this->_db->quote( JFactory::getDate('now', $this->_siteOffset)->toISO8601(TRUE) ) );
		$query->where( $this->_db->quoteName('#__ranking_etapa.insc_end_etapa') . ' >= ' . $this->_db->quote( JFactory::getDate('now', $this->_siteOffset)->toISO8601(TRUE) ) );


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
		$query->group('id_prova');
        $query->group('id_etapa');
		return $query;

    }

	public function getItems()
	{



/*

		$query = $this->_db->getQuery(true);
		$query->select( $this->_db->quoteName(array( '#__users.name',
													 'id_campeonato',
													 'id_etapa',
													 'id_clube',
													 'logo_pj',
													 'name_cidade',
													 'sigla_estado'									 
													)));

		$query->from( $this->_db->quoteName('#__users') );
		$query->innerJoin( $this->_db->quoteName('#__intranet_pj') . 'ON('. $this->_db->quoteName('id').'='. $this->_db->quoteName('id_user').')' );
		$query->innerJoin( $this->_db->quoteName('#__intranet_cidade') . 'USING('. $this->_db->quoteName('id_cidade').','. $this->_db->quoteName('id_estado').')' );
		$query->innerJoin( $this->_db->quoteName('#__intranet_estado') . 'USING('. $this->_db->quoteName('id_estado').')' );		
		$query->innerJoin( $this->_db->quoteName('#__ranking_prova_clube_map') . 'ON('. $this->_db->quoteName('id').'='. $this->_db->quoteName('id_clube').')' );	
		$query->innerJoin( $this->_db->quoteName('#__ranking_etapa_clube_map') . 'USING('. $this->_db->quoteName('id_clube').')' );	
		
		$query->innerJoin( $this->_db->quoteName('#__ranking_etapa') . 'USING('. $this->_db->quoteName('id_etapa').')' );	
		$query->innerJoin( $this->_db->quoteName('#__ranking_campeonato') . 'USING('. $this->_db->quoteName('id_campeonato').')' );
		$query->innerJoin( $this->_db->quoteName('#__ranking_prova') . 'USING('. $this->_db->quoteName('id_prova').','. $this->_db->quoteName('id_campeonato').')' );
		$query->innerJoin( $this->_db->quoteName('#__ranking_modalidade') . 'USING('. $this->_db->quoteName('id_modalidade').')' );
		



		/*
		if($this->_config->type_category_config ==1):
			$query->leftJoin( $this->_db->quoteName('#__categories') . 'ON('. $this->_db->quoteName('#__categories.id'). ' = '. $this->_db->quoteName('#__ranking_profile.category').')' );
		elseif($this->_config->type_category_config ==2):
			$query->select( $this->_db->quoteName('#__k2_categories.image'));
			$query->leftJoin( $this->_db->quoteName('#__k2_categories') . 'ON('. $this->_db->quoteName('#__ranking_profile.category').' = '. $this->_db->quoteName('#__k2_categories.id').')' );
		endif;
		*//*
		$query->where($this->_db->quoteName('id_campeonato') . ' = ' . $this->_db->quote( $this->_id[0] ));		
		$query->where($this->_db->quoteName('id_etapa') . ' = ' . $this->_db->quote( $this->_id[1] ));	

*/






		// Get a storage key.
		$store = $this->getStoreId();

		// Try to load the data from internal storage.
		if (empty($this->cache[$store]))
		{
			
			$items = parent::getItems();

			if (empty($items))
			{
				$this->cache[$store] = $items;
				return $items;
			}

			$id_etapa = array();
			$id_prova = array();
			foreach ($items as $item)
			{
				$id_etapa[] = (int) $item->id_etapa;
				$id_prova[] = (int) $item->id_prova;
				
			}
	
			//print_r(  $id_users  );

			$query = $this->_db->getQuery(true);

			$query->select('GROUP_CONCAT(id_clube) AS clubes, id_prova')
				->from('#__ranking_prova_clube_map')
				->innerJoin('#__ranking_etapa_clube_map  USING(id_clube)' )
				->where('id_etapa IN ('.implode(',', $id_etapa ).')')
				->where('id_prova IN ('.implode(',', $id_prova ).')')
				->group('id_prova');
				// Join over the user groups table.
				//->join('LEFT', '#__usergroups AS g2 ON g2.id = map.group_id');

			$this->_db->setQuery($query);

			$clubeGroups = $this->_db->loadObjectList('id_prova');
			print_r($clubeGroups);
			$error = $this->_db->getErrorMsg();

			if ($error)
			{
				$this->setError($error);
				return false;
			}

			foreach ($items as &$item)
			{
				if (isset($clubeGroups[$item->id_prova]))
				{
					//$item->group_count = $comissoesGroups[$item->id_prova]->id_clube;
					$item->clubes = $this->_getClube($clubeGroups[$item->id_prova]->id_clube);
					
				}
			}
			$this->cache[$store] = $items;
		}

		return $this->cache[$store];
	}


	function _getClube($id_clube = null)
	{
		$query = $this->_db->getQuery(true);	
		$query->select($this->_db->quoteName(array('logo_pj','name')));
		$query->from($this->_db->quoteName('#__users'));
		$query->leftJoin($this->_db->quoteName('#__intranet_pj') . ' ON(' . $this->_db->quoteName('id_user'). '=' .$this->_db->quoteName('id'). ')');
		$query->where($this->_db->quoteName('id') . ' = ' .  $this->_db->quote( $id_clube ));
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
