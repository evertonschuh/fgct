<?php
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.modellist' );

class EASistemasModelDModel extends JModelList {

	var $_id = null;
	var $_data = null;
	var $_db = null;
	var $_app = null;	
	var $_isRoot = null;
	var $_user = null;
	var $_layout = null;
	
	function __construct()
	{
		parent::__construct();
		
		$this->_app 	= JFactory::getApplication(); 
		$this->_db		= JFactory::getDBO();
		$this->_user	= JFactory::getUser();
		$this->_siteOffset = $this->_app->getCfg('offset');
		$this->_isRoot		= $this->_user->get('isRoot');	
		$this->_userAdmin	= $this->_user->get('id');
		$this->_layout = JFactory::getApplication()->input->get('layout');
		
		//echo JPATH_BASE;
		if( JRequest::getVar( 'task' )  != 'add')
		{
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
				$link = 'index.php?view=documentos';
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
			$query->select('A.name AS name_register_documento');											 						 
			$query->select('B.name AS name_update_documento');	
			$query->from('#__intranet_documento');
			$query->leftJoin($this->_db->quoteName('#__users').' A ON ('.$this->_db->quoteName('A.id').'='.$this->_db->quoteName('user_register_documento').')');
			$query->leftJoin($this->_db->quoteName('#__users').' B ON ('.$this->_db->quoteName('B.id').'='.$this->_db->quoteName('user_update_documento').')');	
			//$query->leftJoin($this->_db->quoteName('#__users').'  ON ('.$this->_db->quoteName('id').'='.$this->_db->quoteName('id_user').')');
			$query->where( $this->_db->quoteName('id_documento') . '=' . $this->_db->escape( $this->_id ) );
			$this->_db->setQuery($query);
			if(!(boolean) $this->_data = $this->_db->loadObject()) {
				$this->addTablePath(JPATH_BASE.'/tables');
	    		$this->_data = $this->getTable('documento');
				//$this->_data->name='';
				//$this->_data->email='';
			}
		}
		return $this->_data;
	}
	
    protected function populateState($ordering = null, $direction = null) {

		$layout = '';
		$list_limit = $this->_app->getCfg('list_limit');
		$list_limit_var = 'limit';
		
		if($this->_layout == 'modalm')
			$layout = 'm';
			

		$limit		= $this->_app->getUserStateFromRequest( 
		$this->context . '.list.limit', $list_limit_var, $list_limit, 'int' );
		
		$limitstart	= $this->_app->getUserStateFromRequest( 
		$this->context . '.list.start', 'start', 0, 'int' );

		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
	   	$this->setState('list.limit', $limit); 
	  	$this->setState('list.start', $limitstart); 

        $search = $this->_app->getUserStateFromRequest(
        $this->context .'filter.search'.$layout, 'search', null,'string');
        $this->setState('filter.search'.$layout, $search);  
		
		$ordering = $this->_app->getUserStateFromRequest(
		$this->context .'list.ordering','filter_order', 'name', 'string');
		$this->setState('list.ordering', $ordering); 
			
		$direction = $this->_app->getUserStateFromRequest(
		$this->context .'list.direction','filter_order_Dir', 'ASC','string');
		$this->setState('list.direction', $direction);

	   parent::populateState($ordering, $direction );
		
    }

