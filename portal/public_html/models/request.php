<?php
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.model' );

class EASistemasModelRequest extends JModel {

	var $_id = null;
	var $_data = null;
	var $_db = null;
	var $_app = null;	
	var $_user = null;
	var $_siteOffset = null;
	
	function __construct()
	{
		parent::__construct();
		
		$this->_app 	= JFactory::getApplication(); 
		$this->_db		= JFactory::getDBO();
		$this->_user	= JFactory::getUser();
		$this->_siteOffset = $this->_app->getCfg('offset');
		
		
		if( JRequest::getVar( 'task' )  != 'add')
		{
			$array  	= JRequest::getVar( 'cid', array(0), '', 'array');
			JRequest::setVar( 'cid', $array[0] );
			$this->setId( (int) $array[0] );

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
			$query->from('#__intranet_service');	
			$query->leftJoin($this->_db->quoteName('#__intranet_service_type').'  USING ('.$this->_db->quoteName('id_service_type').')');
			$query->where( $this->_db->quoteName('id_service') . '=' . $this->_db->quote( $this->_id ) );
			$this->_db->setQuery($query);
			if(!(boolean) $this->_data = $this->_db->loadObject()) {
				$this->addTablePath(JPATH_SITE.'/tables');
	    		$this->_data = $this->getTable('request');
				//$this->_data->name='';
				//$this->_data->email='';
			}
			$query->clear();
		}
		return $this->_data;
	}


	function getServices() 
	{
		$query = $this->_db->getQuery(true);
		$query->select('id_service_type as value, CONCAT(codigo_service_type, \' - \', name_service_type) as text, message_service_type as script');
		$query->from('#__intranet_service_type');
		$query->leftJoin( $this->_db->quoteName('#__intranet_documento') . ' USING('. $this->_db->quoteName('id_documento').')' );
		$query->leftJoin( $this->_db->quoteName('#__intranet_documento_map') . ' USING('. $this->_db->quoteName('id_documento').')' );
		$query->where($this->_db->quoteName('status_service_type') . ' = ' . $this->_db->quote('1'));
		$query->where($this->_db->quoteName('public_service_type') . ' = ' . $this->_db->quote('1'));
		
		$query->where('(('.$this->_db->quoteName('status_documento').' = '.$this->_db->quote('1')
						 .') OR ('
						 .$this->_db->quoteName('#__intranet_documento.status_documento') . ' IS NULL'
						 .'))');

		$query->where('('
						.'(' 
						.$this->_db->quoteName('#__intranet_documento.status_documento') . ' IS NULL'
						.' ) OR ('
						.$this->_db->quoteName('public_documento') . ' = ' . $this->_db->quote('1')
						.' ) OR ('
						.$this->_db->quoteName('public_documento') . ' = ' . $this->_db->quote('2')
						.'AND'
						.$this->_db->quoteName('#__intranet_documento_map.id_user') . '=' . $this->_db->quote( $this->_user->get('id') )
						.')'
					 .')');
					 
		$query->group($this->_db->quoteName('id_service_type'));
		$query->order($this->_db->quoteName('codigo_service_type'));
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectList();
		$query->clear();
		return $list;

	}


	function getServiceMaps() 
	{
		$query = $this->_db->getQuery(true);
		$query->select($this->_db->quoteName(array( 'name_service_stage',
													'color_service_stage',
													'title_service',
													'message_service',
													'id_documento_numero',
													'name_documento',
													'update_service',
													'name',
													'image_pf'

													)));
		$query->from($this->_db->quoteName('#__intranet_service_map'));
		$query->innerJoin($this->_db->quoteName('#__intranet_service_stage').' USING ('.$this->_db->quoteName('id_service_stage').')');
		$query->leftJoin($this->_db->quoteName('#__users').'  ON ('.$this->_db->quoteName('#__users.id').'='.$this->_db->quoteName('#__intranet_service_map.id_user').')');
		$query->leftJoin($this->_db->quoteName('#__intranet_pf').' ON ('.$this->_db->quoteName('#__users.id').'='.$this->_db->quoteName('#__intranet_pf.id_user').')');
		$query->leftJoin($this->_db->quoteName('#__intranet_documento_numero') . ' USING ('.$this->_db->quoteName('id_documento_numero').')');

		$query->where( $this->_db->quoteName('id_service') . '=' . $this->_db->quote( $this->_id ) );

		$query->order($this->_db->quoteName('update_service') . ' ASC');
		$this->_db->setQuery($query);
		$items = $this->_db->loadObjectList();
		$query->clear();
		foreach($items as &$item)
			$item->update_service_text =  $this->tempo_corrido($item->update_service);

		return $items;

	}
	

