<?php
defined('_JEXEC') or die('Restricted access');


jimport( 'joomla.application.component.model' );
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
jimport('joomla.image.image');

class EASistemasModelAssociado extends JModel {

	var $_id = null;
	var $_doc = null;
	var $_type = null;
	var $_data = null;
	var $_db = null;
	var $_app = null;	
	var $_isRoot = null;
	var $_user = null;
	var $_userAdmin = null;
	var $_siteOffset = null;
	var $_pagination = null;
	
	function __construct()
	{
		parent::__construct();
		
		$this->_db		= JFactory::getDBO();
		$this->_app 	= JFactory::getApplication(); 
		$this->_user	= JFactory::getUser();
		$this->_siteOffset = $this->_app->getCfg('offset');
		
		$this->_isRoot		= $this->_user->get('isRoot');	
		$this->_userAdmin	= $this->_user->get('id');
		
		
		$this->_session_id = JFactory::getSession()->getId();
		
		
		$this->_doc = JRequest::getVar( 'doc', '', 'GET');
		$this->_type = JRequest::getVar( 'type', '', 'GET');
		//echo JPATH_ADMINISTRATOR;
		if( JRequest::getVar( 'task' )  != 'add')
		{
			$array  	= JRequest::getVar( 'cid', array(0), '', 'array');
			JRequest::setVar( 'cid', $array[0] );
			$this->setId( (int) $array[0] );
		
			if (!$this->isCheckedOut() ) {
				$this->checkout();		
			}
			else {
				$tipo = 'alert-warning';
				$msg = JText::_( 'JGLOBAL_CONTROLLER_CHECKIN_ITEM' );
				$link = 'index.php?view=associados';
				$this->_app->redirect($link, $msg, $tipo);
			}
		}
	}

	function setId( $id ) 
	{
		$this->_id		= $id;
		$this->_data	= null;
	}

	function getUtil(){

		$_util = new stdClass();

		$_util->_app = $this->_app; 
		$_util->_user = $this->_user;
		$_util->_siteOffset = $this->_siteOffset;
		return $_util;
	}

	function setDoc()
	{

		$data = JRequest::get( 'post' );
		$this->_type = $data['id_type'];
		$this->_doc = $data['doc'];

		$query = $this->_db->getQuery(true);			
		$query->select('*');
		$query->select('IF(ISNULL(id_pj),0,1) AS id_tipo');
		$query->select('IF(ISNULL(id_pj),#__intranet_pf.id_estado, #__intranet_pj.id_estado) AS id_estado');
		$query->select('IF(ISNULL(id_pj),#__intranet_pf.id_cidade, #__intranet_pj.id_cidade) AS id_cidade');
		$query->select('IF(ISNULL(id_pj),#__intranet_pf.add_id_estado, #__intranet_pj.add_id_estado) AS add_id_estado');
		$query->select('IF(ISNULL(id_pj),#__intranet_pf.add_id_cidade, #__intranet_pj.add_id_cidade) AS add_id_cidade');			
		
		$query->from('#__intranet_associado');	
		$query->leftJoin($this->_db->quoteName('#__intranet_pf') . ' USING( ' . $this->_db->quoteName('id_user') . ')');
		$query->leftJoin($this->_db->quoteName('#__intranet_pj') . ' USING( ' . $this->_db->quoteName('id_user') . ')');				
		$query->leftJoin($this->_db->quoteName('#__users').'  ON ('.$this->_db->quoteName('id').'='.$this->_db->quoteName('id_user').')');

		$searches = array();
		$searches[]	= $this->_db->quoteName('#__intranet_pf.cpf_pf') . '=' . $this->_db->quote( $this->_doc ) ;
		$searches[]	= $this->_db->quoteName('#__intranet_pj.cnpj_pj') . '=' . $this->_db->quote( $this->_doc ) ;
			

		$query->where('('.implode(' OR ', $searches).')');

		$this->_db->setQuery($query);
		if(!(boolean) $this->_db->loadObject()) 
			return true;
		
		return false;
		
	}
	