    protected function getListQuery() {
		
		$query = $this->_db->getQuery(true);	

		switch($this->_layout)
		{
			case 'modalm':
				$queryDocs = $this->_db->getQuery(true);	
				$queryDocs->select($this->_db->quoteName(array('id_documento', 'id_user')));
				$queryDocs->from($this->_db->quoteName('#__intranet_documento_map'));
				$queryDocs->where( $this->_db->quoteName('id_documento') . '=' . $this->_db->quote( $this->_id ) );

	
				$query->select($this->_db->quoteName(  array('id_associado',													 
															'U.name',															 
															'U.email',													 
															'status_associado',
															'validate_associado',
															'cadastro_associado',
															'Docs.id_documento',													 
															// 'update_pf',	
															'id_user',
															// 'name_cidade',
															// 'sigla_estado',							 
															'#__intranet_associado.checked_out',
															'#__intranet_associado.checked_out_time',
															)));	
											

				//$query->select('A.name AS name_register_pj');											 						 
				//$query->select('B.name AS name_update_pj');	
				//$query->select('IF(ISNULL(Atleta.observacao_pf), Clube.observacao_pj, Atleta.observacao_pf) AS obs');
				//$query->select('IF(ISNULL(Atleta.cpf_pf), Clube.cnpj_pj, Atleta.cpf_pf) AS doc');	
				//$query->select('IF(ISNULL(Atleta.sexo_pf), NULL, Atleta.sexo_pf) AS sexo_pf');	
				//$query->select('IF(ISNULL(Atleta.tel_celular_pf), Clube.celular_pj, Atleta.tel_celular_pf) AS celular');	
				//$query->select('IF(ISNULL(ClubeCidade.name_cidade), AtletaCidade.name_cidade, ClubeCidade.name_cidade) AS name_cidade');	
				//$query->select('IF(ISNULL(ClubeEstado.sigla_estado), AtletaEstado.sigla_estado, ClubeEstado.sigla_estado) AS sigla_estado');
					
				$query->from($this->_db->quoteName('#__intranet_associado'));

				$query->innerJoin($this->_db->quoteName('#__users').' U ON ('.$this->_db->quoteName('U.id').'='.$this->_db->quoteName('id_user').')');
				$query->leftJoin($this->_db->quoteName('#__intranet_pf') . ' AS Atleta USING ('.$this->_db->quoteName('id_user').')');
				//$query->leftJoin($this->_db->quoteName('#__intranet_pj') . ' AS Clube USING ('.$this->_db->quoteName('id_user').')');
				
				$query->leftJoin($this->_db->quoteName('#__intranet_estado').' AS AtletaEstado ON ('.$this->_db->quoteName('AtletaEstado.id_estado').'='.$this->_db->quoteName('Atleta.id_estado').')');
				$query->leftJoin($this->_db->quoteName('#__intranet_cidade').' AS AtletaCidade ON ('.$this->_db->quoteName('AtletaCidade.id_cidade').'='.$this->_db->quoteName('Atleta.id_cidade').')');
				
				$query->leftJoin('('. $queryDocs.') Docs  USING ('.$this->_db->quoteName('id_user').')');
			
				
				$search = $this->getState('filter.searchm');
				if ($search!='') {	 
					// Escape the search token.
					$token	= $this->_db->quote('%'.$this->_db->escape($search).'%');
		
					// Compile the different search clauses.
					$searches	= array();
					$searches[]	= 'id_associado LIKE '.$token;
					$searches[]	= 'U.name LIKE '.$token;
					$searches[]	= 'U.email LIKE '.$token;
					//$searches[]	= 'Atleta.cpf_pf LIKE '.$token;
					//$searches[]	= 'Clube.cnpj_pj LIKE '.$token;
					$searches[]	= 'AtletaCidade.name_cidade LIKE '.$token;
					//$searches[]	= 'ClubeCidade.name_cidade LIKE '.$token;
		
					$query->where('('.implode(' OR ', $searches).')');
				 
				}
					 
					 			
				$query->group($this->_db->quoteName('id_user'));	
		
				$ordering = $this->getState('list.ordering');
				$direction = $this->getState('list.direction');
				$query->order($this->_db->quoteName($ordering) . ' ' . $this->_db->escape($direction) );		
				
			break;
			
			default:
			
			
				$query->select($this->_db->quoteName(  array('name',
															 'id_user',												 
															 'status_associado',
															 'validate_associado',
															 )));	
				

				$query->from($this->_db->quoteName('#__intranet_associado'));
				$query->innerJoin($this->_db->quoteName('#__intranet_documento_map').'  USING ('.$this->_db->quoteName('id_user').')');
				$query->innerJoin($this->_db->quoteName('#__users') . ' ON ('. $this->_db->quoteName('id_user') .'='. $this->_db->quoteName('id') . ')') ;
					
				$query->where( $this->_db->quoteName('id_documento') . '=' . $this->_db->quote( $this->_id  ) );


				$search = $this->getState('filter.search');
				if ($search!='')
				{
					$token	= $this->_db->quote('%'.$this->_db->escape( $search ).'%');
					$searches	= array();
					$searches[]	= $this->_db->quoteName('name'). ' LIKE '.$token;
					$searches[]	= $this->_db->quoteName('email'). ' LIKE '.$token;
					$query->where('('.implode(' OR ', $searches).')');
				} 
				
				$ordering = $this->getState('list.ordering');
				$direction = $this->getState('list.direction');

				$query->order($this->_db->quoteName($ordering) . ' ' . $this->_db->escape($direction) );
				
			break;

		}
        return $query;
    }