	function tempo_corrido($data_informada) {


		$data_informada = JFactory::getDate($data_informada, $this->_siteOffset)->toISO8601(true);
		$timestamp = new DateTime($data_informada);
		$timestampInformado =  $timestamp->getTimestamp();
		$agora = strtotime("now");
		$data_a = $agora - $timestampInformado;
		$segundos = $data_a;
		$minutos = round($data_a / 60);
		$horas = round($data_a / 3600);
		$dias = round($data_a / 86400);
		$semanas = round($data_a / 604800);
		$meses = round($data_a / 2419200);
		$anos = round($data_a / 29030400);
		if ($segundos <= 60) return "1 min atrás";
		else if ($minutos <= 60) return $minutos==1 ?'1 min atrás':$minutos.' min atrás';
		else if ($horas <= 24) return $horas==1 ?'1 hrs atrás':$horas.' horas atrás';
		else if ($dias <= 7) return $dias==1 ?'1 dia atras':$dias.' dias atrás';
		else if ($semanas <= 4) return $semanas==1 ?'1 semana atrás':$semanas.' semanas atrás';
		else if ($meses <= 12) return $meses == 1 ?'1 mês atrás':$meses.' meses atrás';
		else return $anos == 1 ? 'um ano atrás':$anos.' anos atrás';
	}


	
	function store() 
	{

		$auto_execute = false;
		$data = JRequest::get( 'post' );

		$this->addTablePath(JPATH_SITE.'/tables');
		$row = $this->getTable('request');
		
		$id_document = false;

		$data['id_user'] = $this->_user->get('id');
		$data['status_service'] = '1';
		
		$options = array();
		$options['id_user'] = $data['id_user'];
		$data['lastupdate_service'] = JFactory::getDate('now', $this->_siteOffset)->toISO8601(true);
		if($this->_id):
			$row->load($this->_id);	
			$options['message_service'] = $data['message_service'];
			$options['id_service_stage'] = '2';
		else:
			$data['create_service'] = $data['lastupdate_service'];
			$options['id_service_stage'] = '1';
			$options['title_service'] = '';
			$options['message_service'] = '';
			$id_document = $this->getAtuoExecute($data['id_service_type']);
		endif;

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
		
		if ( !$row->store(true) ) 
		{	
			$this->setError( $this->_db->getErrorMsg());
			return false;	
		}

		if($this->_id):
			$textLog = 'edit item';
		else:
			$this->setId( $row->get('id_service') ); 			
			$textLog = 'new item';
		endif;

		//$row = null;
		//$row->load(null);
		
		//$row->unbind();
		//unset($row);
		//$row->checkin( $this->_id );
		//JTable::getInstance

		//$row->load('DocumentNumber');
	
		//echo 'asdasdddddddddddddddddddddddd';*/
		JRequest::setVar( 'cid', $this->_id );

		jimport('joomla.log.log');
		JLog::addLogger(array( 'text_file' => 'log.request.php'));
		JLog::add($this->_user->get('id') . ' - ' . $textLog.' -  id item ('.$this->_id.')', JLog::INFO, 'request');

		//$row->checkin( $this->_id );
		$options['id_service'] = $this->_id;	
		$options['id_documento_numero'] = NULL;
		if(!$this->setNewMapService($options))
			return false;	
		
		if($id_document){
			$this->setMapExecuteDocument('Preparando Documento');
			$options = array();
			$options['id_user'] = $data['id_user'];
			$options['id_document'] = $id_document;
			if( (boolean) $documentPdf = $this->storeNewDocument($options)) {
				$this->setMapExecuteDocument('Salvando Documento');
				if( (boolean) $datdPDF  = $this->createPDFDocumento($documentPdf) ) {
					$documentPdf['datdPDF'] = $datdPDF;
					$this->storeBinDocument($documentPdf);
					$this->setMapExecuteDocument('Documento pronto para download', $documentPdf['id']);
					$this->setFish();
				}
			}
		}			
		return true;
	}

