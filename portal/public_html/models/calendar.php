<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.image.resize');

jimport( 'joomla.application.component.model' );

class EASistemasModelCalendar extends JModel {


	
	var $_data = null;
	var $_db = null;
	var $_app = null;	
	var $_user = null;
	var $_siteOffset = null;
	var $_resize = null;
	
	function __construct()
	{
		parent::__construct();
		
		$this->_db		= JFactory::getDBO();
		$this->_app 	= JFactory::getApplication(); 
		$this->_user	= JFactory::getUser();
		$this->_siteOffset = $this->_app->getCfg('offset');
		$this->_resize = new JResize();


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
														//'id_campeonato',
														'name_campeonato',
														'ano_campeonato',
														'id_modalidade',
														'name_modalidade',
														'#__ranking_etapa.ordering', 
														'#__ranking_etapa.checked_out', 													 
														'#__ranking_etapa.checked_out_time'
														)));				 
			
			$query->select( 'CONCAT(smallname_modalidade, \'  -  \', name_etapa) AS name_calendar' );		
			$query->select( 'GROUP_CONCAT(name_campeonato) AS name_campeonatos' );	
			
			$query->select('GROUP_CONCAT(DISTINCT logo_pj) AS logo_clube');
			$query->select('GROUP_CONCAT(DISTINCT name) AS name_clube');

			$query->select('GROUP_CONCAT(DISTINCT name_prova SEPARATOR \'<br/>\') AS name_provas');

			$query->select('DATE_FORMAT(data_beg_etapa, \'%d/%m/%Y\') AS data_beg');
			$query->select('DATE_FORMAT(data_end_etapa, \'%d/%m/%Y\') AS data_end');
			$query->select('DATE_FORMAT(insc_beg_etapa, \'%d/%m/%Y %H:%i\') AS insc_beg');
			$query->select('DATE_FORMAT(insc_end_etapa, \'%d/%m/%Y %H:%i\') AS insc_end');

			$query->from( $this->_db->quoteName('#__ranking_etapa') );
			$query->innerJoin( $this->_db->quoteName('#__ranking_campeonato') . 'USING('. $this->_db->quoteName('id_campeonato').')' );
			$query->innerJoin( $this->_db->quoteName('#__ranking_prova') . 'USING('. $this->_db->quoteName('id_campeonato').')' );
			$query->innerJoin( $this->_db->quoteName('#__ranking_modalidade') . 'USING('. $this->_db->quoteName('id_modalidade').')' );
			$query->innerJoin( $this->_db->quoteName('#__ranking_etapa_clube_map') . 'USING('. $this->_db->quoteName('id_etapa').')' );	


			$query->innerJoin( $this->_db->quoteName('#__intranet_pj') . 'ON('. $this->_db->quoteName('id_clube').'='. $this->_db->quoteName('id_user').')' );
			$query->innerJoin( $this->_db->quoteName('#__users') . 'ON('. $this->_db->quoteName('id').'='. $this->_db->quoteName('id_user').')' );




			//$query->innerJoin( $this->_db->quoteName('#__ranking_prova_clube_map') . 'USING('. $this->_db->quoteName('id_prova').','. $this->_db->quoteName('id_clube').')' );

			/*
			$query->from( $this->_db->quoteName('#__users') );
			$query->innerJoin( $this->_db->quoteName('#__intranet_pj') . 'ON('. $this->_db->quoteName('id').'='. $this->_db->quoteName('id_user').')' );
			$query->innerJoin( $this->_db->quoteName('#__intranet_cidade') . 'USING('. $this->_db->quoteName('id_cidade').','. $this->_db->quoteName('id_estado').')' );
			$query->innerJoin( $this->_db->quoteName('#__intranet_estado') . 'USING('. $this->_db->quoteName('id_estado').')' );			
			
			
			$query->innerJoin( $this->_db->quoteName('#__ranking_etapa') . 'USING('. $this->_db->quoteName('id_etapa').')' );	
			$query->innerJoin( $this->_db->quoteName('#__ranking_campeonato') . 'USING('. $this->_db->quoteName('id_campeonato').')' );
			$query->innerJoin( $this->_db->quoteName('#__ranking_prova') . 'USING('. $this->_db->quoteName('id_prova').','. $this->_db->quoteName('id_campeonato').')' );
			$query->innerJoin( $this->_db->quoteName('#__ranking_modalidade') . 'USING('. $this->_db->quoteName('id_modalidade').')' );



*/






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
			$query->group($this->_db->quoteName('id_modalidade'));
			$query->group($this->_db->quoteName('data_beg_etapa'));
			$query->order($this->_db->quoteName('data_beg_etapa') . ' ASC' );

			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObjectList();

			foreach($this->_data as &$item)
			if(!empty($item->logo_clube))	{

				$item->logo_clube = explode(',', $item->logo_clube);
			
				if(count($item->logo_clube) < 2 ) {
					$item->logo_clube = $this->_resize->resize(JPATH_CDN.DS. 'images' . DS . 'logos' .DS. $item->logo_clube[0], 266, 266, 'cache/tmp_' . $item->logo_clube[0]);
				}
				else {
					$item->logo_clube = 'vish';
					$item->name_clube = 'Multiplos Clubes';
				}

			}										




		}
		//print_r( $this->_data);
		//exit;
		return $this->_data;
	}
	

	function getModalidades()
	{
		$query = $this->_db->getQuery(true);
					 
		$query->select( $this->_db->quoteName(array('id_modalidade',
													'smallname_modalidade',
													'color_modalidade'
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