	function MontaCPFArray() 
	{

		$cpf[] = array();
		//$cpf[] = '807.281.460-53';
		
		$cpf[] = '000.047.110-09';
		$cpf[] = '593.727.700-78';
		$cpf[] = '565.680.450-04';
		$cpf[] = '509.629.570-20';
		$cpf[] = '954.185.470-20';
		$cpf[] = '915.361.200-06';
		$cpf[] = '922.185.710-72';
		$cpf[] = '024.863.230-23';
		$cpf[] = '640.301.130-87';
		$cpf[] = '021.392.870-10';
		$cpf[] = '900.662.510-87';
		$cpf[] = '807.913.520-72';
		$cpf[] = '572.493.340-34';
		$cpf[] = '017.541.270-70';
		$cpf[] = '014.735.750-01';
		$cpf[] = '732.598.530-49';
		$cpf[] = '718.035.050-87';
		$cpf[] = '001.176.560-70';
		$cpf[] = '004.693.180-58';
		$cpf[] = '648.529.700-68';
		$cpf[] = '601.712.190-15';
		$cpf[] = '999.018.620-00';
		$cpf[] = '026.874.467-00';
		$cpf[] = '494.519.790-34';
		$cpf[] = '917.815.900-87';
		$cpf[] = '019.770.950-89';
		$cpf[] = '691.469.340-49';
		$cpf[] = '641.812.940-72';
		$cpf[] = '761.424.000-63';
		$cpf[] = '200.604.750-72';
		$cpf[] = '003.987.910-07';
		$cpf[] = '021.824.860-11';
		$cpf[] = '017.557.059-05';
		$cpf[] = '550.635.600-59';
		$cpf[] = '594.586.580-04';
		$cpf[] = '377.410.980-04';
		$cpf[] = '893.277.640-72';
		$cpf[] = '431.944.000-53';
		$cpf[] = '760.324.420-04';
		$cpf[] = '006.136.260-36';
		$cpf[] = '007.733.939-86';
		$cpf[] = '835.779.390-87';
		$cpf[] = '274.095.540-53';
		$cpf[] = '284.812.320-68';
		$cpf[] = '210.009.560-91';
		$cpf[] = '020.137.270-38';
		$cpf[] = '012.436.250-82';
		$cpf[] = '316.858.890-34';
		$cpf[] = '031.096.944-17';
		$cpf[] = '578.009.440-34';
		$cpf[] = '727.405.660-34';
		$cpf[] = '314.070.300-78';
		$cpf[] = '313.014.799-34';
		$cpf[] = '438.079.410-53';
		$cpf[] = '012.508.310-67';
		$cpf[] = '303.557.610-68';
		$cpf[] = '806.607.970-20';
		$cpf[] = '370.980.390-04';
		$cpf[] = '015.148.520-80';
		$cpf[] = '013.475.530-88';
		$cpf[] = '836.683.570-72';
		$cpf[] = '094.736.370-04';
		$cpf[] = '432.187.090-91';
		$cpf[] = '232.859.660-68';
		$cpf[] = '387.015.290-72';
		$cpf[] = '706.871.880-91';
		$cpf[] = '807.838.480-72';
		$cpf[] = '000.621.139-92';
		$cpf[] = '901.303.750-04';
		$cpf[] = '895.692.290-04';
		$cpf[] = '694.950.250-87';
		$cpf[] = '830.103.350-91';
		$cpf[] = '242.527.270-49';
		$cpf[] = '638.967.530-53';
		$cpf[] = '385.233.370-91';
		$cpf[] = '540.120.230-04';
		$cpf[] = '630.714.600-15';
		$cpf[] = '679.853.270-53';
		$cpf[] = '465.732.590-68';
		$cpf[] = '007.691.110-17';
		$cpf[] = '026.808.770-98';
		$cpf[] = '930.316.100-91';
		$cpf[] = '028.293.110-43';
		$cpf[] = '821.366.960-68';
		$cpf[] = '008.054.500-92';
		$cpf[] = '919.709.380-72';
		$cpf[] = '699.885.520-68';
		$cpf[] = '810.350.200-68';
		$cpf[] = '913.050.390-68';
		$cpf[] = '010.648.810-41';
		$cpf[] = '668.098.600-10';
		$cpf[] = '899.477.970-15';
		$cpf[] = '199.754.650-72';
		$cpf[] = '021.885.710-10';
		$cpf[] = '032.367.010-56';
		$cpf[] = '996.284.650-15';
		$cpf[] = '002.747.451-86';
		$cpf[] = '277.744.300-91';
		$cpf[] = '021.667.300-35';
		$cpf[] = '666.974.330-00';
		$cpf[] = '914.160.780-53';
		$cpf[] = '368.404.240-49';
		$cpf[] = '959.508.260-00';
		$cpf[] = '696.431.050-91';
		$cpf[] = '321.645.300-63';
		$cpf[] = '943.983.800-00';
		$cpf[] = '816.942.960-91';
		$cpf[] = '693.473.440-87';
		$cpf[] = '538.987.960-00';
		$cpf[] = '937.235.710-34';
		$cpf[] = '612.484.440-00';
		$cpf[] = '823.344.630-00';
		$cpf[] = '010.610.350-42';
		$cpf[] = '032.982.110-52';
		$cpf[] = '456.225.070-49';
		$cpf[] = '024.411.970-88';
		$cpf[] = '680.355.600-00';
		$cpf[] = '010.829.340-89';
		$cpf[] = '759.768.620-04';
		$cpf[] = '512.562.820-49';
		$cpf[] = '592.249.400-72';
		$cpf[] = '965.537.660-53';
		$cpf[] = '473.545.500-00';
		$cpf[] = '457.106.190-00';
		$cpf[] = '591.562.790-00';
		$cpf[] = '778.020.120-15';
		$cpf[] = '961.406.660-15';
		$cpf[] = '820.843.940-15';
		$cpf[] = '987.612.260-68';
		$cpf[] = '674.624.050-91';
		$cpf[] = '818.218.080-53';
		$cpf[] = '021.588.000-54';
		$cpf[] = '917.278.710-49';
		$cpf[] = '623.688.260-68';
		$cpf[] = '836.609.090-68';
		$cpf[] = '947.184.820-20';
		$cpf[] = '690.020.020-68';
		$cpf[] = '941.047.940-15';
		$cpf[] = '826.755.700-87';
		$cpf[] = '804.950.600-20';
		$cpf[] = '470.022.980-20';
		$cpf[] = '929.358.850-15';
		$cpf[] = '007.386.430-76';
		$cpf[] = '001.139.920-17';
		$cpf[] = '023.951.820-95';
		$cpf[] = '884.817.780-87';
		$cpf[] = '928.996.170-87';
		$cpf[] = '617.002.660-04';
		$cpf[] = '165.099.430-34';
		$cpf[] = '934.704.280-34';
		$cpf[] = '440.781.960-04';
		$cpf[] = '015.750.800-52';
		$cpf[] = '031.417.360-90';
		$cpf[] = '991.649.060-00';
		$cpf[] = '551.080.590-00';
		$cpf[] = '363.988.880-49';
		$cpf[] = '691.317.400-49';
		$cpf[] = '201.452.250-20';
		$cpf[] = '827.829.209-44';
		$cpf[] = '438.094.560-04';
		$cpf[] = '893.701.200-68';
		$cpf[] = '990.974.860-68';
		$cpf[] = '827.260.860-04';
		$cpf[] = '818.536.450-87';
		$cpf[] = '006.164.560-50';
		$cpf[] = '026.313.330-39';
		$cpf[] = '588.611.140-91';
		$cpf[] = '010.902.040-51';
		$cpf[] = '012.001.380-07';
		$cpf[] = '883.249.401-97';
		$cpf[] = '659.736.230-68';
		$cpf[] = '881.349.030-53';
		$cpf[] = '024.794.630-36';
		$cpf[] = '982.807.080-49';
		$cpf[] = '197.170.670-15';
		$cpf[] = '427.923.550-34';
		$cpf[] = '634.784.040-53';
		$cpf[] = '994.303.200-68';
		$cpf[] = '006.951.940-46';
		$cpf[] = '442.888.400-15';
		$cpf[] = '809.727.560-00';
		$cpf[] = '620.724.470-20';
		$cpf[] = '005.743.610-06';
		$cpf[] = '757.391.000-20';
		$cpf[] = '935.709.880-15';
		$cpf[] = '562.532.650-91';
		$cpf[] = '498.677.410-72';
		$cpf[] = '827.603.140-49';
		$cpf[] = '005.839.930-52';
		$cpf[] = '831.760.040-87';
		$cpf[] = '009.496.280-42';
		$cpf[] = '834.214.590-53';
		$cpf[] = '015.965.980-93';
		$cpf[] = '378.318.600-53';
		$cpf[] = '013.290.480-24';
		$cpf[] = '021.261.290-55';
		$cpf[] = '670.622.530-49';
		$cpf[] = '270.828.650-15';
		$cpf[] = '951.465.040-91';
		$cpf[] = '005.402.320-33';
		$cpf[] = '596.268.360-91';
		$cpf[] = '803.024.750-87';
		$cpf[] = '022.113.600-25';
		$cpf[] = '770.726.189-53';
		$cpf[] = '382.825.260-53';
		$cpf[] = '975.184.330-87';
		$cpf[] = '991.987.700-04';
		$cpf[] = '690.967.400-68';
		$cpf[] = '343.699.101-53';
		$cpf[] = '415.383.740-72';
		$cpf[] = '238.570.640-72';
		$cpf[] = '361.177.140-68';
		$cpf[] = '243.738.180-53';
		$cpf[] = '892.779.290-49';
		$cpf[] = '308.961.930-04';
		$cpf[] = '566.228.729-53';
		$cpf[] = '420.095.290-49';
		$cpf[] = '198.284.970-34';
		$cpf[] = '216.873.407-06';
		$cpf[] = '187.591.570-20';
		$cpf[] = '562.123.309-30';
		$cpf[] = '516.869.290-72';
		$cpf[] = '617.112.920-87';
		$cpf[] = '763.213.127-20';
		$cpf[] = '953.986.860-20';
		$cpf[] = '478.456.510-87';
		$cpf[] = '030.648.430-79';
		$cpf[] = '429.315.500-78';
		$cpf[] = '954.946.340-00';
		$cpf[] = '020.723.370-54';
		$cpf[] = '004.774.010-82';
		$cpf[] = '604.789.260-49';
		$cpf[] = '809.887.410-91';
		$cpf[] = '938.440.850-68';
		$cpf[] = '648.693.690-87';
		$cpf[] = '005.399.680-13';
		$cpf[] = '370.845.690-49';
		$cpf[] = '310.964.170-49';
		$cpf[] = '820.164.020-91';
		$cpf[] = '739.241.990-00';
		$cpf[] = '262.057.308-47';
		$cpf[] = '006.739.900-28';
		$cpf[] = '017.207.180-16';
		$cpf[] = '600.161.510-11';
		$cpf[] = '815.163.470-72';
		$cpf[] = '758.641.340-15';
		$cpf[] = '026.111.020-90';
		$cpf[] = '778.800.300-00';
		$cpf[] = '967.024.350-53';
		$cpf[] = '002.324.730-48';
		$cpf[] = '494.801.890-20';
		$cpf[] = '019.083.150-24';
		$cpf[] = '436.007.430-15';
		$cpf[] = '025.085.250-06';
		$cpf[] = '025.215.830-06';
		$cpf[] = '780.388.200-53';
		$cpf[] = '013.333.280-28';
		$cpf[] = '031.005.820-12';
		$cpf[] = '596.181.160-34';
		$cpf[] = '531.555.800-20';
		$cpf[] = '499.352.830-20';
		$cpf[] = '046.045.426-94';
		$cpf[] = '028.019.780-27';
		$cpf[] = '450.324.080-34';
		$cpf[] = '504.085.770-53';
		$cpf[] = '661.828.080-04';
		$cpf[] = '021.936.060-05';
		$cpf[] = '679.033.710-53';
		$cpf[] = '026.472.620-04';
		$cpf[] = '603.075.100-00';
		$cpf[] = '005.623.020-69';
		$cpf[] = '975.521.270-15';
		$cpf[] = '963.139.860-91';
		$cpf[] = '215.303.868-53';
		$cpf[] = '008.191.590-00';
		$cpf[] = '015.473.680-55';
		$cpf[] = '781.689.700-63';
		$cpf[] = '897.128.700-49';
		$cpf[] = '700.183.080-15';
		$cpf[] = '816.242.630-20';
		$cpf[] = '697.254.620-68';
		$cpf[] = '489.920.469-87';
		$cpf[] = '012.739.800-77';
		$cpf[] = '753.455.600-72';
		$cpf[] = '943.711.210-04';
		$cpf[] = '432.024.970-49';
		$cpf[] = '375.198.400-34';
		$cpf[] = '466.274.640-04';
		$cpf[] = '642.227.480-72';
		$cpf[] = '831.053.010-20';
		$cpf[] = '004.730.210-05';
		$cpf[] = '010.810.130-44';
		$cpf[] = '459.483.800-68';
		$cpf[] = '618.578.580-34';
		$cpf[] = '005.309.830-79';
		$cpf[] = '243.430.990-91';
		$cpf[] = '044.650.159-09';
		$cpf[] = '712.278.900-44';
		$cpf[] = '009.188.040-86';
		$cpf[] = '013.692.990-79';
		$cpf[] = '595.091.710-34';
		$cpf[] = '668.378.040-49';
		$cpf[] = '040.941.140-09';
		$cpf[] = '620.324.450-34';
		$cpf[] = '956.022.830-72';
		$cpf[] = '017.455.080-40';
		$cpf[] = '911.878.690-15';
		$cpf[] = '642.895.320-04';
		$cpf[] = '987.978.080-91';
		$cpf[] = '029.486.540-33';
		$cpf[] = '817.736.170-87';
		$cpf[] = '022.622.720-00';
		$cpf[] = '813.361.520-87';
		$cpf[] = '006.635.390-48';
		$cpf[] = '993.894.790-53';
		$cpf[] = '960.041.850-00';
		$cpf[] = '801.884.860-20';
		$cpf[] = '009.160.540-71';
		$cpf[] = '809.932.650-49';
		$cpf[] = '814.866.000-00';
		$cpf[] = '460.780.700-10';
		$cpf[] = '286.277.500-25';
		$cpf[] = '406.593.210-68';
		$cpf[] = '663.043.770-53';
		$cpf[] = '018.846.290-24';
		$cpf[] = '164.871.578-80';
		$cpf[] = '997.062.100-91';
		$cpf[] = '974.978.780-34';
		$cpf[] = '188.759.200-87';
		$cpf[] = '409.226.930-72';
		$cpf[] = '782.927.350-20';
		$cpf[] = '780.387.900-49';
		$cpf[] = '002.544.550-26';
		$cpf[] = '197.613.170-72';
		$cpf[] = '582.547.650-49';
		$cpf[] = '311.646.410-34';
		$cpf[] = '532.925.960-68';
		$cpf[] = '386.284.900-78';
		$cpf[] = '537.855.760-72';
		$cpf[] = '584.129.050-91';
		$cpf[] = '956.697.010-20';
		$cpf[] = '206.046.370-04';
		$cpf[] = '348.777.700-20';
		$cpf[] = '002.972.630-13';
		$cpf[] = '937.006.950-04';
		$cpf[] = '018.128.540-13';
		$cpf[] = '023.289.450-74';
		$cpf[] = '005.267.210-73';
		$cpf[] = '018.813.940-00';
		$cpf[] = '004.022.070-25';
		$cpf[] = '007.250.420-01';
		$cpf[] = '014.780.180-03';
		$cpf[] = '811.869.910-20';
		$cpf[] = '025.139.100-01';
		$cpf[] = '670.602.260-87';
		$cpf[] = '582.417.440-72';
		$cpf[] = '904.117.570-91';
		$cpf[] = '778.125.902-59';
		$cpf[] = '113.158.650-68';
		$cpf[] = '605.819.210-20';
		$cpf[] = '473.205.320-34';
		$cpf[] = '702.181.450-04';
		$cpf[] = '620.897.140-34';
		$cpf[] = '992.852.660-53';
		$cpf[] = '819.284.590-72';
		$cpf[] = '579.379.070-53';
		$cpf[] = '691.485.200-63';
		$cpf[] = '975.593.930-04';
		$cpf[] = '205.194.270-68';
		$cpf[] = '784.612.190-15';
		$cpf[] = '003.537.350-43';
		$cpf[] = '011.007.300-28';
		$cpf[] = '521.116.010-04';
		$cpf[] = '469.752.170-68';
		$cpf[] = '613.492.670-15';
		$cpf[] = '981.893.600-00';
		$cpf[] = '010.941.760-79';
		$cpf[] = '000.420.680-02';
		$cpf[] = '962.521.970-68';
		$cpf[] = '602.747.860-87';
		$cpf[] = '926.811.660-04';
		$cpf[] = '002.610.100-93';
		$cpf[] = '913.436.310-68';
		$cpf[] = '020.369.640-95';
		$cpf[] = '829.567.070-00';
		$cpf[] = '594.643.300-82';
		$cpf[] = '984.962.630-53';
		$cpf[] = '925.068.870-91';
		$cpf[] = '727.284.770-00';
		$cpf[] = '817.705.450-34';
		$cpf[] = '926.816.380-20';
		$cpf[] = '012.143.480-09';
		$cpf[] = '956.726.480-53';
		$cpf[] = '002.485.400-09';
		$cpf[] = '694.434.320-72';
		$cpf[] = '711.331.270-53';
		$cpf[] = '003.172.780-88';
		$cpf[] = '655.269.960-34';
		$cpf[] = '699.797.740-53';
		$cpf[] = '552.686.090-68';
		$cpf[] = '231.522.060-20';
		$cpf[] = '248.538.870-91';
		$cpf[] = '813.560.720-20';
		$cpf[] = '727.785.040-87';
		$cpf[] = '018.576.360-07';
		$cpf[] = '819.057.500-72';
		$cpf[] = '015.834.370-02';
		$cpf[] = '301.781.500-53';
		$cpf[] = '057.863.200-49';
		$cpf[] = '281.108.200-00';
		$cpf[] = '008.897.470-75';
		$cpf[] = '995.335.960-15';
		$cpf[] = '001.808.350-17';
		$cpf[] = '657.733.810-87';
		$cpf[] = '017.480.960-36';
		$cpf[] = '970.644.400-91';
		$cpf[] = '971.564.420-15';
		$cpf[] = '025.410.750-81';
		$cpf[] = '019.716.130-88';
		$cpf[] = '003.541.320-40';
		$cpf[] = '001.040.750-23';
		$cpf[] = '210.663.100-68';
		$cpf[] = '695.825.051-68';
		$cpf[] = '579.867.380-49';
		$cpf[] = '968.294.190-34';
		$cpf[] = '009.979.150-13';
		$cpf[] = '243.636.000-63';
		$cpf[] = '010.526.500-40';
		$cpf[] = '003.735.260-16';
		$cpf[] = '295.570.200-59';
		$cpf[] = '316.075.350-68';
		$cpf[] = '007.353.930-95';
		$cpf[] = '939.587.220-91';
		$cpf[] = '235.960.400-78';
		$cpf[] = '449.936.410-87';
		$cpf[] = '308.045.900-87';
		$cpf[] = '000.452.990-13';

		return $cpf;
	}
		
