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