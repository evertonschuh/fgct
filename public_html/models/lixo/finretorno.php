<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');


class IntranetModelFinRetorno extends JModel 
{

	var $_db = null;
	var $_app = null;
	var $_user = null;
	var $_cids = null;
	var $_siteOffset = null;

	
	function __construct()
	{
		parent::__construct();
		$this->_app 	= JFactory::getApplication(); 
		$this->_db	= JFactory::getDBO();
		$this->_user		= JFactory::getUser();
		$this->_siteOffset = $this->_app->getCfg('offset');

	}


	function getReport()
	{
	//	$cids = JRequest::getVar( 'cid',	array(), '', 'array' );
		$filename = JRequest::getVar( 'file', 'GET' );
		$sessionid = JRequest::getVar( 'session', 'GET' );
		//$typesArray = $this->getConteudoTipos();
	
		require_once(JPATH_LIBRARIES . DS . 'boleto' . DS . 'Cnab' . DS . 'Retorno' . DS . 'Factory.php');
	 	$file = JPATH_BASE .DS. 'cache' .DS. $sessionid .DS. $filename;
		
		$retorno = Factory::make($file);
		$retorno->processar();
		$detalhes = $retorno->getDetalhes();			
		$boletos = array();
		

		if(count($detalhes)> 0):
			$config   = JFactory::getConfig();
			$siteOffset = $config->getValue('offset');
		
			$query = $this->_db->getQuery(true);
					

			foreach ($detalhes as $detalhe):
				$item = $detalhe->toArray($detalhe);

				if($item['numeroDocumento'])
					$id_pagamento =  (int) $item['numeroDocumento'];
				else
					$id_pagamento =  (int) substr($item['nossoNumero'], 0, -1);
					
	
				
				$query->clear();
				$query->select($this->_db->quoteName(  array('#__intranet_pagamento.id_pagamento',
															 'id_anuidade',
															 'text_pagamento',
															 'name',
															// 'name_anuidade',
															 'vencimento_pagamento',
															 'registrado_pagamento',
															 'baixa_pagamento',
															 'status_pagamento',
															 'taxa_pagamento',
															 'valor_pagamento',
															 'valor_pago_pagamento',
															 'id_user',
															 'name_pagamento_metodo',
															 'button_pagamento_metodo',
															 'icon_pagamento_metodo',
															 '#__intranet_pagamento.checked_out',
															 '#__intranet_pagamento.checked_out_time',
															 )));	
				$query->select( 'IF( ISNULL(name_anuidade), name_produto, name_anuidade)  AS produto');											 
				$query->select('DATEDIFF(vencimento_pagamento, CURDATE()) AS dias_pagamento');	
						
				$query->from($this->_db->quoteName('#__intranet_pagamento'));
				$query->innerJoin($this->_db->quoteName('#__intranet_pagamento_metodo') . ' USING(' . $this->_db->quoteName('id_pagamento_metodo'). ')');
				$query->leftJoin($this->_db->quoteName('#__intranet_anuidade') . ' USING(' . $this->_db->quoteName('id_anuidade'). ')');
				$query->leftJoin($this->_db->quoteName('#__intranet_produto') . ' USING(' . $this->_db->quoteName('id_produto'). ')');
				$query->innerJoin($this->_db->quoteName('#__users') . ' ON(' . $this->_db->quoteName('id_user'). '=' . $this->_db->quoteName('id'). ')');
				
	
				
				$query->where( $this->_db->quoteName('#__intranet_pagamento.id_pagamento') . '=' . $this->_db->quote($id_pagamento) ) ;
				$this->_db->setQuery($query);
					
				if( !(boolean) $_pagamento = $this->_db->loadObject()) :
					$_pagamento  = new stdClass();
					$_pagamento->id_pagamento = $id_pagamento;
					$_pagamento->name=  'NÃO LOCALIZADA';
					$_pagamento->produto =  'NÃO LOCALIZADA';
					$_pagamento->valor_pagamento = (float) isset($_data['valor']) ? $_data['valor'] : '';
					$_pagamento->valor_pago_pagamento = (float) isset($_data['valorRecebido']) ? $_data['valorRecebido'] : '';
					$_pagamento->baixa_pagamento = isset($_data['dataOcorrencia']) ? $_data['dataOcorrencia'] : '';
					
					/*
					$dataPago = $_data['dataOcorrencia'];
					$pago = (float) $_data['valorRecebido'];
					$receber = (float) $_data['valorRecebido'] -  $_data['valorTarifa'];
					$taxa = $_data['valorTarifa'];
					$sacado = $_pagamento->name;
					$produto = $_pagamento->name_plano;
					$ocorrencia = $_data['ocorrenciaDescricao'];
					*/

				endif;

			$returnArray[] = $_pagamento;
			unset($_pagamento);					
			endforeach;


			
		endif;


		return $returnArray;
		
	}