	function getItem()
	{
		if (empty($this->_data)):
			$query = $this->_db->getQuery(true);	
			if(empty($this->_id)):
				if(!empty($this->_doc)):
				
					$query->select('*');
					$query->select('IF(ISNULL(id_pj),0,1) AS id_tipo');
					$query->select('IF(ISNULL(id_pj),#__intranet_pf.id_user, #__intranet_pj.id_user) AS id_user');
					$query->select('IF(ISNULL(id_pj),#__intranet_pf.id_estado, #__intranet_pj.id_estado) AS id_estado');
					$query->select('IF(ISNULL(id_pj),#__intranet_pf.id_cidade, #__intranet_pj.id_cidade) AS id_cidade');
					$query->select('IF(ISNULL(id_pj),#__intranet_pf.add_id_estado, #__intranet_pj.add_id_estado) AS add_id_estado');
					$query->select('IF(ISNULL(id_pj),#__intranet_pf.add_id_cidade, #__intranet_pj.add_id_cidade) AS add_id_cidade');			
					
					$query->from('#__users');	
					$query->leftJoin($this->_db->quoteName('#__intranet_pf') .'  ON ('.$this->_db->quoteName('id').'='.$this->_db->quoteName('#__intranet_pf.id_user').')');
					$query->leftJoin($this->_db->quoteName('#__intranet_pj') .'  ON ('.$this->_db->quoteName('id').'='.$this->_db->quoteName('#__intranet_pj.id_user').')');				
					
					$searches = array();
					$searches[]	= $this->_db->quoteName('#__intranet_pf.cpf_pf') . '=' . $this->_db->quote( $this->_doc ) ;
					$searches[]	= $this->_db->quoteName('#__intranet_pj.cnpj_pj') . '=' . $this->_db->quote( $this->_doc ) ;
			
					$query->where('('.implode(' OR ', $searches).')');
					$this->_db->setQuery($query);
					
					if(!(boolean) $this->_data = $this->_db->loadObject()):
						$this->addTablePath(JPATH_ADMINISTRATOR.'/tables');
						if($this->_type == 0)
							$this->_data = $this->getTable('pf');
						else
							$this->_data = $this->getTable('pj');
						
						$this->_data->id_tipo = $this->_type;	
						$this->_data->name='';
						$this->_data->email='';
					endif;
					$this->_data->doc = $this->_doc;
					$this->_data->status_associado = 0;
				endif;
			else:	
			
				$query->select('*');
				$query->select('IF(ISNULL(id_pj),0,1) AS id_tipo');
				$query->select('IF(ISNULL(id_pj),#__intranet_pf.id_estado, #__intranet_pj.id_estado) AS id_estado');
				$query->select('IF(ISNULL(id_pj),#__intranet_pf.id_cidade, #__intranet_pj.id_cidade) AS id_cidade');
				$query->select('IF(ISNULL(id_pj),#__intranet_pf.add_id_estado, #__intranet_pj.add_id_estado) AS add_id_estado');
				$query->select('IF(ISNULL(id_pj),#__intranet_pf.add_id_cidade, #__intranet_pj.add_id_cidade) AS add_id_cidade');			
				
				$query->from('#__intranet_associado');	
				$query->leftJoin($this->_db->quoteName('#__intranet_pf') . ' USING( ' . $this->_db->quoteName('id_user') . ')');
				$query->leftJoin($this->_db->quoteName('#__intranet_pj') . ' USING( ' . $this->_db->quoteName('id_user') . ')');				
				$query->leftJoin($this->_db->quoteName('#__users').'  ON ('.$this->_db->quoteName('id').'='.$this->_db->quoteName('id_user').')');
			
				$query->where( $this->_db->quoteName('id_associado') . '=' . $this->_db->escape( $this->_id ) );

				$this->_db->setQuery($query);
				$this->_data = $this->_db->loadObject();
				
			endif;
		endif;
		
		return $this->_data;
	}
	