	function listCPF() 
	{
	
		$cpfs = $this->MontaCPFArray();

		$vids = array();

		foreach($cpfs as $cpf){

			$query = $this->_db->getQuery(true);	
			$query->select($this->_db->quoteName( 'id_user'));									
			$query->from($this->_db->quoteName('#__intranet_pf'));

			$query->where( $this->_db->quoteName('cpf_pf') . '=' . $this->_db->quote( $cpf  ) );

			$this->_db->setQuery($query);
			if((boolean) $id_user = $this->_db->loadResult()) {
				$vids[] = $id_user;
			}
		}

		//$vids = JRequest::getVar( 'vid', array(), 'post', 'array');
 		$temp = JRequest::get( 'post' );
				
		$this->addTablePath(JPATH_BASE.'/tables');
	    
		

		foreach( $vids as $key => $vid ) {
			$data = array();
			$data['id_documento'] = $temp['id_documento'];
			$data['id_user'] = $vid;

			$row = $this->getTable('DocumentoMap');

			if ( !$row->bind($data)) {
				$this->setError($this->_db->getErrorMsg());
				return false;	
			}
				
			if ( !$row->check($data)) {
				$this->setError($this->_db->getErrorMsg());
				return false;	
			}	

			if ( !$row->store(true) ) {	
				$this->setError( $this->_db->getErrorMsg());
				return false;	
			}
		
			jimport('joomla.log.log');
			JLog::addLogger(array( 'text_file' => 'log.documento.php'));
			JLog::add($this->_user->get('id') . JText::_('		Usuário Adicionado -  idAula('.$this->_id.')'), JLog::INFO, 'documento');

		}	

		return true;
	}