	function import() 
	{

		$cids = JRequest::getVar( 'cid',	array(), '', 'array' );
		$filename = JRequest::getVar( 'filename', 'post' );
		$sessionid = JRequest::getVar( 'sessionid', 'post' );
		//$typesArray = $this->getConteudoTipos();
		
		
		require_once(JPATH_LIBRARIES . DS . 'boleto' . DS . 'Cnab' . DS . 'Retorno' . DS . 'Factory.php');
	 	$file = JPATH_BASE .DS. 'cache' .DS. $sessionid .DS. $filename;
		
		$retorno = Factory::make($file);
		$retorno->processar();
		$detalhes = $retorno->getDetalhes();			
		$boletos = array();
		if(count($detalhes)> 0):
		
			$config   = JFactory::getConfig();
			$siteOffset = $config->getValue('offset');
		
			$this->addTablePath(JPATH_ADMINISTRATOR.'/tables');
			$row = $this->getTable('pagamento');
			$query = $this->_db->getQuery(true);
					

			foreach ($detalhes as $detalhe):
				$item = $detalhe->toArray($detalhe);
	
				if($item['numeroDocumento'])
					$id_pagamento =  (int) $item['numeroDocumento'];
				else
					$id_pagamento =  (int) substr($item['nossoNumero'], 0, -1);
				

				if( in_array($id_pagamento, $cids) ) :
					
					$query->clear();
					$query->select($this->_db->quoteName(  array('#__intranet_pagamento.id_pagamento',
																 'id_anuidade',
																 'text_pagamento',
																 'name',
																// 'name_anuidade',
																 'vencimento_pagamento',
																 'registrado_pagamento',
																 'baixa_pagamento',
																 'status_pagamento',
																 'taxa_pagamento',
																 'valor_pagamento',
																 'valor_pago_pagamento',
																 'id_user',
																 'name_pagamento_metodo',
																 'button_pagamento_metodo',
																 'icon_pagamento_metodo',
																 '#__intranet_pagamento.checked_out',
																 '#__intranet_pagamento.checked_out_time',
																 )));	
					$query->select( 'IF( ISNULL(name_anuidade), name_produto, name_anuidade)  AS produto');											 
					$query->select('DATEDIFF(vencimento_pagamento, CURDATE()) AS dias_pagamento');	
							
					$query->from($this->_db->quoteName('#__intranet_pagamento'));
					$query->innerJoin($this->_db->quoteName('#__intranet_pagamento_metodo') . ' USING(' . $this->_db->quoteName('id_pagamento_metodo'). ')');
					$query->leftJoin($this->_db->quoteName('#__intranet_anuidade') . ' USING(' . $this->_db->quoteName('id_anuidade'). ')');
					$query->leftJoin($this->_db->quoteName('#__intranet_produto') . ' USING(' . $this->_db->quoteName('id_produto'). ')');
					$query->innerJoin($this->_db->quoteName('#__users') . ' ON(' . $this->_db->quoteName('id_user'). '=' . $this->_db->quoteName('id'). ')');
					
		
					
					$query->where( $this->_db->quoteName('#__intranet_pagamento.id_pagamento') . '=' . $this->_db->quote($id_pagamento) ) ;
					$this->_db->setQuery($query);
					$_pagamento = $this->_db->loadObject();
					
					$valorBaixa = (float) ( (float) $item['valorRecebido'] ) -  $_pagamento->taxa_pagamento;
					$dataBaixa = JFactory::getDate(implode("-",array_reverse(explode("/", $item['dataOcorrencia']))), $siteOffset)->toFormat('%Y-%m-%d', true);
	
					$row->load($id_pagamento);
					$row->update_pagamento = JFactory::getDate('now', $siteOffset)->toFormat('%Y-%m-%d', true);	
					$row->valor_pago_pagamento =  $valorBaixa ;
					$row->baixa_pagamento = $dataBaixa;	
					$row->resposta_pagamento = 'Baixa via Arquivo de Retorno';
	
					/*
					if ( !$row->bind($data)) 
						return false;	
						
					if ( !$row->check($data)) 
						return false;	
						*/
					if ( !$row->store() ) 
						return false;	
	
					jimport('joomla.log.log');
					JLog::addLogger(array( 'text_file' => 'log.retorno.php'));
					JLog::add($this->_user->get('id') . JText::_('		Baixa Pagamento -  idPg('.$id_pagamento.')'), JLog::INFO, 'finretorno');
					
					$options = array();
					//$options['email'] = $_pagamento->email;
					$options['nome'] = $_pagamento->name;
					//$options['produto'] = $_pagamento->produto;
					$options['id_user'] = $_pagamento->id_user;
					$options['id_anuidade'] = $_pagamento->id_anuidade;
					//$options['ordering'] = $_pagamento->ordering;
					$options['referencia'] = $_pagamento->text_pagamento;
					//$options['type'] = $_pagamento->id_pagamento_produto_tipo;				
					$options['id_pagamento'] = $_pagamento->id_pagamento; 
					$options['valor_pago_pagamento'] = $valorBaixa ;
					$options['baixa_pagamento'] = $dataBaixa;	
					
					if($options['baixa_pagamento'] && $options['id_anuidade'] > 0):
				
						$query->clear();
						$query->select($this->_db->quoteName(array( 'id_associado',
																	'status_associado',
																	'cadastro_associado',
																	'validate_associado')));
						$query->from('#__intranet_associado');	
					
						$query->where( $this->_db->quoteName('id_user') . '=' . $this->_db->quote( $options['id_user'] ) );
						$this->_db->setQuery($query);
						$associado = $this->_db->loadObject();
						
						$query->clear();	
						$query->select($this->_db->quoteName(array( 'id_anuidade',
																	'validate_anuidade')));
						$query->from('#__intranet_anuidade');	
					
						$query->where( $this->_db->quoteName('id_anuidade') . '=' . $this->_db->quote( $options['id_anuidade'] ) );
						$this->_db->setQuery($query);
						$anuidade = $this->_db->loadObject();
									
						if( JFactory::getDate($anuidade->validate_anuidade, $this->_siteOffset)->toFormat('%Y-%m-%d', true) >= JFactory::getDate($associado->validate_associado, $this->_siteOffset)->toFormat('%Y-%m-%d', true)):
						
							$query->clear();
							$fields = array(
								$this->_db->quoteName('confirmado_associado') . ' = ' . $this->_db->quote( JFactory::getDate($options['baixa_pagamento'], $this->_siteOffset)->toFormat('%Y-%m-%d', true) ),
								$this->_db->quoteName('validate_associado') . ' = ' . $this->_db->quote( JFactory::getDate($anuidade->validate_anuidade, $this->_siteOffset)->toFormat('%Y-%m-%d', true) ),
							);
							$conditions = array(
								$this->_db->quoteName('id_user') . '=' . $this->_db->quote( $options['id_user'] )			 
							);
							$query->update($this->_db->quoteName('#__intranet_associado'))->set($fields)->where($conditions);
							$this->_db->setQuery($query);
							if ( !((boolean) $this->_db->query()))
								return false;	
						
						
							if( JFactory::getDate($anuidade->validate_anuidade, $this->_siteOffset)->toFormat('%Y-%m-%d', true) >= JFactory::getDate('now', $this->_siteOffset)->toFormat('%Y-%m-%d', true)): 
			
								$query->clear();
								$fields = array( $this->_db->quoteName('block') . ' = ' . $this->_db->quote( '0' ) );
								$conditions = array( $this->_db->quoteName('id') . '=' . $this->_db->quote( $options['id_user'] ));	
								$query->update($this->_db->quoteName('#__users'))->set($fields)->where($conditions);
								$this->_db->setQuery($query);
								if ( !$this->_db->query())	
									return false;
							endif;
						endif;
					endif;
					//$this->sendMailConfirm($options);
				endif;
				
			endforeach;

		endif;
		
		return true;
	}
	
	function setCurrentPayment( $options = array() )
	{
		
		$query = $this->_db->getQuery(true);	
		$query->select('id_pagamento');
		$query->from($this->_db->quoteName('#__pagamento'));
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
	
	function sendMailConfirm( $options = array() )
	{
		
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
			$mailer->addBcc($Bcc);
		endif;

		$mailer->setSubject($subject);
		$mailer->setBody($emailBody);

		$send = $mailer->Send();	
	}
	
	function getConteudoTipos()
	{
		$query = $this->_db->getQuery(true);
		$query->select(array('id_conteudo_type', 
							 'table_conteudo_type',
							 'parent_conteudo_type',
							 ));
		$query->from('#__conteudo_type');
		$query->where( $this->_db->quoteName('status_conteudo_type') . ' = ' . $this->_db->quote( '1' ) );
		$query->order('table_conteudo_type ASC');
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();	
	}
	
	
	
	
}
