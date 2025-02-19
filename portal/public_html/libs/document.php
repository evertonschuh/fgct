<?php
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.model' );
class EASistemasLibsDocument {


	var $_db = null;
	var $_app = null;
	var $_user = null;
	var $_siteOffset = null;
	
 	public function __construct() {   

		$this->_db	= JFactory::getDBO();		
		$this->_app	 = JFactory::getApplication(); 
		$this->_user = JFactory::getUser();
		$this->_siteOffset = $this->_app->getCfg('offset');
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

	function getUserInfo($id_user = null)
	{
		$query = $this->_db->getQuery(true);	
		$query->select( $this->_db->quoteName(array('id',
													'name',
													'cadastro_associado',
													'validate_associado',
													'id_associado',
													'data_nascimento_pf',
													'image_pf',
													'cpf_pf',
													'numcr_pf',
													'vencr_pf',
													'logradouro_pf',
													'numero_pf',
													'bairro_pf',
													'complemento_pf',
													'cep_pf',
													'name_cidade',
													'name_estado',
													'sigla_estado',
												)));
		
		$query->from( $this->_db->quoteName('#__users') );
		$query->innerJoin( $this->_db->quoteName('#__intranet_pf') . 'ON('. $this->_db->quoteName('id'). ' = '. $this->_db->quoteName('id_user').')' );
		$query->innerJoin( $this->_db->quoteName('#__intranet_associado') . 'USING('. $this->_db->quoteName('id_user').')' );
		$query->leftJoin( $this->_db->quoteName('#__intranet_cidade') . 'USING('. $this->_db->quoteName('id_cidade').', '. $this->_db->quoteName('id_estado').')' );
		$query->leftJoin( $this->_db->quoteName('#__intranet_estado') . 'USING('. $this->_db->quoteName('id_estado'). ')' );
		
		$query->where($this->_db->quoteName('id') . ' = ' . $this->_db->quote( $id_user ));		

		$this->_db->setQuery($query);
		return $this->_db->loadObject();
	}


	function createDocument($options = array())
	{
	
		$userInfo = $this->getUserInfo($options['id_user']);//$options['id_user']);

		$printDoc['NUMERO_DOCUMENTO'] = $options['numero_documento'];
		$printDoc['DATA_HORA_EXTENSO'] = JHTML::_('date', JFactory::getDate('now', $this->_siteOffset)->toISO8601(true), 'DATE_FORMAT_LC2');
		$printDoc['NOME_ASSOCIADO'] = $options['user_info']->name;
		$printDoc['CPF_ASSOCIADO'] = $options['user_info']->cpf_pf;

		$printDoc['CR_ASSOCIADO'] = $options['user_info']->numcr_pf;
		$printDoc['MATRICULA_ASSOCIADO'] = $options['user_info']->id_associado;
		$printDoc['DT_REGISTRO_ASSOCIADO'] = JHTML::_('date', JFactory::getDate($options['user_info']->cadastro_associado, $this->_siteOffset)->toISO8601(true), 'DATE_FORMAT');


		$printDoc['client_name'] = $options['user_info']->name;
		$printDoc['client_cpf'] = $options['user_info']->cpf_pf;
		$printDoc['client_endereco'] = $options['user_info']->logradouro_pf 
										. ', ' . $options['user_info']->numero_pf 
										. (!empty($options['user_info']->complemento_pf) ? (' - ' . $options['user_info']->complemento_pf) : '')
										. ', ' . $options['user_info']->name_cidade .'/'. $options['user_info']->sigla_estado;

		$printDoc['client_cr'] = $options['user_info']->numcr_pf;
		$printDoc['client_cr_validade'] = $options['user_info']->vencr_pf ? JHTML::_('date', JFactory::getDate($options['user_info']->vencr_pf, $this->_siteOffset)->toISO8601(true), 'DATE_FORMAT') : 'Não Informado';

		$printDoc['client_nr'] = $options['user_info']->id_associado;
		$printDoc['client_validate'] = JHTML::_('date', JFactory::getDate($options['user_info']->validate_associado, $this->_siteOffset)->toISO8601(true), 'DATE_FORMAT');

		$textDocument = $options['text_documento'];


		if(strpos($textDocument, 'LISTA_PARTICIPACOES') !== false)
			$printDoc['LISTA_PARTICIPACOES'] = $this->getParticipacoes($options['id_user'], $printDoc['NUMERO_DOCUMENTO'], null, null);


		if(strpos($textDocument, 'LISTA_PARTICIPACOES_365') !== false)
			$printDoc['LISTA_PARTICIPACOES_365'] = $this->getParticipacoes($options['id_user'], $printDoc['NUMERO_DOCUMENTO'], null);
			//$textDocument = str_replace('{{LISTA_PARTICIPACOES_365}}', $this->getParticipacoes365($printDoc['NUMERO_DOCUMENTO'], null), $textDocument);

		if(strpos($textDocument, 'LISTA_PARTICIPACOES_ESTADUAL_365') !== false)
			$printDoc['LISTA_PARTICIPACOES_ESTADUAL_365'] = $this->getParticipacoes($options['id_user'], $printDoc['NUMERO_DOCUMENTO'], '1');
			//$textDocument = str_replace('{{LISTA_PARTICIPACOES_ESTADUAL_365}}', $this->getParticipacoes365($printDoc['NUMERO_DOCUMENTO'], '1'), $textDocument);

		if(strpos($textDocument, 'LISTA_PARTICIPACOES_NACIONAL_365') !== false)
			$printDoc['LISTA_PARTICIPACOES_NACIONAL_365'] = $this->getParticipacoes($options['id_user'], $printDoc['NUMERO_DOCUMENTO'], '2');
			//$textDocument = str_replace('{{LISTA_PARTICIPACOES_NACIONAL_365}}', $this->getParticipacoes365($printDoc['NUMERO_DOCUMENTO'], '2'), $textDocument);

		if(strpos($textDocument, 'client_habituality') !== false)
			$printDoc['client_habituality'] = $this->getHabituality($options['id_user']);


		if (preg_match_all("/{{SE}}(.*?){{\/SE}}/", $textDocument, $m)) {
			foreach ($m[1] as $i => $varname) {
				if (preg_match_all("/{{(.*?)}}/", $varname, $g)) {
					foreach ($g[1] as $y => $varname1) {
						$SeText ='';
						if(!empty($printDoc[$varname1]))
							$SeText = str_replace($g[0][$y], $printDoc[$varname1], $varname);
							//$SeText = str_replace($g[0][$y], sprintf($printDoc[$varname1], $varname1), $varname);

						$textDocument = str_replace($m[0][$i], $SeText, $textDocument);
					}
				}
			}
		}
		/*
		if (preg_match_all("/{{(.*?)}}/", $textDocument, $m)) {
			foreach ($m[1] as $i => $varname) {
				
				//$textDocument = str_replace($m[0][$i], sprintf($printDoc[$varname], $varname), $textDocument);
				$textDocument = str_replace($m[0][$i], $printDoc[$varname], $textDocument);
			}
		}*/

		$loads = array();
		if (preg_match_all("/{{(.*?)}}/", $textDocument, $m)) {

			foreach ($m[1] as $i => $varname) {				
				$temp_varname = explode('|',$varname);
				if(count($temp_varname) > 1){
					$optionsSign = array();
					switch($temp_varname[0]){
						case 'SIGNATURE':
							$optionsSign['id_signature'] = $temp_varname[1];
							if(!isset($loads['SIGNATURE'][$optionsSign['id_signature']]))
								$loads['SIGNATURE'][$optionsSign['id_signature']] = $this->getSignature($optionsSign);
								$textDocument = str_replace($m[0][$i], ($loads['SIGNATURE'][$optionsSign['id_signature']]['signature_signature']), $textDocument);
						break;
					}
					unset($temp_varname);
				} 
				else {
					if(isset($printDoc[$varname]))
					$textDocument = str_replace($m[0][$i], $printDoc[$varname], $textDocument);
				}
			}
		}

		$response = new stdClass();
		$response->loads = $loads;
		$response->textDocument = $textDocument;

		return $response;

	}

	function getSignature($options = array())
	{

		$this->_db	= JFactory::getDBO();
		$query = $this->_db->getQuery(true);
		$query->select('*');
		$query->from('#__intranet_signature');	
		$query->where($this->_db->quoteName('id_signature').'='.$this->_db->quote($options['id_signature']));
		$this->_db->setQuery($query);
		return $this->_db->loadAssoc();
	}

	
	/*
	function setSignature($options = array())
	{

		$contentpfx = file_get_contents(JPATH_CDN . DS . 'certificate' . DS . $options['certificate_signature']);

		if (!openssl_pkcs12_read($contentpfx, $cert_info, $options['password_signature'])) {
			echo "Error: Unable to read the cert store.\n";
			return false;
		}

		//Informações da assinatura - Preencha com os seus dados
		$info = array(
		   'Name' => $options['name_signature'],
		   'Location' => 'Porto Alegre - Rio Grande do Sul',
		   'Reason' => 'Comprovação de veracidade de documento digital criado por meio do porta do associado da FGCT',
		   'ContactInfo' => 'Federação Gaúcha de Caça e Tiro - FGCT',
		);
			

		$pdfFile =  md5(uniqid()) .'.pdf';

		$name = JPATH_CACHE . DS . $pdfFile;

		file_put_contents($name, $options['data']); 

		$CartaPortrait = array(610,790);

		$pdf = new Fpdi('P','px', $CartaPortrait);

		//Configura a assinatura. Para saber mais sobre os parâmetros
		//consulte a documentação do TCPDF, exemplo 52.
		//Não esqueça de mudar 'senha' para a senha do seu certificado
		//$pdf->setSignatureAparence();
		//$pdf->setSignature('file://'.$certificateCrt, 'file://'.$certificateCrt, $options['password_signature'],'', 2, $info);
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);

        $pageCount = $pdf->setSourceFile($name);
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $templateId = $pdf->importPage($pageNo);
            $pdf->AddPage();
            $pdf->useTemplate($templateId, 0, 0);

        }

		$pdf->setSignature($cert_info['cert'], $cert_info['pkey'], '','', 2, $info, 'A');


		// create content for signature (image and/or text)
		$pdf->Image('images/tcpdf_signature.png', 180, 60, 15, 15, 'PNG');

		// define active area for signature appearance
		$pdf->setSignatureAppearance(180, 60, 15, 15);

		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

		// *** set an empty signature appearance ***
		$pdf->addEmptySignatureAppearance(180, 80, 15, 15);
		

		exit;


		ob_clean();

		return $pdf->Output('','S');

	}
*/

	function getHabituality( $id_user = null, $numero_doc = null ) 
	{
		$query = $this->_db->getQuery(true);
		$query->select( $this->_db->quoteName(array('#__ranking_inscricao.id_inscricao',
													'#__ranking_inscricao_etapa.id_inscricao_etapa',
													'#__intranet_arma.registro_arma',
													'#__intranet_calibre.id_calibre',
													'#__intranet_calibre.name_calibre',
													

													'#__ranking_etapa.data_beg_etapa',
													'#__ranking_etapa.data_end_etapa',
													'#__ranking_prova.id_prova',
													'#__ranking_prova.name_prova',
													'#__ranking_prova.abrangencia_prova',
													
													'#__ranking_resultado.date_register_resultado',

													/*'#__ranking_modalidade.name_modalidade',
													'#__ranking_modalidade.id_modalidade',
													'#__ranking_campeonato.name_campeonato',
													'#__ranking_campeonato.id_campeonato',
													'#__ranking_campeonato.ano_campeonato',
													
													'#__ranking_prova.id_prova',
													'#__ranking_prova.decimal_prova',
													'#__ranking_prova.name_prova',
													'#__ranking_prova.decimal_prova',
													'#__ranking_prova.type_prova',
													'#__ranking_prova.nr_etapa_prova',
													'#__ranking_prova.ns_etapa_prova',
													'#__ranking_prova.ns_variavel_prova',
													'#__ranking_prova.rs_etapa_prova',
													'#__ranking_prova.rf_etapa_prova',
													'#__ranking_prova.shot_off_prova',
													'#__ranking_prova.equipe_prova',
													'#__ranking_prova.nr_equipe_prova',
													'#__ranking_prova.special_names_prova',
													
													'#__ranking_inscricao.id_genero',
													'#__ranking_inscricao.id_categoria',
													'#__ranking_inscricao.id_classe',

													'#__ranking_etapa.id_etapa',
													'#__ranking_etapa.name_etapa',
													'#__ranking_etapa.data_beg_etapa',
													'#__ranking_etapa.data_end_etapa',
														
													'#__intranet_cidade.name_cidade',	
													'#__intranet_estado.sigla_estado',	*/											
													)));
		

		$query->select('IF(ISNULL(#__intranet_calibre.restrict_calibre), 9, #__intranet_calibre.restrict_calibre) AS restrict_calibre');
		$query->select('IF(#__ranking_prova.rf_inscricao_prova=1 , SUM(#__ranking_inscricao_etapa_arma_map.tiros), MAX(#__ranking_inscricao_etapa_arma_map.tiros)) AS tiros');


		$query->from( $this->_db->quoteName('#__ranking_inscricao') );
		
		$query->innerJoin( $this->_db->quoteName('#__ranking_inscricao_etapa') . ' USING('. $this->_db->quoteName('id_inscricao').')' );

		$query->innerJoin( $this->_db->quoteName('#__ranking_campeonato') . ' USING('. $this->_db->quoteName('id_campeonato').')' );
		$query->innerJoin( $this->_db->quoteName('#__ranking_modalidade') . ' USING('. $this->_db->quoteName('id_modalidade').')' );
		$query->innerJoin( $this->_db->quoteName('#__ranking_prova') . ' USING('. $this->_db->quoteName('id_prova').')' );
		
		$query->innerJoin( $this->_db->quoteName('#__ranking_resultado') . ' USING('. $this->_db->quoteName('id_inscricao').', '. $this->_db->quoteName('id_etapa').')' );
		
		$query->innerJoin( $this->_db->quoteName('#__ranking_etapa') . ' USING('. $this->_db->quoteName('id_etapa').')' );



		
	//	$query->innerJoin( $this->_db->quoteName('#__ranking_genero') . ' USING('. $this->_db->quoteName('id_genero').')' );
		//$query->innerJoin( $this->_db->quoteName('#__ranking_categoria') . ' USING('. $this->_db->quoteName('id_categoria').')' );		
		//$query->innerJoin( $this->_db->quoteName('#__ranking_classe') . ' USING('. $this->_db->quoteName('id_classe').')' );
		$query->innerJoin( $this->_db->quoteName('#__users') . 'ON('. $this->_db->quoteName('id'). ' = '. $this->_db->quoteName('#__ranking_inscricao.id_user').')' );
		
		//$query->leftJoin( $this->_db->quoteName('#__intranet_pj') . 'ON('. $this->_db->quoteName('#__ranking_inscricao_etapa.id_local'). ' = '. $this->_db->quoteName('#__intranet_pj.id_user').')' );
	// 	
	   // $query->leftJoin( $this->_db->quoteName('#__intranet_cidade') . 'USING ('.  $this->_db->quoteName('id_cidade').')' );
	//	$query->leftJoin( $this->_db->quoteName('#__intranet_estado') . 'ON('. $this->_db->quoteName('#__intranet_cidade.id_estado'). ' = '. $this->_db->quoteName('#__intranet_estado.id_estado').')' );
		
	

		$query->leftJoin( $this->_db->quoteName('#__ranking_inscricao_etapa_arma_map') . ' USING('. $this->_db->quoteName('id_inscricao_etapa').')' );
		$query->leftJoin( $this->_db->quoteName('#__intranet_arma') . ' USING('. $this->_db->quoteName('id_arma').')' );
		$query->leftJoin( $this->_db->quoteName('#__intranet_calibre') . ' USING('. $this->_db->quoteName('id_calibre').')' );
		
		$query->where($this->_db->quoteName('#__ranking_inscricao.id_user') . ' = ' . $this->_db->quote( $id_user ));


		$query->where( $this->_db->quoteName('block') . ' = ' . $this->_db->escape('0') );
		
		
		$query->where($this->_db->quoteName('status_resultado') . ' = ' . $this->_db->quote('1'));
		$query->where($this->_db->quoteName('status_inscricao') . ' = ' . $this->_db->quote('1'));
		$query->where($this->_db->quoteName('status_modalidade') . ' = ' . $this->_db->quote('1'));	
		$query->where($this->_db->quoteName('status_campeonato') . ' = ' . $this->_db->quote('1'));
		$query->where($this->_db->quoteName('status_prova') . ' = ' . $this->_db->quote('1'));
		$query->where($this->_db->quoteName('status_etapa') . ' = ' . $this->_db->quote('1'));
		$query->where($this->_db->quoteName('status_inscricao_etapa') . ' = ' . $this->_db->quote('1'));

		//if($abrangencia)
		//	$query->where( $this->_db->quoteName('#__ranking_prova.abrangencia_prova') . '=' . $this->_db->quote( $abrangencia ) );
		$query->where( $this->_db->quoteName('#__ranking_etapa.data_beg_etapa') . ' >= ' . $this->_db->quote( JFactory::getDate('now -1 year', $this->_siteOffset)->format('Y-m-d',true) ) );
		
		
		
		$query->order($this->_db->quoteName('restrict_calibre'));
		$query->order($this->_db->quoteName('#__intranet_calibre.name_calibre'));

		$query->order($this->_db->quoteName('#__ranking_etapa.data_beg_etapa') . ' DESC');
		$query->order($this->_db->quoteName('#__ranking_campeonato.ordering'));
		$query->order($this->_db->quoteName('#__ranking_etapa.ordering'));
		$query->order($this->_db->quoteName('#__ranking_prova.ordering'));
		//	$query->order($this->_db->quoteName('#__ranking_genero.ordering'));
		//	$query->order($this->_db->quoteName('#__ranking_categoria.ordering'));	
		//	$query->order($this->_db->quoteName('#__ranking_classe.ordering'));

		
		//$query->group($this->_db->quoteName('id_prova'));
		$query->group($this->_db->quoteName('id_inscricao_etapa'));
		$query->group($this->_db->quoteName('id_arma'));
		//$query->group($this->_db->quoteName('date_register_resultado'));
		
		
		$this->_db->setQuery($query);
		$items = $this->_db->loadObjectList();

		$html = '';
		if(count($items) > 0):	

			/*

			$html .= '<table cellpadding="0" cellspacing="0" border="1" style="border: 1px" width="100%">';
			$html .= '<tbody>';
			$html .= '<tr align="center">';
			$html .= '<td width="30%"><strong>DATA</strong></td>';
			$html .= '<td width="40%"><strong>EVENTO/ATIVIDADE</strong></td>';
			$html .= '<td width="5%"><strong> ARMA </strong></td>';
			$html .= '<td width="5%"><strong> CAL </strong></td>';
			$html .= '<td width="5%"><strong> QTD. <br/> MUN. </strong></td>';
			$html .= '<td width="30%"><strong>LOCAL</strong></td>';
			$html .= '</tr>';
			*/
			$newHtmlFooter = '';
			$htmlFooter = '';
			$ordem = 0;
			$pages = 1;
			$lines = 0;
			$linesCumulate = 0;
			foreach ( $items as $i => $item ) :
				$ordem++; 
				$lines++;
				if($i == 0 || $items[$i -1]->id_calibre != $item->id_calibre):

					$html .= '<table cellpadding="0" cellspacing="0" border="1" style="border: 1px; font-size:14px" width="100%">';
					$html .= '<thead style="padding: 5px; ">';
					$html .= '<tr align="center">';
					if($item->restrict_calibre !== '' && !empty($item->name_calibre)):
						$html .= '<th colspan="3">Calibre de uso '.($item->restrict_calibre == '0' ? 'permitido' : 'restrito').'</th>';
						$html .= '<th style="padding: 5px;">'.$item->name_calibre.'</th>';
					else:
						$html .= '<th colspan="3">Calibre - Reg ant Port 166</th>';
						$html .= '<th style="padding: 5px;"></th>';
					endif;
					$html .= '<th style="padding: 5px;">Tipo de Evento</th>';
					$html .= '</tr>';
					$html .= '<tr align="center">';
					$html .= '<th style="padding: 5px;">Ordem</th>';
					$html .= '<th style="padding: 5px;">Data-hora</th>';
					$html .= '<th style="padding: 5px;">SIGMA</th>';
					$html .= '<th style="padding: 5px;">Qtd Munição</th>';
					$html .= '<th style="padding: 5px;">Treinamento ou Competição (Estadual, Distrital, Regional, Nacional ou Internacional)</th>';
					$html .= '</tr>';
					$html .= '</thead>';
					$html .= '<tbody>';
					$lines++;
					$lines++;

				endif;

				$html .= '<tr align="center">';
				$html .= '<td style="padding: 5px;">'.$ordem.'</td>';
				$html .= '<td style="padding: 5px;">' . JHTML::_('date', JFactory::getDate( $item->date_register_resultado, $this->_siteOffset)->toISO8601(true), 'DATE_FORMAT_DATATIME') .'</td>';
				/*
				$html .= '<td>';
				if ($item->data_beg_etapa != $item->data_end_etapa) : 
					$html .= JHTML::_('date', JFactory::getDate( $item->data_beg_etapa, $this->_siteOffset)->toISO8601(true), 'DATE_FORMAT_DATATIME');
					$html .= ' à ';
					$html .= JHTML::_('date', JFactory::getDate( $item->data_end_etapa, $this->_siteOffset)->toISO8601(true), 'DATE_FORMAT_DATATIME');
				else :
					$html .= JHTML::_('date', JFactory::getDate( $item->data_end_etapa, $this->_siteOffset)->toISO8601(true), 'DATE_FORMAT_DATATIME');
				endif;
				$html .= '</td>';*/
				
				$html .= '<td style="padding: 5px;">'.(!empty($item->registro_arma) ? $item->registro_arma : 'Reg ant Port 166').'</td>';
				$html .= '<td style="padding: 5px;">'.(!empty($item->tiros) ? $item->tiros : 'Reg ant Port 166').'</td>';
				$html .= '<td style="padding: 5px;">Competição '.($item->abrangencia_prova == 1 ? 'Estadual' : ($item->abrangencia_prova == 2 ? 'Nacional' : 'Internacional')).' - '.$item->name_prova.'</td>';
				$html .= '</tr>';


				$htmlFooter .= '<tr align="center">';
				$htmlFooter .= '<td colspan="2" style="padding: 5px;">Portal FGCT</td>';
				$htmlFooter .= '<td colspan="2" style="padding: 5px;">'.$item->id_inscricao_etapa.'</td>';
				$htmlFooter .= '<td style="padding: 5px;">' . JHTML::_('date', JFactory::getDate( $item->date_register_resultado, $this->_siteOffset)->toISO8601(true), 'DATE_FORMAT_DATATIME') .'</td>';
				$htmlFooter .= '</tr>';
				$linesCumulate++;


				if(	($pages == 1 && $lines > 4 )
					||
				   	($pages > 1 && (( $lines + $linesCumulate ) > 10 ) && ($i == count($items) - 1 || $items[$i+1]->id_calibre != $item->id_calibre) )
					||
					($pages > 1  && $lines >10)
				   ):
					$pages++;

					$html .= '</tbody>';
					$html .= '</table>';
					$html .= '<br/><br/><hr/><br/><div style="page-break-after: always;"></div>';
					$html .= '<div style="margin-top: 220px; margin-bottom: 20px">(CONTINUAÇÃO DECLARAÇÃO DE RANKING - FGCT '.$numero_doc.' - PÁGINA '.$pages.' DE PAGINATOTAL )</div>';
					$html .= '<table cellpadding="0" cellspacing="0" border="1" style="border: 1px; font-size:14px" width="100%">';
					if(!($i == count($items) - 1 || $items[$i+1]->id_calibre != $item->id_calibre)):
					
						$lines=2;

						$html .= '<thead>';
						$html .= '<tr align="center">';
						if($item->restrict_calibre !== '' && !empty($item->name_calibre)):
							$html .= '<th colspan="3">Calibre de uso '.($item->restrict_calibre == '0' ? 'permitido' : 'restrito').'</th>';
							$html .= '<th style="padding: 5px;">'.$item->name_calibre.'</th>';
						else:
							$html .= '<th colspan="3">Calibre - Reg ant Port 166</th>';
							$html .= '<th style="padding: 5px;"></th>';
						endif;
						$html .= '<th style="padding: 5px;">Tipo de Evento</th>';
						$html .= '</tr>';
						$html .= '<tr align="center">';
						$html .= '<th style="padding: 5px;">Ordem</th>';
						$html .= '<th style="padding: 5px;">Data-hora</th>';
						$html .= '<th style="padding: 5px;">SIGMA</th>';
						$html .= '<th style="padding: 5px;">Qtd Munição</th>';
						$html .= '<th style="padding: 5px;">Treinamento ou Competição (Estadual, Distrital, Regional, Nacional ou Internacional)</th>';
						$html .= '</tr>';
						$html .= '</thead>';
						$html .= '<tbody>';
					endif;

				endif;


				if($i == count($items) - 1 || $items[$i+1]->id_calibre != $item->id_calibre):

		
					$html .= $newHtmlFooter;

					$html .= '</tbody>';
					$html .= '<tfoot>';
					$html .= '<tr align="center">';
					$html .= '<th colspan="2" style="padding: 5px;">Sistema</th>';
					$html .= '<th colspan="2" style="padding: 5px;">Nr. Registro</th>';
					$html .= '<th style="padding: 5px;">Data do Lançamento</th>';
					$html .= '</tr>';
					$html .= $htmlFooter;
					$html .= '</tfoot>';


					$html .= '</table>';
					$html .= '<br/>';
					//$html .= '<br/><br/><hr/><br/>';

					$linesCumulate = 0;
					$newHtmlFooter = '';
					$htmlFooter = '';
					
				endif;


				if(	$pages > 1 && $linesCumulate > 8):

					
					$newHtmlFooter .= '</tbody>';
					$newHtmlFooter .= '<tfoot>';
					$newHtmlFooter .= '<tr align="center">';
					$newHtmlFooter .= '<th colspan="2" style="padding: 5px;">Sistema</th>';
					$newHtmlFooter .= '<th colspan="2" style="padding: 5px;">Nr. Registro</th>';
					$newHtmlFooter .= '<th style="padding: 5px;">Data do Lançamento</th>';
					$newHtmlFooter .= '</tr>';
					$newHtmlFooter .= $htmlFooter;
					$newHtmlFooter .= '</tfoot>';
					$newHtmlFooter .= '</table>';


					$newHtmlFooter .= '<br/><br/><hr/><br/><div style="page-break-after: always;"></div>';
					$newHtmlFooter .= '<div style="margin-top: 220px; margin-bottom: 20px">(CONTINUAÇÃO DECLARAÇÃO DE RANKING - FGCT '.$numero_doc.' - PÁGINA '.$pages.' DE PAGINATOTAL )</div>';
					$newHtmlFooter .= '<table cellpadding="0" cellspacing="0" border="1" style="border: 1px; font-size:14px" width="100%">';
					
					$newHtmlFooter .= '<tbody>';



					$htmlFooter = '';
					$pages++;
					$linesCumulate = 0;


				endif;


				/*

				$html .= '<tr align="center">';
				$html .= '<td>';
				if ($item->data_beg_etapa != $item->data_end_etapa) : 
					$html .= JHTML::_('date', JFactory::getDate( $item->data_beg_etapa, $this->_siteOffset)->toISO8601(true), 'DATE_FORMAT');
					$html .= ' à ';
					$html .= JHTML::_('date', JFactory::getDate( $item->data_end_etapa, $this->_siteOffset)->toISO8601(true), 'DATE_FORMAT');
				else :
					$html .= JHTML::_('date', JFactory::getDate( $item->data_end_etapa, $this->_siteOffset)->toISO8601(true), 'DATE_FORMAT');
				endif;
				$html .= '</td>';
				$html .= '<td>';
				$html .= $item->name_etapa . ' - ' . $item->name_prova;
				$html .= '</td>';
				$html .= '<td>&nbsp;</td>';
				$html .= '<td>&nbsp;</td>';
				$html .= '<td>&nbsp;</td>';
				$html .= '<td>';
				$html .= $item->name_cidade . '/' . $item->sigla_estado;
				$html .= '</td>';
				$html .= '</tr>';




				if($z > 10 && $i < count($items) - 1  ) :
					$z = 0;
					$paginas++;
					$html .= '</tbody>';
					$html .= '</table>';
					$html .= '<hr/><br/><div style="page-break-after: always;"></div>';
					//$html .= '<span style="page-break-before:always;">&nbsp</span>';
					$html .= '<div style="margin-top: 220px; margin-bottom: 50px">(CONTINUAÇÃO DECLARAÇÃO DE RANKING - FGCT '.$numero_doc.' - PÁGINA '.$paginas.' DE PAGINATOTAL )</div>';						
					
					$html .= '<table cellpadding="0" cellspacing="0" border="1" style="border: 1px" width="100%">';
					$html .= '<tbody>';
					$html .= '<tr align="center">';
					$html .= '<td width="30%"><strong>DATA</strong></td>';
					$html .= '<td width="40%"><strong>EVENTO/ATIVIDADE</strong></td>';
					$html .= '<td width="5%"><strong> ARMA </strong></td>';
					$html .= '<td width="5%"><strong> CAL </strong></td>';
					$html .= '<td width="5%"><strong> QTD. <br/> MUN. </strong></td>';
					$html .= '<td width="30%"><strong>LOCAL</strong></td>';
					$html .= '</tr>';

				endif;*/
			endforeach;


			$html = str_replace('PAGINATOTAL', $pages, $html);
		else:			
			$html = '<br/>Não há participações nos últimos 365 dias<br/>';
		endif;

		return $html;


	}


	
	function getParticipacoes( $id_user = null, $numero_doc = null, $abrangencia = null, $periodo = '1' )
	{
		$query = $this->_db->getQuery(true);
		$query->select( $this->_db->quoteName(array('#__ranking_inscricao.id_inscricao',
													'#__ranking_inscricao_etapa.id_inscricao_etapa',
													'#__ranking_modalidade.name_modalidade',
													'#__ranking_modalidade.id_modalidade',
													'#__ranking_campeonato.name_campeonato',
													'#__ranking_campeonato.id_campeonato',
													'#__ranking_campeonato.ano_campeonato',
													
													'#__ranking_prova.id_prova',
													'#__ranking_prova.decimal_prova',
													'#__ranking_prova.name_prova',
													'#__ranking_prova.decimal_prova',
													'#__ranking_prova.type_prova',
													'#__ranking_prova.nr_etapa_prova',
													'#__ranking_prova.ns_etapa_prova',
													'#__ranking_prova.ns_variavel_prova',
													'#__ranking_prova.rs_etapa_prova',
													'#__ranking_prova.rf_etapa_prova',
													'#__ranking_prova.shot_off_prova',
													'#__ranking_prova.equipe_prova',
													'#__ranking_prova.nr_equipe_prova',
													'#__ranking_prova.special_names_prova',
													
													'#__ranking_inscricao.id_genero',
													'#__ranking_inscricao.id_categoria',
													'#__ranking_inscricao.id_classe',

													'#__ranking_etapa.id_etapa',
													'#__ranking_etapa.name_etapa',
													'#__ranking_etapa.data_beg_etapa',
													'#__ranking_etapa.data_end_etapa',
														
													'#__intranet_cidade.name_cidade',	
													'#__intranet_estado.sigla_estado',												
													)));
		
		$query->from( $this->_db->quoteName('#__ranking_inscricao') );
		
		$query->innerJoin( $this->_db->quoteName('#__ranking_campeonato') . ' USING('. $this->_db->quoteName('id_campeonato').')' );
		$query->innerJoin( $this->_db->quoteName('#__ranking_modalidade') . ' USING('. $this->_db->quoteName('id_modalidade').')' );
		$query->innerJoin( $this->_db->quoteName('#__ranking_prova') . ' USING('. $this->_db->quoteName('id_prova').')' );
		
		$query->innerJoin( $this->_db->quoteName('#__ranking_inscricao_etapa') . ' USING('. $this->_db->quoteName('id_inscricao').')' );
		$query->innerJoin( $this->_db->quoteName('#__ranking_resultado') . ' USING('. $this->_db->quoteName('id_inscricao').', '. $this->_db->quoteName('id_etapa').')' );
		$query->innerJoin( $this->_db->quoteName('#__ranking_etapa') . ' USING('. $this->_db->quoteName('id_etapa').')' );
		$query->innerJoin( $this->_db->quoteName('#__ranking_genero') . ' USING('. $this->_db->quoteName('id_genero').')' );
		$query->innerJoin( $this->_db->quoteName('#__ranking_categoria') . ' USING('. $this->_db->quoteName('id_categoria').')' );		
		$query->innerJoin( $this->_db->quoteName('#__ranking_classe') . ' USING('. $this->_db->quoteName('id_classe').')' );
		$query->innerJoin( $this->_db->quoteName('#__users') . 'ON('. $this->_db->quoteName('id'). ' = '. $this->_db->quoteName('#__ranking_inscricao.id_user').')' );
		
		$query->leftJoin( $this->_db->quoteName('#__intranet_pj') . 'ON('. $this->_db->quoteName('#__ranking_inscricao_etapa.id_local'). ' = '. $this->_db->quoteName('#__intranet_pj.id_user').')' );
	// 	
	    $query->leftJoin( $this->_db->quoteName('#__intranet_cidade') . 'USING ('.  $this->_db->quoteName('id_cidade').')' );
		$query->leftJoin( $this->_db->quoteName('#__intranet_estado') . 'ON('. $this->_db->quoteName('#__intranet_cidade.id_estado'). ' = '. $this->_db->quoteName('#__intranet_estado.id_estado').')' );
		
		$query->where( $this->_db->quoteName('block') . ' = ' . $this->_db->escape('0') );
		
		$query->where($this->_db->quoteName('status_resultado') . ' = ' . $this->_db->quote('1'));
		$query->where($this->_db->quoteName('status_inscricao') . ' = ' . $this->_db->quote('1'));
		$query->where($this->_db->quoteName('status_modalidade') . ' = ' . $this->_db->quote('1'));	
		$query->where($this->_db->quoteName('status_campeonato') . ' = ' . $this->_db->quote('1'));
		$query->where($this->_db->quoteName('status_prova') . ' = ' . $this->_db->quote('1'));
		$query->where($this->_db->quoteName('status_etapa') . ' = ' . $this->_db->quote('1'));
		$query->where($this->_db->quoteName('status_inscricao_etapa') . ' = ' . $this->_db->quote('1'));

		$query->where($this->_db->quoteName('#__ranking_inscricao.id_user') . ' = ' . $this->_db->quote( $id_user ));
		if($abrangencia)
			$query->where( $this->_db->quoteName('#__ranking_prova.abrangencia_prova') . '=' . $this->_db->quote( $abrangencia ) );
		
		if(!is_null($periodo))
			$query->where( $this->_db->quoteName('#__ranking_etapa.data_beg_etapa') . ' >= ' . $this->_db->quote( JFactory::getDate('now -'.$periodo.' year', $this->_siteOffset)->format('Y-m-d',true) ) );
		
		$query->order($this->_db->quoteName('#__ranking_etapa.data_beg_etapa') . ' DESC');
		$query->order($this->_db->quoteName('#__ranking_campeonato.ordering'));
		$query->order($this->_db->quoteName('#__ranking_etapa.ordering'));
		$query->order($this->_db->quoteName('#__ranking_prova.ordering'));
		$query->order($this->_db->quoteName('#__ranking_genero.ordering'));
		$query->order($this->_db->quoteName('#__ranking_categoria.ordering'));	
		$query->order($this->_db->quoteName('#__ranking_classe.ordering'));

		$query->group($this->_db->quoteName('id_inscricao_etapa'));
		
		$this->_db->setQuery($query);
		$items = $this->_db->loadObjectList();


		$html = '';
		if(count($items) > 0):








			$html .= '<table cellpadding="0" cellspacing="0" border="1" style="border: 1px; font-size:14px" width="100%">';
			$html .= '<thead style="padding: 5px;">';
			$html .= '<tr align="center">';
			$html .= '<th style="padding: 5px;">Data do Evento</th>';
			$html .= '<th style="padding: 5px;">Evento / Atividade</td>';
			$html .= '<th style="padding: 5px;">Cidade / Uf </th>';
			$html .= '</tr>';
			$html .= '</thead>';
			$html .= '<tbody>';
			$z = 0;
			$paginas = 1;
			foreach ( $items as $i => $item ) :
				$z++; 
				$html .= '<tr align="center">';
				$html .= '<td>';
				if ($item->data_beg_etapa != $item->data_end_etapa) : 
					$html .= JHTML::_('date', JFactory::getDate( $item->data_beg_etapa, $this->_siteOffset)->toISO8601(true), 'DATE_FORMAT');
					$html .= ' à ';
					$html .= JHTML::_('date', JFactory::getDate( $item->data_end_etapa, $this->_siteOffset)->toISO8601(true), 'DATE_FORMAT');
				else :
					$html .= JHTML::_('date', JFactory::getDate( $item->data_end_etapa, $this->_siteOffset)->toISO8601(true), 'DATE_FORMAT');
				endif;
				$html .= '</td>';
				$html .= '<td>';
				$html .= $item->name_etapa . ' - ' . $item->name_prova;
				$html .= '</td>';
				$html .= '<td>';
				$html .= $item->name_cidade . '/' . $item->sigla_estado;
				$html .= '</td>';
				$html .= '</tr>';




				if(($paginas == 1 && $z > 12 && $i < count($items) - 1)  || ($paginas > 1 && $z > 20 && $i < count($items) - 1)) :
					$z = 0;
					$paginas++;
					$html .= '</tbody>';
					$html .= '</table>';
					$html .= '<hr/><br/><div style="page-break-after: always;"></div>';
					//$html .= '<span style="page-break-before:always;">&nbsp</span>';
					$html .= '<div style="margin-top: 220px; margin-bottom: 50px">(CONTINUAÇÃO DECLARAÇÃO DO HISTÓRICO - FGCT '.$numero_doc.' - PÁGINA '.$paginas.' DE PAGINATOTAL )</div>';						
					
					$html .= '<table cellpadding="0" cellspacing="0" border="1" style="border: 1px; font-size:14px" width="100%">';
					$html .= '<thead style="padding: 5px;">';
					$html .= '<tr align="center">';
					$html .= '<th style="padding: 5px;">Data do Evento</th>';
					$html .= '<th style="padding: 5px;">Evento / Atividade</td>';
					$html .= '<th style="padding: 5px;">Cidade / Uf </th>';
					$html .= '</tr>';
					$html .= '</thead>';
					$html .= '<tbody>';

				endif;
			endforeach;
			$html .= '</tbody>';
			$html .= '</table>';


			$html = str_replace('PAGINATOTAL', $paginas, $html);
		//else:			
		//	$html = '<br/>Não há participações nos últimos 365 dias';
		endif;

		return $html;



	}

}