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
		$cpf[] = '363.466.400-20';
		$cpf[] = '816.579.190-72';
		$cpf[] = '569.506.089-34';
		$cpf[] = '386.135.490-04';
		$cpf[] = '463.507.210-04';
		$cpf[] = '645.984.460-72';
		$cpf[] = '751.762.890-91';
		$cpf[] = '012.043.720-12';
		$cpf[] = '828.679.680-20';
		$cpf[] = '802.315.000-68';
		$cpf[] = '931.278.200-20';
		$cpf[] = '021.629.840-75';
		$cpf[] = '830.385.310-49';
		$cpf[] = '010.670.910-09';
		$cpf[] = '831.482.729-00';
		$cpf[] = '922.185.800-63';
		$cpf[] = '053.601.700-00';
		$cpf[] = '425.277.240-00';
		$cpf[] = '822.408.170-20';
		$cpf[] = '696.210.470-72';
		$cpf[] = '367.089.920-00';
		$cpf[] = '164.251.030-00';
		$cpf[] = '013.067.370-60';
		$cpf[] = '722.013.710-91';
		$cpf[] = '133.148.580-00';
		$cpf[] = '593.024.021-34';
		$cpf[] = '719.664.120-53';
		$cpf[] = '015.208.520-37';
		$cpf[] = '327.209.200-53';
		$cpf[] = '502.552.660-49';
		$cpf[] = '561.698.680-15';
		$cpf[] = '571.253.330-87';
		$cpf[] = '777.247.980-87';
		$cpf[] = '946.568.190-34';
		$cpf[] = '651.207.950-72';
		$cpf[] = '300.641.980-49';
		$cpf[] = '609.439.390-20';
		$cpf[] = '885.537.600-49';
		$cpf[] = '926.159.450-68';
		$cpf[] = '806.795.520-49';
		$cpf[] = '009.478.770-02';
		$cpf[] = '945.137.080-34';
		$cpf[] = '032.996.760-67';
		$cpf[] = '009.681.881-68';
		$cpf[] = '010.321.440-25';
		$cpf[] = '961.163.070-00';
		$cpf[] = '255.046.490-72';
		$cpf[] = '773.170.330-49';
		$cpf[] = '006.293.030-37';
		$cpf[] = '013.340.500-15';
		$cpf[] = '389.277.130-87';
		$cpf[] = '811.521.670-49';
		$cpf[] = '409.179.320-72';
		$cpf[] = '016.376.310-06';
		$cpf[] = '003.842.200-02';
		$cpf[] = '031.408.450-90';
		$cpf[] = '948.610.560-04';
		$cpf[] = '827.613.960-49';
		$cpf[] = '974.752.380-91';
		$cpf[] = '936.652.960-72';
		$cpf[] = '982.587.100-87';
		$cpf[] = '069.449.490-91';
		$cpf[] = '949.353.430-87';
		$cpf[] = '753.189.970-15';
		$cpf[] = '452.896.620-49';
		$cpf[] = '813.287.450-15';
		$cpf[] = '607.352.950-34';
		$cpf[] = '007.135.320-84';
		$cpf[] = '005.026.190-82';
		$cpf[] = '671.773.283-00';
		$cpf[] = '243.428.580-53';
		$cpf[] = '153.056.000-49';
		$cpf[] = '328.330.910-87';
		$cpf[] = '213.274.673-72';
		$cpf[] = '053.394.980-77';
		$cpf[] = '286.505.730-53';
		$cpf[] = '645.178.450-87';
		$cpf[] = '208.637.800-91';
		$cpf[] = '006.257.550-30';
		$cpf[] = '027.099.260-08';
		$cpf[] = '026.623.540-97';
		$cpf[] = '565.123.250-87';
		$cpf[] = '003.612.050-23';
		$cpf[] = '026.333.580-12';
		$cpf[] = '005.926.650-38';
		$cpf[] = '970.040.730-68';
		$cpf[] = '912.245.190-00';
		$cpf[] = '020.692.670-75';
		$cpf[] = '938.707.448-04';
		$cpf[] = '026.213.180-37';
		$cpf[] = '030.289.570-19';
		$cpf[] = '023.200.720-98';
		$cpf[] = '541.306.110-20';
		$cpf[] = '008.438.160-40';
		$cpf[] = '003.712.240-12';
		$cpf[] = '021.672.000-13';
		$cpf[] = '534.549.600-53';
		$cpf[] = '415.332.910-04';
		$cpf[] = '502.155.820-04';
		$cpf[] = '554.265.140-20';
		$cpf[] = '296.977.600-63';
		$cpf[] = '354.052.520-34';
		$cpf[] = '468.365.510-15';
		$cpf[] = '993.948.630-87';
		$cpf[] = '714.025.510-53';
		$cpf[] = '601.437.540-68';
		$cpf[] = '033.352.589-24';
		$cpf[] = '016.073.990-08';
		$cpf[] = '262.542.120-72';
		$cpf[] = '907.434.660-04';
		$cpf[] = '926.237.000-87';
		$cpf[] = '737.852.610-04';
		$cpf[] = '925.160.840-72';
		$cpf[] = '001.904.320-12';
		$cpf[] = '424.138.260-68';
		$cpf[] = '542.493.760-87';
		$cpf[] = '291.329.320-49';
		$cpf[] = '231.633.300-10';
		$cpf[] = '809.652.020-20';
		$cpf[] = '001.437.830-25';
		$cpf[] = '463.778.670-34';
		$cpf[] = '080.390.110-00';
		$cpf[] = '012.041.990-43';
		$cpf[] = '013.893.590-42';
		$cpf[] = '996.265.860-87';
		$cpf[] = '004.302.240-52';
		$cpf[] = '026.693.610-56';
		$cpf[] = '023.815.150-62';
		$cpf[] = '576.013.990-87';
		$cpf[] = '010.942.700-91';
		$cpf[] = '060.895.180-34';
		$cpf[] = '002.603.280-57';
		$cpf[] = '981.024.600-59';
		$cpf[] = '027.787.120-46';
		$cpf[] = '952.378.950-34';
		$cpf[] = '043.888.929-04';
		$cpf[] = '033.491.940-10';
		$cpf[] = '819.784.560-34';
		$cpf[] = '966.055.370-68';
		$cpf[] = '024.593.120-16';
		$cpf[] = '553.222.980-53';
		$cpf[] = '022.950.550-32';
		$cpf[] = '293.257.810-34';
		$cpf[] = '030.006.140-44';
		$cpf[] = '277.143.300-15';
		$cpf[] = '926.295.040-34';
		$cpf[] = '451.023.680-87';
		$cpf[] = '013.652.490-77';
		$cpf[] = '920.565.120-68';
		$cpf[] = '710.274.400-53';
		$cpf[] = '935.035.040-87';
		$cpf[] = '950.402.770-91';
		$cpf[] = '003.049.600-47';
		$cpf[] = '003.730.560-36';
		$cpf[] = '091.188.100-04';
		$cpf[] = '001.339.370-70';
		$cpf[] = '001.216.320-16';
		$cpf[] = '605.807.120-87';
		$cpf[] = '820.737.160-91';
		$cpf[] = '529.559.820-91';
		$cpf[] = '939.644.390-53';
		$cpf[] = '000.472.540-92';
		$cpf[] = '001.545.880-67';
		$cpf[] = '001.781.780-39';
		$cpf[] = '888.918.790-53';
		$cpf[] = '015.302.380-56';
		$cpf[] = '995.430.100-30';
		$cpf[] = '003.818.660-84';
		$cpf[] = '000.061.230-82';
		$cpf[] = '821.432.420-34';
		$cpf[] = '753.550.780-87';
		$cpf[] = '023.590.150-44';
		$cpf[] = '953.326.180-34';
		$cpf[] = '643.104.930-68';
		$cpf[] = '001.269.660-95';
		$cpf[] = '703.891.440-53';
		$cpf[] = '446.635.100-78';
		$cpf[] = '016.196.430-30';
		$cpf[] = '007.055.300-94';
		$cpf[] = '003.553.810-44';
		$cpf[] = '337.205.170-04';
		$cpf[] = '016.894.860-58';
		$cpf[] = '645.191.980-20';
		$cpf[] = '021.625.000-51';
		$cpf[] = '010.359.450-70';
		$cpf[] = '017.370.540-51';
		$cpf[] = '041.511.089-04';
		$cpf[] = '068.276.320-91';
		$cpf[] = '982.705.750-20';
		$cpf[] = '943.564.400-72';
		$cpf[] = '933.120.910-04';
		$cpf[] = '021.262.050-96';
		$cpf[] = '005.967.580-22';
		$cpf[] = '953.591.680-72';
		$cpf[] = '017.367.400-32';
		$cpf[] = '022.121.120-92';
		$cpf[] = '012.993.980-30';
		$cpf[] = '015.497.390-42';
		$cpf[] = '692.817.340-87';
		$cpf[] = '071.268.189-20';
		$cpf[] = '497.890.150-20';
		$cpf[] = '028.096.670-98';
		$cpf[] = '912.879.799-04';
		$cpf[] = '959.867.740-00';
		$cpf[] = '636.319.540-34';
		$cpf[] = '004.856.460-56';
		$cpf[] = '754.975.650-34';
		$cpf[] = '447.635.490-49';
		$cpf[] = '030.926.430-89';
		$cpf[] = '018.314.840-13';
		$cpf[] = '894.760.690-15';
		$cpf[] = '491.431.070-87';
		$cpf[] = '988.464.340-72';
		$cpf[] = '002.032.320-46';
		$cpf[] = '022.610.540-76';
		$cpf[] = '005.423.230-95';
		$cpf[] = '591.591.540-04';
		$cpf[] = '313.632.880-91';
		$cpf[] = '961.393.230-53';
		$cpf[] = '351.432.580-49';
		$cpf[] = '833.708.970-91';
		$cpf[] = '564.521.360-20';
		$cpf[] = '237.340.940-20';
		$cpf[] = '068.935.700-15';
		$cpf[] = '351.055.342-04';
		$cpf[] = '036.526.140-89';
		$cpf[] = '832.063.450-49';
		$cpf[] = '007.400.640-10';
		$cpf[] = '713.391.400-04';
		$cpf[] = '013.512.530-85';
		$cpf[] = '660.939.530-68';
		$cpf[] = '012.606.550-08';
		$cpf[] = '973.234.970-00';
		$cpf[] = '977.660.200-25';
		$cpf[] = '820.843.940-15';
		$cpf[] = '007.810.250-28';
		$cpf[] = '706.060.050-72';
		$cpf[] = '020.477.790-90';
		$cpf[] = '935.696.290-15';
		$cpf[] = '012.276.930-95';
		$cpf[] = '000.419.740-26';
		$cpf[] = '024.042.260-02';
		$cpf[] = '972.888.450-87';
		$cpf[] = '932.885.300-15';
		$cpf[] = '618.158.700-44';
		$cpf[] = '918.920.580-49';
		$cpf[] = '000.801.610-06';
		$cpf[] = '018.408.120-36';
		$cpf[] = '018.315.750-86';
		$cpf[] = '006.952.710-59';
		$cpf[] = '017.306.430-27';
		$cpf[] = '017.306.430-27';
		$cpf[] = '618.034.800-68';
		$cpf[] = '243.773.250-00';
		$cpf[] = '022.398.850-25';
		$cpf[] = '440.257.180-49';
		$cpf[] = '003.320.430-61';
		$cpf[] = '017.564.660-09';
		$cpf[] = '005.299.240-30';
		$cpf[] = '948.008.040-00';
		$cpf[] = '978.622.230-04';
		$cpf[] = '946.823.308-10';
		$cpf[] = '023.940.890-08';
		$cpf[] = '242.500.660-53';
		$cpf[] = '431.338.510-04';
		$cpf[] = '018.509.150-40';
		$cpf[] = '431.389.000-97';
		$cpf[] = '588.703.266-91';
		$cpf[] = '809.149.180-87';
		$cpf[] = '950.142.760-91';
		$cpf[] = '193.292.370-53';
		$cpf[] = '032.089.380-47';
		$cpf[] = '953.621.930-15';
		$cpf[] = '048.408.020-25';
		$cpf[] = '956.962.700-00';
		$cpf[] = '927.019.300-49';
		$cpf[] = '283.315.308-29';
		$cpf[] = '003.270.173-09';
		$cpf[] = '010.230.230-83';
		$cpf[] = '007.304.300-14';
		$cpf[] = '995.471.040-04';
		$cpf[] = '834.773.140-34';
		$cpf[] = '944.419.010-20';
		$cpf[] = '036.010.376-61';
		$cpf[] = '959.101.580-15';
		$cpf[] = '313.752.860-72';
		$cpf[] = '134.107.680-68';
		$cpf[] = '288.564.220-34';
		$cpf[] = '711.294.990-49';
		$cpf[] = '460.010.930-91';
		$cpf[] = '000.408.790-97';
		$cpf[] = '910.940.890-87';
		$cpf[] = '933.383.250-53';
		$cpf[] = '752.553.590-68';
		$cpf[] = '005.747.970-41';
		$cpf[] = '001.602.380-30';
		$cpf[] = '006.164.560-50';
		$cpf[] = '995.476.190-04';
		$cpf[] = '009.259.490-51';
		$cpf[] = '802.131.200-97';
		$cpf[] = '007.586.220-40';
		$cpf[] = '030.890.620-97';
		$cpf[] = '975.767.860-00';
		$cpf[] = '954.889.950-72';
		$cpf[] = '026.672.550-38';
		$cpf[] = '025.842.680-27';
		$cpf[] = '024.818.110-62';
		$cpf[] = '977.363.410-87';
		$cpf[] = '758.437.140-04';
		$cpf[] = '016.104.160-40';
		$cpf[] = '013.724.230-10';
		$cpf[] = '004.167.840-03';
		$cpf[] = '599.389.780-53';
		$cpf[] = '013.864.570-19';
		$cpf[] = '463.674.380-68';
		$cpf[] = '115.275.790-34';
		$cpf[] = '146.552.760-53';
		$cpf[] = '026.753.120-61';
		$cpf[] = '007.413.090-09';
		$cpf[] = '000.248.520-66';
		$cpf[] = '950.230.200-10';
		$cpf[] = '010.810.230-07';
		$cpf[] = '590.991.000-00';
		$cpf[] = '016.717.880-60';
		$cpf[] = '006.437.999-07';
		$cpf[] = '008.132.040-03';
		$cpf[] = '006.275.530-79';
		$cpf[] = '619.668.860-04';
		$cpf[] = '245.960.940-04';
		$cpf[] = '401.692.190-20';
		$cpf[] = '216.499.280-68';
		$cpf[] = '689.715.240-49';
		$cpf[] = '147.790.050-00';
		$cpf[] = '011.319.080-82';
		$cpf[] = '912.755.390-68';
		$cpf[] = '146.501.420-91';
		$cpf[] = '619.111.350-15';
		$cpf[] = '009.738.930-79';
		$cpf[] = '383.880.650-68';
		$cpf[] = '826.462.360-34';
		$cpf[] = '650.421.233-34';
		$cpf[] = '819.614.210-20';
		$cpf[] = '934.738.180-20';
		$cpf[] = '940.027.030-53';
		$cpf[] = '026.757.850-40';
		$cpf[] = '722.830.330-04';
		$cpf[] = '803.891.710-34';
		$cpf[] = '022.717.570-02';
		$cpf[] = '357.299.700-30';
		$cpf[] = '976.400.930-15';
		$cpf[] = '960.643.400-10';
		$cpf[] = '924.915.880-72';
		$cpf[] = '055.871.246-06';
		$cpf[] = '441.869.910-49';
		$cpf[] = '414.605.370-68';
		$cpf[] = '564.456.280-87';
		$cpf[] = '179.601.500-87';
		$cpf[] = '184.887.140-68';
		$cpf[] = '590.360.010-72';
		$cpf[] = '386.575.970-04';
		$cpf[] = '008.625.100-74';
		$cpf[] = '001.312.740-31';
		$cpf[] = '007.368.960-23';
		$cpf[] = '019.739.843-00';
		$cpf[] = '978.023.950-20';
		$cpf[] = '082.435.389-76';
		$cpf[] = '028.170.170-99';
		$cpf[] = '015.852.360-12';
		$cpf[] = '027.080.360-25';
		$cpf[] = '812.635.890-49';
		$cpf[] = '026.954.090-36';
		$cpf[] = '955.871.360-00';
		$cpf[] = '472.427.793-91';
		$cpf[] = '991.987.700-04';
		$cpf[] = '015.044.550-45';
		$cpf[] = '271.045.910-87';
		$cpf[] = '503.998.290-91';
		$cpf[] = '271.234.990-34';
		$cpf[] = '134.231.693-20';
		$cpf[] = '444.490.110-15';
		$cpf[] = '242.276.680-34';
		$cpf[] = '302.516.990-72';
		$cpf[] = '229.783.800-04';
		$cpf[] = '367.482.770-00';
		$cpf[] = '001.356.610-56';
		$cpf[] = '437.410.020-20';
		$cpf[] = '018.725.770-16';
		$cpf[] = '814.896.410-68';
		$cpf[] = '639.218.800-25';
		$cpf[] = '001.784.570-00';
		$cpf[] = '264.003.160-00';
		$cpf[] = '164.434.890-04';
		$cpf[] = '365.487.740-00';
		$cpf[] = '444.720.050-34';
		$cpf[] = '746.987.370-87';
		$cpf[] = '006.847.020-70';
		$cpf[] = '003.361.320-69';
		$cpf[] = '000.746.790-74';
		$cpf[] = '593.878.580-49';
		$cpf[] = '604.789.260-49';
		$cpf[] = '088.115.567-59';
		$cpf[] = '934.758.880-68';
		$cpf[] = '824.885.250-49';
		$cpf[] = '964.561.670-00';
		$cpf[] = '258.740.980-20';
		$cpf[] = '485.743.500-44';
		$cpf[] = '647.722.090-34';
		$cpf[] = '924.579.320-68';
		$cpf[] = '366.701.970-04';
		$cpf[] = '817.292.940-49';
		$cpf[] = '020.953.110-00';
		$cpf[] = '366.111.310-00';
		$cpf[] = '017.885.430-17';
		$cpf[] = '353.602.670-20';
		$cpf[] = '909.857.709-10';
		$cpf[] = '391.698.710-00';
		$cpf[] = '925.972.380-91';
		$cpf[] = '377.195.800-87';
		$cpf[] = '493.761.790-72';
		$cpf[] = '821.013.700-00';
		$cpf[] = '937.936.930-15';
		$cpf[] = '579.778.100-00';
		$cpf[] = '825.287.210-72';
		$cpf[] = '008.214.560-16';
		$cpf[] = '822.642.010-53';
		$cpf[] = '028.130.510-20';
		$cpf[] = '028.392.240-03';
		$cpf[] = '816.077.840-68';
		$cpf[] = '031.681.810-05';
		$cpf[] = '035.651.600-80';
		$cpf[] = '836.493.440-68';
		$cpf[] = '068.482.570-87';
		$cpf[] = '033.943.889-44';
		$cpf[] = '383.769.030-04';
		$cpf[] = '898.177.100-68';
		$cpf[] = '025.215.830-06';
		$cpf[] = '001.120.280-71';
		$cpf[] = '003.411.380-02';
		$cpf[] = '820.158.480-53';
		$cpf[] = '027.754.510-20';
		$cpf[] = '015.852.540-02';
		$cpf[] = '001.187.310-84';
		$cpf[] = '037.159.580-06';
		$cpf[] = '025.257.640-30';
		$cpf[] = '044.891.610-00';
		$cpf[] = '002.472.880-20';
		$cpf[] = '352.551.290-20';
		$cpf[] = '026.296.450-37';
		$cpf[] = '020.223.150-06';
		$cpf[] = '495.528.990-87';
		$cpf[] = '673.508.100-59';
		$cpf[] = '788.041.830-04';
		$cpf[] = '824.345.650-34';
		$cpf[] = '559.834.130-15';
		$cpf[] = '512.470.200-10';
		$cpf[] = '005.453.520-45';
		$cpf[] = '022.629.270-30';
		$cpf[] = '009.525.310-62';
		$cpf[] = '017.424.780-00';
		$cpf[] = '281.900.250-15';
		$cpf[] = '775.817.170-20';
		$cpf[] = '036.882.150-10';
		$cpf[] = '997.540.530-49';
		$cpf[] = '108.712.840-49';
		$cpf[] = '003.469.180-49';
		$cpf[] = '015.072.640-62';
		$cpf[] = '041.326.258-85';
		$cpf[] = '516.107.280-68';
		$cpf[] = '988.698.170-91';
		$cpf[] = '004.488.860-06';
		$cpf[] = '026.220.470-37';
		$cpf[] = '040.542.209-16';
		$cpf[] = '613.255.460-20';
		$cpf[] = '499.228.820-00';
		$cpf[] = '004.821.630-54';
		$cpf[] = '957.371.970-34';
		$cpf[] = '589.141.450-34';
		$cpf[] = '027.478.510-28';
		$cpf[] = '007.119.190-97';
		$cpf[] = '918.282.440-15';
		$cpf[] = '020.327.960-31';
		$cpf[] = '501.981.300-15';
		$cpf[] = '678.752.380-72';
		$cpf[] = '001.466.010-50';
		$cpf[] = '018.070.969-04';
		$cpf[] = '697.894.600-15';
		$cpf[] = '637.805.930-68';
		$cpf[] = '954.463.030-91';
		$cpf[] = '001.316.390-64';
		$cpf[] = '819.256.200-00';
		$cpf[] = '003.373.110-13';
		$cpf[] = '932.960.100-68';
		$cpf[] = '657.590.600-15';
		$cpf[] = '683.409.660-49';
		$cpf[] = '478.249.560-91';
		$cpf[] = '628.785.650-53';
		$cpf[] = '801.183.990-04';
		$cpf[] = '016.172.630-52';
		$cpf[] = '534.883.340-15';
		$cpf[] = '474.571.800-44';
		$cpf[] = '372.835.290-04';
		$cpf[] = '008.339.130-42';
		$cpf[] = '012.095.770-11';
		$cpf[] = '024.067.789-75';
		$cpf[] = '451.534.690-34';
		$cpf[] = '431.567.460-53';
		$cpf[] = '405.719.980-20';
		$cpf[] = '015.392.060-25';
		$cpf[] = '016.218.020-94';
		$cpf[] = '024.660.870-65';
		$cpf[] = '017.737.620-10';
		$cpf[] = '024.041.630-94';
		$cpf[] = '003.430.020-19';
		$cpf[] = '026.350.480-88';
		$cpf[] = '837.172.180-34';
		$cpf[] = '001.016.200-39';
		$cpf[] = '008.056.430-52';
		$cpf[] = '889.213.870-72';
		$cpf[] = '005.715.160-17';
		$cpf[] = '027.394.010-43';
		$cpf[] = '004.284.480-03';
		$cpf[] = '743.619.290-68';
		$cpf[] = '902.198.130-00';
		$cpf[] = '018.625.670-18';
		$cpf[] = '015.695.340-47';
		$cpf[] = '613.255.460-20';
		$cpf[] = '684.881.730-91';
		$cpf[] = '971.414.020-04';
		$cpf[] = '621.228.190-49';
		$cpf[] = '633.617.103-59';
		$cpf[] = '244.687.700-15';
		$cpf[] = '183.970.180-34';
		$cpf[] = '963.943.230-04';
		$cpf[] = '366.047.550-53';
		$cpf[] = '996.134.760-91';
		$cpf[] = '965.413.340-72';
		$cpf[] = '011.820.220-06';
		$cpf[] = '896.710.150-34';
		$cpf[] = '971.426.380-87';
		$cpf[] = '230.456.800-97';
		$cpf[] = '814.866.000-00';
		$cpf[] = '392.257.809-82';
		$cpf[] = '337.579.170-49';
		$cpf[] = '759.612.531-04';
		$cpf[] = '061.922.110-00';
		$cpf[] = '729.708.980-04';
		$cpf[] = '681.961.480-20';
		$cpf[] = '997.062.100-91';
		$cpf[] = '821.160.240-72';
		$cpf[] = '437.561.900-72';
		$cpf[] = '923.410.120-00';
		$cpf[] = '825.795.120-04';
		$cpf[] = '006.018.600-39';
		$cpf[] = '036.499.019-86';
		$cpf[] = '375.529.880-53';
		$cpf[] = '818.944.900-10';
		$cpf[] = '325.308.460-49';
		$cpf[] = '828.548.150-68';
		$cpf[] = '626.956.810-20';
		$cpf[] = '665.284.020-00';
		$cpf[] = '994.704.110-72';
		$cpf[] = '280.557.640-34';
		$cpf[] = '453.238.440-00';
		$cpf[] = '440.107.610-91';
		$cpf[] = '829.024.610-20';
		$cpf[] = '493.135.250-20';
		$cpf[] = '029.002.980-59';
		$cpf[] = '956.696.630-04';
		$cpf[] = '167.286.450-04';
		$cpf[] = '010.219.160-30';
		$cpf[] = '223.470.620-34';
		$cpf[] = '034.952.750-45';
		$cpf[] = '019.699.970-79';
		$cpf[] = '806.573.890-72';
		$cpf[] = '018.549.220-76';
		$cpf[] = '007.250.420-01';
		$cpf[] = '950.091.590-15';
		$cpf[] = '728.008.790-68';
		$cpf[] = '436.423.060-04';
		$cpf[] = '945.450.900-44';
		$cpf[] = '056.930.009-66';
		$cpf[] = '218.094.960-04';
		$cpf[] = '930.049.400-78';
		$cpf[] = '974.993.230-72';
		$cpf[] = '006.148.620-51';
		$cpf[] = '022.202.230-27';
		$cpf[] = '025.222.806-51';
		$cpf[] = '005.690.580-74';
		$cpf[] = '260.913.480-00';
		$cpf[] = '022.267.780-55';
		$cpf[] = '762.319.500-00';
		$cpf[] = '486.668.900-53';
		$cpf[] = '004.500.980-52';
		$cpf[] = '569.053.270-34';
		$cpf[] = '003.831.050-30';
		$cpf[] = '286.086.580-20';
		$cpf[] = '913.888.569-72';
		$cpf[] = '740.262.400-53';
		$cpf[] = '002.777.900-93';
		$cpf[] = '024.508.140-27';
		$cpf[] = '096.231.750-00';
		$cpf[] = '579.291.650-00';
		$cpf[] = '668.431.540-34';
		$cpf[] = '134.286.950-00';
		$cpf[] = '911.954.380-87';
		$cpf[] = '134.538.900-00';
		$cpf[] = '914.397.100-82';
		$cpf[] = '529.819.160-68';
		$cpf[] = '004.892.790-23';
		$cpf[] = '808.542.060-00';
		$cpf[] = '016.979.120-36';
		$cpf[] = '004.627.510-07';
		$cpf[] = '900.329.770-34';
		$cpf[] = '659.570.830-20';
		$cpf[] = '014.256.660-86';
		$cpf[] = '988.080.010-91';
		$cpf[] = '977.156.620-20';
		$cpf[] = '925.068.870-91';
		$cpf[] = '004.383.960-65';
		$cpf[] = '003.094.640-99';
		$cpf[] = '738.170.900-72';
		$cpf[] = '921.462.440-20';
		$cpf[] = '884.935.470-34';
		$cpf[] = '022.942.380-95';
		$cpf[] = '012.502.350-22';
		$cpf[] = '482.979.840-87';
		$cpf[] = '003.172.780-88';
		$cpf[] = '765.823.700-06';
		$cpf[] = '382.017.710-87';
		$cpf[] = '003.759.010-35';
		$cpf[] = '975.871.090-72';
		$cpf[] = '801.239.290-91';
		$cpf[] = '981.901.560-04';
		$cpf[] = '008.265.660-65';
		$cpf[] = '699.797.740-53';
		$cpf[] = '316.461.400-44';
		$cpf[] = '033.141.210-16';
		$cpf[] = '181.389.340-34';
		$cpf[] = '423.808.390-34';
		$cpf[] = '821.514.230-34';
		$cpf[] = '009.717.420-30';
		$cpf[] = '972.266.960-53';
		$cpf[] = '047.589.070-13';
		$cpf[] = '539.236.340-72';
		$cpf[] = '022.392.240-45';
		$cpf[] = '021.262.010-07';
		$cpf[] = '989.822.810-53';
		$cpf[] = '399.166.690-15';
		$cpf[] = '238.843.980-91';
		$cpf[] = '018.334.400-64';
		$cpf[] = '974.101.100-82';
		$cpf[] = '490.621.070-87';
		$cpf[] = '920.185.647-49';
		$cpf[] = '001.244.820-66';
		$cpf[] = '560.541.140-34';
		$cpf[] = '019.316.530-97';
		$cpf[] = '029.468.359-36';
		$cpf[] = '015.763.580-54';
		$cpf[] = '010.888.510-02';
		$cpf[] = '206.411.760-15';
		$cpf[] = '952.947.320-68';
		$cpf[] = '960.119.390-15';
		$cpf[] = '008.757.420-92';
		$cpf[] = '004.476.560-66';
		$cpf[] = '009.024.950-03';
		$cpf[] = '822.829.780-72';
		$cpf[] = '011.166.400-40';
		$cpf[] = '827.179.820-00';
		$cpf[] = '009.365.210-07';
		$cpf[] = '027.725.780-89';
		$cpf[] = '011.685.570-38';
		$cpf[] = '665.282.160-53';
		$cpf[] = '009.098.420-00';
		$cpf[] = '131.944.290-00';
		$cpf[] = '554.851.110-68';
		$cpf[] = '337.171.170-68';
		$cpf[] = '666.427.360-87';
		$cpf[] = '946.229.810-68';
		$cpf[] = '069.501.240-15';
		$cpf[] = '361.279.490-68';
		$cpf[] = '929.539.720-72';
		$cpf[] = '019.232.310-57';
		$cpf[] = '023.048.400-00';
		$cpf[] = '313.738.870-87';
		$cpf[] = '023.143.690-41';
		$cpf[] = '255.079.310-20';
		$cpf[] = '005.457.310-62';
		$cpf[] = '015.838.710-47';
		$cpf[] = '006.952.670-27';
		$cpf[] = '823.726.400-20';
		$cpf[] = '957.183.110-72';
		$cpf[] = '018.561.010-29';
		$cpf[] = '022.042.040-80';
		$cpf[] = '031.053.270-10';
		$cpf[] = '020.483.220-93';
		$cpf[] = '005.221.230-07';
		$cpf[] = '663.276.287-53';
		$cpf[] = '567.096.760-72';
		$cpf[] = '118.595.040-00';
		$cpf[] = '672.581.070-53';
		$cpf[] = '030.410.640-22';
		$cpf[] = '093.485.220-00';
		$cpf[] = '659.420.190-53';
		$cpf[] = '008.862.220-79';
		$cpf[] = '008.396.320-02';
		$cpf[] = '019.422.600-08';
		$cpf[] = '057.658.540-87';
		$cpf[] = '353.974.970-53';
		$cpf[] = '293.190.210-15';
		$cpf[] = '893.645.620-20';
		
		
		
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