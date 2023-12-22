<?php
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_THEMES_NATIVE .DS. 'includes' .DS. 'trataimagem.php');

jimport( 'joomla.application.component.model' );

class IntranetModelConveniado extends JModel {

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
				$link = 'index.php?view=conveniados';
				$this->_app->redirect($link, $msg, $tipo);
			}
		}
	}

	function setId( $id ) 
	{
		$this->_id		= $id;
		$this->_data	= null;
	}

	function testConveniado()
	{

		$data = JRequest::get( 'post' );

		$query = $this->_db->getQuery(true);			
		$query->select('id_conveniado');
		$query->from('#__intranet_conveniado');	
		$query->innerJoin($this->_db->quoteName('#__intranet_pf') . ' USING( ' . $this->_db->quoteName('id_user') . ')');
		$query->where($this->_db->quoteName('cpf_pf') . '=' . $this->_db->quote( $data['doc'] ));
		$this->_db->setQuery($query);
		if(!(boolean) $this->_db->loadResult()) 
			return true;
		
		return false;
		
	}

	
	function setDoc()
	{
		$data = JRequest::get( 'post' );
		$this->_doc = $data['doc'];
	}
	
	function getItem()
	{
		if (empty($this->_data)):
			$query = $this->_db->getQuery(true);	
			if(empty($this->_id)):
				if(!empty($this->_doc)):
				
					$query->select('*');
					$query->select('IF(ISNULL(id_pf),NULL,0) AS id_tipo');
					$query->from('#__users');	
					$query->leftJoin($this->_db->quoteName('#__intranet_pf') .'  ON ('.$this->_db->quoteName('id').'='.$this->_db->quoteName('#__intranet_pf.id_user').')');
								
					$query->where($this->_db->quoteName('#__intranet_pf.cpf_pf') . '=' . $this->_db->quote( $this->_doc ) );
					$this->_db->setQuery($query);

					if(!(boolean) $this->_data = $this->_db->loadObject()):
						$this->addTablePath(JPATH_ADMINISTRATOR.'/tables');
							$this->_data = $this->getTable('pf');
						
						$this->_data->id_tipo = 0;	
						$this->_data->name='';
						$this->_data->email='';
					endif;
					$this->_data->status_conveniado = 0;	
					$this->_data->doc = $this->_doc;
				endif;
			else:	
			
				$query->select('*');
				$query->select('IF(ISNULL(id_pf),NULL,0) AS id_tipo');
		
				
				$query->from('#__intranet_conveniado');	
				$query->innerJoin($this->_db->quoteName('#__intranet_pf') . ' USING( ' . $this->_db->quoteName('id_user') . ')');
				$query->leftJoin($this->_db->quoteName('#__users').'  ON ('.$this->_db->quoteName('id').'='.$this->_db->quoteName('id_user').')');
			
				$query->where( $this->_db->quoteName('id_conveniado') . '=' . $this->_db->escape( $this->_id ) );

				$this->_db->setQuery($query);
				$this->_data = $this->_db->loadObject();
				
			endif;
		endif;
		
		return $this->_data;
	}
	
	function getConvenios()
	{
		$query = $this->_db->getQuery(true);
		$query->select( $this->_db->quoteName('id_convenio') . ' AS value, ' . $this->_db->quoteName('name_convenio') . ' AS text' );	
		$query->from($this->_db->quoteName('#__intranet_convenio'));
		$query->order( $this->_db->quoteName('text') );
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList(); 
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
		
		if($data['status_conveniado']==0):
			$dataUser['block'] = 1;
		else:
			$dataUser['block'] = 0;
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
			$dataUser['password'] = substr( preg_replace("/[^0-9]/", "", $this->_doc), 0, 6); 
			//$dataUser['block'] = 1;	
			$dataUser['sendEmail'] = 1;
			
			$dataUser['groups'] = array(2);

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


		$row = $this->getTable('pf');
		$image = JRequest::getVar( 'image_pf_new', '', 'files', 'array' );
		$_path = JPATH_MEDIA.DS.'images' . DS . 'avatar';

		
		if(!isset($data['cpf_pf']))
			$data['cpf_pf'] = $this->_doc;
			
		$data['observacao_pf'] = JRequest::getVar('observacao_pf', null, 'default', 'none', JREQUEST_ALLOWHTML);
		
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
		
		if (!JFolder::exists($_path))
		{
			$_folder_permissions = "0755";
			$_folder_permissions = octdec((int)$_folder_permissions);
			JFolder::create($_path, $_folder_permissions);
			$buffer = '<html><body bgcolor="#FFFFFF"></body></html>';
			JFile::write( $_path . DS. 'index.html', $buffer ); 
		}
		

		if(isset($data['remove_image_pf']))
		{
			if ( JFile::exists( $_path . DS .$data['image_pf'] ) )
				JFile::delete( $_path . DS . $data['image_pf'] );	
			
			$data['image_pf'] = NULL;	
		}
		elseif(isset($image['name']) && $image['name'] != '') {   
			$ext = strtolower( JFile::getExt($image['name']) );
			$name =  md5(uniqid());	
			$thumb = $name .'.'. $ext;		

			if ( JFile::exists( $_path . DS .$thumb ) )
				JFile::delete( $_path . DS . $thumb );
				
			if (!JFile::upload($image['tmp_name'], $_path .DS. $thumb)) 	
				return false;
			
			if ( JFile::exists( $_path . DS .$data['image_pf'] ) )
				JFile::delete( $_path . DS . $data['image_pf'] );

			$data['image_pf'] = $thumb;
		}
					
		//salva data se for novo
		if($data['id_pf'] >0) {
			$row->load($data['id_pf']);
			$data['update_pf'] = JFactory::getDate('now', $this->_siteOffset)->toISO8601(true);
			$data['user_update_pf'] = $this->_userAdmin;			
		}
		else {
			$data['register_pf'] = JFactory::getDate('now', $this->_siteOffset)->toISO8601(true);
			$data['user_register_pf'] = $this->_userAdmin;
		}
		
		if ( !$row->bind($data)) {
			return false;	
		}
		
		if(isset($data['remove_image_pf']))
			$row->image_pf = NULL;
			
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
		
		if ( !$row->check($data)) {
			return false;	
		}	
			
		if ( !$row->store(TRUE) ) {	
			return false;	
		}
				
				
		$row = $this->getTable('conveniado');
		if($this->_id) {
			$row->load($this->_id);
		}
		else {
			$data['cadastro_conveniado'] = JFactory::getDate('now', $this->_siteOffset)->toISO8601(true);
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
		JLog::addLogger(array( 'text_file' => 'log.conveniado.php'));
		
		if($this->_id):
			$row->checkin($this->_id);
			JLog::add($this->_user->get('id') . JText::_('		Conveniado Editado -  idConv('.$this->_id.')'), JLog::INFO, 'conveniado');
		else:
			$this->setId( $row->get('id_conveniado') ); 	
			JLog::add($this->_user->get('id') . JText::_('		Conveniado Cadastrado -  idConv('.$this->_id.')'), JLog::INFO, 'conveniado');
		endif;
		
		JRequest::setVar( 'cid', $this->_id );
		
		
		
		return true;
	}
	
	function isCheckedOut()
	{		
		$this->addTablePath(JPATH_ADMINISTRATOR.'/tables');
	    $row = $this->getTable('conveniado');
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
	    $row = $this->getTable('conveniado');
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
	    $row = $this->getTable('conveniado');
		
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