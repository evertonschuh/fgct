<?php
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_THEMES_NATIVE .DS. 'includes' .DS. 'trataimagem.php');

jimport( 'joomla.application.component.model' );

class IntranetModelUpdate extends JModel {

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
		
		$this->_app 	= JFactory::getApplication(); 
		$this->_db		= JFactory::getDBO();
		$this->_user	= JFactory::getUser();
		
		$this->_isRoot		= $this->_user->get('isRoot');	
		$this->_userAdmin	= $this->_user->get('id');
		
		$this->_trataImagem = new TrataImagem();
		$this->_session_id = JFactory::getSession()->getId();
		
		$this->_siteOffset = $this->_app->getCfg('offset');
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
	
	function getItem()
	{
		if (empty($this->_data)):

			
			$query = $this->_db->getQuery(true);	
			
			$query->select($this->_db->quoteName(  array('id_pf_update',
														 'status_pf_update',													 
														 'User.name',																 
														 'name_pf_update',																 
														 'User.email',																 
														 'email_pf_update',													 
														 'cpf_pf',
														 'cpf_pf_update',
														 'compressed_air_pf',
														 'compressed_air_pf_update',
														 'rg_pf',
														 'rg_pf_update',
														 'data_expedicao_pf',
														 'data_expedicao_pf_update',
														 'orgao_expeditor_pf',
														 'orgao_expeditor_pf_update',
														 'uf_orga_expeditor_pf',
														 'uf_orga_expeditor_pf_update',
														 'sexo_pf',
														 'sexo_pf_update',
														 'data_nascimento_pf',
														 'data_nascimento_pf_update',
														 'tsangue_pf',
														 'tsangue_pf_update',
														 'npai_pf',
														 'npai_pf_update',
														 'nmae_pf',
														 'nmae_pf_update',
														 'nacionalidade_pf',
														 'nacionalidade_pf_update',
														 'name_estado_civil',
														 'naturalidade_uf_pf',
														 'naturalidade_uf_pf_update',
														 'naturalidade_pf',
														 'naturalidade_pf_update',
														 'profissao_pf',
														 'profissao_pf_update',
														 'tel_celular_pf',
														 'tel_celular_pf_update',
														 'tel_residencial_pf',
														 'tel_residencial_pf_update',
														 'cep_pf',
														 'cep_pf_update',
														 'logradouro_pf',
														 'logradouro_pf_update',
														 'numero_pf',
														 'numero_pf_update',
														 'complemento_pf',
														 'complemento_pf_update',
														 'bairro_pf',	
														 'bairro_pf_update',
														 'add_cep_pf',
														 'add_cep_pf_update',
														 'add_logradouro_pf',
														 'add_logradouro_pf_update',
														 'add_numero_pf',
														 'add_numero_pf_update',
														 'add_complemento_pf',
														 'add_complemento_pf_update',
														 'add_bairro_pf',		
														 'add_bairro_pf_update',										 
														 'pcd_pf',
														 'pcd_pf_update',
														 'confederado_pf',
														 'confederado_pf_update',
														 'filho_pf',
														 'filho_pf_update',
														 'numcr_pf',
														 'numcr_pf_update',
														 'vencr_pf',
														 'vencr_pf_update',
														 'stacr_pf',
														 'stacr_pf_update',	
														 'id_pf',
														 'id_associado',
														 '#__intranet_pf_update.checked_out',
														 '#__intranet_pf_update.checked_out_time',
														 )));	

			
			$query->select('IF(ISNULL(Atual.cpf_pf), \'Cadastro Novo\', \'Atualização Cadastral\') AS tipo_executa_pf_update');
			$query->select('Atual.id_estado_civil AS id_estado_civil');	
			$query->select('Atual.id_estado AS id_estado');
			$query->select('Atual.id_cidade AS id_cidade');
			$query->select('Atual.add_id_estado AS add_id_estado');
			$query->select('Atual.add_id_cidade AS add_id_cidade');
			$query->select('Atual.id_clube AS id_clube');
			$query->select('Atual.id_user AS id_user');
			$query->select('Clube.name AS name_clube');
			$query->select('UfRG.sigla_estado AS sigla_uf_orga_expeditor_pf');
			$query->select('EstNasc.name_estado AS estado_naturalidade_uf_pf');		
			$query->select('CidNasc.name_cidade AS cidade_naturalidade_pf');	
			$query->select('#__intranet_pf_update.id_estado_civil AS id_estado_civil_update');	
			$query->select('#__intranet_pf_update.id_estado AS id_estado_update');		
			$query->select('#__intranet_pf_update.id_cidade AS id_cidade_update');	
			$query->select('#__intranet_pf_update.add_id_estado AS add_id_estado_update');		
			$query->select('#__intranet_pf_update.add_id_cidade AS add_id_cidade_update');			
			$query->select('#__intranet_pf_update.id_clube AS id_clube_update');
			$query->select('EstEnd.name_estado AS name_estado');
			$query->select('CidEnd.name_cidade AS name_cidade');
			$query->select('AddEstEnd.name_estado AS add_name_estado');
			$query->select('AddCidEnd.name_cidade AS add_name_cidade');
			
			$query->from($this->_db->quoteName('#__intranet_pf_update'));
			$query->leftJoin($this->_db->quoteName('#__intranet_pf') . ' AS Atual ON ('.$this->_db->quoteName('cpf_pf').'='.$this->_db->quoteName('cpf_pf_update').')');
			$query->leftJoin($this->_db->quoteName('#__users').' AS User ON ('.$this->_db->quoteName('User.id').'='.$this->_db->quoteName('Atual.id_user').')');
			$query->leftJoin($this->_db->quoteName('#__intranet_associado').' AS Associado USING ('.$this->_db->quoteName('id_user').')');
			$query->leftJoin($this->_db->quoteName('#__intranet_estado_civil').' AS EstCiv ON ('.$this->_db->quoteName('EstCiv.id_estado_civil').'='.$this->_db->quoteName('Atual.id_estado_civil').')');
			$query->leftJoin($this->_db->quoteName('#__intranet_estado') . ' AS UfRG ON ('.$this->_db->quoteName('uf_orga_expeditor_pf').'='.$this->_db->quoteName('UfRG.id_estado').')');
			$query->leftJoin($this->_db->quoteName('#__intranet_estado') . ' AS EstNasc ON ('.$this->_db->quoteName('naturalidade_uf_pf').'='.$this->_db->quoteName('EstNasc.id_estado').')');
			$query->leftJoin($this->_db->quoteName('#__intranet_cidade') . ' AS CidNasc ON ('.$this->_db->quoteName('naturalidade_pf').'='.$this->_db->quoteName('CidNasc.id_cidade').')');
			$query->leftJoin($this->_db->quoteName('#__intranet_estado') . ' AS EstEnd ON ('.$this->_db->quoteName('Atual.id_estado').'='.$this->_db->quoteName('EstEnd.id_estado').')');
			$query->leftJoin($this->_db->quoteName('#__intranet_cidade') . ' AS CidEnd ON ('.$this->_db->quoteName('Atual.id_cidade').'='.$this->_db->quoteName('CidEnd.id_cidade').')');
			$query->leftJoin($this->_db->quoteName('#__intranet_estado') . ' AS AddEstEnd ON ('.$this->_db->quoteName('Atual.add_id_estado').'='.$this->_db->quoteName('AddEstEnd.id_estado').')');
			$query->leftJoin($this->_db->quoteName('#__intranet_cidade') . ' AS AddCidEnd ON ('.$this->_db->quoteName('Atual.add_id_cidade').'='.$this->_db->quoteName('AddCidEnd.id_cidade').')');
			$query->leftJoin($this->_db->quoteName('#__users').' AS Clube ON ('.$this->_db->quoteName('Clube.id').'='.$this->_db->quoteName('Atual.id_clube').')');
	
	
	
			$query->where($this->_db->quoteName('id_pf_update') . ' = ' . $this->_db->quote( $this->_id ));
			
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
			
			
			if($this->_data->status_pf_update>0):
				$query->clear();
				$fields = array($this->_db->quoteName('status_pf_update').'='.$this->_db->quote('0'));
				$conditions = array($this->_db->quoteName('id_pf_update').'='.$this->_db->quote($this->_id));
				$query->update($this->_db->quoteName('#__intranet_pf_update'))->set($fields)->where($conditions);
				$this->_db->setQuery($query);
				$this->_db->query();
			endif;
		endif;
		
		return $this->_data;
	}
	