	function getRanking()
	{
		
		$query = $this->_db->getQuery(true);
		$query->select('COUNT(DISTINCT(id_etapa)) AS total');
		
		$query->select($this->_db->quoteName(  array('id_user',													 
													 'name_prova',													 
													 'name_campeonato',												 
													 'ano_campeonato',												 
													 )));
		
		
		
		$query->from( $this->_db->quoteName('#__ranking_resultado') );
		$query->innerJoin( $this->_db->quoteName('#__ranking_etapa') . ' USING('. $this->_db->quoteName('id_etapa').')' );
		$query->innerJoin( $this->_db->quoteName('#__ranking_inscricao') . ' USING('. $this->_db->quoteName('id_inscricao').', '. $this->_db->quoteName('id_campeonato').')');
		$query->innerJoin( $this->_db->quoteName('#__ranking_prova') . ' USING('. $this->_db->quoteName('id_prova').', '. $this->_db->quoteName('id_campeonato').')');
		$query->innerJoin( $this->_db->quoteName('#__ranking_campeonato') . ' USING('. $this->_db->quoteName('id_campeonato').')' );
		$query->innerJoin( $this->_db->quoteName('#__intranet_associado') . ' USING('. $this->_db->quoteName('id_user').')' );
		
		//---------------------------------------------------------------------------------------------------------------------------------------------
		$queryPagamento = $this->_db->getQuery(true);																							//-----
		$queryPagamento->select('ano_anuidade AS ano_campeonato');																				//-----
		$queryPagamento->select('id_user');																										//-----
		$queryPagamento->select('MIN(baixa_pagamento) AS baixa_pagamento');																		//-----
		$queryPagamento->from( $this->_db->quoteName('#__intranet_anuidade') );																	//-----
		$queryPagamento->innerJoin( $this->_db->quoteName('#__intranet_pagamento') . ' USING('. $this->_db->quoteName('id_anuidade').')');		//-----
		$queryPagamento->where($this->_db->quoteName('baixa_pagamento') . ' IS NOT NULL' );														//-----
		$queryPagamento->where($this->_db->quoteName('status_pagamento') . '=' . $this->_db->quote( '1' ) );									//-----
		$queryPagamento->group($this->_db->quoteName('id_anuidade'));																			//-----
		$queryPagamento->group($this->_db->quoteName('id_user'));																				//-----
		//---------------------------------------------------------------------------------------------------------------------------------------------
																																									
		$query->innerJoin( '(' . $queryPagamento . ') Pagamento USING('. $this->_db->quoteName('id_user').','. $this->_db->quoteName('ano_campeonato').')');

		$query->where( $this->_db->quoteName('#__ranking_etapa.data_beg_etapa') . ' >= ' . $this->_db->quote( JFactory::getDate('now -1 year', $this->_siteOffset)->toFormat() ) );
		$query->where( $this->_db->quoteName('Pagamento.baixa_pagamento').'<'. $this->_db->quoteName( 'date_register_resultado' ) );
		$query->where($this->_db->quoteName('status_resultado') . ' = ' . $this->_db->quote('1'));
		$query->where($this->_db->quoteName('status_inscricao') . ' = ' . $this->_db->quote('1'));
		$query->where($this->_db->quoteName('status_resultado') . ' = ' . $this->_db->quote('1'));
		$query->where($this->_db->quoteName('status_inscricao') . ' = ' . $this->_db->quote('1'));
		$query->where($this->_db->quoteName('status_campeonato') . ' = ' . $this->_db->quote('1'));
		$query->where($this->_db->quoteName('status_prova') . ' = ' . $this->_db->quote('1'));
		$query->where($this->_db->quoteName('status_etapa') . ' = ' . $this->_db->quote('1'));
		$query->group($this->_db->quoteName('id_inscricao'));
		
		$query->where( $this->_db->quoteName('id_associado') . '=' . $this->_db->escape( $this->_id ) );
		$query->order( $this->_db->quoteName('ano_campeonato') . ' DESC');
		$query->order( $this->_db->quoteName('name_prova'));
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();
		
	}
	
	function getItemAtividades()
	{
		if($this->_id) {
			if(empty($this->_data))
				$this->getItem();	
			$query = $this->_db->getQuery(true);
			$query->select( $this->_db->quoteName('id_atividade') );
			$query->from( $this->_db->quoteName('#__intranet_atividade_map') );
			$query->where($this->_db->quoteName('id_user') . ' = ' . $this->_db->quote( $this->_data->id_user ));
			$this->_db->setQuery($query);
			return 	$this->_db->loadResultArray();
		}			
		return array();
	}
			