	function addUsers() 
	{

		$vids = JRequest::getVar( 'vid', array(), 'post', 'array');
		$temp = JRequest::get( 'post' );


		$this->addTablePath(JPATH_BASE.'/tables');
	    
	
		foreach( $vids as $key => $vid ) {
			$data = array();
			$data['id_documento'] = $temp['id_documento'];
			$data['id_user'] = $vid;

			$row = $this->getTable('DocumentoMap');


	
			if ( !$row->bind($data)) {
				$this->setError($this->_db->getErrorMsg());
				return false;	
			}
				
			if ( !$row->check($data)) {
				$this->setError($this->_db->getErrorMsg());
				return false;	
			}	

			if ( !$row->store(true) ) {	
				$this->setError( $this->_db->getErrorMsg());
				return false;	
			}

			jimport('joomla.log.log');
			JLog::addLogger(array( 'text_file' => 'log.documento.php'));
			JLog::add($this->_user->get('id') . JText::_('		Usuário Adicionado -  idAula('.$this->_id.')'), JLog::INFO, 'documento');

		}	

		return true;
	}

	function removeUsers()
	{	
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		$cid = JRequest::getVar( 'mid',	array(), '', 'array' );

		JArrayHelper::toInteger($cid);
		if (count( $cid )) 
		{		
					
			$cids = implode( ',', $cid );
			
			$conditions = array( $this->_db->quoteName('id_user') . ' IN (' . $cids . ')' ,
								 $this->_db->quoteName('id_documento') . '=' . $this->_db->quote( $this->_id  ) 
								);			
			
			$query = $this->_db->getQuery(true);
			$query->delete( $this->_db->quoteName('#__intranet_documento_map') );
			$query->where($conditions);
			$this->_db->setQuery($query);
			if ( !$this->_db->query() ) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			
			jimport('joomla.log.log');
			JLog::addLogger(array( 'text_file' => 'log.documento.php'));
			JLog::add($this->_user->get('id') . JText::_('		Usuário Removido -  idAula('.$this->_id.')'), JLog::INFO, 'documento');
			
			return true;
		}
	}


