<?php
defined('_JEXEC') or die('Restricted access');


jimport( 'joomla.application.component.model' );

class EASistemasModelCalendar extends JModel {


	
	var $_data = null;
	var $_db = null;
	var $_app = null;	
	var $_user = null;
	var $_siteOffset = null;
	
	function __construct()
	{
		parent::__construct();
		
		$this->_db		= JFactory::getDBO();
		$this->_app 	= JFactory::getApplication(); 
		$this->_user	= JFactory::getUser();
		$this->_siteOffset = $this->_app->getCfg('offset');

	}

	function getItems()
	{
		if (empty($this->_data)){			
			$query = $this->_db->getQuery(true);		 
			$query->select( $this->_db->quoteName(array('id_etapa',
														'name_etapa',
														'data_beg_etapa',
														'data_end_etapa',
														'insc_beg_etapa',
														'insc_end_etapa',
														'id_campeonato',
														'name_campeonato',
														'ano_campeonato',
														'id_modalidade',
														'name_modalidade',
														'#__ranking_etapa.ordering', 
														'#__ranking_etapa.checked_out', 													 
														'#__ranking_etapa.checked_out_time'
														)));				 
			
			$query->select( 'CONCAT(smallname_modalidade, \'  -  \', name_etapa) AS name_calendar' );											
			$query->from( $this->_db->quoteName('#__ranking_etapa') );
			$query->innerJoin( $this->_db->quoteName('#__ranking_campeonato') . 'USING('. $this->_db->quoteName('id_campeonato').')' );
			$query->innerJoin( $this->_db->quoteName('#__ranking_modalidade') . 'USING('. $this->_db->quoteName('id_modalidade').')' );

			$query->where( $this->_db->quoteName('finalized_campeonato') . '=' . $this->_db->escape( '0' ) );
			
			$query->where( $this->_db->quoteName('status_etapa') . '=' . $this->_db->escape( '1' ) );
	/*
			$modalidade = $this->getState('filter.modalidade');

			if ($modalidade!='')
				$query->where( $this->_db->quoteName('id_modalidade') . '=' . $this->_db->escape( $modalidade ) );

			$campeonato = $this->getState('filter.campeonato');
			if ($campeonato!='')
				$query->where( $this->_db->quoteName('id_campeonato') . '=' . $this->_db->escape( $campeonato ) );

			$search = $this->getState('filter.search');
			if ($search!='')
				$query->where( $this->_db->quoteName('name_etapa') . ' LIKE \'%' . $this->_db->escape($search) . '%\'' );
			
			$ordering = $this->getState('list.ordering');
			$direction = $this->getState('list.direction');
			*/
			$query->group($this->_db->quoteName('id_modalidade') . ' ASC' );
			$query->order($this->_db->quoteName('data_beg_etapa') . ' ASC' );

			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObjectList();
		}
		
		return $this->_data;
	}
	

	function getModalidades()
	{
		$query = $this->_db->getQuery(true);
					 
		$query->select( $this->_db->quoteName(array('id_modalidade',
													'name_modalidade',
													)));				 
		

		$query->from( $this->_db->quoteName('#__ranking_etapa') );
		$query->innerJoin( $this->_db->quoteName('#__ranking_campeonato') . 'USING('. $this->_db->quoteName('id_campeonato').')' );
		$query->innerJoin( $this->_db->quoteName('#__ranking_modalidade') . 'USING('. $this->_db->quoteName('id_modalidade').')' );

		$query->where( $this->_db->quoteName('finalized_campeonato') . '=' . $this->_db->escape( '0' ) );
		
		$query->where( $this->_db->quoteName('status_etapa') . '=' . $this->_db->escape( '1' ) );
		$query->group( $this->_db->quoteName('id_modalidade'));
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();
	
	}
	
}