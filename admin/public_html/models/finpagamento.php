<?php

defined('_JEXEC') or die('Restricted access');

//require_once(JPATH_LIBRARIES . DS . 'nfe'. DS . 'nfe.php');

jimport('joomla.application.component.model');


class IntranetModelFinPagamento extends JModel 
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

	
	function __construct()
	{
		parent::__construct();
		
		$this->_db	= JFactory::getDBO();
		$this->_user		= JFactory::getUser();
		$this->_app 	= JFactory::getApplication(); 
		$this->_siteOffset = $this->_app->getCfg('offset');
		
		$this->_isRoot		= $this->_user->get('isRoot');	
		$this->_userAdmin	= $this->_user->get('id'); 	
				
		$array  	= JRequest::getVar( 'cid', array(0), '', 'array');
		JRequest::setVar( 'cid', $array[0] );
		$this->setId( (int) $array[0] );

		if (!$this->isCheckedOut() ) {
			$this->checkout();		
		}
		else {
			$tipo = 'alert-warning';
			$msg = JText::_( 'JGLOBAL_CONTROLLER_CHECKIN_ITEM' );
			$link = 'index.php?view=finpagamentos';
			$this->_app->redirect($link, $msg, $tipo);
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
			//$typesArray = $this->getConteudoTipos();
			
			$query = $this->_db->getQuery(true);	
			$query->select('*');
			$query->from($this->_db->quoteName('#__intranet_pagamento'));
			$query->innerJoin($this->_db->quoteName('#__intranet_pagamento_metodo'). ' USING(' . $this->_db->quoteName('id_pagamento_metodo'). ')');
			$query->innerJoin($this->_db->quoteName('#__users') . ' ON(' . $this->_db->quoteName('id'). '=' . $this->_db->quoteName('id_user'). ')');
			$query->where( $this->_db->quoteName('id_pagamento') . '=' . $this->_db->escape( $this->_id ) );
			$this->_db->setQuery($query);
			if( !(boolean) $this->_data = $this->_db->loadObject())
			{
				$this->addTablePath(JPATH_BASE.'/tables');
	    		$this->_data = $this->getTable('pagamento');
			}
		}
		return $this->_data;
	}

	
	function getAssociadosList()
	{
		$query = $this->_db->getQuery(true);
		$query->select('id as value, name as text');
		$query->from('#__users');
		$query->order('text');
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();
	}
	
	function getAnuidadePagamento()
	{
		$query = $this->_db->getQuery(true);
		$query->select('id_anuidade as value, name_anuidade as text');
		$query->from($this->_db->quoteName('#__intranet_anuidade'));
		$query->order('text');
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();	
	}

	function getProdutoPagamento()
	{
		$query = $this->_db->getQuery(true);
		$query->select('id_produto as value, name_produto as text');
		$query->from($this->_db->quoteName('#__intranet_produto'));
		$query->order('text');
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();	
	}


	function getCobranca() 
	{

		$query = $this->_db->getQuery(true);	
		$query->select($this->_db->quoteName(array('module_pagamento_metodo')));
		$query->from($this->_db->quoteName('#__intranet_pagamento'));
		$query->innerJoin($this->_db->quoteName('#__intranet_pagamento_metodo'). ' USING(' . $this->_db->quoteName('id_pagamento_metodo'). ')');
		$query->where( $this->_db->quoteName('id_pagamento') . '=' . $this->_db->quote( $this->_id ) );
		$this->_db->setQuery($query);
		$_data = $this->_db->loadObject();
		
		

		require_once(JPATH_MODULE .DS. 'mod_' . $_data->module_pagamento_metodo. DS. 'mod_' . $_data->module_pagamento_metodo . '.php');
		//require_once(JPATH_MODULE .DS. 'mod_bolbradesco' .DS. 'mod_bolbradesco.php');
		
		$prefix  = 'Intranet';
		$type = $_data->module_pagamento_metodo. 'Module';
		$type = preg_replace('/[^A-Z0-9_\.-]/i', '', $type);
		$modelClass = $prefix . ucfirst($type);
		
		if (!class_exists($modelClass))
			return false;
		
		

				
		
		$_module_pagament = new $modelClass();
		

		$_module_pagament->setData($this->getValues());
		//$_module_pagament->setPayment();
		$_module_pagament->setPayment();

	}	
	
	function getValues() 
	{

		//$typesArray = $this->getConteudoTipos();
		
		$query = $this->_db->getQuery(true);
		$query->select($this->_db->quoteName(array( '#__intranet_pagamento.id_pagamento',
													'text_pagamento',
													//'transacao_pagamento',
													'taxa_pagamento',
													'baixa_pagamento',
													'vencimento_pagamento',
													'valor_pagamento',
													'vencimento_desconto_pagamento',
													'valor_desconto_pagamento',
													'email',
												 	//'name_produto',
													//'parcela_centro_custos',
													//'types_id',
													//'cb_id',
													'sigla_estado',
													'name_cidade',
													//'value_plano',
													)));	
		
		
		$query->select( 'IF( ISNULL(#__intranet_pf.cpf_pf), #__intranet_pj.cnpj_pj, #__intranet_pf.cpf_pf) AS doc');
		$query->select( 'IF( ISNULL(#__intranet_pf.cep_pf), #__intranet_pj.cep_pj, #__intranet_pf.cep_pf) AS cep');
		$query->select( 'IF( ISNULL(#__intranet_pf.logradouro_pf), #__intranet_pj.logradouro_pj, #__intranet_pf.logradouro_pf) AS logradouro');
		$query->select( 'IF( ISNULL(#__intranet_pf.numero_pf), #__intranet_pj.numero_pj, #__intranet_pf.numero_pf) AS numero');
		$query->select( 'IF( ISNULL(#__intranet_pf.complemento_pf), #__intranet_pj.complemento_pj, #__intranet_pf.complemento_pf) AS complemento');
		
		$query->select( 'IF( ISNULL(#__intranet_pf.bairro_pf), #__intranet_pj.bairro_pj, #__intranet_pf.bairro_pf) AS bairro');
		$query->select( 'IF( ISNULL(#__intranet_pf.tel_celular_pf), #__intranet_pj.telefone_pj, #__intranet_pf.tel_celular_pf) AS telefone');		
		$query->select( 'name AS sacado');
		$query->select( 'IF( ISNULL(name_anuidade), name_produto, name_anuidade)  AS produto');
		$query->select('IF(ISNULL(id_pj),0,1) AS types_id');
		//$query->select( 'tipo AS types_id');

		$query->from($this->_db->quoteName('#__intranet_pagamento'));
		$query->leftJoin($this->_db->quoteName('#__intranet_anuidade') . ' USING(' . $this->_db->quoteName('id_anuidade'). ')');
		$query->leftJoin($this->_db->quoteName('#__intranet_produto') . ' USING(' . $this->_db->quoteName('id_produto'). ')');	
		
		$query->leftJoin($this->_db->quoteName('#__intranet_pf') . ' USING(' . $this->_db->quoteName('id_user'). ')');
		$query->leftJoin($this->_db->quoteName('#__intranet_pj') . ' USING(' . $this->_db->quoteName('id_user'). ')');;	
		$query->innerJoin($this->_db->quoteName('#__users') . ' ON(' . $this->_db->quoteName('id'). '=' . $this->_db->quoteName('id_user'). ')');
		$query->leftJoin($this->_db->quoteName('#__intranet_estado') . ' ON(' 
																	 . $this->_db->quoteName('#__intranet_estado.id_estado'). '=' . $this->_db->quoteName('#__intranet_pf.id_estado')
																	 . 'OR '
																	 . $this->_db->quoteName('#__intranet_estado.id_estado'). '=' . $this->_db->quoteName('#__intranet_pj.id_estado')
																	 . ')');	
		$query->leftJoin($this->_db->quoteName('#__intranet_cidade') . ' ON(' 
																	 . $this->_db->quoteName('#__intranet_cidade.id_cidade'). '=' . $this->_db->quoteName('#__intranet_pf.id_cidade')
																 	 . 'OR '
																 	 . $this->_db->quoteName('#__intranet_cidade.id_cidade'). '=' . $this->_db->quoteName('#__intranet_pj.id_cidade')
																	 . ')');			
		
		$query->where( $this->_db->quoteName('#__intranet_pagamento.id_pagamento') . '=' . $this->_db->quote( $this->_id ) );
		
		
		$this->_db->setQuery($query);
		return $this->_db->loadAssoc();
		
	}

	function sendmail()
	{
		
		
		$recipient = JRequest::getVar('destinatario', '', 'post');

		$_data = $this->getValues();
		
		$query = $this->_db->getQuery(true);	
		$query->select($this->_db->quoteName(array('module_pagamento_metodo')));
		$query->from($this->_db->quoteName('#__intranet_pagamento'));
		$query->innerJoin($this->_db->quoteName('#__intranet_pagamento_metodo'). ' USING(' . $this->_db->quoteName('id_pagamento_metodo'). ')');
		$query->where( $this->_db->quoteName('id_pagamento') . '=' . $this->_db->quote( $this->_id ) );
		$this->_db->setQuery($query);
		$_metodo = $this->_db->loadObject();

		require_once(JPATH_MODULE .DS. 'mod_' . $_metodo->module_pagamento_metodo. DS. 'mod_' . $_metodo->module_pagamento_metodo . '.php');
		
		$prefix  = 'Intranet';
		$type = $_metodo->module_pagamento_metodo. 'Module';
		$type = preg_replace('/[^A-Z0-9_\.-]/i', '', $type);
		$modelClass = $prefix . ucfirst($type);
		
		if (!class_exists($modelClass))
			return false;
		
		$_module_pagament = new $modelClass();
		$_module_pagament->setData($_data);
		$html = $_module_pagament->getPayment();
		
		$fromname	= $this->_app->getCfg('fromname');
		$mailfrom	= $this->_app->getCfg('mailfrom');
		$sitename	= $this->_app->getCfg('sitename');
		$siteurl	= JUri::root();
		
		$subject = JText::_('Envio de Cobrança FGCT');
		
		$infoPagamento = '<strong>Dados para pagamento:</strong><br/>
						  Boleto Bancário em anexo a esta mensagem de e-mail.<br/><br/>';
																
		$emailBody = '
			<style type="text/css">
				table{
					font-size: 14px !important; 
					font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; !important;  
					color:#333 !important; 
				}
				h2 { font-size: 1.5em !important; }
				p {	margin: 0 0 1.35em 0 !important; line-height: 142% !important; color:#666; 	}
				a { text-decoration:none !important; }
				.title { font-weight:bold !important;}
				.text{
					text-align:justify !important;  
				}
				.td-footer {
					padding:30px !important;
				}
				.span-footer {
					text-align:center !important; 
					color:#FFF !important;
				}
				.link-footer {
					text-decoration:none !important; 
					color:#FFF !important;
				}
			</style>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#EFEFEF">
				<tbody>
					<tr>
						<td align="center" valign="top">
							<table width="600" border="0" cellspacing="0" cellpadding="0" style="border:1px solid;">
								<tbody>
									<tr>
										<td valign="middle">
											<img src="cid:logo" alt="FBT" border="0" width="600" height="100"/>
										</td>
									</tr>
									<tr bgcolor="#FFFFFF">
										<td>
											<table width="100%" border="0" cellspacing="40" cellpadding="0" >
												<tbody>
													<tr>
														<td valign="middle" width="100%">
															<h2 class="title" style="font-weight:bold;">
																Cobrança FGCT.<br/><br/>
															</h2>
															<p>
																Olá <strong>'.$_data['sacado'].'</strong>,<br/>
															</p>
															<p class="text" style="text-align:justify; color:#666" >
																Estamos enviando os dados para pagamento referente à <strong>'.$_data['produto'].'</strong> da Federação Gaúcha de Caça e Tiro (FGCT).<br/><br/>
															</p>
															<p>
																' . $infoPagamento . '
															</p>
															<p>
																<a href="http://www.fgct.com.br" class="link" target="_blank" style="text-decoration:none;" >Acesse o site da FGCT clicando aqui</a>
															</p>		
														</td>
													</tr>
												</tbody>
											</table>		
										</td>
									</tr>
									<tr bgcolor="#1b1b1b">	
										<td align="center" valign="middle" class="td-footer" style="padding:30px;">
											<span class="span-footer" style="text-align:center; color:#FFF;">
												Rua Portugal, 840 - São João - Porto Alegre - RS - Brasil<br/>
												Contato:&nbsp;+55 (51) 3779.9600</a><br/>+55 (51) 99340.0947<br/>+55 (51) 99354-9756<br/>
												<a href="mailto:fgct@fgct.com.br" class="link-footer" target="_blank" style="text-decoration:none; color:#FFF" >fgct@fgct.com.br </a><br/>
											</span>
										</td>
									</tr>	
								</tbody>
							</table>
						</td>
					</tr>
				</tbody>
			</table>';
			
			
			
		$mailer = JFactory::getMailer(); 
		
		$mailer->AddEmbeddedImage( JPATH_BASE .DS. 'views' .DS. 'system' .DS. 'images' .DS. 'logo_email.jpg' , 'logo', 'logo.jpg', 'base64', 'image/jpg' );
		
		
		/*
		$dompdf = new Dompdf();
		$dompdf->loadHtml($html);
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();
		
		$pdf_gen = $dompdf->output();
		
		*/			
		$mailer->isHTML(true);
		$mailer->Encoding = 'base64';
		$mailer->setSender( array( $mailfrom, $fromname ) );
		$mailer->addRecipient($recipient);
		$mailer->setSubject($subject);
		$mailer->setBody($emailBody);
		


		//echo mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
		
		//exit;
		require_once(JPATH_LIBRARIES .DS. 'Dompdf' .DS. 'src' .DS. 'Dompdf.php');
		
		//$options = new Options();
		//$options->set('enable_html5_parser', true);
	
		
		$dompdf = new Dompdf();
		//$dompdf->set_base_path('http://minha.fbtedu.com.br/admin/modules/mod_bolbradesco/tmpl/css/boleto.css');
	  // 	$dompdf->load_html(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
		//$dompdf->set_default_view('FitH');
		// (Optional) Setup the paper size and orientation
		//$dompdf->setPaper('A4', 'landscape');
		//$dompdf->set_paper('A4', 'portrait');
		//$dompdf->loadHtml(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));

		$dompdf->loadHtml(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));

		$dompdf->render();
		
		$pdfdata = $dompdf->output();
		$output = JPATH_BASE .DS. 'cache' .DS. 'boleto.pdf';
		file_put_contents($output, $pdfdata);
		
		$mailer->addAttachment($output);	
		
		$mailer->AddEmbeddedImage( JPATH_BASE .DS. 'views' .DS. 'system' .DS. 'images' .DS. 'logo_email.jpg' , 'logo', 'logo.jpg', 'base64', 'image/jpg' );
		
		return $mailer->Send();

	}

	function store() 
	{
		$this->addTablePath(JPATH_BASE.'/tables');
	    $row = $this->getTable('pagamento');
		$data = JRequest::get( 'post' );

		if(!$this->_id) {
			$data['cadastro_pagamento'] = JFactory::getDate('now', $this->_siteOffset)->toFormat('%Y-%m-%d', true);
		}
		else {
			$row->load($this->_id);
			$data['update_pagamento'] = JFactory::getDate('now', $this->_siteOffset)->toFormat('%Y-%m-%d', true);
		}

		if(!empty($data['valor_pago_pagamento']))
			$data['valor_pago_pagamento'] =  (float) str_replace(',', '.', str_replace('.', '', str_replace('R$ ', '', $data['valor_pago_pagamento'])));
		else
			$data['valor_pago_pagamento'] = NULL;

		$data['valor_pagamento'] =  (float) str_replace(',', '.', str_replace('.', '', str_replace('R$ ', '', $data['valor_pagamento'])));
		$data['vencimento_pagamento'] = JFactory::getDate(implode("-",array_reverse(explode("/", $data['vencimento_pagamento']))), $this->_siteOffset)->toFormat('%Y-%m-%d', true);
		
		if(!empty($data['valor_desconto_pagamento']))
			$data['valor_desconto_pagamento'] =  (float) str_replace(',', '.', str_replace('.', '', str_replace('R$ ', '', $data['valor_desconto_pagamento'])));
		else
			$data['valor_desconto_pagamento'] = NULL;
		
		if(!empty($data['vencimento_desconto_pagamento']))
			$data['vencimento_desconto_pagamento'] = JFactory::getDate(implode("-",array_reverse(explode("/", $data['vencimento_desconto_pagamento']))), $this->_siteOffset)->toFormat('%Y-%m-%d', true);
		else
			$data['vencimento_desconto_pagamento'] = NULL;
			

		if(!empty($data['baixa_pagamento']))
			$data['baixa_pagamento'] = JFactory::getDate(implode("-",array_reverse(explode("/", $data['baixa_pagamento']))), $this->_siteOffset)->toFormat('%Y-%m-%d', true);
		else
			$data['baixa_pagamento'] = NULL;
				
		if($data['type_pagamento']==1)
			$data['id_anuidade'] = NULL;
		else
			$data['id_produto'] = NULL;	
				
				
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
		
		if(!$data['id_anuidade'])
			$row->id_anuidade= NULL;
		
		if(!$data['id_produto'])
			$row->id_produto= NULL;
					
		if ( !$row->check($data)) 
			return false;	
			
		if ( !$row->store(TRUE) ) 
			return false;	
		

		
		jimport('joomla.log.log');
		JLog::addLogger(array( 'text_file' => 'log.pagamento.php'));

		if($this->_id):
			$row->checkin($this->_id);
			JLog::add($this->_user->get('id') . JText::_('		Pagamento Editado -  idPg('.$this->_id.')'), JLog::INFO, 'finpagamento');
		else:
			$this->setId( $row->get('id_pagamento') ); 	
			JLog::add($this->_user->get('id') . JText::_('		Pagamento Cadastrado -  idPg('.$this->_id.')'), JLog::INFO, 'finpagamento');
		endif;
		
		JRequest::setVar( 'cid', $this->_id );

		if($data['baixa_pagamento'] && !empty($data['id_anuidade'])):
	
			$query = $this->_db->getQuery(true);	
			$query->select($this->_db->quoteName(array( 'id_associado',
														'status_associado',
														'cadastro_associado',
														'validate_associado')));
			$query->from('#__intranet_associado');	
		
			$query->where( $this->_db->quoteName('id_user') . '=' . $this->_db->quote( $data['id_user'] ) );
			$this->_db->setQuery($query);
			$associado = $this->_db->loadObject();
			
			$query->clear();	
			$query->select($this->_db->quoteName(array( 'id_anuidade',
														'validate_anuidade')));
			$query->from('#__intranet_anuidade');	
		
			$query->where( $this->_db->quoteName('id_anuidade') . '=' . $this->_db->quote( $data['id_anuidade'] ) );
			$this->_db->setQuery($query);
			$anuidade = $this->_db->loadObject();
						
			if( JFactory::getDate($anuidade->validate_anuidade, $this->_siteOffset)->toFormat('%Y-%m-%d', true) >= JFactory::getDate($associado->validate_associado, $this->_siteOffset)->toFormat('%Y-%m-%d', true)):
			
				$query->clear();
				$fields = array(
					$this->_db->quoteName('confirmado_associado') . ' = ' . $this->_db->quote( JFactory::getDate($data['baixa_pagamento'], $this->_siteOffset)->toFormat('%Y-%m-%d', true) ),
					$this->_db->quoteName('validate_associado') . ' = ' . $this->_db->quote( JFactory::getDate($anuidade->validate_anuidade, $this->_siteOffset)->toFormat('%Y-%m-%d', true) ),
				);
				$conditions = array(
					$this->_db->quoteName('id_user') . '=' . $this->_db->quote( $data['id_user'] )			 
				);
				$query->update($this->_db->quoteName('#__intranet_associado'))->set($fields)->where($conditions);
				$this->_db->setQuery($query);
				if ( !((boolean) $this->_db->query()))
					return false;	
			
				if( JFactory::getDate($anuidade->validate_anuidade, $this->_siteOffset)->toFormat('%Y-%m-%d', true) >= JFactory::getDate('now', $this->_siteOffset)->toFormat('%Y-%m-%d', true)): 
			
					$query->clear();
					$fields = array( $this->_db->quoteName('block') . ' = ' . $this->_db->quote( '0' ) );
					$conditions = array( $this->_db->quoteName('id') . '=' . $this->_db->quote( $data['id_user'] ));	
					$query->update($this->_db->quoteName('#__users'))->set($fields)->where($conditions);
					$this->_db->setQuery($query);
					if ( !$this->_db->query())	
						return false;
						
				endif;
			endif;
		endif;

		return true;
	
	}
	
	function processDownPayment()
	{
		
		$query = $this->_db->getQuery(true);
		
		if (empty($this->_data))
			$this->getItem();	
			
		$options = array();
		$options['email'] = $this->_data->email;
		$options['nome'] = $this->_data->sacado;
		$options['produto'] = $this->_data->produto;
		$options['idproduto'] = $this->_data->id_pagamento_produto;
		$options['ordering'] = $this->_data->ordering;
		$options['referencia'] = $this->_data->text_pagamento;
		$options['type'] = $this->_data->id_pagamento_produto_tipo;
		$options['id_pagamento'] = $this->_id; 
		$options['valorpago_pagamento'] = $this->_data->valorpago_pagamento ;
		$options['confirmado_pagamento'] = $this->_data->confirmado_pagamento;
				
		switch($this->_data->id_pagamento_produto_tipo)
		{
			case '1': //BAIXA FBT

				$query->clear();
				$query->select( 'COUNT(id_pagamento)');
				$query->from($this->_db->quoteName('#__intranet_pagamento'));
				$query->where( $this->_db->quoteName('id_pagamento_produto') . '=' . $this->_db->quote($this->_data->id_pagamento_produto)) ;
				$query->where( $this->_db->quoteName('id_pagamento_produto_tipo') . '=' . $this->_db->quote($options['type'])) ;
				$query->where( $this->_db->quoteName('status_pagamento') . '=' . $this->_db->quote('1')) ;
				$this->_db->setQuery($query);
				$totalPagamento = $this->_db->loadResult();
					
				if($totalPagamento == 1 && $this->_data->ordering == 1 && $this->_data->plots_create_evento ==1 && $this->_data->plots_inscricao > 1):
					
					$options['value'] = round($this->_data->value_inscricao/$this->_data->plots_inscricao,2);
					$options['plots'] = $this->_data->plots_inscricao;
					$options['date'] = $this->_data->plots_date_first_evento;
					$options['metodo'] =  $this->_data->id_payment;
					$options['cc_id'] =  $this->_data->cc_id;
					$this->setPlots($options);
				endif;
				
				$this->setCurrentPayment($options);
				
			break;
			
			case '2':  //BAIXA INEJE
				$this->updateCentroCustosINEJE($options);
			break;
		}
				
		return true;
	
	}
	
	
	function setCurrentPayment( $options = array() )
	{
		
		$query = $this->_db->getQuery(true);	
		$query->select('id_pagamento');
		$query->from($this->_db->quoteName('#__intranet_pagamento'));
		$query->where( $this->_db->quoteName('id_pagamento_produto') . '=' . $this->_db->quote( $options['idproduto'] ));
		$query->where( $this->_db->quoteName('id_pagamento_produto_tipo') . '=' . $this->_db->quote( $options['type'] ));
		$query->where( $this->_db->quoteName('confirmado_pagamento') . ' IS NULL');
		$query->order('ordering ASC');
		$this->_db->setQuery($query,0,1);
		if( (boolean) $id_pagamento = $this->_db->loadResult()):
		
			$query->clear();
			$fields = array(
				$this->_db->quoteName('id_pagamento') . ' = ' . $this->_db->quote( $id_pagamento ),
			);
			$conditions = array(
				$this->_db->quoteName('id_inscricao') . '=' . $this->_db->quote( $options['idproduto'] )			 
			);
			$query->update($this->_db->quoteName('#__inscricao'))->set($fields)->where($conditions);
			$this->_db->setQuery($query);
			if ( !((boolean) $this->_db->query()))
				return false;	
		
		endif;
		
		return true;
	
	}
	
	function setPlots( $options = array() )
	{
		
		$this->addTablePath(JPATH_BASE.'/tables');
		
		$query = $this->_db->getQuery(true);	
		$query->select($this->_db->quoteName(array( 'id_inscricao',
													'plots_inscricao',
													'value_inscricao',
													'cc_id',
													'plots_date_first_evento',
													'id_payment',
													'params_pagamento_metodo'
													)));
		$query->from($this->_db->quoteName('#__inscricao'));
		$query->innerJoin( $this->_db->quoteName('#__evento') . ' USING(' . $this->_db->quoteName('id_evento'). ')');	
		$query->innerJoin( $this->_db->quoteName('#__pagamento_metodo') . ' ON(' . $this->_db->quoteName('id_payment'). '=' . $this->_db->quoteName('id_pagamento_metodo'). ')');
		$query->where( $this->_db->quoteName('id_inscricao') . '=' . $this->_db->quote( $options['idproduto'] ) );
		$query->group('id_inscricao');
		$this->_db->setQuery($query);
		$creatPlots = $this->_db->loadObject();
		
		
		$registry = new JRegistry;
		$registry->loadString($creatPlots->params_pagamento_metodo);
		$params = $registry->toArray();	
		$taxa = 0;
		if($params['taxa'])
			$taxa = (float) str_replace(',', '.', str_replace('.', '', str_replace('R$ ', '', $params['taxa'])));
			
		$valuePlot = round($creatPlots->value_inscricao/$creatPlots->plots_inscricao,2);	
		$months = 0;	
		for($ordering=1; $ordering <= $creatPlots->plots_inscricao; $ordering++)
		{

			$query->clear();
			$query->select($this->_db->quoteName(array('id_pagamento')));
			$query->from($this->_db->quoteName('#__pagamento'));
			$query->where( $this->_db->quoteName('id_pagamento_produto') . '=' . $this->_db->quote( $creatPlots->id_inscricao ) );
			$query->where( $this->_db->quoteName('id_pagamento_produto_tipo') . '=' . $this->_db->quote( '1' ) );
			$query->where( $this->_db->quoteName('status_pagamento') . '=' . $this->_db->quote( '1' ) );
			$query->where( $this->_db->quoteName('ordering') . '=' . $this->_db->quote( $ordering ) );
			$this->_db->setQuery($query);
			if( (boolean) $id_pagamento = $this->_db->loadResult())
				continue;
					
			$data = array();
			$data['status_pagamento']='1';
			$data['id_pagamento_produto'] = $creatPlots->id_inscricao;
			$data['id_pagamento_produto_tipo'] = 1;
			$data['id_pagamento_metodo'] = $creatPlots->id_payment;
			$data['cadastro_pagamento'] = JFactory::getDate('now', $this->_siteOffset)->toFormat('%Y-%m-%d',true);
			$data['valor_pagamento']= $valuePlot;
			$data['taxa_pagamento'] = $taxa;
			$data['ordering']=$ordering;
			$data['carteira_pagamento']='09';
			$data['text_pagamento']= 'Parcela ' .$ordering;
			
			if($months > 0):
				$data['vencimento_pagamento'] = JFactory::getDate($creatPlots->plots_date_first_evento . ' + '. $months . ' months', $this->_siteOffset)->toFormat('%Y-%m-%d',true);
			else:
				$data['vencimento_pagamento'] = JFactory::getDate($creatPlots->plots_date_first_evento , $this->_siteOffset)->toFormat('%Y-%m-%d',true);
			endif;
			
			$row = $this->getTable('pagamento');
			if ( !$row->bind($data)) 
				return false;	
			
			if ( !$row->check($data)) 
				return false;	
			
			if ( !$row->store($data) ) 
				return false;
			
			//sistema antigo //
			$cb_id = $row->get('id_pagamento');

			$query->clear();
			$query->insert( $this->_db->quoteName('cobranca') );
			$query->columns($this->_db->quoteName(array('cb_id',
														'cb_idcentrocustos',
														'cb_vencimento',
														'cb_valor',
														'cb_ordem',
														'cb_tipo',
														'cb_taxa', 
														'cb_destino'
														)));	
			
			$values = array($this->_db->quote($cb_id),
							$this->_db->quote($creatPlots->cc_id),
							$this->_db->quote($data['vencimento_pagamento']),
							$this->_db->quote($valuePlot),
							$this->_db->quote($ordering),
							$this->_db->quote('09'),
							$this->_db->quote('3.60'),
							$this->_db->quote('Bradesco'));
			
			$query->values(implode(',', $values));
		
			$this->_db->setQuery($query);
			$this->_db->query();
			//fim do sistema antigo //
			
			$months++;
		}
		return true;
	}
		
	function updateCentroCustosINEJE( $options = array() )
	{

		$query = $this->_db->getQuery(true);
		$query->select($this->_db->quoteName(array( 'ineje_pagamento.id_pagamento',
													'id_centro_custos',
													'period_plano',
													'period_amount_plano',
													'validity_plano',
													'dias_atraso_plano',
													'validity_amount_plano',
													'data_contratacao_centro_custos',
													'data_vencimento_centro_custos',
													'text_centro_custos',
													'ineje_centro_custos.user_id',
													'name',
													'email',
													)));
		$query->from($this->_db->quoteName('ineje_pagamento'));
		$query->innerJoin($this->_db->quoteName('ineje_centro_custos') . ' USING(' . $this->_db->quoteName('id_centro_custos') . ')');	
		$query->innerJoin($this->_db->quoteName('ineje_plano') . ' USING(' . $this->_db->quoteName('id_plano') . ')');	
		$query->innerJoin($this->_db->quoteName('ineje_users') . ' ON(' . $this->_db->quoteName('ineje_centro_custos.user_id') . '=' . $this->_db->quoteName('ineje_users.id') . ')');	
		$query->where( $this->_db->quoteName('ineje_pagamento.cb_id') . '=' . $this->_db->quote(  $options['id_pagamento'] ) );
		$this->_db->setQuery($query);
		$infoUpdate = $this->_db->loadObject();

		switch ($infoUpdate->period_plano) {
			case 1:		$period = "hours";	break;
			case 2:		$period = "days";	break;
			case 3:		$period = "months";	break;
			case 4:		$period = "years";	break;
			default:	$period = "months";	break;
		}
		
		switch ($infoUpdate->validity_plano) {
			case 1:		$validPeriod = "hours";		break;
			case 2:		$validPeriod = "days";		break;
			case 3:		$validPeriod = "months";	break;
			case 4:		$validPeriod = "years";		break;
			default:	$validPeriod = "months";	break;
		}	
		
		if(JFactory::getDate( $options['confirmado_pagamento'], $this->_siteOffset)->toFormat('%Y-%m-%d') <= JFactory::getDate( $infoUpdate->data_vencimento_centro_custos . '  + ' . $infoUpdate->period_amount_plano . ' ' . $period, $this->_siteOffset)->toFormat('%Y-%m-%d')):
			$dataVencimento = JFactory::getDate( $infoUpdate->data_vencimento_centro_custos . '  + ' . $infoUpdate->period_amount_plano . ' ' . $period, $this->_siteOffset)->toFormat('%Y-%m-%d') ;
		else:
			$dataVencimento = JFactory::getDate( $options['confirmado_pagamento'] . '  + ' . $infoUpdate->period_amount_plano . ' ' . $period, $this->_siteOffset)->toFormat('%Y-%m-%d');
		endif;

		if($infoUpdate->dias_atraso_plano > 0):
			$options1 = array();
			$options1['data_vencimento_centro_custos'] = $infoUpdate->data_vencimento_centro_custos ;
			$options1['dias_atraso_plano'] = $infoUpdate->dias_atraso_plano;
			$options1['dataVencimento'] = $dataVencimento;
			$options1['user_id'] = $infoUpdate->user_id;
			$this->updatePlanosControle( $options1 );
		endif;
		
		$query->clear();
		$fields = array(
			$this->_db->quoteName('data_vencimento_centro_custos') . '=' . $this->_db->quote( $dataVencimento ),
			$this->_db->quoteName('data_pago_centro_custos') . '=' . $this->_db->quote( $options['confirmado_pagamento'] ),
			$this->_db->quoteName('parcela_centro_custos') . '=' . $this->_db->quoteName( 'parcela_centro_custos' ) . ' + 1',
		);
		$conditions = array(
			$this->_db->quoteName('id_centro_custos') . '=' . $this->_db->quote( $infoUpdate->id_centro_custos )			 
		);
		$query->update($this->_db->quoteName('ineje_centro_custos'))->set($fields)->where($conditions);
		$this->_db->setQuery($query);
		if ( !((boolean) $this->_db->query()))
			return false;	


		$query->clear();
		$fields = array(
			$this->_db->quoteName('confirmado_pagamento') . '=' . $this->_db->quote( $options['confirmado_pagamento'] ),
			$this->_db->quoteName('valorpago_pagamento') . '=' . $this->_db->quote( $options['valorpago_pagamento'] ),
			$this->_db->quoteName('update_pagamento') . '=' . $this->_db->quote( $options['confirmado_pagamento'] ),
			$this->_db->quoteName('resposta_pagamento') . '=' . $this->_db->quote( 'Baixa via Arquivo de Retorno' ),
		);
		$conditions = array(
			$this->_db->quoteName('id_pagamento') . '=' . $this->_db->quote( $infoUpdate->id_pagamento )			 
		);
		$query->update($this->_db->quoteName('ineje_pagamento'))->set($fields)->where($conditions);
		$this->_db->setQuery($query);
		if ( !((boolean) $this->_db->query()))
			return false;

		$this->unblockUser($infoUpdate->id_pagamento);
	
		return true;
		
	}
	
	function updatePlanosControle( $options = array() )
	{

		$data_vencimento_dias = JFactory::getDate($options['data_vencimento_centro_custos'] . '+ '.$options['dias_atraso_plano'].' day',  $this->_siteOffset)->toFormat('%Y-%m-%d', true) ;

		$query = $this->_db->getQuery(true);
		$fields = array(
			$this->_db->quoteName('vencimento_plano_controle_dist') . '=' . $this->_db->quote( $options['dataVencimento'] ),
		);
		$conditions = array(
			$this->_db->quoteName('user_id') . '=' . $this->_db->quote( $options['user_id'] ),
			$this->_db->quoteName('data_plano_controle_dist') . '>' . $this->_db->quote( $options['data_vencimento_centro_custos'] ),
			$this->_db->quoteName('vencimento_plano_controle_dist') . '=' . $this->_db->quote( $data_vencimento_dias ),
					 
		);
		$query->update($this->_db->quoteName('ineje_plano_controle_dist'))->set($fields)->where($conditions);
		$this->_db->setQuery($query);
		if ( !((boolean) $this->_db->query()))
			return false;
			
			
		$query->clear();
		$fields = array(
			$this->_db->quoteName('vencimento_plano_controle') . '=' . $this->_db->quote( $options['dataVencimento'] ),
		);
		$conditions = array(
			$this->_db->quoteName('user_id') . '=' . $this->_db->quote( $options['user_id'] ),
			$this->_db->quoteName('data_plano_controle') . '>' . $this->_db->quote( $options['data_vencimento_centro_custos'] ),
			$this->_db->quoteName('vencimento_plano_controle') . '=' . $this->_db->quote( $data_vencimento_dias ),
					 
		);
		$query->update($this->_db->quoteName('ineje_plano_controle'))->set($fields)->where($conditions);
		$this->_db->setQuery($query);
		if ( !((boolean) $this->_db->query()))
			return false;
		
		return true;
	}
	
	function unblockUser( $id_pagamento = null )
	{

		$query = $this->_db->getQuery(true);
		$query->select($this->_db->quoteName(array( 'types_id',
													'pf_pj_id'
													)));
		$query->from($this->_db->quoteName('ineje_pagamento'));
		$query->innerJoin($this->_db->quoteName('ineje_centro_custos') . ' USING(' . $this->_db->quoteName('id_centro_custos') . ')');	
		$query->innerJoin($this->_db->quoteName('ineje_user_usertypes_map') . ' USING(' . $this->_db->quoteName('user_id') . ')');
		$query->where( $this->_db->quoteName('ineje_pagamento.id_pagamento') . '=' . $this->_db->quote( $id_pagamento ) );
		$this->_db->setQuery($query);
		if((boolean) $infoUpdate = $this->_db->loadObject() ):
		
			$type = 'pj'; 
			if($infoUpdate->types_id == 1)
				$type = 'pf'; 

			$query->clear();
			$fields = array(
				$this->_db->quoteName('block_'. $type) . ' = ' . $this->_db->quote( '0' )
			);
			$conditions = array(
				$this->_db->quoteName('id_' . $type) . '=' . $this->_db->quote( $infoUpdate->pf_pj_id )			 
			);
			$query->update($this->_db->quoteName('ineje_' . $type))->set($fields)->where($conditions);
			$this->_db->setQuery($query);
			$this->_db->query();
		endif;
		
		return true;
	}
	
	function sendMailConfirm()
	{
		
		$query = $this->_db->getQuery(true);
		
		if (empty($this->_data))
			$this->getItem();	
			
		$options = array();
		$options['email'] = $this->_data->email;
		$options['nome'] = $this->_data->sacado;
		$options['produto'] = $this->_data->produto;
		$options['idproduto'] = $this->_data->id_pagamento_produto;
		$options['ordering'] = $this->_data->ordering;
		$options['referencia'] = $this->_data->text_pagamento;
		$options['type'] = $this->_data->id_pagamento_produto_tipo;
		$options['id_pagamento'] = $this->_id; 
		$options['valorpago_pagamento'] = $this->_data->valorpago_pagamento ;
		$options['confirmado_pagamento'] = $this->_data->confirmado_pagamento;	
		
		
		$mailer = JFactory::getMailer(); 
		
		$mailer->isHTML(true);
		$mailer->Encoding = 'base64';

		$subject = JText::_('Confirmação de Pagamento');
		
		if($options['type']==1):
			$fromname = 'Faculdade Brasileira de Tributação';
			$mailfrom = 'contato@fbtedu.com.br';
			$sitename = 'FBT';
			if($options['ordering']==1):
				$textoConfirma = 'Seu pagamento referente a inscrição realizada em <strong>' . $options['produto'] . '</strong> na Faculdade Brasileira de Tributação (FBT) foi confirmado com sucesso.';
			else:
				$textoConfirma = 'Seu pagamento referente à <strong>'. $options['referencia'] . '</strong> de <strong>'. $options['produto'] . '</strong> da Faculdade Brasileira de Tributação (FBT) foi confirmada com sucesso.<br/><br/>';
			endif;
			
			$emailBody = '
				<style type="text/css">
					table{
						font-size: 14px !important; 
						font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; !important;  
						color:#333 !important; 
					}
					h2 { font-size: 1.5em !important; }
					p {	margin: 0 0 1.35em 0 !important; line-height: 142% !important; color:#666; 	}
					a { text-decoration:none !important; }
					.title { font-weight:bold !important;}
					.text{
						text-align:justify !important;  
					}
					.td-footer {
						padding:30px !important;
					}
					.span-footer {
						text-align:center !important; 
						color:#FFF !important;
					}
					.link-footer {
						text-decoration:none !important; 
						color:#FFF !important;
					}
				</style>
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tbody>
						<tr>
							<td align="center" valign="top">
								<table width="600" border="0" cellspacing="0" cellpadding="0" style="border:1px solid;">
									<tbody>
										<tr>
											<td valign="middle">
												<img src="cid:logo" alt="FBT" border="0" width="600" height="100"/>
											</td>
										</tr>
										<tr bgcolor="#F5F5F5">
											<td>
												<table width="100%" border="0" cellspacing="40" cellpadding="0" >
													<tbody>
														<tr>
															<td valign="middle" width="100%">
																<h2 class="title" style="font-weight:bold;">
																	Confirmação de Pagamento - FBT.<br/><br/>
																</h2>
																<p>
																	Olá <strong>'.$options['nome'].'</strong>,<br/>
																</p>
																<p class="text" style="text-align:justify; color:#666" >
																	'.$textoConfirma.'
																	Aproveite tudo que a FBT pode oferecer para você.<br/><br/>
																</p>
																<p>
																	<a href="http://www.fbtedu.com.br" class="link" target="_blank" style="text-decoration:none;" >Acesse o site da FBT clicando aqui</a>
																</p>		
															</td>
														</tr>
													</tbody>
												</table>		
											</td>
										</tr>
										<tr bgcolor="#252525">	
											<td align="center" valign="middle" class="td-footer" style="padding:30px;">
												<span class="span-footer" style="text-align:center; color:#FFF;">
													Rua Mostardeiro, 88 - Independência - Porto Alegre - RS - Brasil<br/>
													Porto Alegre e Região Metropolitana:&nbsp;<a href="#" class="link-footer" style="text-decoration:none; color:#FFF" >+55 (51) 3073-1001</a><br/>Demais Regiões:&nbsp;0800 602 0700<br/>
													<a href="mailto:contato@fbtedu.com.br" class="link-footer" target="_blank" style="text-decoration:none; color:#FFF" >contato@fbtedu.com.br</a><br/>
												</span>
											</td>
										</tr>	
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>';
			$mailer->AddEmbeddedImage( JPATH_BASE .DS. 'views' .DS. 'system' .DS. 'images' .DS. 'logo_email_fbt.jpg' , 'logo', 'logo.jpg', 'base64', 'image/jpg' );
		elseif($options['type']==2):
			
			$fromname = 'INEJE - Instituto Nacional de Estudos Jurídicos e Empresariais';
			$mailfrom = 'ineje@ineje.com.br';
			$sitename = 'INEJE';
			
			if($options['ordering']==1):
				$textoConfirma = 'Seu pagamento referente à contratação do <strong>' . $options['produto'] . '</strong> do Instituto Nacional de Estudos Jurídicos e Empresariais (INEJE) foi confirmado com sucesso.<br/><br/>';
			else:
				$textoConfirma = 'Seu pagamento referente à <strong>'. $options['referencia'] . '</strong> do <strong>'. $options['produto'] . '</strong> do Instituto Nacional de Estudos Jurídicos e Empresariais (INEJE) foi confirmado com sucesso.<br/><br/>';
			endif;
			
			$emailBody = '
				<style type="text/css">
					table{
						font-size: 14px !important; 
						font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; !important;  
						color:#333 !important; 
					}
					h2 { font-size: 1.5em !important; }
					p {	margin: 0 0 1.35em 0 !important; line-height: 142% !important; color:#666; 	}
					a { text-decoration:none !important; }
					.title { font-weight:bold !important;}
					.text{
						text-align:justify !important;  
					}
					.td-footer {
						padding:30px !important;
					}
					.span-footer {
						text-align:center !important; 
						color:#FFF !important;
					}
					.link-footer {
						text-decoration:none !important; 
						color:#FFF !important;
					}
				</style>
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tbody>
						<tr>
							<td align="center" valign="top">
								<table width="600" border="0" cellspacing="0" cellpadding="0" style="border:1px solid;">
									<tbody>
										<tr>
											<td valign="middle">
												<img src="cid:logo" alt="FBT" border="0" width="600" height="100"/>
											</td>
										</tr>
										<tr>
											<td>
												<table width="100%" border="0" cellspacing="40" cellpadding="0" >
													<tbody>
														<tr>
															<td valign="middle" width="100%">
																<h2 class="title" style="font-weight:bold;">
																	Confirmação de Pagamento - INEJE.<br/><br/>
																</h2>
																<p>
																	Olá <strong>'.$options['nome'].'</strong>,<br/>
																</p>
																<p class="text" style="text-align:justify; color:#666" >
																	'. $textoConfirma . '
																	Aproveite tudo que o INEJE pode oferecer para você.<br/><br/>
																</p>
																<p>
																	<a href="http://site.ineje.com.br/" class="link" target="_blank" style="text-decoration:none;" >Acesse o site do INEJE clicando aqui</a>
																</p>		
															</td>
														</tr>
													</tbody>
												</table>		
											</td>
										</tr>
										<tr bgcolor="#0a3151">	
											<td align="center" valign="middle" class="td-footer" style="padding:30px;">
												<span class="span-footer" style="text-align:center; color:#FFF;">
													Rua Mostardeiro, 88 - Independência - Porto Alegre - RS - Brasil<br/>
													Porto Alegre e Região Metropolitana:&nbsp;<a href="#" class="link-footer" style="text-decoration:none; color:#FFF" >+55 (51) 3073-1001</a><br/>Demais Regiões:&nbsp;0800 602 0700<br/>
													<a href="mailto:contato@ineje.com.br" class="link-footer" target="_blank" style="text-decoration:none; color:#FFF" >contato@ineje.com.br</a><br/>
												</span>
											</td>
										</tr>	
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>';

			$mailer->AddEmbeddedImage( JPATH_BASE .DS. 'views' .DS. 'system' .DS. 'images' .DS. 'logo_email_ineje.jpg' , 'logo', 'logo.jpg', 'base64', 'image/jpg' );

		endif;

		$recipient = $options['email'];

		$mailer->setSender( array( $mailfrom, $fromname ) );
		$mailer->addRecipient($recipient);
		
		$Bcc[] = 'everton-schuh@hotmail.com';
		
		if($options['ordering']==1):
			$Bcc[] = 'everton-schuh@hotmail.com';
			$Bcc[] = 'ead@fbtedu.com.br';
			$Bcc[] = 'secretaria@fbtedu.com.br';
			//$Bcc[] = 'coordenacaoacademica@fbtedu.com.br';
			$Bcc[] = 'financeiro@fbtedu.com.br';
			$Bcc[] = 'comunicado@fbtedu.com.br';
			//$Bcc[] = 'supervisao@fbtedu.com.br';
			//Bcc[] = 'vendas1@fbtedu.com.br';
			$Bcc[] = 'contato@fbtedu.com.br';
			//$Bcc[] = 'felipe@fbtedu.com.br';
			//$Bcc[] = 'luizfilho@ineje.com.br';
		endif;
		$mailer->addBcc($Bcc);
		
		$mailer->setSubject($subject);
		$mailer->setBody($emailBody);

		$send = $mailer->Send();	
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
	
	
	function setNfe()
	{		

		$setNFSe =  new NFSe();

	}
	

	function isCheckedOut()
	{		

		$this->addTablePath(JPATH_BASE.'/tables');
	    $row = $this->getTable('pagamento');
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
	    $row = $this->getTable('pagamento');
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
	    $row = $this->getTable('pagamento');
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