	function store() 
	{
		$config   = JFactory::getConfig();
		$siteOffset = $config->getValue('offset');

		$this->addTablePath(JPATH_BASE.'/tables');
	    $row = $this->getTable('documento');
		$data = JRequest::get( 'post' );
	
		if($this->_id)
			$row->load($this->_id);	
				
		if(!$this->_id) {
			
			$data['register_documento'] = JFactory::getDate('now', $siteOffset)->toISO8601(true);
			$data['user_register_documento'] = $this->_userAdmin;
		}
		else {
			$data['update_documento'] = JFactory::getDate('now', $siteOffset)->toISO8601(true);
			$data['user_update_documento'] = $this->_userAdmin;
		}

		$data['text_documento'] = JRequest::getVar('text_documento', null, 'default', 'none', JREQUEST_ALLOWHTML);	

		$keysSwitch = array('status_documento');
		foreach($keysSwitch as $key => $value)
			$data[$value] = isset($data[$value]) ? $data[$value] : '0';

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
		

		if($this->_id):
			$row->checkin($this->_id);
			$textLog = 'edit item';
		else:
			$this->setId( $row->get('id_documento') ); 			
			$textLog = 'new item';
		endif;

		JRequest::setVar( 'cid', $this->_id );

		jimport('joomla.log.log');
		JLog::addLogger(array( 'text_file' => 'log.document.php'));
		JLog::add($this->_user->get('id') . ' - ' . $textLog.' -  id item ('.$this->_id.')', JLog::INFO, 'document');


		
		if($data['public_documento']!=2){		
			$query = $this->_db->getQuery(true);	
			$conditions = array( $this->_db->quoteName('id_documento') . ' IN (' . $cid . ')' );
			$query->delete( $this->_db->quoteName('#__intranet_documento_map') );

			$query->where( $this->_db->quoteName('id_documento') . '=' . $this->_db->quote($this->_id));
			$this->_db->setQuery($query);
			if ( !$this->_db->query() ) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}

		JRequest::setVar( 'cid', $this->_id );
		return true;
	}
	