	function getUfs() 
	{
		$query = $this->_db->getQuery(true);
		$query->select('id_estado as value, sigla_estado as text');
		$query->from('#__intranet_estado');
		$query->where('status_estado = 1');
		$query->order($this->_db->quoteName('ordering'));
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();	
	}
	
	function getEstadoCivil() 
	{
		$query = $this->_db->getQuery(true);
		$query->select('id_estado_civil as value, name_estado_civil as text');
		$query->from('#__intranet_estado_civil');
		$query->where('status_estado_civil = 1');
		$query->order($this->_db->quoteName('ordering'));
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();	
	}
	
	function getEstados() 
	{
		$query = $this->_db->getQuery(true);
		$query->select('id_estado as value, name_estado as text');
		$query->from('#__intranet_estado');
		$query->where('status_estado = 1');
		$query->order($this->_db->quoteName('ordering'));
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();	
	}
	
	function getCidades() 
	{
		if(empty($this->_data))
			$this->getItem();
			
		if ($this->_data->id_estado) 
		{
			$query = $this->_db->getQuery(true);
			$query->select('id_cidade as value, name_cidade as text');
			$query->from('#__intranet_cidade');
			$query->where('id_estado = '. $this->_db->quote( $this->_data->id_estado ));
			$query->where('status_cidade = 1');
			$query->order($this->_db->quoteName('ordering'));
			$this->_db->setQuery($query);
			return $this->_db->loadObjectList();	
		}
	}
	function getAddCidades() 
	{
		if(empty($this->_data))
			$this->getItem();
			
		if ($this->_data->add_id_estado) 
		{
			$query = $this->_db->getQuery(true);
			$query->select('id_cidade as value, name_cidade as text');
			$query->from('#__intranet_cidade');
			$query->where('id_estado = '. $this->_db->quote( $this->_data->add_id_estado ));
			$query->where('status_cidade = 1');
			$query->order($this->_db->quoteName('ordering'));
			$this->_db->setQuery($query);
			return $this->_db->loadObjectList();	
		}
	}
	function getCidadesNasceu() 
	{
		if(empty($this->_data))
			$this->getItem();
			
		if ($this->_data->naturalidade_uf_pf) 
		{
			$query = $this->_db->getQuery(true);
			$query->select('id_cidade as value, name_cidade as text');
			$query->from('#__intranet_cidade');
			$query->where('id_estado = '. $this->_db->quote( $this->_data->naturalidade_uf_pf ));
			$query->where('status_cidade = 1');
			$query->order($this->_db->quoteName('ordering'));
			$this->_db->setQuery($query);
			return $this->_db->loadObjectList();	
		}
	}
	
	function getClubes()
	{
		$query = $this->_db->getQuery(true);
		$query->select( $this->_db->quoteName('id') . ' AS value, ' . $this->_db->quoteName('name') . ' AS text' );	
		
		$query->from($this->_db->quoteName('#__intranet_associado'));
		$query->innerJoin($this->_db->quoteName('#__users').' U ON ('.$this->_db->quoteName('U.id').'='.$this->_db->quoteName('id_user').')');
		$query->innerJoin($this->_db->quoteName('#__intranet_pj') . ' AS Clube USING ('.$this->_db->quoteName('id_user').')');
		$query->order( $this->_db->quoteName('name') );
		
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList(); 
	}
	
	function getAtividades()
	{
		if(empty($this->_data))
			$this->getItem();
		
		$query = $this->_db->getQuery(true);
		$query->select( $this->_db->quoteName('id_atividade') . ' AS value, ' . $this->_db->quoteName('name_atividade') . ' AS text' );	
		$query->from( $this->_db->quoteName('#__intranet_atividade') );
		//$query->innerJoin( $this->_db->quoteName('#__ranking_profile') . 'ON('. $this->_db->quoteName('id').'='. $this->_db->quoteName('id_user').')' );
		
		$query->where( $this->_db->quoteName('tipo_associado') .  '=' . $this->_db->quote($this->_data->id_tipo) );

		$query->order( $this->_db->quoteName('name_atividade') );
		
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList(); 
	}
	