	function getItemAtividades()
	{
		if(empty($this->_data))
			$this->getItem();	
					
		if(isset($this->_data->id_user)) {
			$query = $this->_db->getQuery(true);
			$query->select( $this->_db->quoteName('id_atividade') );
			$query->from( $this->_db->quoteName('#__intranet_atividade_map') );
			$query->where($this->_db->quoteName('id_user') . ' = ' . $this->_db->quote( $this->_data->id_user ));
			$this->_db->setQuery($query);
			return 	$this->_db->loadResultArray();
		}			
		return array();
	}
	
	function getItemAtividadesUpdate()
	{
		if(isset($this->_id )) {
			$query = $this->_db->getQuery(true);
			$query->select( $this->_db->quoteName('id_atividade') );
			$query->from( $this->_db->quoteName('#__intranet_pf_update_atividade_map') );
			$query->where($this->_db->quoteName('id_pf_update') . ' = ' . $this->_db->quote( $this->_id ));
			$this->_db->setQuery($query);
			return 	$this->_db->loadResultArray();
		}			
		return array();
	}
	
	function getAtividades()
	{
		if(empty($this->_data))
			$this->getItem();
		
		$query = $this->_db->getQuery(true);
		$query->select( $this->_db->quoteName('id_atividade') . ' AS value, ' . $this->_db->quoteName('name_atividade') . ' AS text' );	
		$query->from( $this->_db->quoteName('#__intranet_atividade') );
		$query->where( $this->_db->quoteName('tipo_associado') .  '=' . $this->_db->quote('0') );
		$query->order( $this->_db->quoteName('name_atividade') );
		
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList(); 
	}
	