	function getSkinDocumentos()
	{
		
		$_files_array = JFolder::files(JPATH_CDN .DS. 'images' .DS.  'ptimbrado', '_tumb.jpg' );

		JArrayHelper::toString($_files_array);

		$result = array();
		
		foreach($_files_array as $key => $value) {
			$object = new stdClass();
			$object->value = str_replace( '_tumb.jpg', '', $value);
			$object->text = mb_convert_case($object->value , MB_CASE_TITLE, "UTF-8");
			$result[] = $object;
		}

		return $result;	
	}

	function getAssociados(){
		$query = $this->_db->getQuery(true);	
		
		$query->select('U.id as value, U.name as text');	

			
		$query->from($this->_db->quoteName('#__intranet_associado'));
		$query->innerJoin($this->_db->quoteName('#__users').' U ON ('.$this->_db->quoteName('U.id').'='.$this->_db->quoteName('id_user').')');

		$query->where( $this->_db->quoteName('status_associado') . '=' . $this->_db->quote( '1' ) );


	/* 
		$tipo = $this->getState('filter.tipo');
        if ($tipo!=''):
			if ($tipo=='1')
				$query->where( $this->_db->quoteName('cpf_pf') . ' IS NULL');
			else
			 	$query->where( $this->_db->quoteName('cnpj_pj') . ' IS NULL');
		endif;



		$situacao = $this->getState('filter.situacao');
        if ($situacao!=''):
			switch ($situacao):
				case '0':
						$query->where( $this->_db->quoteName('validate_associado') . ' IS NULL');
				break;
				case '1':
						$query->where( $this->_db->quoteName('validate_associado') . '>=' . $this->_db->quote(JFactory::getDate('now', $this->_siteOffset)->toFormat('%Y-%m-%d', true)));
				break;
				case '2':
						$query->where( $this->_db->quoteName('validate_associado') . '<' . $this->_db->quote(JFactory::getDate('now', $this->_siteOffset)->toFormat('%Y-%m-%d', true)));
						$query->where( $this->_db->quoteName('validate_associado') . '>=' . $this->_db->quote(JFactory::getDate('now -1 year', $this->_siteOffset)->toFormat('%Y-%m-%d', true)));
				break;
				case '3':
						$query->where( $this->_db->quoteName('validate_associado') . '<' . $this->_db->quote(JFactory::getDate('now', $this->_siteOffset)->toFormat('%Y-%m-%d', true)));
				break;
			endswitch;	
		endif;

*/




		
		$query->group($this->_db->quoteName('id_user'));	

		$query->order($this->_db->quoteName('name') . ' ASC' );
		
		$this->_db->setQuery($query);

		return $this->_db->loadObjectList();
	}


	function isCheckedOut()
	{		
		$this->addTablePath(JPATH_BASE.'/tables');
	    $row = $this->getTable('documento');
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
	    $row = $this->getTable('documento');
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
	    $row = $this->getTable('documento');
		
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