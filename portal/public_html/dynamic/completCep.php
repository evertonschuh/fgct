<?php 

include( 'joomla.inc.php' );

include('phpQuery-onefile.php');


$obj = new EASistemasCompletCep();

class EASistemasCompletCep {
	
	var $_db = null;
	var $_data = null;
	
	function __construct()
	{						

		$lang = JFactory::getLanguage();
		$extension = 'tpl_system';
		$base_dir = JPATH_SITE;
		$language_tag = 'pt-BR';
		$reload = true;
		$lang->load($extension, $base_dir, $language_tag, $reload);
	
		$estado_buscacep = JRequest::getVar( 'estado_buscacep', '', 'post' );
		$cidade_buscacep = JRequest::getVar( 'cidade_buscacep', '', 'post' );
		$logradouro_buscacep = JRequest::getVar( 'logradouro_buscacep', '', 'post' );

		$cep_endereco = JRequest::getVar( 'cep_endereco', '', 'post' );

		$cep_endereco = str_replace('-', '', $cep_endereco);

		if ( !empty($cep_endereco ) )
		{


			try
				{
					$config = array(
						"trace" => 1, 
						"exception" => 0, 
						"cache_wsdl" => 200
					);
					$address = 'https://apps.correios.com.br/SigepMasterJPA/AtendeClienteService/AtendeCliente?wsdl';   
					$client = new SoapClient($address, $config);
					$results  = $client->consultaCEP(['cep' => $cep_endereco]);

					if(!isset($results->return->uf))
						die();
					$uf         = $results->return->uf;
					$cidade     = $results->return->cidade;		
					$bairro     = $results->return->bairro;
					$logradouro = $results->return->end;
					
					$posicao = strpos($logradouro, ' - ');
					if ($posicao!=0)
						$logradouro = substr($logradouro, 0, $posicao);				
					else
						$logradouro = $logradouro;
					
					
					//$uf = str_replace('&nbsp;','',$uf);
					$uf = substr($uf, 0,2);

					$this->_db	= JFactory::getDBO();
					
					$query = $this->_db->getQuery(true);
					$query->select($this->_db->quoteName(array('id_estado', 'name_estado')));
					$query->from('#__intranet_estado');
					$query->where('sigla_estado =' .$this->_db->quote( $uf ));
					$this->_db->setQuery($query);
					$result = $this->_db->loadObject();
					if ( !(boolean) $result = $this->_db->loadObject() )
						die();

					$id_estado =  $result->id_estado;
					$estado = $result->name_estado;


					$query = $this->_db->getQuery(true);
					$query->select( 'id_cidade' );
					$query->from( '#__intranet_cidade' );
					$query->where( 'id_estado='. $this->_db->quote( $id_estado ));
					$query->where( 'name_cidade='. $this->_db->quote( $cidade ));
					$this->_db->setQuery($query);
					
					if ( (boolean) $result = $this->_db->loadObject() )
						$id_cidade = $result->id_cidade;
					else 
						die();
							
					
				
					$dados = array(
							'logradouro'=> $logradouro,
							'bairro'=> $bairro,
							'cidade'=> $cidade,
							'id_cidade'=> $id_cidade,
							'estado'=> $estado,
							'id_estado'=> $id_estado,
					);
					die(json_encode($dados));
				}
				catch(Exception $e) 
				{
					die();
				}
			
			
		}

		

		
		if ( !empty($estado_buscacep) && !empty($cidade_buscacep) && !empty($logradouro_buscacep) )
		{
		
			//$app = JFactory::getApplication();
			//$app->setUserState( 'resultBuscaCEP', NULL );
			$this->_db	= JFactory::getDBO();
			
			$id_estado = $estado_buscacep;
			$query = $this->_db->getQuery(true);
			$query->select($this->_db->quoteName('sigla_estado') . ', ' . $this->_db->quoteName('name_estado') );
			$query->from($this->_db->quoteName('#__intranet_estado'));
			$query->where($this->_db->quoteName('id_estado'). ' = ' . $this->_db->quote( $id_estado ) );
			$this->_db->setQuery($query);
			$result = $this->_db->loadObject();	
			
			$estado = $result->name_estado;
			$uf = $result->sigla_estado;
			$estado_buscacep = $result->sigla_estado;
			
			$id_cidade = $cidade_buscacep;
			$query = $this->_db->getQuery(true);
			$query->select($this->_db->quoteName('name_cidade'));
			$query->from($this->_db->quoteName('#__intranet_cidade'));
			$query->where($this->_db->quoteName('id_cidade'). ' = ' . $this->_db->quote( $id_cidade ) );
			$this->_db->setQuery($query);
			$result = $this->_db->loadObject();	
			$cidade = $result->name_cidade;
			$cidade_buscacep = $result->name_cidade;
			
			$PesquisaMontada = utf8_decode( $logradouro_buscacep ).'/'.utf8_decode( $cidade_buscacep ).'/'. utf8_decode( $estado_buscacep );
			$html = $this->simple_curl('http://www.buscacep.correios.com.br/sistemas/buscacep/resultadoBuscaCep.cfm',array(
				'UF'=>$estado_buscacep,
				'Localidade'=>$cidade_buscacep,
				'Logradouro'=>$logradouro_buscacep,
				'tipoCep'=>'',
				'cepTemp'=>'',
				'metodo'=>'buscarCep'
			));
			
			phpQuery::newDocumentHTML($html, $charset = 'utf-8');
		
			$doc = new DOMDocument;
		 
			$doc->preserveWhiteSpace = false;
			$doc->strictErrorChecking = false;
			$doc->recover = true;
		 
			$doc->loadHTML($html);
			//$doc->loadHTML(html_entity_decode(mb_convert_encoding($result, 'UTF-8', 'ISO-8859-2'), ENT_QUOTES, 'UTF-8'));
			//$doc->loadHTML($result);
			
			
			$xpath = new DOMXPath($doc);
		 
			$query = "//table[@class='tmptabela']//td";
		 
			$entries = $xpath->query($query);
		 	
			print_r($entries->item(16)->nodeValue);
			exit;

			
			
			
			$uf         = explode('/',$entries->item(2)->nodeValue);
			$uf         = $uf[1];
			//$cidade     = explode('/',$entries->item(2)->nodeValue);
			$cidade     = $cidade[0];		
			$bairro     = substr($entries->item(1)->nodeValue,0,-2);
			$logradouro = substr($entries->item(0)->nodeValue,0,-2);
	
	
					$logradouro_completo = '';
				$logradouro='';
				$cep = '';
				$bairro  = '';
				$cidade = '';
				$uf = '';
	
	
		
	/*	
			$i = 0;
			while ( is_numeric( substr( trim(pq('.caixacampobranco:eq('. $i .') .resposta:contains("CEP: ") + .respostadestaque:eq(0)')->html()), 0,5) ) ) {

				$logradouro_completo = '';
				$logradouro='';
				$cep = '';
				$bairro  = '';
				$cidade = '';
				$uf = '';
		*/

			if ( !is_numeric( substr( trim(pq('.resposta:contains("CEP: "):eq(0) + .respostadestaque:eq(0)')->html()), 0,5) ) )
			{
				$PesquisaMontada = utf8_decode( $cidade_buscacep ).'/'. utf8_decode( $estado_buscacep );
				$html = $this->simple_curl('http://www.buscacep.correios.com.br/sistemas/buscacep/resultadoBuscaCep.cfm',array(
					'cepEntrada'=>$PesquisaMontada,
					'tipoCep'=>'',
					'cepTemp'=>'',
					'metodo'=>'buscarCep'
				));
				
				phpQuery::newDocumentHTML($html, $charset = 'utf-8');
				
			}
			$error = true;
			$resultBuscaCEP = array();	

			/*
			$html = $this->simple_curl('http://www.buscacep.correios.com.br/servicos/dnec/consultaLogradouroAction.do',array(
				'UF' => utf8_decode( $data['estado_buscacep'] ),
				'Localidade' => utf8_decode( $data['cidade_buscacep'] ),
				'Tipo' => '',
				'Logradouro' => utf8_decode( $data['logradouro_buscacep'] ),
				'Numero' => '',
				'cfm' => '1',
				'Metodo' => 'listaLogradouro',
				'TipoConsulta' => 'logradouro',
				'StartRow' => '1',
				'EndRow' => '10'
			));
			
			phpQuery::newDocumentHTML($html, $charset = 'utf-8');
			$error = true;
			$resultBuscaCEP = array();
			
			
			
			
				$logradouro_completo = trim(pq('.ctrlcontent div table tr:eq('. $i .') td:eq(0)')->html());
				$bairro = trim(pq('.ctrlcontent div table tr:eq('. $i .') td:eq(1)')->html());
				$cidade = trim(pq('.ctrlcontent div table tr:eq('. $i .') td:eq(2)')->html());
				$uf = trim(pq('.ctrlcontent div table tr:eq('. $i .') td:eq(3)')->html());
				$cep = trim(pq('.ctrlcontent div table tr:eq('. $i .') td:eq(4)')->html());				
				
				*/		
			
			
			$i = 0;
			while ( is_numeric( substr( trim(pq('.caixacampobranco:eq('. $i .') .resposta:contains("CEP: ") + .respostadestaque:eq(0)')->html()), 0,5) ) ) {

				$logradouro_completo = '';
				$logradouro='';
				$cep = '';
				$bairro  = '';
				$cidade = '';
				$uf = '';
				
				

				$cep = trim(pq('.caixacampobranco:eq('. $i .') .resposta:contains("CEP: ") + .respostadestaque:eq(0)')->html());
				$logradouro_completo = trim(pq('.caixacampobranco:eq('. $i .') .resposta:contains("Logradouro: ") + .respostadestaque:eq(0)')->html());
				if( !$logradouro_completo) $logradouro_completo = trim(pq('.caixacampobranco:eq('. $i .') .resposta:contains("Endereço: ") + .respostadestaque:eq(0)')->html());
				$bairro = trim(pq('.caixacampobranco:eq('. $i .') .resposta:contains("Bairro: ") + .respostadestaque:eq(0)')->html());
				$cidadeuf = trim(pq('.caixacampobranco:eq('. $i .') .resposta:contains("Localidade / UF: ") + .respostadestaque:eq(0)')->html());
				if( !$cidadeuf) $cidadeuf = trim(pq('.caixacampobranco:eq('. $i .') .resposta:contains("Localidade/UF: ") + .respostadestaque:eq(0)')->html());
				
				$cidadeuf = explode('/', $cidadeuf);
				$cidade = trim($cidadeuf[0]);
				$uf = trim($cidadeuf[1]);
				
				$cidade = explode("\n", $cidade);
				$cidade = $cidade[0];
		
				while (strpos($cidade, "\n")) {
					$cidade = str_replace("\n","",$cidade);
				}
				
				while (strpos($cidade, "\t")) {
					$cidade = str_replace("\t"," ",$cidade);
				}
				
				while (strpos($cidade, "  ")) {
					$cidade = str_replace("  "," ",$cidade);
				}	
			
				$uf = explode("\n", $uf);
				$uf = $uf[0];
		
				while (strpos($uf, "\n")) {
					$uf = str_replace("\n","",$uf);
				}
				
				while (strpos($uf, "\t")) {
					$uf = str_replace("\t"," ",$uf);
				}
				
				while (strpos($uf, "  ")) {
					$uf = str_replace("  "," ",$uf);
				}	


				$posicao = strpos($logradouro_completo, ' - ');
				if ($posicao!=0)
					$logradouro = substr($logradouro_completo, 0, $posicao);				
				else
					$logradouro = $logradouro_completo;

				if (!$logradouro_completo) {
					$logradouro_completo = 'CEP único para Cidade';
					$logradouro = '';
				}
				
				$cep = substr( $cep, 0,5) . '-' .  substr( $cep, 5,3);

				$query = $this->_db->getQuery(true);
				$query->select( 'id_cidade' );
				$query->from( '#__intranet_cidade' );
				$query->where( 'name_cidade='. $this->_db->quote( $cidade ));
				$this->_db->setQuery($query);
				
				if ( (boolean) $result = $this->_db->loadObject() )
					$id_cidade = $result->id_cidade;
					
				else {
					
					$query = $this->_db->getQuery(true);
					$query->select( 'MAX( ' . $this->_db->quoteName('ordering') . ' ) as ordering' );
					$query->from( '#__intranet_cidade' );
					$query->where( 'id_estado ='.$this->_db->quote( $id_estado ) );
					$this->_db->setQuery($query);					
					$result = $this->_db->loadObject();
					$ordering = $result->ordering +1;
										
					$columns = array('id_estado',
									 'status_cidade',
									 'name_cidade',
									 'ordering');
									 
					$values = array($this->_db->quote( $id_estado ), 
									$this->_db->quote(1), 
									$this->_db->quote( $cidade ) ,  
									$this->_db->quote( $ordering ) );										 

				
					$query = $this->_db->getQuery(true);
					$query->insert( $this->_db->quoteName('#__intranet_cidade') );
					$query->columns($this->_db->quoteName($columns));	
					$query->values(implode(',', $values));
					$this->_db->setQuery($query);
					$this->_db->query();

					$query = $this->_db->getQuery(true);
					$query->select( 'id_cidade' );
					$query->from( '#__intranet_cidade' );
					$query->where( 'name_cidade='. $this->_db->quote( $cidade ) );
					
					$this->_db->setQuery($query);
					
					$result = $this->_db->loadObject();
					
					$id_cidade = $result->id_cidade;
					
					
				}


				$object = new stdClass();
				$object->logradouro_completo = $logradouro_completo;
				$object->logradouro = $logradouro;
				$object->bairro = $bairro;
				$object->id_cidade = $id_cidade;
				$object->cidade =  $cidade;
				$object->id_estado = $id_estado;
				$object->estado = $estado;
				$object->uf = $uf;
				$object->cep = $cep;
				$resultBuscaCEP[] = $object;	
				
				
				$i++;  
				$error = false;
				
				
			}
			
			
			$i = 0;
			while ( is_numeric( substr( trim(pq('.caixacampoazul:eq('. $i .') .resposta:contains("CEP: ") + .respostadestaque:eq(0)')->html()), 0,5) ) ) {

				$logradouro_completo = '';
				$logradouro='';
				$cep = '';
				$bairro  = '';
				$cidade = '';
				$uf = '';
				
				

				$cep = trim(pq('.caixacampoazul:eq('. $i .') .resposta:contains("CEP: ") + .respostadestaque:eq(0)')->html());
				$logradouro_completo = trim(pq('.caixacampoazul:eq('. $i .') .resposta:contains("Logradouro: ") + .respostadestaque:eq(0)')->html());
				if( !$logradouro_completo) $logradouro_completo = trim(pq('.caixacampoazul:eq('. $i .') .resposta:contains("Endereço: ") + .respostadestaque:eq(0)')->html());
				$bairro = trim(pq('.caixacampoazul:eq('. $i .') .resposta:contains("Bairro: ") + .respostadestaque:eq(0)')->html());
				$cidadeuf = trim(pq('.caixacampoazul:eq('. $i .') .resposta:contains("Localidade / UF: ") + .respostadestaque:eq(0)')->html());
				if( !$cidadeuf) $cidadeuf = trim(pq('.caixacampoazul:eq('. $i .') .resposta:contains("Localidade/UF: ") + .respostadestaque:eq(0)')->html());
				
				$cidadeuf = explode('/', $cidadeuf);
				$cidade = trim($cidadeuf[0]);
				$uf = trim($cidadeuf[1]);
				
				$cidade = explode("\n", $cidade);
				$cidade = $cidade[0];
		
				while (strpos($cidade, "\n")) {
					$cidade = str_replace("\n","",$cidade);
				}
				
				while (strpos($cidade, "\t")) {
					$cidade = str_replace("\t"," ",$cidade);
				}
				
				while (strpos($cidade, "  ")) {
					$cidade = str_replace("  "," ",$cidade);
				}	
			
				$uf = explode("\n", $uf);
				$uf = $uf[0];
		
				while (strpos($uf, "\n")) {
					$uf = str_replace("\n","",$uf);
				}
				
				while (strpos($uf, "\t")) {
					$uf = str_replace("\t"," ",$uf);
				}
				
				while (strpos($uf, "  ")) {
					$uf = str_replace("  "," ",$uf);
				}	


				$posicao = strpos($logradouro_completo, ' - ');
				if ($posicao!=0)
					$logradouro = substr($logradouro_completo, 0, $posicao);				
				else
					$logradouro = $logradouro_completo;

				if (!$logradouro_completo) {
					$logradouro_completo = 'CEP único para Cidade';
					$logradouro = '';
				}
				$cep = substr( $cep, 0,5) . '-' .  substr( $cep, 5,3);

				$query = $this->_db->getQuery(true);
				$query->select( 'id_cidade' );
				$query->from( '#__intranet_cidade' );
				$query->where( 'name_cidade='. $this->_db->quote( $cidade ));
				$this->_db->setQuery($query);
				
				if ( (boolean) $result = $this->_db->loadObject() )
					$id_cidade = $result->id_cidade;
					
				else {
					
					$query = $this->_db->getQuery(true);
					$query->select( 'MAX( ' . $this->_db->quoteName('ordering') . ' ) as ordering' );
					$query->from( '#__intranet_cidade' );
					$query->where( 'id_estado ='.$this->_db->quote( $id_estado ) );
					$this->_db->setQuery($query);					
					$result = $this->_db->loadObject();
					$ordering = $result->ordering +1;
										
					$columns = array('id_estado',
									 'status_cidade',
									 'name_cidade',
									 'ordering');
									 
					$values = array($this->_db->quote( $id_estado ), 
									$this->_db->quote(1), 
									$this->_db->quote( $cidade ) ,  
									$this->_db->quote( $ordering ) );										 

				
					$query = $this->_db->getQuery(true);
					$query->insert( $this->_db->quoteName('#__intranet_cidade') );
					$query->columns($this->_db->quoteName($columns));	
					$query->values(implode(',', $values));
					$this->_db->setQuery($query);
					$this->_db->query();

					$query = $this->_db->getQuery(true);
					$query->select( 'id_cidade' );
					$query->from( '#__intranet_cidade' );
					$query->where( 'name_cidade='. $this->_db->quote( $cidade ) );
					
					$this->_db->setQuery($query);
					
					$result = $this->_db->loadObject();
					
					$id_cidade = $result->id_cidade;

					
					
				}



				$object = new stdClass();
				$object->logradouro_completo = $logradouro_completo;
				$object->logradouro = $logradouro;
				$object->bairro = $bairro;
				$object->id_cidade = $id_cidade;
				$object->cidade =  $cidade;
				$object->id_estado = $id_estado;
				$object->estado = $estado;
				$object->uf = $uf;
				$object->cep = $cep;
				$resultBuscaCEP[] = $object;	
				
				
				$i++;  
				$error = false;
				
				
			}
			

				
			if ($error)
				
				echo "Erro no site dos Correios";
				//return false;
			else {
				$i = 0;
				$k = count( $resultBuscaCEP ) -1;
				$n = 0;

				foreach ( $resultBuscaCEP as $row) 
           		{
					$endtable = '';
					if($n != $k) $endtable = 'end-table';
					echo 	'<div class="edit col-xs-12 col-sm-12 col-md-12 list-'. $i . ' ' . $endtable . '" onclick="inserirendereco(this)" >
								<div class="form-group col-xs-12 col-sm-6 col-md-7 col-lg-7">           
										<label>' . $row->logradouro_completo . '</label>
										<input type="hidden" name="logradouro_respbuscacep[]" value="' .  $row->logradouro . '" />
								</div>';
						/*
						
                        <?php if ($row->bairro) { ?>
                        <div class="form-group col-xs-12 col-sm-12 col-md-12">  
                            <div class="form-group col-xs-3 col-sm-3 col-md-3">         
                                <label>Bairro:</label>
                            </div> 
                            <div class="form-group col-xs-9 col-sm-9 col-md-9">   
                                <label><?php echo $row->bairro; ?></label>
                                 
                            </div>             
                        </div>
                        <?php } ?>
						*/
						
					echo		'<div class="form-group col-xs-12 col-sm-3 col-md-3 col-lg-3 end-table">   
										<label>' . $row->cidade . '/' . $row->uf .'</label>
										<input type="hidden" name="bairro_respbuscacep[]" value="' . $row->bairro . '" />
										<input type="hidden" name="id_cidade_respbuscacep[]" value="' . $row->id_cidade . '" />
										<input type="hidden" name="id_estado_respbuscacep[]" value="' . $row->id_estado . '" /> 
								</div>
								<div class="form-group col-xs-12 col-sm-3 col-md-2 col-lg-2 end-table">   
										<label>' . $row->cep . '</label>
										<input type="hidden" name="cep_respbuscacep[]" value="' . $row->cep . '" />
								</div>
							</div>';
					
                if($i ==0)
                    $i = 1;
                else
                    $i = 0;	
                    $n++;	
				}

				//$app->setUserState( 'resultBuscaCEP', $resultBuscaCEP );
				//print_r($resultBuscaCEP);
				//return true;
			}

		}
		
	}
	
	
	function simple_curl($url,$post=array(),$get=array()){
		$url = explode('?',$url,2);
		if(count($url)===2){
			$temp_get = array();
			parse_str($url[1],$temp_get);
			$get = array_merge($get,$temp_get);
		}
	
		$ch = curl_init($url[0]."?".http_build_query($get));
		curl_setopt ($ch, CURLOPT_POST, 1);
		curl_setopt ($ch, CURLOPT_POSTFIELDS, http_build_query($post));
		curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		return curl_exec ($ch);
	}

}