	function setNewMapService($options = array()) 
	{

		if(	empty($options['id_service']) ||
			empty($options['id_service_stage'])
		  )
			return false;

		$query = $this->_db->getQuery(true);
		$query->insert( $this->_db->quoteName('#__intranet_service_map') );
		$query->columns($this->_db->quoteName(array('id_service', 'id_user', 'id_service_stage', 'title_service', 'message_service', 'id_documento_numero', 'update_service')));	
	
		
		$values = array($this->_db->quote($options['id_service']),
						$this->_db->quote($options['id_user']), 
						$this->_db->quote($options['id_service_stage']), 
						$this->_db->quote($options['title_service']),
						$this->_db->quote($options['message_service']),
						$this->_db->quote($options['id_documento_numero']),
						$this->_db->quote(JFactory::getDate('now', $this->_siteOffset)->toISO8601(true))
					);
						
		$query->values(implode(',', $values));
		
		$this->_db->setQuery($query);
		if(!$this->_db->query())
			return false;

		

		return true;
	}

	function setMapExecuteDocument($mensagem = null, $id_documento_numero = null) {

		if(is_null($mensagem))
			return false;

		$options = array();
		$options['id_service'] = $this->_id;
		$options['id_user']= NULL;
		$options['id_service_stage'] = '3';
		$options['title_service'] = $mensagem;
		$options['id_documento_numero'] = (is_null($id_documento_numero) ? NULL : $id_documento_numero);
		$options['message_service'] = NULL;
		$options['update_service']= NULL;

		if(!$this->setNewMapService($options))
			return false;	
		
		return true;

	}