	function getPagamentos() 
	{
		if(empty($this->_data))
			$this->getItem();
			
		if ($this->_data->id_user) 
		{
			$query = $this->_db->getQuery(true);		
			$query->select($this->_db->quoteName(  array('id_pagamento',
														 'vencimento_pagamento',
														 'baixa_pagamento',
														 'status_pagamento',
														 'valor_pagamento',
														 'valor_pago_pagamento',
														 'name_anuidade',
														 'name_pagamento_metodo',
														 )));	
														 
	
			$query->from($this->_db->quoteName('#__intranet_pagamento'));
			$query->innerJoin($this->_db->quoteName('#__intranet_pagamento_metodo') . ' USING(' . $this->_db->quoteName('id_pagamento_metodo'). ')');
			$query->innerJoin($this->_db->quoteName('#__intranet_anuidade') . ' USING(' . $this->_db->quoteName('id_anuidade'). ')');
		
			$query->where( $this->_db->quoteName('id_user') . '=' . $this->_db->quote( $this->_data->id_user ) );
			
			$query->order($this->_db->quoteName('id_pagamento') . ' DESC');
			$this->_db->setQuery($query);
			return $this->_db->loadObjectList();	
		}

	}
	
	function store() 
	{

		$this->addTablePath(JPATH_ADMINISTRATOR.'/tables');
		$data = JRequest::get( 'post' );
		
		if(empty($this->_data))
			$this->getItem();
		
		$pk		= (!empty($data['id_user'])) ? $data['id_user'] : 0;
		$dataUser = array();
		$dataUser['name'] = $data['name'];
		$dataUser['email'] = $data['email'];		
		
		if($data['status_associado']==0):
			$dataUser['block'] = 1;
		elseif(!empty($this->_data) && !empty($this->_data->validate_associado) && JFactory::getDate($this->_data->validate_associado, $this->_siteOffset)->toFormat('%Y-%m-%d', true) >= JFactory::getDate('now', $this->_siteOffset)->toFormat('%Y-%m-%d', true) ):
			$dataUser['block'] = 0;
		else:
			$dataUser['block'] = 1;
		endif;
		
		if(!empty($data['id_user'])):
			$user	= JUser::getInstance($data['id_user']);
			if ( $user->get('name') != $data['name'] || $user->get('email') != $data['email'] || $user->get('block') != $dataUser['block'] ):
				if (!$user->bind($dataUser)):
					$this->setError($user->getError());
					return false;
				endif;
				
				if (!$user->save()):
					$this->setError($user->getError());
					return false;
				endif;
			endif;
		else:
			$dataUser = array();
			$dataUser['name'] = $data['name'];
			$dataUser['email'] = $data['email'];
			$dataUser['username'] = preg_replace("/[^0-9]/", "", $this->_doc);
			$dataUser['password'] = substr( preg_replace("/[^0-9]/", "", $this->_doc), 0, 7); 
			$dataUser['block'] = 1;	
			$dataUser['sendEmail'] = 1;
			
			if($data['id_tipo'] == 0):
				$dataUser['groups'] = array(2);
			else:
				$dataUser['groups'] = array(2,17);
			endif;
			$user = new JUser;
			$dataU = (array)$dataUser;
	
			if (!$user->bind($dataU)) {
				return false;
			}
			
			if (!$user->save()) {
				return false;
			}
			$data['id_user'] = $user->get('id');
		endif;
						
		unset($dataUser);

		if($data['id_tipo'] == 0):
			$row = $this->getTable('pf');
			$image = JRequest::getVar( 'image_pf_new', '', 'files', 'array' );
			$_path = JPATH_MEDIA.DS.'images'.DS.'avatar';
			$key = 'pf';
			$key_image = 'image_pf';
			$id_load = $data['id_pf'];
			if(!isset($data['cpf_pf']))
				$data['cpf_pf'] = $this->_doc;
				
			$data['status_pf'] = '1';

			$data['observacao_pf'] = JRequest::getVar('observacao_pf', null, 'default', 'none', JREQUEST_ALLOWHTML);
			

			switch($data['tipo_socio']) {

				case '0': 
					$data['compressed_air_pf'] = '0';
					$data['copa_brasil_pf'] = '0';
				break;
				case '1': 
					$data['compressed_air_pf'] = '1';
					$data['copa_brasil_pf'] = '0';
				break;				
				case '2': 
					$data['compressed_air_pf'] = '0';
					$data['copa_brasil_pf'] = '1';
				break;
			}




			if(!empty($data['data_nascimento_pf']))
			{
				$dataaTmp = explode(" ",$data['data_nascimento_pf']);
				$data['data_nascimento_pf'] = implode("-",array_reverse(explode("/", $dataaTmp[0])));
				$data['data_nascimento_pf'] = JFactory::getDate($data['data_nascimento_pf'], $this->_siteOffset)->toFormat('%Y-%m-%d', true);
			}
			else
				$data['data_nascimento_pf'] = NULL;
	
			if(!empty($data['data_expedicao_pf']))
			{
				$dataaTmp = explode(" ",$data['data_expedicao_pf']);
				$data['data_expedicao_pf'] = implode("-",array_reverse(explode("/", $dataaTmp[0])));
				$data['data_expedicao_pf'] = JFactory::getDate($data['data_expedicao_pf'], $this->_siteOffset)->toFormat('%Y-%m-%d', true);
			}
			else
				$data['data_expedicao_pf'] = NULL;
			
			
			if(!empty($data['vencr_pf']))
			{
				$dataaTmp = explode(" ",$data['vencr_pf']);
				$data['vencr_pf'] = implode("-",array_reverse(explode("/", $dataaTmp[0])));
				$data['vencr_pf'] = JFactory::getDate($data['vencr_pf'], $this->_siteOffset)->toFormat('%Y-%m-%d', true);
			}
			else
				$data['vencr_pf'] = NULL;
				
			if($data['id_clube']===''):
				$data['id_clube'] = NULL;	
			endif;
		else:
			$row = $this->getTable('pj');
			$image = JRequest::getVar( 'logo_pj_new', '', 'files', 'array' );
			$_path = JPATH_MEDIA.DS.'images'.DS.'logos';
			$key = 'pj';
			$key_image = 'logo_pj';
			$id_load = $data['id_pj'];
			if(!isset($data['cnpj_pj']))
				$data['cnpj_pj'] = $this->_doc;
				
			$data['status_pj'] = '1';
			$data['observacao_pj'] = JRequest::getVar('observacao_pj', null, 'default', 'none', JREQUEST_ALLOWHTML);
	
			if(!empty($data['fundacao_pj']))
			{
				$dataaTmp = explode(" ",$data['fundacao_pj']);
				$data['fundacao_pj'] = implode("-",array_reverse(explode("/", $dataaTmp[0])));
				$data['fundacao_pj'] = JFactory::getDate($data['fundacao_pj'], $this->_siteOffset)->toFormat('%Y-%m-%d', true);
			}
			else
				$data['fundacao_pj'] = NULL;
				
			if(!empty($data['vencr_pj']))
			{
				$dataaTmp = explode(" ",$data['vencr_pj']);
				$data['vencr_pj'] = implode("-",array_reverse(explode("/", $dataaTmp[0])));
				$data['vencr_pj'] = JFactory::getDate($data['vencr_pj'], $this->_siteOffset)->toFormat('%Y-%m-%d', true);
			}
			else
				$data['vencr_pj'] = NULL;	
			
		endif;

		$data['block_'.$key] = 0;
		
		if (!JFolder::exists($_path))
		{
			$_folder_permissions = "0755";
			$_folder_permissions = octdec((int)$_folder_permissions);
			JFolder::create($_path, $_folder_permissions);
			$buffer = '<html><body bgcolor="#FFFFFF"></body></html>';
			JFile::write( $_path . DS. 'index.html', $buffer ); 
		}
		
		$key_remove_image = 'remove_' . $key_image;
		if(isset($data[$key_remove_image]))
		{
			if ( JFile::exists( $_path . DS .$data[$key_image] ) )
				JFile::delete( $_path . DS . $data[$key_image] );	
			
			$data[$key_image] = NULL;	
		}
		elseif(isset($image['name']) && $image['name'] != '') {   
			$ext = strtolower( JFile::getExt($image['name']) );
			$name =  md5(uniqid());	
			$thumb = $name .'.'. $ext;		

			if ( JFile::exists( $_path . DS .$thumb ) )
				JFile::delete( $_path . DS . $thumb );
				
			if (!JFile::upload($image['tmp_name'], $_path .DS. $thumb)) 	
				return false;
			
			if ( JFile::exists( $_path . DS .$data[$key_image] ) )
				JFile::delete( $_path . DS . $data[$key_image] );

			$data[$key_image] = $thumb;
		}
					
		//salva data se for novo
		if($id_load>0) {
			$row->load($id_load);
			$data['update_' . $key] = JFactory::getDate('now', $this->_siteOffset)->toISO8601(true);
			$data['user_update_' . $key] = $this->_userAdmin;			
		}
		else {
			$data['register_' . $key] = JFactory::getDate('now', $this->_siteOffset)->toISO8601(true);
			$data['user_register_' . $key] = $this->_userAdmin;
		}
		
		if ( !$row->bind($data)) {
			return false;	
		}
		
		if(isset($data[$key_remove_image]))
			$row->$key_image = NULL;
			
		if($data['id_tipo'] == 0){

			if(!$data['vencr_pf']) {
				$row->vencr_pf = NULL;
			}
			
			if(!$data['data_nascimento_pf']) {
				$row->data_nascimento_pf = NULL;
			}
			
			if(!$data['data_expedicao_pf']) {
				$row->data_expedicao_pf = NULL;
			}
			
			if($data['id_clube']===NULL) {
				$row->id_clube = NULL;
			}		
		}
		else{
			if(!$data['fundacao_pj']) {
				$row->fundacao_pj = NULL;
			}
			
			if(!$data['vencr_pj']) {
				$row->vencr_pj = NULL;
			}
		}
		
		if ( !$row->check($data)) {
			return false;	
		}	
			
		if ( !$row->store(TRUE) ) {	
			return false;	
		}
				
		$row = $this->getTable('associado');
		if($this->_id) {
			$row->load($this->_id);
		}
		else {
			$data['cadastro_associado'] = JFactory::getDate('now', $this->_siteOffset)->toISO8601(true);
			$data['status_associado'] = 1;
		}
		
		if ( !$row->bind($data)) {
			return false;	
		}
		
		if ( !$row->check($data)) {
			return false;	
		}	
			
		if ( !$row->store($data) ) {	
			return false;	
		}

		jimport('joomla.log.log');
		JLog::addLogger(array( 'text_file' => 'log.associado.php'));
		
		if($this->_id):
			$row->checkin($this->_id);
			JLog::add($this->_user->get('id') . JText::_('		Associado Editado -  idAssoc('.$this->_id.')'), JLog::INFO, 'associado');
		else:
			$this->setId( $row->get('id_associado') ); 	
			JLog::add($this->_user->get('id') . JText::_('		Associado Cadastrado -  idAssoc('.$this->_id.')'), JLog::INFO, 'associado');
		endif;
		
		JRequest::setVar( 'cid', $this->_id );
		
		// store Map Clube
		$query = $this->_db->getQuery(true);
		$query->delete($this->_db->quoteName('#__intranet_atividade_map'));
		$query->where($this->_db->quoteName('id_user') . ' = ' . $this->_db->quote($data['id_user']));
		$this->_db->setQuery($query);
		$this->_db->execute();
	
		$query = $this->_db->getQuery(true);
		$query->insert( $this->_db->quoteName('#__intranet_atividade_map') );
		$query->columns($this->_db->quoteName(array('id_user', 'id_atividade')));	
	
		foreach ( $data['id_atividade'] as $id_atividade) {
			$values = array($this->_db->quote($data['id_user']), 
							$this->_db->quote($id_atividade));
							
			$query->values(implode(',', $values));
		}
		
		$this->_db->setQuery($query);
		$this->_db->query();

		
		
		return true;
	}
	
	function isCheckedOut()
	{		
		$this->addTablePath(JPATH_ADMINISTRATOR.'/tables');
	    $row = $this->getTable('associado');
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
		$this->addTablePath(JPATH_ADMINISTRATOR.'/tables');
	    $row = $this->getTable('associado');
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
		$this->addTablePath(JPATH_ADMINISTRATOR.'/tables');
	    $row = $this->getTable('associado');
		
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