<?php


include( 'joomla.inc.php' );

$obj = new IntranetAppCore();

class IntranetAppCore
{
	var $_db;
	var $_app;
	var $_user;
	var $_data;
	var $_offset;
	
	function __construct()
	{
		
		$this->_db	= JFactory::getDBO();
		$this->_app = JFactory::getApplication();
		$this->_user = JFactory::getUser();
		//$session = JFactory::getSession();
		$this->_offset = $this->_app->getCfg('offset');
	
		
		$_varpost = array();
		$_varpost['token'] = JRequest::getVar( 'token', '', 'POST' );
		$_varpost['userid'] = JRequest::getVar( 'userid', '', 'POST' );
		$_varpost['execute'] = JRequest::getVar( 'execute', '', 'POST' );
		$_varpost['data'] = JRequest::getVar( 'data', '', 'POST' );

/*
		$cpf = '577.107.759-34';
        $_varpost['data'] = str_replace('=', '', strrev( base64_encode(base64_encode( $cpf ) ) ) );
		$_varpost['execute'] ='getInfoQRCode';
*/
		if(!empty($_varpost['execute'])):
			switch ($_varpost['execute']):
				case 'getInfoQRCode': 
					$response = $this->getInfoQRCode( $_varpost ); 
				break;
				case 'setLogoff': 
					$response = $this->setLogoff( $_varpost ); 
				break;
				default:
					$response = new stdClass();
					$response->response = 'error';
					$response->message = 'Execução solicitada é .' . $_varpost['execute'];	
				break;
			endswitch;
		else:
		
			$response = new stdClass();
			$response->response = 'error';
			$response->message = 'Nenhuma execução solicitada.';
		endif;

		header('Content-Type: application/json');
		if($response)
			echo json_encode($response);

		
	}


