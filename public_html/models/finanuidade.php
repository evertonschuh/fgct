<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');


class IntranetModelFinAnuidade extends JModel 
{

	var $_id = null;
	var $_data = null;
	var $_db = null;
	var $_app = null;
	var $_user = null;
	var $_isRoot = null;
	var $_userLogged = null;
	var $_userAdmin = null;
	var $_siteOffset = null;
	
	protected $helpURL;

	protected $_cache;
	
	
	function __construct()
	{
		parent::__construct();

		$this->_app 		= JFactory::getApplication(); 
		$this->_db			= JFactory::getDBO();
		$this->_user		= JFactory::getUser();
		
		$this->_siteOffset 	= $this->_app->getCfg('offset');
		$this->_isRoot		= $this->_user->get('isRoot');	
		$this->_userAdmin	= $this->_user->get('id'); 	
			

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
				$link = 'index.php?view=finanuidades';
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
			$query->from('#__intranet_anuidade');
			$query->where($this->_db->quoteName('id_anuidade').'='.$this->_db->quote( $this->_id ) );
			$this->_db->setQuery($query);
			
			if( !(boolean) $this->_data = $this->_db->loadObject()) {;
			
				$this->addTablePath(JPATH_ADMINISTRATOR.'/tables');
				$this->_data = $this->getTable('anuidade');
			}
			
		}
		return $this->_data;
	}
	
	function getNewYear()
	{	
		$query = $this->_db->getQuery(true);
		$query->select('MAX(ano_anuidade)');
		$query->from('#__intranet_anuidade');
		$query->where($this->_db->quoteName('status_anuidade').'='.$this->_db->quote( '1' ) );
		$this->_db->setQuery($query);
		return $this->_db->loadResult();

	}
	
	function store()
	{

		$this->addTablePath(JPATH_ADMINISTRATOR.'/tables');
	    $row = $this->getTable('anuidade');
		$data = JRequest::get( 'post' );
		
		if($this->_id)
			$row->load($this->_id);
			
		$dataaTmp = explode(" ",$data['validate_anuidade']);
		$data['validate_anuidade'] = implode("-",array_reverse(explode("/", $dataaTmp[0]))) .' '. $dataaTmp[1];
		$data['validate_anuidade'] = JFactory::getDate($data['validate_anuidade'], $this->_siteOffset)->toFormat('%Y-%m-%d', true);
		
		
		//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		// --------------------------------------------------------------------------  Atleta Novo  -------------------------------------------------------------------------------
		//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		$data['valor_atleta_novo_anuidade'] =  (float) str_replace(',', '.', str_replace('.', '', str_replace('R$ ', '', $data['valor_atleta_novo_anuidade'])));			//-----
		//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		
		
		//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		// ------------------------------------------------------------------------  Atleta Período 1  ----------------------------------------------------------------------------
		//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		$data['valor_atleta_periodo1_anuidade'] =  (float) str_replace(',', '.', str_replace('.', '', str_replace('R$ ', '', $data['valor_atleta_periodo1_anuidade'])));	//-----
		$dataaTmp = explode(" ",$data['vencimento_atleta_periodo1_anuidade']);																								//-----
		$data['vencimento_atleta_periodo1_anuidade'] = implode("-",array_reverse(explode("/", $dataaTmp[0]))) .' '. $dataaTmp[1];											//-----
		$data['vencimento_atleta_periodo1_anuidade'] = JFactory::getDate($data['vencimento_atleta_periodo1_anuidade'], $this->_siteOffset)->toFormat('%Y-%m-%d', true);			//-----
		//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		
		
		//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		// ------------------------------------------------------------------------  Atleta Período 2  ----------------------------------------------------------------------------
		//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		if(!empty($data['valor_atleta_periodo2_anuidade']))																													//-----
			$data['valor_atleta_periodo2_anuidade'] =  (float) str_replace(',', '.', str_replace('.', '', str_replace('R$ ', '', $data['valor_atleta_periodo2_anuidade'])));//-----
		else																																								//-----
			$data['valor_atleta_periodo2_anuidade'] = NULL;																													//-----
																																											//-----
		if(!empty($data['vencimento_atleta_periodo2_anuidade'])) {																											//-----
			$dataaTmp = explode(" ",$data['vencimento_atleta_periodo2_anuidade']);																							//-----
			$data['vencimento_atleta_periodo2_anuidade'] = implode("-",array_reverse(explode("/", $dataaTmp[0]))) .' '. $dataaTmp[1];										//-----
			$data['vencimento_atleta_periodo2_anuidade'] = JFactory::getDate($data['vencimento_atleta_periodo2_anuidade'], $this->_siteOffset)->toFormat('%Y-%m-%d', true);		//-----
		}																																									//-----
		else																																								//-----
			$data['vencimento_atleta_periodo2_anuidade'] = NULL;																											//-----
		//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		
			
		//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		// ------------------------------------------------------------------------  Atleta Período 3  ----------------------------------------------------------------------------
		//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		if(!empty($data['valor_atleta_periodo3_anuidade']))																													//-----
			$data['valor_atleta_periodo3_anuidade'] =  (float) str_replace(',', '.', str_replace('.', '', str_replace('R$ ', '', $data['valor_atleta_periodo3_anuidade'])));//-----
		else																																								//-----
			$data['valor_atleta_periodo3_anuidade'] = NULL;																													//-----
																																											//-----
		if(!empty($data['vencimento_atleta_periodo3_anuidade'])) {																											//-----
			$dataaTmp = explode(" ",$data['vencimento_atleta_periodo3_anuidade']);																							//-----
			$data['vencimento_atleta_periodo3_anuidade'] = implode("-",array_reverse(explode("/", $dataaTmp[0]))) .' '. $dataaTmp[1];										//-----
			$data['vencimento_atleta_periodo3_anuidade'] = JFactory::getDate($data['vencimento_atleta_periodo3_anuidade'], $this->_siteOffset)->toFormat('%Y-%m-%d', true);		//-----
		}																																									//-----
		else																																								//-----
			$data['vencimento_atleta_periodo3_anuidade'] = NULL;																											//-----
		//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
			
			
		//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		// ------------------------------------------------------------------------  Atleta Período 4  ----------------------------------------------------------------------------
		//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		if(!empty($data['valor_atleta_periodo4_anuidade']))																													//-----
			$data['valor_atleta_periodo4_anuidade'] =  (float) str_replace(',', '.', str_replace('.', '', str_replace('R$ ', '', $data['valor_atleta_periodo4_anuidade'])));//-----
		else																																								//-----
			$data['valor_atleta_periodo4_anuidade'] = NULL;																													//-----
																																											//-----
		if(!empty($data['vencimento_atleta_periodo4_anuidade'])) {																											//-----
			$dataaTmp = explode(" ",$data['vencimento_atleta_periodo4_anuidade']);																							//-----
			$data['vencimento_atleta_periodo4_anuidade'] = implode("-",array_reverse(explode("/", $dataaTmp[0]))) .' '. $dataaTmp[1];										//-----
			$data['vencimento_atleta_periodo4_anuidade'] = JFactory::getDate($data['vencimento_atleta_periodo4_anuidade'], $this->_siteOffset)->toFormat('%Y-%m-%d', true);		//-----
		}																																									//-----
		else																																								//-----
			$data['vencimento_atleta_periodo4_anuidade'] = NULL;																											//-----
		//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------				
		
		
		
		
		//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		// --------------------------------------------------------------------------  Clube Novo  --------------------------------------------------------------------------------
		//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		$data['valor_clube_novo_anuidade'] =  (float) str_replace(',', '.', str_replace('.', '', str_replace('R$ ', '', $data['valor_clube_novo_anuidade'])));				//-----
		//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		
		
		//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		// ------------------------------------------------------------------------  Clube Período 1  -----------------------------------------------------------------------------
		//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		$data['valor_clube_periodo1_anuidade'] =  (float) str_replace(',', '.', str_replace('.', '', str_replace('R$ ', '', $data['valor_clube_periodo1_anuidade'])));		//-----
		$dataaTmp = explode(" ",$data['vencimento_clube_periodo1_anuidade']);																								//-----
		$data['vencimento_clube_periodo1_anuidade'] = implode("-",array_reverse(explode("/", $dataaTmp[0]))) .' '. $dataaTmp[1];											//-----
		$data['vencimento_clube_periodo1_anuidade'] = JFactory::getDate($data['vencimento_clube_periodo1_anuidade'], $this->_siteOffset)->toFormat('%Y-%m-%d', true);				//-----
		//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		
		
		//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		// ------------------------------------------------------------------------  Clube Período 2  -----------------------------------------------------------------------------
		//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		if(!empty($data['valor_clube_periodo2_anuidade']))																													//-----
			$data['valor_clube_periodo2_anuidade'] =  (float) str_replace(',', '.', str_replace('.', '', str_replace('R$ ', '', $data['valor_clube_periodo2_anuidade'])));	//-----
		else																																								//-----
			$data['valor_clube_periodo2_anuidade'] = NULL;																													//-----
																																											//-----
		if(!empty($data['vencimento_clube_periodo2_anuidade'])) {																											//-----
			$dataaTmp = explode(" ",$data['vencimento_clube_periodo2_anuidade']);																							//-----
			$data['vencimento_clube_periodo2_anuidade'] = implode("-",array_reverse(explode("/", $dataaTmp[0]))) .' '. $dataaTmp[1];										//-----
			$data['vencimento_clube_periodo2_anuidade'] = JFactory::getDate($data['vencimento_clube_periodo2_anuidade'], $this->_siteOffset)->toFormat('%Y-%m-%d', true);			//-----
		}																																									//-----
		else																																								//-----
			$data['vencimento_clube_periodo2_anuidade'] = NULL;																												//-----
		//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		
			
		//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		// ------------------------------------------------------------------------  Clube Período 3  -----------------------------------------------------------------------------
		//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		if(!empty($data['valor_clube_periodo3_anuidade']))																													//-----
			$data['valor_clube_periodo3_anuidade'] =  (float) str_replace(',', '.', str_replace('.', '', str_replace('R$ ', '', $data['valor_clube_periodo3_anuidade'])));	//-----
		else																																								//-----
			$data['valor_clube_periodo3_anuidade'] = NULL;																													//-----
																																											//-----
		if(!empty($data['vencimento_clube_periodo3_anuidade'])) {																											//-----
			$dataaTmp = explode(" ",$data['vencimento_clube_periodo3_anuidade']);																							//-----
			$data['vencimento_clube_periodo3_anuidade'] = implode("-",array_reverse(explode("/", $dataaTmp[0]))) .' '. $dataaTmp[1];										//-----
			$data['vencimento_clube_periodo3_anuidade'] = JFactory::getDate($data['vencimento_clube_periodo3_anuidade'], $this->_siteOffset)->toFormat('%Y-%m-%d', true);			//-----
		}																																									//-----
		else																																								//-----
			$data['vencimento_clube_periodo3_anuidade'] = NULL;																												//-----
		//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
			
			
		//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		// ------------------------------------------------------------------------  Clube Período 4  -----------------------------------------------------------------------------
		//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		if(!empty($data['valor_clube_periodo4_anuidade']))																													//-----
			$data['valor_clube_periodo4_anuidade'] =  (float) str_replace(',', '.', str_replace('.', '', str_replace('R$ ', '', $data['valor_clube_periodo4_anuidade'])));	//-----
		else																																								//-----
			$data['valor_clube_periodo4_anuidade'] = NULL;																													//-----
																																											//-----
		if(!empty($data['vencimento_clube_periodo4_anuidade'])) {																											//-----
			$dataaTmp = explode(" ",$data['vencimento_clube_periodo4_anuidade']);																							//-----
			$data['vencimento_clube_periodo4_anuidade'] = implode("-",array_reverse(explode("/", $dataaTmp[0]))) .' '. $dataaTmp[1];										//-----
			$data['vencimento_clube_periodo4_anuidade'] = JFactory::getDate($data['vencimento_clube_periodo4_anuidade'], $this->_siteOffset)->toFormat('%Y-%m-%d', true);			//-----
		}																																									//-----
		else																																								//-----
			$data['vencimento_clube_periodo4_anuidade'] = NULL;																												//-----
		//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------				
		
		if(empty($data['desconto_compressed_air_anuidade']))
			$data['desconto_compressed_air_anuidade'] = NULL;

		if(empty($data['desconto_copa_brasil_anuidade']))
			$data['desconto_copa_brasil_anuidade'] = NULL;

		if(empty($data['desconto_paratleta_anuidade']))
			$data['desconto_paratleta_anuidade'] = NULL;

		if(empty($data['desconto_maior_idade_anuidade']))
			$data['desconto_maior_idade_anuidade'] = NULL;
			
		if(empty($data['maior_idade_anuidade']))
			$data['maior_idade_anuidade'] = NULL;			
			
		if(empty($data['desconto_menor_idade_anuidade']))
			$data['desconto_menor_idade_anuidade'] = NULL;			
			
		if(empty($data['menor_idade_anuidade']))
			$data['menor_idade_anuidade'] = NULL;
	
		if(empty($data['desconto_sexo_anuidade']))
			$data['desconto_sexo_anuidade'] = NULL;
			
		if(empty($data['sexo_anuidade']))
			$data['sexo_anuidade'] = NULL;												
		
		if(empty($data['desconto_filho_anuidade']))
			$data['desconto_filho_anuidade'] = NULL;
			
		if(empty($data['idade_filho_anuidade']))
			$data['idade_filho_anuidade'] = NULL;	

		//cria a parta aula se ainda nao existe
		if ( !$row->bind($data)) 
		{
			$this->setError($this->_db->getErrorMsg());
			return false;	
		}

		if(!$data['valor_atleta_periodo2_anuidade'])
			$row->valor_atleta_periodo2_anuidade= NULL;
			
		if(!$data['vencimento_atleta_periodo2_anuidade'])
			$row->vencimento_atleta_periodo2_anuidade= NULL;

		if(!$data['valor_atleta_periodo3_anuidade'])
			$row->valor_atleta_periodo3_anuidade= NULL;
			
		if(!$data['vencimento_atleta_periodo3_anuidade'])
			$row->vencimento_atleta_periodo3_anuidade= NULL;
			
		if(!$data['valor_atleta_periodo4_anuidade'])
			$row->valor_atleta_periodo4_anuidade= NULL;
			
		if(!$data['vencimento_atleta_periodo4_anuidade'])
			$row->vencimento_atleta_periodo4_anuidade= NULL;
				
		if(!$data['valor_clube_periodo2_anuidade'])
			$row->valor_clube_periodo2_anuidade= NULL;
			
		if(!$data['vencimento_clube_periodo2_anuidade'])
			$row->vencimento_clube_periodo2_anuidade= NULL;

		if(!$data['valor_clube_periodo3_anuidade'])
			$row->valor_clube_periodo3_anuidade= NULL;
			
		if(!$data['vencimento_clube_periodo3_anuidade'])
			$row->vencimento_clube_periodo3_anuidade= NULL;
			
		if(!$data['valor_clube_periodo4_anuidade'])
			$row->valor_clube_periodo4_anuidade= NULL;
			
		if(!$data['vencimento_clube_periodo4_anuidade'])
			$row->vencimento_clube_periodo4_anuidade= NULL;

		if(!$data['desconto_compressed_air_anuidade'])
			$row->desconto_compressed_air_anuidade= NULL;

		if(!$data['desconto_copa_brasil_anuidade'])
			$row->desconto_copa_brasil_anuidade= NULL;
			
		if(!$data['desconto_paratleta_anuidade'])
			$row->desconto_paratleta_anuidade= NULL;
			
		if(!$data['desconto_maior_idade_anuidade'])
			$row->desconto_maior_idade_anuidade= NULL;
						
		if(!$data['maior_idade_anuidade'])
			$row->maior_idade_anuidade= NULL;			
			
		if(!$data['desconto_menor_idade_anuidade'])
			$row->desconto_menor_idade_anuidade= NULL;
						
		if(!$data['menor_idade_anuidade'])
			$row->menor_idade_anuidade= NULL;				
			
		if(!$data['desconto_sexo_anuidade'])
			$row->desconto_sexo_anuidade= NULL;
						
		if(!$data['sexo_anuidade'])
			$row->sexo_anuidade= NULL;		
			
		if(!$data['desconto_filho_anuidade'])
			$row->desconto_filho_anuidade= NULL;
						
		if(!$data['idade_filho_anuidade'])
			$row->idade_filho_anuidade= NULL;		
			
			
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

		jimport('joomla.log.log');
		JLog::addLogger(array( 'text_file' => 'log.finanuidades.php'));
		

		if($this->_id):
			$row->checkin($this->_id);
			JLog::add($this->_user->get('id') . JText::_('		Anuidade Editada -  idAnuidade('.$this->_id.')'), JLog::INFO, 'finanuidades');
		else:
			$this->setId( $row->get('id_cupom') ); 	
			JLog::add($this->_user->get('id') . JText::_('		Anuidade Cadastrada -  idAnuidade('.$this->_id.')'), JLog::INFO, 'finanuidades');
		endif;
		
		JRequest::setVar( 'cid', $this->_id );
		
		return true;
	
	}
	
	
	function isCheckedOut()
	{		
		$this->addTablePath(JPATH_ADMINISTRATOR.'/tables');
	    $row = $this->getTable('anuidade');
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
	    $row = $this->getTable('anuidade');
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
	    $row = $this->getTable('anuidade');
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


	function getMetodos()
	{
		$query = $this->_db->getQuery(true);
		$query->select('id_pagamento_metodo as value, name_pagamento_metodo as text');
		$query->from('#__intranet_pagamento_metodo');
		$query->where( $this->_db->quoteName('status_pagamento_metodo') . '=' . $this->_db->escape( '1' ));
		$query->order( $this->_db->quoteName('name_pagamento_metodo') );
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();
	}
	
	function setRemessa()
	{
		$options = JRequest::get( 'post' );
		$this->_app->setUserState( 'options', $options );
		$this->_app->setUserState( 'openModal', true );

		return true; 
	}
	
	
	function setRegistroRemessa()
	{
		//$this->_app->redirect(JRoute::_('index.php?view=finpagamentos&format=raw', false));
		//$options = JRequest::get( 'post' );
	//	$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );

		$data = $this->_app->getUserStateFromRequest( 'options' );


			$cids = implode( ',', $cid );
			$query = $this->_db->getQuery(true);
			
			if($registro==1):
				
				$fields[] = $this->_db->quoteName('registrado_pagamento') . ' = ' . $this->_db->quote( '1' ) ;
				$conditions = array( $this->_db->quoteName('id_pagamento') . ' IN (' . $cids . ')' );
				$query->update($this->_db->quoteName('#__intranet_pagamento'))->set($fields)->where($conditions);
				$this->_db->setQuery($query);
							
				if ( !$this->_db->query() ) 
					return false;

			
					
				$hoje = JFactory::getDate('now', $this->_siteOffset)->toFormat('%Y-%m-%d',true);	
				$dia = JFactory::getDate('now', $this->_siteOffset)->toFormat('%d',true);	
				$mes = JFactory::getDate('now', $this->_siteOffset)->toFormat('%m',true);	
				$query->clear();
				$query->select($this->_db->quoteName(array(	'id_pagamento_remessa')));
				$query->from($this->_db->quoteName('#__intranet_pagamento_remessa'));
				$query->where( $this->_db->quoteName('date_pagamento_remessa') . '=' . $this->_db->quote( $hoje ) );
				$this->_db->setQuery($query);
							
				$pagamentosRemessa = $this->_db->loadObjectList();
				$arquivos = count($pagamentosRemessa) + 1; 

				$data = array();
				$nameFile = 'CB' . sprintf('%02d', $dia) . sprintf('%02d', $mes) . sprintf('%02d', $arquivos);
				$data['name_pagamento_remessa'] = $nameFile;
				$data['id_pagamento_metodo'] = $id_pagamento_metodo;
				$data['id_pagamento_produto_tipo'] = 1;
				
				$params   = new JParameter();
				foreach( $cid as $i => $value ):
					$params->set($i, $value);
				endforeach;
				$data['pagamentos_pagamento_remessa'] = $params->toString();
				
				$data['date_pagamento_remessa'] = JFactory::getDate('now', $this->_siteOffset)->toFormat('%Y-%m-%d',true);
				$data['register_pagamento_remessa'] = JFactory::getDate('now', $this->_siteOffset)->toISO8601(true);
				$data['user_register_pagamento_remessa'] = $this->_user->get('id');
				
				
				$this->addTablePath(JPATH_ADMINISTRATOR.'/tables');
				$row = $this->getTable('pagamentoremessa');
				if ( !$row->bind($data)) 
					return false;	
				
				if ( !$row->check($data)) 
					return false;	
				
				if ( !$row->store($data) ) 
					return false;
				
				//sistema antigo //
				$id_pagamento_remessa = $row->get('id_pagamento_remessa');
				$this->_app->setUserState( 'id_pagamento_remessa', $id_pagamento_remessa );
				$this->_app->setUserState( 'name_pagamento_remessa', $nameFile );
				
				
			endif;		
							
			$this->_app->setUserState( 'cnba_type', $cnba_type );
			$this->_app->setUserState( 'type_assoc', $type_assoc );
			$this->_app->setUserState( 'status_assoc', $status_assoc );
			$this->_app->setUserState( 'id_pagamento_metodo', $id_pagamento_metodo );
			$this->_app->setUserState( 'openModal', true );


		return true; 
	}

	function getCnabNameFile()
	{
		$options = $this->_app->getUserStateFromRequest( 'options' );
		if(empty($options['name_pagamento_remessa']));
			$options['name_pagamento_remessa'] = 'Cnab' . (($options['cnba_type']==1) ? '400' : '240') . '_' . JFactory::getDate('now', $this->_siteOffset)->toFormat('%d-%m-%y', true);	
		
		return $options['name_pagamento_remessa'];
	}
	
	function getCnab()
	{
		
		$data = $this->_app->getUserStateFromRequest( 'options' );
		$pagamentos = $this->getAssociados($data);

		/*
		[tipo_cobranca] => 08 
		[id_pagamento_metodo] => 1 
		[cnba_type] => 0 
		[type_assoc] => Array ( [0] => 0 [1] => 1 ) 
		[status_assoc] => Array ( [0] => 0 ) 
		[instrucao] => 
		[tipo_executa] => 0 
		[id_anuidade] => 9 
		*/


		if(count($pagamentos)>0):	
			$options = array();
			$options['pagamentos'] = $pagamentos;			
			$options['tipo_cobranca'] = $data['tipo_cobranca'];
			$options['taxa_isento'] = true;
			$options['numero_sequencia'] = '0';
			$options['instrucao'] = $data['instrucao'];
			$options['data_geracao'] = JFactory::getDate('now', $this->_siteOffset)->toFormat('%d%m%y', true);
			$options['filename'] = 'CB' . sprintf('%02d', JFactory::getDate('now', $this->_siteOffset)->toFormat('%d', true)) . sprintf('%02d', JFactory::getDate('now', $this->_siteOffset)->toFormat('%m', true)) . '01'; 

			


			$query = $this->_db->getQuery(true);
			$query->select($this->_db->quoteName(array('module_pagamento_metodo')));
			$query->from($this->_db->quoteName('#__intranet_pagamento_metodo'));
			$query->where( $this->_db->quoteName('id_pagamento_metodo') . '=' . $this->_db->quote( $data['id_pagamento_metodo'] ) );
			$this->_db->setQuery($query);
		
			if( (boolean) $module_pagamento_metodo = $this->_db->loadResult()):

				require_once(JPATH_MODULE .DS. 'mod_' . $module_pagamento_metodo. DS. 'mod_' . $module_pagamento_metodo . '.php');
				
				$prefix  = 'Intranet';
				$type = $module_pagamento_metodo. 'Module';
				$type = preg_replace('/[^A-Z0-9_\.-]/i', '', $type);
				$modelClass = $prefix . ucfirst($type);
				
				if (!class_exists($modelClass))
					return false;
				


				$_module_pagament = new $modelClass();

				$this->_app->setUserState( 'options', NULL );



				
				if($data['cnba_type']==1){

					return $_module_pagament->setCnab400( $options );
				}
				else{
					return $_module_pagament->setCnab240( $options );

				}
				
					
			endif;
		endif;
		
	}


	function getAssociados($options = array())
	{
		

		$this->setId( (int) $options['id_anuidade'] );
		

		if (empty($this->_data))
			$this->getItem();
		
		$query = $this->_db->getQuery(true);
		$query->select( 'MAX(id_pagamento) AS id_pagamento');
		$query->from($this->_db->quoteName('#__intranet_pagamento'));
		$this->_db->setQuery($query);
		$id_pagamento = $this->_db->loadResult();
		
		$id_pagamento_test = $id_pagamento;
		
		$query->clear();
		$query->select('id_associado');
		$query->from($this->_db->quoteName('#__intranet_associado'));
		$query->innerJoin($this->_db->quoteName('#__intranet_pagamento').' USING ('.$this->_db->quoteName('id_user').')');
		$query->where( $this->_db->quoteName('id_anuidade') . '=' . $this->_db->quote($this->_id));
		//$query->where( $this->_db->quoteName('baixa_pagamento') . ' IS NOT NULL'  );
		$query->group($this->_db->quoteName('id_user'));
		
		$this->_db->setQuery($query);
		$associados = $this->_db->loadResultArray();



		$query->clear();
		$query->select($this->_db->quoteName(  array('id',
													 'name',
													 'Atleta.compressed_air_pf',
													 'Atleta.copa_brasil_pf',
													 'Atleta.pcd_pf',
													 'Atleta.sexo_pf',
													 'Atleta.data_nascimento_pf',
													 'Atleta.filho_pf',
													 'validate_associado',
													 )));	

		$query->select( 'IF( ISNULL(Atleta.cpf_pf), Clube.cnpj_pj, Atleta.cpf_pf) AS doc');
		$query->select( 'IF( ISNULL(Atleta.add_cep_pf), Clube.add_cep_pj, Atleta.add_cep_pf) AS cep');
		$query->select( 'IF( ISNULL(Atleta.add_logradouro_pf), Clube.add_logradouro_pj, Atleta.add_logradouro_pf) AS logradouro');
		$query->select( 'IF( ISNULL(Atleta.add_numero_pf), Clube.add_numero_pj, Atleta.add_numero_pf) AS numero');
		$query->select( 'IF( ISNULL(Atleta.add_complemento_pf), Clube.add_complemento_pj, Atleta.add_complemento_pf) AS complemento');
		$query->select( 'IF( ISNULL(Atleta.add_bairro_pf), Clube.add_bairro_pj, Atleta.add_bairro_pf) AS bairro');
		$query->select( 'IF( ISNULL(Atleta.tel_celular_pf), Clube.telefone_pj, Atleta.tel_celular_pf) AS telefone');
		$query->select('IF(ISNULL(ClubeCidade.name_cidade), AtletaCidade.name_cidade, ClubeCidade.name_cidade) AS name_cidade');	
		$query->select('IF(ISNULL(ClubeEstado.sigla_estado), AtletaEstado.sigla_estado, ClubeEstado.sigla_estado) AS sigla_estado');
	
		$query->select( 'name AS sacado');
		$query->select('IF(ISNULL(Clube.id_pj),0,1) AS types_id');
	
		$query->from($this->_db->quoteName('#__intranet_associado'));
		$query->innerJoin($this->_db->quoteName('#__users').' U ON ('.$this->_db->quoteName('U.id').'='.$this->_db->quoteName('id_user').')');
		
		$query->leftJoin($this->_db->quoteName('#__intranet_pf') . ' AS Atleta USING ('.$this->_db->quoteName('id_user').')');
		$query->leftJoin($this->_db->quoteName('#__intranet_pj') . ' AS Clube USING ('.$this->_db->quoteName('id_user').')');
		
		$query->leftJoin($this->_db->quoteName('#__intranet_estado').' AS AtletaEstado ON ('.$this->_db->quoteName('AtletaEstado.id_estado').'='.$this->_db->quoteName('Atleta.add_id_estado').')');
		$query->leftJoin($this->_db->quoteName('#__intranet_cidade').' AS AtletaCidade ON ('.$this->_db->quoteName('AtletaCidade.id_cidade').'='.$this->_db->quoteName('Atleta.add_id_cidade').')');
		
		$query->leftJoin($this->_db->quoteName('#__intranet_estado').' AS ClubeEstado ON ('.$this->_db->quoteName('ClubeEstado.id_estado').'='.$this->_db->quoteName('Clube.add_id_estado').')');
		$query->leftJoin($this->_db->quoteName('#__intranet_cidade').' AS ClubeCidade ON ('.$this->_db->quoteName('ClubeCidade.id_cidade').'='.$this->_db->quoteName('Clube.add_id_cidade').')');

		$query->where( $this->_db->quoteName('status_associado') . '=' . $this->_db->quote('1'));
		$query->where( $this->_db->quoteName('id_associado') . ' NOT IN (' . implode(',', $associados) .')');
		
		
        if (count($options['type_assoc']) < 2):
		 	if($options['type_assoc'][0]==1)
			 	$query->where( $this->_db->quoteName('Atleta.cpf_pf') . ' IS NULL' );
			else
				$query->where( $this->_db->quoteName('Clube.cnpj_pj') . ' IS NULL'  );
		endif;
		
        if (count($options['status_assoc']) < 2):
		 	if($options['status_assoc'][0]==1)
				$query->where( $this->_db->quoteName('validate_associado') . '<' . $this->_db->quote( JFactory::getDate('now', $this->_siteOffset)->toFormat('%Y-%m-%d', true) ));
			else
				$query->where( $this->_db->quoteName('validate_associado') . '>=' . $this->_db->quote( JFactory::getDate('now', $this->_siteOffset)->toFormat('%Y-%m-%d', true) ));
		endif;
		
		if($options['tipo_executa']==0):
			$query->order('rand()'); 
		endif;
		
		$this->_db->setQuery($query);
		$pagamentos = $this->_db->loadObjectList();
				
		$returnPagamentos = array();
		$returnPagamentosTest = array();
		$discount = array();
		


		foreach($pagamentos as $i => $pagamento):
			$pagamentos[$i]->id_anuidade = $this->_data->id_anuidade;
			$pagamentos[$i]->id_pagamento_metodo = $options['id_pagamento_metodo'];
			
			$pagamentos[$i]->produto = $this->_data->name_anuidade;
			$pagamentos[$i]->valor_pagamento = ($pagamento->types_id==1) ? $this->_data->valor_clube_periodo1_anuidade : $this->_data->valor_atleta_periodo1_anuidade;
			$pagamentos[$i]->vencimento_pagamento = ($pagamento->types_id==1) ? $this->_data->vencimento_clube_periodo1_anuidade : $this->_data->vencimento_atleta_periodo1_anuidade;
			$pagamentos[$i]->valor_desconto_pagamento = NULL;
			$pagamentos[$i]->vencimento_desconto_pagamento = NULL;
			$pagamentos[$i]->text_pagamento = 'Período 1';
			
	

			$desconto = 0;
			$tipo = ($pagamento->types_id==1) ? 1 : 0;	

			if($tipo <1):

				if($pagamento->compressed_air_pf == 1 && $this->_data->desconto_compressed_air_anuidade > 0):
					$tipo = 7;
					$desconto  = $this->_data->desconto_compressed_air_anuidade;

				elseif($pagamento->copa_brasil_pf == 1 && $this->_data->desconto_copa_brasil_anuidade > 0):
					$tipo = 8;
					$desconto  = $this->_data->desconto_copa_brasil_anuidade;
				else:
					if($pagamento->pcd_pf == 1 && $desconto < $this->_data->desconto_paratleta_anuidade):
						$tipo = 2;
						$desconto  = $this->_data->desconto_paratleta_anuidade;
					endif;

					if($pagamento->sexo_pf == $this->_data->sexo_anuidade && $desconto < $this->_data->desconto_sexo_anuidade):
						$tipo = 3;
						$desconto  = $this->_data->desconto_sexo_anuidade;
					endif;	

					
					if(JFactory::getDate($pagamento->data_nascimento_pf, $this->_siteOffset)->toFormat('%Y', true) <= JFactory::getDate($this->_data->validate_anuidade . ' - ' . ($this->_data->maior_idade_anuidade + 1) . ' year' ,$this->_siteOffset)->toFormat('%Y', true) && $desconto < $this->_data->desconto_maior_idade_anuidade):
						$tipo = 4;
						$desconto  = $this->_data->desconto_maior_idade_anuidade;
					endif; 
					
					if($pagamento->filho_pf == 1 && JFactory::getDate($pagamento->data_nascimento_pf, $this->_siteOffset)->toFormat('%Y', true) >= JFactory::getDate($this->_data->validate_anuidade . ' - ' . ($this->_data->idade_filho_anuidade) . ' year' ,$this->_siteOffset)->toFormat('%Y', true)  && $desconto < $this->_data->desconto_filho_anuidade):
						$desconto = $this->_data->desconto_filho_anuidade;
						$tipo = 5;
					endif;

					if(JFactory::getDate($pagamento->data_nascimento_pf, $this->_siteOffset)->toFormat('%Y', true) >= JFactory::getDate($this->_data->validate_anuidade . ' - ' . ($this->_data->menor_idade_anuidade) . ' year' ,$this->_siteOffset)->toFormat('%Y', true) && $desconto < $this->_data->desconto_menor_idade_anuidade):
						$tipo = 6;
						$desconto  = $this->_data->desconto_menor_idade_anuidade;
					endif;		

				endif;

				/*
				if($pagamento->filho_pf == 1): 
					if(JFactory::getDate($pagamento->data_nascimento_pf, $this->_siteOffset)->toFormat('%Y', true) >= JFactory::getDate($this->_data->validate_anuidade . ' - ' . ($this->_data->menor_idade_anuidade) . ' year' ,$this->_siteOffset)->toFormat('%Y', true) && $desconto < $this->_data->desconto_maior_idade_anuidade):
						$desconto = $this->_data->desconto_maior_idade_anuidade;
						$tipo = 6;
					elseif(JFactory::getDate($pagamento->data_nascimento_pf, $this->_siteOffset)->toFormat('%Y', true) > JFactory::getDate($this->_data->validate_anuidade . ' - ' . ($this->_data->idade_filho_anuidade) . ' year' ,$this->_siteOffset)->toFormat('%Y', true)  && $desconto < $this->_data->desconto_filho_anuidade):
						$desconto = $this->_data->desconto_filho_anuidade;
						$tipo = 5;
						
						echo JFactory::getDate($pagamento->data_nascimento_pf, $this->_siteOffset)->toFormat('%Y', true);
						
						//JFactory::getDate($pagamento->data_nascimento_pf, $this->_siteOffset)->toFormat('%Y', true);
						
						
					endif;
				endif;
					*/	
				if($desconto>0) 
					$pagamentos[$i]->valor_pagamento = $pagamentos[$i]->valor_pagamento - (($pagamentos[$i]->valor_pagamento*$desconto)/100);
	
				//echo $pagamento->name . ' => ' . $tipo .' - ' . $desconto . ' - '.  JFactory::getDate($pagamento->data_nascimento_pf, $this->_siteOffset)->toFormat('%Y', true) .  '<br/>';
			endif;


			if($desconto < 100):
				if($options['tipo_executa']==2)
					$id_pagamento = $this->storePagamento($pagamentos[$i]);
				else
					$id_pagamento++;
				
				$pagamentos[$i]->id_pagamento = $id_pagamento;
				$returnPagamentos[] = $pagamentos[$i];
				
				if(!isset($discount[$tipo]) || (isset($discount[$tipo]) && count($discount[$tipo])<2)):
					$id_pagamento_test++;
					$pagamentos[$i]->id_pagamento = $id_pagamento_test;
					$returnPagamentosTest[] = 	$pagamentos[$i];
					$discount[$tipo][] = 	$pagamentos[$i];
				endif;							
			endif;
			
		endforeach;

		//print_r($returnPagamentosTest);
		//exit;
		
		if($options['tipo_executa']>0):
			return $returnPagamentos;
		else:
			return $returnPagamentosTest;
		endif;
		
	}

	function storePagamento(stdClass $pagamento) 
	{
		$this->addTablePath(JPATH_ADMINISTRATOR.'/tables');
	    $row = $this->getTable('pagamento');
		
		$data['status_pagamento'] = 1;
		$data['id_anuidade'] = $pagamento->id_anuidade;
		$data['id_user'] = $pagamento->id;
		$data['id_pagamento_metodo'] = $pagamento->id_pagamento_metodo;
		$data['registrado_pagamento'] = 1;
		$data['taxa_pagamento'] = 0;
		$data['carteira_pagamento'] = 1;
		$data['text_pagamento'] = 'Período 1';
		$data['cadastro_pagamento'] = JFactory::getDate('now', $this->_siteOffset)->toFormat('%Y-%m-%d', true);
		$data['vencimento_desconto_pagamento'] = NULL;
		$data['valor_pagamento'] =  $pagamento->valor_pagamento;
		$data['vencimento_pagamento'] = $pagamento->vencimento_pagamento;
		$data['valor_desconto_pagamento'] = NULL;
		$data['baixa_pagamento'] = NULL;
		$data['valor_pago_pagamento'] = NULL;
			
		//cria a parta aula se ainda nao existe
		if ( !$row->bind($data)) 
			return false;	

		if(!$data['baixa_pagamento'])
			$row->baixa_pagamento= NULL;
		
		if(!$data['valor_pago_pagamento'])
			$row->valor_pago_pagamento= NULL;
			
		if(!$data['vencimento_desconto_pagamento'])
			$row->vencimento_desconto_pagamento= NULL;
			
		if(!$data['valor_desconto_pagamento'])
			$row->valor_desconto_pagamento= NULL;
			
		if ( !$row->check($data)) 
			return false;	
			
		if ( !$row->store(TRUE) ) 
			return false;	
			
		return $row->get('id_pagamento');
	}


}




