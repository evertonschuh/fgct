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
		$cpf[] = '578.009.440-34';
		$cpf[] = '313.014.799-34';
		$cpf[] = '945.558.690-87';
		$cpf[] = '565.680.450-04';
		$cpf[] = '000.047.110-09';
		$cpf[] = '593.727.700-78';
		$cpf[] = '638.806.360-87';
		$cpf[] = '954.185.470-20';
		$cpf[] = '922.185.710-72';
		$cpf[] = '900.662.510-87';
		$cpf[] = '807.913.520-72';
		$cpf[] = '014.735.750-01';
		$cpf[] = '620.737.960-87';
		$cpf[] = '001.176.560-70';
		$cpf[] = '004.693.180-58';
		$cpf[] = '648.529.700-68';
		$cpf[] = '601.712.190-15';
		$cpf[] = '999.018.620-00';
		$cpf[] = '006.967.370-51';
		$cpf[] = '647.395.895-91';
		$cpf[] = '494.519.790-34';
		$cpf[] = '917.815.900-87';
		$cpf[] = '956.231.910-53';
		$cpf[] = '019.770.950-89';
		$cpf[] = '013.714.500-48';
		$cpf[] = '803.864.820-04';
		$cpf[] = '773.170.330-49';
		$cpf[] = '341.489.060-72';
		$cpf[] = '936.652.960-72';
		$cpf[] = '017.557.059-05';
		$cpf[] = '550.635.600-59';
		$cpf[] = '377.410.980-04';
		$cpf[] = '753.189.970-15';
		$cpf[] = '760.324.420-04';
		$cpf[] = '027.876.340-52';
		$cpf[] = '007.733.939-86';
		$cpf[] = '835.779.390-87';
		$cpf[] = '274.095.540-53';
		$cpf[] = '284.812.320-68';
		$cpf[] = '316.858.890-34';
		$cpf[] = '031.096.944-17';
		$cpf[] = '314.070.300-78';
		$cpf[] = '020.925.230-82';
		$cpf[] = '012.816.270-89';
		$cpf[] = '018.358.280-27';
		$cpf[] = '438.079.410-53';
		$cpf[] = '000.205.810-33';
		$cpf[] = '806.607.970-20';
		$cpf[] = '953.007.320-87';
		$cpf[] = '094.736.370-04';
		$cpf[] = '432.187.090-91';
		$cpf[] = '232.859.660-68';
		$cpf[] = '207.164.600-25';
		$cpf[] = '548.478.000-44';
		$cpf[] = '000.621.139-92';
		$cpf[] = '006.362.170-35';
		$cpf[] = '830.103.350-91';
		$cpf[] = '040.255.050-10';
		$cpf[] = '638.967.530-53';
		$cpf[] = '396.871.800-30';
		$cpf[] = '055.679.849-01';
		$cpf[] = '560.568.260-15';
		$cpf[] = '646.118.920-34';
		$cpf[] = '934.298.860-15';
		$cpf[] = '630.714.600-15';
		$cpf[] = '420.852.920-20';
		$cpf[] = '945.593.760-34';
		$cpf[] = '026.808.770-98';
		$cpf[] = '930.316.100-91';
		$cpf[] = '754.026.880-87';
		$cpf[] = '031.939.910-90';
		$cpf[] = '550.364.900-10';
		$cpf[] = '006.062.290-35';
		$cpf[] = '919.709.380-72';
		$cpf[] = '810.350.200-68';
		$cpf[] = '030.006.140-44';
		$cpf[] = '612.937.760-68';
		$cpf[] = '773.700.580-34';
		$cpf[] = '010.648.810-41';
		$cpf[] = '965.922.060-04';
		$cpf[] = '668.098.600-10';
		$cpf[] = '940.960.070-72';
		$cpf[] = '899.477.970-15';
		$cpf[] = '199.754.650-72';
		$cpf[] = '897.882.730-68';
		$cpf[] = '621.399.830-68';
		$cpf[] = '021.885.710-10';
		$cpf[] = '618.428.770-20';
		$cpf[] = '051.266.459-51';
		$cpf[] = '005.839.900-37';
		$cpf[] = '996.284.650-15';
		$cpf[] = '815.090.220-15';
		$cpf[] = '981.248.540-68';
		$cpf[] = '277.744.300-91';
		$cpf[] = '666.974.330-00';
		$cpf[] = '020.309.640-18';
		$cpf[] = '914.160.780-53';
		$cpf[] = '835.033.310-34';
		$cpf[] = '321.645.300-63';
		$cpf[] = '943.983.800-00';
		$cpf[] = '762.942.340-34';
		$cpf[] = '538.987.960-00';
		$cpf[] = '937.235.710-34';
		$cpf[] = '022.673.720-96';
		$cpf[] = '010.610.350-42';
		$cpf[] = '009.274.550-48';
		$cpf[] = '829.488.280-15';
		$cpf[] = '680.355.600-00';
		$cpf[] = '010.829.340-89';
		$cpf[] = '512.562.820-49';
		$cpf[] = '473.545.500-00';
		$cpf[] = '457.106.190-00';
		$cpf[] = '686.117.650-87';
		$cpf[] = '007.390.180-60';
		$cpf[] = '946.998.520-68';
		$cpf[] = '961.406.660-15';
		$cpf[] = '997.185.020-68';
		$cpf[] = '987.612.260-68';
		$cpf[] = '011.616.260-00';
		$cpf[] = '525.652.380-53';
		$cpf[] = '021.588.000-54';
		$cpf[] = '818.741.700-53';
		$cpf[] = '011.484.950-10';
		$cpf[] = '963.004.930-91';
		$cpf[] = '917.278.710-49';
		$cpf[] = '711.773.960-68';
		$cpf[] = '962.082.280-34';
		$cpf[] = '836.609.090-68';
		$cpf[] = '001.229.430-66';
		$cpf[] = '023.369.580-07';
		$cpf[] = '947.184.820-20';
		$cpf[] = '690.020.020-68';
		$cpf[] = '941.047.940-15';
		$cpf[] = '535.502.690-72';
		$cpf[] = '925.528.460-68';
		$cpf[] = '003.453.980-84';
		$cpf[] = '929.358.850-15';
		$cpf[] = '007.386.430-76';
		$cpf[] = '023.437.630-97';
		$cpf[] = '468.262.700-72';
		$cpf[] = '023.951.820-95';
		$cpf[] = '957.555.860-04';
		$cpf[] = '820.704.400-44';
		$cpf[] = '928.996.170-87';
		$cpf[] = '617.002.660-04';
		$cpf[] = '903.850.638-49';
		$cpf[] = '165.099.430-34';
		$cpf[] = '440.781.960-04';
		$cpf[] = '015.750.800-52';
		$cpf[] = '030.686.990-02';
		$cpf[] = '009.325.030-47';
		$cpf[] = '009.465.610-01';
		$cpf[] = '815.472.690-49';
		$cpf[] = '995.203.700-72';
		$cpf[] = '551.080.590-00';
		$cpf[] = '954.877.270-15';
		$cpf[] = '944.419.010-20';
		$cpf[] = '691.317.400-49';
		$cpf[] = '004.488.080-48';
		$cpf[] = '201.452.250-20';
		$cpf[] = '827.829.209-44';
		$cpf[] = '004.694.410-97';
		$cpf[] = '438.094.560-04';
		$cpf[] = '015.012.840-10';
		$cpf[] = '827.260.860-04';
		$cpf[] = '594.581.270-68';
		$cpf[] = '006.164.560-50';
		$cpf[] = '028.929.946-24';
		$cpf[] = '938.361.550-87';
		$cpf[] = '026.313.330-39';
		$cpf[] = '588.611.140-91';
		$cpf[] = '012.001.380-07';
		$cpf[] = '883.249.401-97';
		$cpf[] = '659.736.230-68';
		$cpf[] = '881.349.030-53';
		$cpf[] = '026.753.120-61';
		$cpf[] = '590.991.000-00';
		$cpf[] = '994.303.200-68';
		$cpf[] = '809.727.560-00';
		$cpf[] = '005.743.610-06';
		$cpf[] = '917.333.240-20';
		$cpf[] = '006.375.700-14';
		$cpf[] = '930.958.050-04';
		$cpf[] = '935.709.880-15';
		$cpf[] = '899.571.640-15';
		$cpf[] = '912.755.390-68';
		$cpf[] = '765.903.800-15';
		$cpf[] = '026.757.850-40';
		$cpf[] = '831.760.040-87';
		$cpf[] = '834.214.590-53';
		$cpf[] = '015.965.980-93';
		$cpf[] = '010.291.280-73';
		$cpf[] = '360.703.860-00';
		$cpf[] = '378.318.600-53';
		$cpf[] = '013.290.480-24';
		$cpf[] = '670.622.530-49';
		$cpf[] = '670.622.530-49';
		$cpf[] = '951.465.040-91';
		$cpf[] = '753.879.210-49';
		$cpf[] = '596.268.360-91';
		$cpf[] = '803.024.750-87';
		$cpf[] = '022.113.600-25';
		$cpf[] = '770.726.189-53';
		$cpf[] = '382.825.260-53';
		$cpf[] = '011.089.080-98';
		$cpf[] = '023.304.330-69';
		$cpf[] = '000.891.610-17';
		$cpf[] = '668.231.450-72';
		$cpf[] = '991.987.700-04';
		$cpf[] = '343.699.101-53';
		$cpf[] = '415.383.740-72';
		$cpf[] = '361.177.140-68';
		$cpf[] = '566.228.729-53';
		$cpf[] = '420.095.290-49';
		$cpf[] = '780.444.480-04';
		$cpf[] = '216.873.407-06';
		$cpf[] = '187.591.570-20';
		$cpf[] = '419.515.060-49';
		$cpf[] = '617.112.920-87';
		$cpf[] = '438.532.770-04';
		$cpf[] = '763.213.127-20';
		$cpf[] = '001.784.570-00';
		$cpf[] = '953.986.860-20';
		$cpf[] = '478.456.510-87';
		$cpf[] = '007.762.230-89';
		$cpf[] = '954.946.340-00';
		$cpf[] = '916.774.830-91';
		$cpf[] = '004.774.010-82';
		$cpf[] = '604.789.260-49';
		$cpf[] = '010.216.660-92';
		$cpf[] = '986.488.290-20';
		$cpf[] = '716.065.800-00';
		$cpf[] = '938.440.850-68';
		$cpf[] = '485.743.500-44';
		$cpf[] = '924.579.320-68';
		$cpf[] = '015.209.320-65';
		$cpf[] = '005.399.680-13';
		$cpf[] = '007.945.140-30';
		$cpf[] = '370.845.690-49';
		$cpf[] = '310.964.170-49';
		$cpf[] = '820.164.020-91';
		$cpf[] = '739.241.990-00';
		$cpf[] = '006.739.900-28';
		$cpf[] = '017.207.180-16';
		$cpf[] = '600.161.510-11';
		$cpf[] = '815.163.470-72';
		$cpf[] = '778.800.300-00';
		$cpf[] = '967.024.350-53';
		$cpf[] = '002.324.730-48';
		$cpf[] = '014.119.740-42';
		$cpf[] = '494.801.890-20';
		$cpf[] = '834.257.800-34';
		$cpf[] = '436.007.430-15';
		$cpf[] = '830.500.600-00';
		$cpf[] = '031.005.820-12';
		$cpf[] = '499.352.830-20';
		$cpf[] = '625.446.249-49';
		$cpf[] = '495.528.990-87';
		$cpf[] = '537.897.090-34';
		$cpf[] = '862.338.070-34';
		$cpf[] = '028.019.780-27';
		$cpf[] = '504.085.770-53';
		$cpf[] = '661.828.080-04';
		$cpf[] = '021.936.060-05';
		$cpf[] = '026.472.620-04';
		$cpf[] = '023.901.290-99';
		$cpf[] = '603.075.100-00';
		$cpf[] = '005.623.020-69';
		$cpf[] = '975.521.270-15';
		$cpf[] = '821.091.330-15';
		$cpf[] = '008.191.590-00';
		$cpf[] = '015.473.680-55';
		$cpf[] = '662.151.020-91';
		$cpf[] = '630.915.340-49';
		$cpf[] = '700.183.080-15';
		$cpf[] = '746.707.270-87';
		$cpf[] = '918.282.440-15';
		$cpf[] = '816.242.630-20';
		$cpf[] = '697.254.620-68';
		$cpf[] = '489.920.469-87';
		$cpf[] = '000.674.560-14';
		$cpf[] = '012.739.800-77';
		$cpf[] = '675.408.990-34';
		$cpf[] = '929.094.770-53';
		$cpf[] = '753.455.600-72';
		$cpf[] = '943.711.210-04';
		$cpf[] = '580.451.200-53';
		$cpf[] = '466.274.640-04';
		$cpf[] = '013.055.710-29';
		$cpf[] = '617.243.940-53';
		$cpf[] = '802.813.700-87';
		$cpf[] = '022.578.099-20';
		$cpf[] = '010.810.130-44';
		$cpf[] = '664.934.550-49';
		$cpf[] = '459.483.800-68';
		$cpf[] = '691.209.190-34';
		$cpf[] = '243.430.990-91';
		$cpf[] = '552.211.300-68';
		$cpf[] = '635.148.930-04';
		$cpf[] = '009.188.040-86';
		$cpf[] = '668.378.040-49';
		$cpf[] = '032.675.910-74';
		$cpf[] = '040.941.140-09';
		$cpf[] = '620.324.450-34';
		$cpf[] = '956.022.830-72';
		$cpf[] = '016.178.550-65';
		$cpf[] = '017.455.080-40';
		$cpf[] = '955.433.170-34';
		$cpf[] = '381.743.400-68';
		$cpf[] = '911.878.690-15';
		$cpf[] = '642.895.320-04';
		$cpf[] = '029.486.540-33';
		$cpf[] = '817.736.170-87';
		$cpf[] = '813.361.520-87';
		$cpf[] = '006.635.390-48';
		$cpf[] = '009.160.540-71';
		$cpf[] = '809.932.650-49';
		$cpf[] = '460.780.700-10';
		$cpf[] = '818.569.460-53';
		$cpf[] = '406.593.210-68';
		$cpf[] = '164.871.578-80';
		$cpf[] = '013.891.460-57';
		$cpf[] = '974.978.780-34';
		$cpf[] = '188.759.200-87';
		$cpf[] = '203.912.420-91';
		$cpf[] = '988.017.590-53';
		$cpf[] = '009.876.630-97';
		$cpf[] = '782.927.350-20';
		$cpf[] = '032.572.090-88';
		$cpf[] = '931.744.460-15';
		$cpf[] = '780.387.900-49';
		$cpf[] = '002.544.550-26';
		$cpf[] = '033.274.490-66';
		$cpf[] = '582.547.650-49';
		$cpf[] = '311.646.410-34';
		$cpf[] = '532.925.960-68';
		$cpf[] = '537.855.760-72';
		$cpf[] = '584.129.050-91';
		$cpf[] = '348.777.700-20';
		$cpf[] = '002.972.630-13';
		$cpf[] = '433.958.800-87';
		$cpf[] = '937.006.950-04';
		$cpf[] = '005.267.210-73';
		$cpf[] = '996.719.720-04';
		$cpf[] = '017.739.450-17';
		$cpf[] = '007.250.420-01';
		$cpf[] = '014.780.180-03';
		$cpf[] = '666.158.520-04';
		$cpf[] = '025.139.100-01';
		$cpf[] = '013.357.780-55';
		$cpf[] = '670.602.260-87';
		$cpf[] = '039.846.989-09';
		$cpf[] = '582.417.440-72';
		$cpf[] = '904.117.570-91';
		$cpf[] = '711.349.720-91';
		$cpf[] = '113.158.650-68';
		$cpf[] = '605.819.210-20';
		$cpf[] = '473.205.320-34';
		$cpf[] = '702.181.450-04';
		$cpf[] = '819.284.590-72';
		$cpf[] = '579.379.070-53';
		$cpf[] = '972.686.660-04';
		$cpf[] = '975.593.930-04';
		$cpf[] = '816.357.020-20';
		$cpf[] = '937.502.260-91';
		$cpf[] = '521.116.010-04';
		$cpf[] = '391.269.000-68';
		$cpf[] = '469.752.170-68';
		$cpf[] = '014.833.050-90';
		$cpf[] = '000.420.680-02';
		$cpf[] = '011.036.230-60';
		$cpf[] = '002.610.100-93';
		$cpf[] = '913.436.310-68';
		$cpf[] = '003.176.810-52';
		$cpf[] = '020.369.640-95';
		$cpf[] = '904.139.460-53';
		$cpf[] = '984.962.630-53';
		$cpf[] = '944.671.530-04';
		$cpf[] = '660.478.200-00';
		$cpf[] = '727.284.770-00';
		$cpf[] = '536.978.040-49';
		$cpf[] = '817.705.450-34';
		$cpf[] = '992.084.740-20';
		$cpf[] = '926.816.380-20';
		$cpf[] = '000.842.400-40';
		$cpf[] = '012.143.480-09';
		$cpf[] = '956.726.480-53';
		$cpf[] = '851.188.420-34';
		$cpf[] = '008.886.200-39';
		$cpf[] = '711.331.270-53';
		$cpf[] = '449.786.500-20';
		$cpf[] = '552.686.090-68';
		$cpf[] = '813.560.720-20';
		$cpf[] = '727.785.040-87';
		$cpf[] = '015.834.370-02';
		$cpf[] = '008.897.470-75';
		$cpf[] = '823.564.750-87';
		$cpf[] = '677.591.570-53';
		$cpf[] = '001.808.350-17';
		$cpf[] = '007.175.620-50';
		$cpf[] = '836.156.870-00';
		$cpf[] = '970.644.400-91';
		$cpf[] = '019.716.130-88';
		$cpf[] = '003.541.320-40';
		$cpf[] = '009.098.420-00';
		$cpf[] = '001.040.750-23';
		$cpf[] = '897.325.530-49';
		$cpf[] = '210.663.100-68';
		$cpf[] = '695.825.051-68';
		$cpf[] = '380.140.410-20';
		$cpf[] = '016.715.250-51';
		$cpf[] = '929.539.720-72';
		$cpf[] = '968.294.190-34';
		$cpf[] = '635.642.110-04';
		$cpf[] = '716.436.800-72';
		$cpf[] = '342.545.600-87';
		$cpf[] = '010.526.500-40';
		$cpf[] = '828.385.000-82';
		$cpf[] = '316.075.350-68';
		$cpf[] = '005.221.230-07';
		$cpf[] = '807.745.630-87';
		$cpf[] = '995.104.130-20';
		$cpf[] = '825.162.860-15';
		$cpf[] = '000.452.990-13';
		$cpf[] = '028.969.910-00';
		


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