	function getInfoQRCode( $options = array())
	{
		//
		if(!isset($options['data']) || empty($options['data']))
			return false;
		$options['data'] = base64_decode( base64_decode( strrev( '=' . $options['data'] ) ) );
		


		$curl = curl_init();
		$header = array(
			//"accept: application/vnd.itau",
			"Content-Type: application/json",
			"Accept: application/json"
			);

		$JSON_request = array(
			'hashCode' => 'OzGR134z+qqwN48v8ducww==',
			//'userId' => '214d8311-7984-422e-bb90-2b3359d6aadc',
			//'userId' => '9eb4b6ab-6c5c-449a-a1db-52d86075598d',
			'userId' => 'b813a575-56a3-4369-bc62-0e13e2cb0fad',
			'cpf' => preg_replace("/[^0-9]/", "", $options['data'])
			);


		curl_setopt_array($curl, array(
			CURLOPT_URL =>  'https://portal.scmfire.com.br/Integracao/GetClienteByCpf/',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "", 
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
			CURLOPT_SSL_VERIFYPEER => true,
			CURLOPT_POSTFIELDS => json_encode($JSON_request),
			CURLOPT_HTTPHEADER => $header,
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);
		$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		curl_close($curl);

		$response = json_decode($response);

		if(empty($response->result))
			return false;


		$pf = $response->result;

		$id_pf = $this->getPfbyCPF($pf->cpf);


		JTable::addIncludePath(JPATH_SITE.'/tables');
		$row = JTable::getInstance('pf', 'Table');

		$data = array();
		
		if ($id_pf)
			$row->load($id_pf);


		if (!$id_pf) {
			$data['register_pf'] = JFactory::getDate('now', $this->_offset)->toISO8601(true);
			$data['user_register_pf'] = $this->_userAdmin;
			$data['status_pf'] = '1';
		} else {
			$data['update_pf'] = JFactory::getDate('now', $this->_offset)->toISO8601(true);
			$data['user_update_pf'] = $this->_userAdmin;
		}

		$data['name_pf'] = $pf->nome;
		$data['email_pf'] = $pf->email;
		$data['cpf_pf'] = $pf->cpf;
		$data['rg_pf'] = $pf->rg;
		$data['orgao_expeditor_pf'] = $pf->orgaoExpedidor;
		$data['uf_orga_expeditor_pf'] =  $this->getEstadoByUF($pf->estadoExpedidor);

		$data['sexo_pf'] = $pf->sexo == 'Feminino' ? 1 : 0;
		//$data['tsangue_pf'] = $pf->tipoSangue escrito par ainteiro

		$data['npai_pf'] = $pf->nomePai;
		$data['nmae_pf'] = $pf->nomeMae;

		$data['cep_pf'] = str_replace('.', '', $pf->cep);
		
		$data['logradouro_pf'] = $pf->endereco;
		
		$data['numero_pf'] = $pf->numEndereco;
		
		$data['complemento_pf'] = $pf->complemento;
		
		$data['bairro_pf'] = $pf->bairro;
		
		$data['tel_celular_pf'] = $pf->celular;
		
		$data['tel_residencial_pf'] = $pf->telefone;
		
		$data['numcr_pf'] = $pf->cr;

		$data['id_estado'] = $this->getEstadoByName($pf->estado);

		$data['id_cidade'] = $this->getCidadeByName($pf->cidade, $data['id_estado']);
		if(!empty($pf->estadoNascimento))
			$data['naturalidade_uf_pf'] = $this->getEstadoByName($pf->estadoNascimento);

		if(!empty($data['naturalidade_uf_pf']))
			$data['naturalidade_pf'] = $this->getCidadeByName($pf->cidadeNascimento, $data['naturalidade_uf_pf']);

			
		$data['nacionalidade_pf'] = $pf->nacionalidade;
		
/*

		stdClass Object ( [$id] => 1 [result] => stdClass Object ( [$id] => 2 
		[id] => 1 
		[numeroFiliacao] => 
		[dataValidade] => 
		[dataFiliacao] => 
		[nome] => Fabricio Migliorini 
		[categoria] => 
 		[rg] => 1034615359 
		[dataNascimento] => 05/10/1972 
 		[orgaoExpedidor] => SSP 
 		[dataExpedicao] => 
		[estadoExpedidor] => Rs 
		[email] => fmigliorini@rsshooting.com 
		[situacao] => 
		[tipoSangue] => NaoInformado 
 		[nomePai] => 
		[nomeMae] => 
		[tituloEleitor] =>
		[profissao] => 
		[estadoCivil] => NaoInformado 
		[sexo] => Feminino 
		[paisNascimento] => 
		[estadoNascimento] => 
		[cidadeNascimento] => 
		[rm] => 
		[rmOm] => 
		[nacionalidade] => 
		[cepCr] => 
		[enderecoCr] => 
		[numEnderecoCr] => 
		[complementoCr] => 
		[bairroCr] => 
		[paisCr] => 
		[estadoCr] => 
		[cidadeCr] => ) 
		[message] => stdClass Object ( [$id] => 3 [messages] => Array ( [0] => stdClass Object ( [$id] => 4 [message] => [stackTrace] => [date] => 27/10/2021 [type] => 4 ) ) ) )
*/

		if (!empty($pf->dataNascimento)) {
			$dataaTmp = $pf->dataNascimento;
			$data['data_nascimento_pf'] = implode("-", array_reverse(explode("/", $dataaTmp)));
			$data['data_nascimento_pf'] = JFactory::getDate($data['data_nascimento_pf'], $this->_offset)->toFormat('%Y-%m-%d', true);
		} else
			$data['data_nascimento_pf'] = NULL;


		if (!empty($pf->dataExpedicao)) {
			$dataaTmp = $pf->dataExpedicao;
			$data['data_expedicao_pf'] = implode("-", array_reverse(explode("/", $dataaTmp)));
			$data['data_expedicao_pf'] = JFactory::getDate($data['data_expedicao_pf'], $this->_offset)->toFormat('%Y-%m-%d', true);
		} else
			$data['data_expedicao_pf'] = NULL;


		if (!empty($pf->dataValidadeCr)) {
			$dataaTmp = $pf->dataValidadeCr;
			$data['vencr_pf'] = implode("-", array_reverse(explode("/", $dataaTmp)));
			$data['vencr_pf'] = JFactory::getDate($data['vencr_pf'], $this->_offset)->toFormat('%Y-%m-%d', true);
		} else
			$data['vencr_pf'] = NULL;

			/*
		if (!empty($data['venc_ibama_pf'])) {
			$dataaTmp = explode(" ", $data['venc_ibama_pf']);
			$data['venc_ibama_pf'] = implode("-", array_reverse(explode("/", $dataaTmp[0]))) . ' ' . $dataaTmp[1];
			$data['venc_ibama_pf'] = JFactory::getDate($data['venc_ibama_pf'], $this->_offset)->toFormat('%Y-%m-%d', true);
		} else
			$data['venc_ibama_pf'] = NULL;

		*/

		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (!$data['vencr_pf'])
			$row->vencr_pf = NULL;

		if (!$row->check($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (!$row->store($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		jimport('joomla.log.log');
		JLog::addLogger(array('text_file' => 'log.pf.php'));

		if ($id_pf):
			JLog::add($this->_user->get('id') . JText::_('		Pessoa Física Editada (importação) -  idPF(' . $id_pf . ')'), JLog::INFO, 'pf');
		else :
			$id_pf = $row->get('id_pf');
			JLog::add($this->_user->get('id') . JText::_('		Pessoa Física Cadastrada (importação) -  idPF(' . $id_pf . ')'), JLog::INFO, 'pf');
		endif;
		
		$query = $this->_db->getQuery(true);
		$query->select('*');
		$query->from('#__pf');
		$query->where($this->_db->quoteName('id_pf') . '=' . $this->_db->quote($id_pf));
		$this->_db->setQuery($query);

		$response = new stdClass();

		if( (boolean) $response->data = $this->_db->loadObject()):
			$response->response = 'success';
			$response->message = 'Pesquisou com sucesso';
		else:
			$response = new stdClass();
			$response->response = 'error';
			$response->message = 'Não é possivel lançar resultados para esta inscrição.';
		endif;

		return $response;
		
	}


	function getPfbyCPF($cpf_pf=NULL){

		if(is_null($cpf_pf))
			return false;
		$query = $this->_db->getQuery(true);
		$query->select($this->_db->quoteName('id_pf'));
		$query->from($this->_db->quoteName('#__pf'));
		$query->where($this->_db->quoteName('cpf_pf') . '=' . $this->_db->quote($cpf_pf));
		$this->_db->setQuery($query);
		if (!(boolean) $id_pf = $this->_db->loadResult()) {
			return false;
		}
		return $id_pf;

	}

	function getEstadoByUF($sigla_estado=NULL){

		if(is_null($sigla_estado))
			return false;

		$query = $this->_db->getQuery(true);
		$query->select($this->_db->quoteName('id_estado'));
		$query->from($this->_db->quoteName('#__estado'));
		$query->where($this->_db->quoteName('sigla_estado') . ' LIKE ' . $this->_db->quote('%' . $this->_db->escape($sigla_estado) . '%'));
		$this->_db->setQuery($query);
		if (!(boolean) $id_estado = $this->_db->loadResult()) {
			return false;
		}
		return $id_estado;

	}

	function getEstadoByName($name_estado=NULL){

		if(is_null($name_estado))
			return false;

		$query = $this->_db->getQuery(true);
		$query->select($this->_db->quoteName('id_estado'));
		$query->from($this->_db->quoteName('#__estado'));
		$query->where($this->_db->quoteName('name_estado') . ' LIKE ' . $this->_db->quote('%' . $this->_db->escape($name_estado) . '%'));
		$this->_db->setQuery($query);
		if (!(boolean) $id_estado = $this->_db->loadResult()) {
			return false;
		}
		return $id_estado;

	}

	function getCidadeByName($name_cidade=NULL, $id_estado = NULL){

		if(is_null($name_cidade) || is_null($id_estado))
			return false;

		$query = $this->_db->getQuery(true);
		$query->select($this->_db->quoteName('id_cidade'));
		$query->from($this->_db->quoteName('#__cidade'));
		$query->where($this->_db->quoteName('id_estado') . '=' . $this->_db->quote($id_estado));
		$query->where($this->_db->quoteName('name_cidade') . ' LIKE ' . $this->_db->quote('%' . $this->_db->escape($name_cidade) . '%'));
		$this->_db->setQuery($query);
		if (!(boolean) $id_cidade = $this->_db->loadResult()) {
			return false;
		}
		return $id_cidade;

	}




	function setLogoff( $options = array())
	{

		$response = new stdClass();
		$response->response = 'error';
		$response->message = 'Erro ao tentar encerrada a sessão.';
		
		
		if($options['userid'] && $options['token']):
			$params = array(
				'clientid' => ($options['userid']) ? 0 : 1,
				'session_id' => ($options['token'])
			);
	
			$result = $this->_app->logout($options['userid'], $params);
	
			if (!($result instanceof Exception)):
				
				//$msg = 'A sessão ativa foi encerrada com sucesso!.';

			$response->response = 'success';
			$response->message = 'A sessão ativa foi encerrada com sucesso!';
			//$this->setRedirect(JRoute::_('index.php?view=login', false), $msg, 'danger');
			endif;
		endif;
		
		return $response;
		
	}
		
}
?>