	function storeNewDocument($options = array())
	{

		require_once(JPATH_LIB .DS. 'document.php');
		$modelClass = 'EASistemasLibsDocument';
		
		if (!class_exists($modelClass))
			return false;
	
		$classDocument = new $modelClass();

		$this->addTablePath(JPATH_SITE.'/tables');
		$row = $this->getTable('DocumentNumber');


		unset($row->id_service);
		unset($row->status_service);
		unset($row->id_service_type);
		unset($row->create_service);
		unset($row->lastupdate_service);
		unset($row->checked_out);
		unset($row->checked_out_time);


		$documento = $classDocument->getDocumento($options['id_document']);

		$data = array();
		$data['id_documento']  = $documento->id_documento;
		$data['id_user'] = $options['id_user'];
		$data['ano_documento_numero'] = JFactory::getDate('now', $this->_siteOffset)->Format('Y', true);
		$data['name_documento'] = $documento->name_documento;
		$data['register_documento_numero'] = JFactory::getDate('now', $this->_siteOffset)->toISO8601(true);
		$data['user_register_documento_numero'] = $options['id_user'];
		$data['numero_documento_numero'] = $classDocument->getNumero();

		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (!$row->check($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (!$row->store(true)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}


		jimport('joomla.log.log');
		JLog::addLogger(array('text_file' => 'log.documents.php'));
		JLog::add($this->_user->get('id')  . JText::_('		New Document -  idDocumento(' . $row->get('id_documento_numero') . ')'), JLog::INFO, 'documents');

		$options['user_info'] = $classDocument->getUserInfo($options['id_user']);
		$options['numero_documento'] = 1 ;
		$options['numero_documento'] = $row->get('numero_documento_numero') .'/'. $row->get('ano_documento_numero');
		$options['text_documento'] = $documento->text_documento;

		$responseDocument = $classDocument->createDocument($options);

		$documentPdf = array();
		$documentPdf['id'] = $row->id_documento_numero;
		$documentPdf['texto'] = $responseDocument->textDocument;
		$documentPdf['register_documento_numero'] = $row->register_documento_numero;
		$documentPdf['numeber'] = $options['numero_documento'];


		$documentPdf['name'] = $documento->name_documento;
		$documentPdf['skin'] = $documento->skin_documento;
		$documentPdf['loads'] = $responseDocument->loads;

		$documentPdf['id_user'] = $options['user_info']->id;
		$documentPdf['cpf_pf'] = $options['user_info']->cpf_pf;
		$documentPdf['name'] = $options['user_info']->name;


		return $documentPdf;
	}

	function storeBinDocument($options = array())
	{

		$this->addTablePath(JPATH_SITE.'/tables');
		$row = $this->getTable('DocumentNumber');

		$row->load($options['id']);
	

		$data = array();
		$data['texto_documento_numero']  = $options['datdPDF'];


		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		unset($row->id_service);
		unset($row->status_service);
		unset($row->id_service_type);
		unset($row->create_service);
		unset($row->lastupdate_service);
		unset($row->checked_out);
		unset($row->checked_out_time);

		if (!$row->store(true)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		
		jimport('joomla.log.log');
		JLog::addLogger(array('text_file' => 'log.documents.php'));
		JLog::add($this->_user->get('id')  . JText::_('		Store Data Bin Document -  idDocumento(' . $row->get('id_documento_numero') . ')'), JLog::INFO, 'documents');

		return true;

	}


	function getDocumento($id_documento = null)
	{
		$post = JRequest::get('post');
		$query = $this->_db->getQuery(true);
		$query->select('*');
		$query->from('#__intranet_documento');
		$query->where($this->_db->quoteName('id_documento') . '=' . $this->_db->quote($id_documento));
		$this->_db->setQuery($query);
		return $this->_db->loadObject();
	}

	function getNumero()
	{
		$numero_protocolo = 0;
		$query = $this->_db->getQuery(true);
		$query->select('MAX(numero_documento_numero)');
		$query->from('#__intranet_documento_numero');
		$query->where($this->_db->quoteName('ano_documento_numero') . ' = YEAR(CURDATE())');
		$this->_db->setQuery($query);
		$numero_protocolo += $this->_db->loadResult();
		$numero_protocolo++;

		return  $numero_protocolo;
	}

	function createPDFDocumento($options = array())  {

		require_once(JPATH_INCLUDES .DS. 'document.php');

		$modelClass = 'EASistemasIncludesDocument';

		if (!class_exists($modelClass))
			return false;

		$classDocument = new $modelClass();

		if(!(boolean) $datdPDF = $classDocument->createDocumentPdf($options))
			return false;
		
		return $datdPDF;
		
	}


	function getAtuoExecute($id_service_type = null) 
	{
		$query = $this->_db->getQuery(true);
		$query->select('id_documento');
		$query->from('#__intranet_service_type');
		$query->where('automatic_service_type = 1');
		$query->where('status_service_type = 1');
		$query->where( $this->_db->quoteName('id_service_type') . '=' . $this->_db->quote( $id_service_type ) );
		$this->_db->setQuery($query);
		return $this->_db->loadResult();	
	}

	function setFish(){
		if (!empty( $this->_id)) 
		{
			$query = $this->_db->getQuery(true);
			$fields = array( $this->_db->quoteName('status_service').'='.$this->_db->quote( '2' ) );
			$conditions = array( $this->_db->quoteName('id_service').'='.$this->_db->quote( $this->_id ) );
			$query->update($this->_db->quoteName('#__intranet_service'))->set($fields)->where($conditions);
			$this->_db->setQuery($query);
			if ( !$this->_db->execute() ) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			return true;
		}	
	}





/*
	function isCheckedOut()
	{		
		$this->addTablePath(JPATH_SITE.'/tables');
	    $row = $this->getTable('request');
		$cid = JRequest::getVar( 'cid', array(0), '', 'array' );
		$cid = $cid[0];
		if ( $row->load( $cid ) ) 
		{	
			if (!$row->isCheckedOut( $this->_user->get('id') ) )
			{
				return false;
			}
			return true;
		}
		return false;
	}
	*/
	function checkout()
	{		
		/*
		$this->addTablePath(JPATH_SITE.'/tables');
	    $row = $this->getTable('request');
		$cid = JRequest::getVar( 'cid', array(0), '', 'array' );
		$cid = $cid[0];
		if ( $row->load( $cid ) ) 
		{	
			if(! $row->checkout( $this->_user->get('id') ) ) 
			{
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			return true;
		}
		return false;*/
	}
	
	function checkin()
	{	
		/*$this->addTablePath(JPATH_SITE.'/tables');
	    $row = $this->getTable('request');
		
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
		return false;*/
	}

	
	
}