	function getTrataImagem()
	{
		return $this->_trataImagem;
	}
	
	function getSession() 
	{
		return $this->_session_id;
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
			
		if (!empty($this->_data->id_estado) || !empty($this->_data->id_estado_update)) 
		{
			$query = $this->_db->getQuery(true);
			$query->select('id_cidade as value, name_cidade as text');
			$query->from('#__intranet_cidade');
			
			if (!empty($this->_data->id_estado_update))
				$query->where('id_estado = '. $this->_db->quote( $this->_data->id_estado_update ));
			else
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
			
		if (!empty($this->_data->add_id_estado) || !empty($this->_data->add_id_estado_update)) 
		{
			$query = $this->_db->getQuery(true);
			$query->select('id_cidade as value, name_cidade as text');
			$query->from('#__intranet_cidade');
			if (!empty($this->_data->add_id_estado_update))
				$query->where('id_estado = '. $this->_db->quote( $this->_data->add_id_estado_update ));
			else
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
			
		if (!empty($this->_data->naturalidade_uf_pf) || !empty($this->_data->naturalidade_uf_pf_update)) 
		{
			$query = $this->_db->getQuery(true);
			$query->select('id_cidade as value, name_cidade as text');
			$query->from('#__intranet_cidade');
			if (!empty($this->_data->naturalidade_uf_pf_update))
				$query->where('id_estado = '. $this->_db->quote( $this->_data->naturalidade_uf_pf_update ));
			else
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
	

	
	
	function store() 
	{

		$this->addTablePath(JPATH_ADMINISTRATOR.'/tables');
		$post = JRequest::get( 'post' );
		$data = array();
		
		
		if(empty($this->_data))
			$this->getItem();
			
		$data['id_user'] = $this->_data->id_user;
		$data['id_pf'] = $this->_data->id_pf;
		
		if(!empty($data['id_user']) || !empty( $post['id_pf'])):
		
			foreach($post['checkbox'] as $key => $value)
			if($value):
				$data[$key] = $post[$key.'_update'];
			endif;
		else:
		
			foreach($post as $key => $value)
				$data[str_replace('_update','',$key)] = $post[$key];
		
			$data['id_user'] = $this->_data->id_user;
			$data['id_pf'] = $this->_data->id_pf;		
		
		endif;

		
		$pk		= (!empty($data['id_user'])) ? $data['id_user'] : 0;
		$dataUser = array();
		$dataUser['name'] = $data['name_pf'];
		$dataUser['email'] = $data['email_pf'];		

    
        
		if(!empty($data['id_user'])):
			$user	= JUser::getInstance($data['id_user']);
			if ( isset($dataUser['name']) && $user->get('name') != $dataUser['name'] || isset($dataUser['email']) && $user->get('email') != $dataUser['email']):

                if(!isset($dataUser['name']) || empty($dataUser['name']))
                    unset($dataUser['name']);
        
                if(!isset($dataUser['email']) || empty($dataUser['email']))
                    unset($dataUser['email']);
        
        
                if (!$user->bind($dataUser)):
					$this->setError($user->getError());
					return false;
				endif;
				
        
				if (!$user->save($dataUser)):
					$this->setError($user->getError());
					return false;
				endif;
        
        
			endif;
		else:
        
			$data['status_associado']==1;
			$data['block_pf'] = 0;
			$dataUser = array();
			$dataUser['name'] = $data['name_pf'];
			$dataUser['email'] = $data['email_pf'];	
			$dataUser['username'] = preg_replace("/[^0-9]/", "", $data['cpf_pf']);
			$dataUser['password'] = substr( preg_replace("/[^0-9]/", "", $data['cpf_pf']), 0, 7); 
			$dataUser['block'] = 1;	
			$dataUser['sendEmail'] = 1;
			
			$dataUser['groups'] = array(2);

			$user = new JUser;
			$dataU = (array)$dataUser;
	
			if (!$user->bind($dataU)) 
				return false;

			if (!$user->save()) 
				return false;

			$data['id_user'] = $user->get('id');
		endif;
						
		unset($dataUser);


		$row = $this->getTable('pf');
		
		if(!empty($data['data_nascimento_pf']))
		{
			$dataaTmp = explode(" ",$data['data_nascimento_pf']);
			$data['data_nascimento_pf'] = implode("-",array_reverse(explode("/", $dataaTmp[0])));
			$data['data_nascimento_pf'] = JFactory::getDate($data['data_nascimento_pf'], $this->_siteOffset)->toFormat('%Y-%m-%d', true);
		}

		if(!empty($data['data_expedicao_pf']))
		{
			$dataaTmp = explode(" ",$data['data_expedicao_pf']);
			$data['data_expedicao_pf'] = implode("-",array_reverse(explode("/", $dataaTmp[0])));
			$data['data_expedicao_pf'] = JFactory::getDate($data['data_expedicao_pf'], $this->_siteOffset)->toFormat('%Y-%m-%d', true);
		}
		
		
		if(!empty($data['vencr_pf']))
		{
			$dataaTmp = explode(" ",$data['vencr_pf']);
			$data['vencr_pf'] = implode("-",array_reverse(explode("/", $dataaTmp[0])));
			$data['vencr_pf'] = JFactory::getDate($data['vencr_pf'], $this->_siteOffset)->toFormat('%Y-%m-%d', true);
		}
		
	
		//salva data se for novo
		if($data['id_pf']>0) {
			$row->load($data['id_pf']);
			$data['update_pf'] = JFactory::getDate('now', $this->_siteOffset)->toISO8601(true);
			$data['user_update_pf'] = $this->_userAdmin;			
		}
		else {
			$data['register_pf'] = JFactory::getDate('now', $this->_siteOffset)->toISO8601(true);
			$data['user_register_pf'] = $this->_userAdmin;
		}
		
		if ( !$row->bind($data))
			return false;	

		if ( !$row->check($data)) 
			return false;		
			
		if ( !$row->store(TRUE) ) 
			return false;	

				
		
		if(empty($this->_data->id_associado)) {
			$row = $this->getTable('associado');
			$data['cadastro_associado'] = JFactory::getDate('now', $this->_siteOffset)->toISO8601(true);
			$data['status_associado'] = 1;
			
			if ( !$row->bind($data)) 
				return false;	
			
			if ( !$row->check($data)) 
				return false;	
				
			if ( !$row->store($data) ) 
				return false;	
	
			jimport('joomla.log.log');
			JLog::addLogger(array( 'text_file' => 'log.associado.php'));
			$id_associado = $row->get('id_associado');

			JLog::add($this->_user->get('id') . JText::_('		Associado Cadastrado -  idAssoc('.$id_associado.')'), JLog::INFO, 'associado');
		}
		


		
		if(isset($data['id_atividade'])):
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
		endif;
		return true;
	}
		
	
	function isCheckedOut()
	{		
		$this->addTablePath(JPATH_ADMINISTRATOR.'/tables');
	    $row = $this->getTable('pfupdate');
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
	    $row = $this->getTable('pfupdate');
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
	    $row = $this->getTable('pfupdate');
		
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