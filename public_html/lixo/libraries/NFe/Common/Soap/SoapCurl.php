<?php

//namespace NFePHP\Common\Soap;

/**
 * SoapClient based in cURL class
 *
 * @category  NFePHP
 * @package   NFePHP\Common\Soap\SoapCurl
 * @copyright NFePHP Copyright (c) 2016
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-common for the canonical source repository
 */

//use NFePHP\Common\Soap\SoapBase;
//use NFePHP\Common\Soap\SoapInterface;
//use NFePHP\Common\Exception\SoapException;
//use NFePHP\Common\Certificate;
//use Psr\Log\LoggerInterface;

require_once(JPATH_LIBRARIES . DS . 'NFe'. DS . 'Common' . DS . 'Soap' . DS . 'SoapBase.php');
require_once(JPATH_LIBRARIES . DS . 'NFe'. DS . 'Common' . DS . 'Soap' . DS . 'SoapInterface.php');
require_once(JPATH_LIBRARIES . DS . 'NFe'. DS . 'Common' . DS . 'Exception' . DS . 'SoapException.php');
require_once(JPATH_LIBRARIES . DS . 'NFe'. DS . 'Common' . DS . 'Certificate.php');


require_once(JPATH_LIBRARIES . DS . 'NFe'. DS . 'NFSe' . DS . 'Common' . DS. 'EntitiesCharacters.php');

class SoapCurl extends SoapBase implements SoapInterface
{
    /**
     * Constructor
     * @param Certificate $certificate
     * @param LoggerInterface $logger
	 
	 
     */
	 
	var $_xml = null;
	var $_xmlItem  = null;
	var $_xmlRoot = null; 
	 
    public function __construct(Certificate $certificate = null, LoggerInterface $logger = null)
    {
        parent::__construct($certificate, $logger);
    }
    
    /**
     * Send soap message to url
     * @param string $url
     * @param string $operation
     * @param string $action
     * @param int $soapver
     * @param array $parameters
     * @param array $namespaces
     * @param string $request
     * @param \SoapHeader $soapheader
     * @return string
     * @throws \NFePHP\Common\Exception\SoapException
     */
    public function send(
        $url,
        $operation = '',
        $action = '',
        $soapver = SOAP_1_2,
        $parameters = array(),
        $namespaces = array(),
        $request = '',
        $soapheader = null
    ) {
        //check or create key files
        //before send request
        $this->saveTemporarilyKeyFiles();
        $response = '';

        $envelope = $this->makeEnvelopeSoap(
            $request,
            $namespaces,
            $soapver,
            $soapheader
        );
        $msgSize = strlen($envelope);
        $parameters = array(
            "Content-Type: application/soap+xml;charset=utf-8;",
            "Content-length: $msgSize"
        );

        if (!empty($action)) {
            $parameters[0] .= "action=$action";
        }
        $this->requestHead = implode("\n", $parameters);
        $this->requestBody = $envelope;


		//print_r($parameters);
		//echo '<br/>';
		$parameters[0] = str_replace('application/soap+xml;', 'text/xml;', $parameters[0]);
		//print_r($envelope);
		//exit;
/*
		$envelope  = '<?xml version=\'1.0\' encoding=\'UTF-8\'?>
		<s:Envelope xmlns:S="http://schemas.xmlsoap.org/soap/envelope/">
			<s:Body>
				<ns2:ConsultarNfseRequest
					xmlns:ns2="http://ws.bhiss.pbh.gov.br">
					<nfseCabecMsg>
						&lt;?xml version=\'1.0\' encoding=\'UTF-8\'?&gt;
					 &lt;cabecalho xmlns="http://www.abrasf.org.br/nfse.xsd" versao="0.01"&gt;
						&lt;versaoDados&gt;1.00&lt;/versaoDados&gt;
					 &lt;/cabecalho&gt;
					</nfseCabecMsg>
					<nfseDadosMsg>
						&lt;?xml version="1.0" encoding="UTF-8"?&gt;
						&lt;ConsultarNfseEnvio xmlns="http://www.abrasf.org.br/nfse.xsd"&gt;
						  &lt;Prestador&gt;
							&lt;Cnpj&gt;02600321000152&lt;/Cnpj&gt;
							&lt;InscricaoMunicipal&gt;16957822&lt;/InscricaoMunicipal&gt;
						  &lt;/Prestador&gt;
						  &lt;PeriodoEmissao&gt;
							&lt;DataInicial&gt;2018-07-01&lt;/DataInicial&gt;
							&lt;DataFinal&gt;2018-07-30&lt;/DataFinal&gt;
						  &lt;/PeriodoEmissao&gt;
						&lt;/ConsultarNfseEnvio&gt;
					</nfseDadosMsg>
				</ns2:ConsultarNfseRequest>
			</s:Body>
		</s:Envelope>';
		

		
	/*
		$envelope  = '<?xml version="1.0" encoding="utf-8"?>  
					<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:tem="http://tempuri.org/">  
						<soapenv:Header/>  
						<soapenv:Body>  
							<soapenv:ConsultarNfse>  
								<soapenv:cabec xmlns="http://http://www.abrasf.org.br/nfse" versao="1.0">  
									<versaoDados>2.0</versaoDados>  
								</soapenv:cabec>  
								<soapenv:msg>  
									<ConsultarNfseEnvio xmlns="http://www.abrasf.org.br/nfse">  
										<Prestador>  
											<Cnpj>02600321000152</Cnpj>  
											<InscricaoMunicipal>16957822</InscricaoMunicipal>  
										</Prestador>  
										<PeriodoEmissao>  
											<DataInicial>2018-08-01-03:00</DataInicial>  
											<DataFinal>2018-08-05-03:00</DataFinal>  
										</PeriodoEmissao>   
									</ConsultarNfseEnvio>  
								</soapenv:msg>  
							</soapenv:ConsultarNfse>  
						</soapenv:Body>  
					</soapenv:Envelope>';
		
				
			//print_r($this->requestBody);
			//echo  $this->soapprotocol;
			
			*/
			//print_r($httpcode);
			////echo 'sdfsfsdf';
			//exit;
   
		  // echo $envelope;  
		 // exit;
		 
		 
		 /*
		$envelope  = '<?xml version=\'1.0\' encoding=\'UTF-8\'?>
		<S:Envelope xmlns:S="http://schemas.xmlsoap.org/soap/envelope/">
			<S:Body>
				<ns2:ConsultarNfseRequest
					xmlns:ns2="http://ws.bhiss.pbh.gov.br">
					<ns2:nfseCabecMsg>
						&lt;?xml version=\'1.0\' encoding=\'UTF-8\'?&gt;
					 &lt;cabecalho xmlns="http://www.abrasf.org.br/nfse.xsd" versao="0.01"&gt;
						&lt;versaoDados&gt;1.00&lt;/versaoDados&gt;
					 &lt;/cabecalho&gt;
					</ns2:nfseCabecMsg>
					
					<ns2:nfseDadosMsg>
						&lt;?xml version="1.0" encoding="UTF-8"?&gt;
						&lt;ConsultarNfseEnvio xmlns="http://www.abrasf.org.br/nfse.xsd"&gt;
						  &lt;Prestador&gt;
							&lt;Cnpj&gt;02600321000152&lt;/Cnpj&gt;
							&lt;InscricaoMunicipal&gt;16957822&lt;/InscricaoMunicipal&gt;
						  &lt;/Prestador&gt;
						  &lt;PeriodoEmissao&gt;
							&lt;DataInicial&gt;2018-07-01&lt;/DataInicial&gt;
							&lt;DataFinal&gt;2018-07-30&lt;/DataFinal&gt;
						  &lt;/PeriodoEmissao&gt;
						&lt;/ConsultarNfseEnvio&gt;
					</ns2:nfseDadosMsg>
				</ns2:ConsultarNfseRequest>
			</S:Body>
		</S:Envelope>';
	/*
		$envelope  = '<?xml version=\'1.0\' encoding=\'UTF-8\'?>
		<S:Envelope xmlns:S="http://schemas.xmlsoap.org/soap/envelope/">
			<S:Body>
				<ns2:ConsultarNfseRequest xmlns:ns2="http://ws.bhiss.pbh.gov.br">
					<nfseCabecMsg>
						<cabecalho xmlns=\'http://www.abrasf.org.br/nfse.xsd\' versao=\'1.00\'>
						<versaoDados>1.00</versaoDados>
						</cabecalho>
                   	</nfseCabecMsg>
                    <nfseDadosMsg>
						<ConsultarNfseEnvio xmlns="http://www.abrasf.org.br/nfse.xsd">
							<Prestador>  
								<Cnpj>02600321000152</Cnpj>
								<InscricaoMunicipal>16957822</InscricaoMunicipal>
							</Prestador>  
							<PeriodoEmissao>
								<DataInicial>2018-08-01</DataInicial>
								<DataFinal>2018-08-05</DataFinal>
							</PeriodoEmissao>   
						</ConsultarNfseEnvio>
					</nfseDadosMsg>
				</ns2:ConsultarNfseRequest>
			</S:Body>
		</S:Envelope>';
	
	
	//<nfseCabecMsg>&lt;?xml version="1.0" encoding="UTF-8"?&gt;&lt;cabecalho versao="1.00" xmlns="http://www.abrasf.org.br/nfse.xsd"&gt;&lt;versaoDados&gt;1.00&lt;/versaoDados&gt;&lt;/cabecalho&gt;</nfseCabecMsg><nfseDadosMsg>&lt;?xml version="1.0" encoding="UTF-8"?&gt;
		$envelope  = '<?xml version=\'1.0\' encoding=\'UTF-8\'?>
		<S:Envelope xmlns:S="http://schemas.xmlsoap.org/soap/envelope/">
			<S:Body>
				<ns2:ConsultarNfseRequest
					xmlns:ns2="http://ws.bhiss.pbh.gov.br">
					<teste>
						<ConsultarNfseEnvio xmlns="http://www.abrasf.org.br/nfse.xsd">
							<Prestador>  
								<Cnpj>02600321000152</Cnpj>
								<InscricaoMunicipal>16957822</InscricaoMunicipal>
							</Prestador>  
							<PeriodoEmissao>
								<DataInicial>2018-08-01</DataInicial>
								<DataFinal>2018-08-05</DataFinal>
							</PeriodoEmissao>   
						</ConsultarNfseEnvio>  
					</teste>
				</ns2:ConsultarNfseRequest>
			</S:Body>
		</S:Envelope>';
		 
		  */
		$nfseCabecMsg  = '<?xml version=\'1.0\' encoding=\'utf-8\'?>
					<cabecalho xmlns="http://www.abrasf.org.br/nfse.xsd"
					versao="1.00">
					<versaoDados>1.00</versaoDados>
					</cabecalho>';
		//$nfseCabecMsg  = EntitiesCharacters::unconvert($nfseCabecMsg);
		
		$nfseCabecMsg = new SimpleXMLElement($nfseCabecMsg);
		
		$nfseDadosMsg  = '<?xml version=\'1.0\' encoding=\'utf-8\'?>
						<ConsultarNfseEnvio xmlns="http://www.abrasf.org.br/nfse.xsd">
							<Prestador>
								<Cnpj>02600321000152</Cnpj>
								<InscricaoMunicipal>16957822</InscricaoMunicipal>
							</Prestador>
							<PeriodoEmissao>
								<DataInicial>2018-07-20</DataInicial>
								<DataFinal>2018-08-01</DataFinal>
							</PeriodoEmissao>
							<Tomador>
								<CpfCnpj>
									<Cnpj>02600321000152</Cnpj>
								</CpfCnpj>
								<InscricaoMunicipal>16957822</InscricaoMunicipal>
							</Tomador>
						</ConsultarNfseEnvio>';
		//$nfseDadosMsg  = EntitiesCharacters::unconvert($nfseDadosMsg);
		$nfseDadosMsg = new SimpleXMLElement($nfseDadosMsg);			
			
			
			
			
		$ConsultarNfseRequest = new SimpleXMLElement('<ns2:ConsultarNfseRequest xmlns:ns2="http://ws.bhiss.pbh.gov.br"></ns2:ConsultarNfseRequest>');	
		$_xmlitem = $ConsultarNfseRequest->addChild("nfseDadosMsg");		
				
		$node = dom_import_simplexml($_xmlitem);
		$no   = $node->ownerDocument;
		$_xmlitem = $no->createCDATASection($nfseDadosMsg->asXML());	
				
			/*		
$envelope  = '<?xml version=\'1.0\' encoding=\'utf-8\'?>
<S:Envelope xmlns:S="http://schemas.xmlsoap.org/soap/envelope/">
	<S:Body>
		<ns2:ConsultarNfseRequest xmlns:ns2="http://ws.bhiss.pbh.gov.br">
			<nfseCabecMsg>'. $nfseCabecMsg .'</nfseCabecMsg>
			<nfseDadosMsg>'. $nfseDadosMsg .'</nfseDadosMsg>
		</ns2:ConsultarNfseRequest>
	</S:Body>
</S:Envelope>';
*/
/*$envelope  = '<?xml version=\'1.0\' encoding=\'utf-8\'?><S:Envelope xmlns:S="http://schemas.xmlsoap.org/soap/envelope/"><S:Body>'. $ConsultarNfseRequest. '</S:Body></S:Envelope>';*/


			$envelope = new SimpleXMLElement('<?xml version=\'1.0\' encoding=\'utf-8\'?><S:Envelope xmlns:S="http://schemas.xmlsoap.org/soap/envelope/"><S:Body>'. $ConsultarNfseRequest. '</S:Body></S:Envelope>');
	
	//		$envelope->addAttribute("xmlns:S", "http://schemas.xmlsoap.org/soap/envelope/");
			

			$envelope->addChild($ConsultarNfseRequest->asXML());
			
		//	$node = dom_import_simplexml($envelope->getChild("nfseCabecMsg"));
		//	$no   = $node->ownerDocument;
			//$node->appendChild($no->createCDATASection($nfseCabecMsg));



		
		//  $envelope  = ToolsModel::stringTransform($envelope);
	/*
			$this->_xml = new SimpleXMLElement("<?xml version='1.0' encoding='UTF-8'?><S:Envelope xmlns:S=\"http://schemas.xmlsoap.org/soap/envelope/\"><S:Body></S:Body></S:Envelope>");
			$this->_xmlRoot = $this->_xml->addChild("S:Body");
			$this->_xmlRoot->addChild('ns2:ConsultarNfseRequest xmlns:ns2="http://ws.bhiss.pbh.gov.br"');
			$this->_xmlRoot->addChild('nfseCabecMsg');
			
			/*
			$nfseCabecMsg = new SimpleXMLElement("<?xml version='1.0' encoding='UTF-8'?><cabecalho xmlns=\"http://www.abrasf.org.br/nfse.xsd\" versao=\"1.00\"><cabecalho>");
			$nfseCabecMsg->addChild('versaoDados', '1.00');
			//$nfseCabecMsg->appendChild('1.00');
			*/
			
			header("Content-type: text/xml");
			echo $envelope->asXML();
			exit;
		 
/*
$envelope  = '<?xml version="1.0" encoding="UTF-8"?>
<S:Envelope xmlns:S="http://schemas.xmlsoap.org/soap/envelope/">
<S:Body>
<ns2:ConsultarNfseRequest xmlns:ns2="http://ws.bhiss.pbh.gov.br">
<inputXML>
<?xml version="1.0" encoding="UTF-8"?>
<ConsultarNfseEnvio xmlns="http://bhissdigital.pbh.gov.br/bhissws/schemas/servico_consultar_nfse_envio.xsd">
<Prestador xmlns="http://bhissdigital.pbh.gov.br/bhissws/schemas/servico_consultar_nfse_envio.xsd">
<Cnpj xmlns="http://bhissdigital.pbh.gov.br/bhissws/schemas/tipos_complexos.xsd">02600321000152</Cnpj>
<InscricaoMunicipal xmlns="http://bhissdigital.pbh.gov.br/bhissws/schemas/tipos_complexos.xsd">16957822</InscricaoMunicipal>
</Prestador>
<PeriodoEmissao xmlns="http://bhissdigital.pbh.gov.br/bhissws/schemas/servico_consultar_nfse_envio.xsd">
<DataInicial xmlns="http://bhissdigital.pbh.gov.br/bhissws/schemas/servico_consultar_nfse_envio.xsd">2018-08-01</DataInicial>
<DataFinal xmlns="http://bhissdigital.pbh.gov.br/bhissws/schemas/servico_consultar_nfse_envio.xsd">2018-08-08</DataFinal>
</PeriodoEmissao>
</ConsultarNfseEnvio>
</inputXML>
</ns2:ConsultarNfseRequest>
</S:Body>
</S:Envelope>';		 
		 
		*/ 
		 
		 
		 
		//header("Content-type: text/xml");
		//echo $envelope;
		//print_r( $request);
		//application/soap+xml
	//	exit; 
		 
		 
        try {
            $oCurl = curl_init();
            $this->setCurlProxy($oCurl);
            curl_setopt($oCurl, CURLOPT_URL, $url);
            curl_setopt($oCurl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            curl_setopt($oCurl, CURLOPT_CONNECTTIMEOUT, $this->soaptimeout);
            curl_setopt($oCurl, CURLOPT_TIMEOUT, $this->soaptimeout + 20);
            curl_setopt($oCurl, CURLOPT_HEADER, 1);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, 0);
            if (!$this->disablesec) {
                curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, 2);
                if (is_file($this->casefaz)) {
                    curl_setopt($oCurl, CURLOPT_CAINFO, $this->casefaz);
                }
            }
			
            curl_setopt($oCurl, CURLOPT_SSLVERSION, $this->soapprotocol);
            curl_setopt($oCurl, CURLOPT_SSLCERT, $this->tempdir . $this->certfile);
            curl_setopt($oCurl, CURLOPT_SSLKEY, $this->tempdir . $this->prifile);
            if (!empty($this->temppass)) {
                curl_setopt($oCurl, CURLOPT_KEYPASSWD, $this->temppass);
            }
            curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
            if (!empty($envelope)) {
                curl_setopt($oCurl, CURLOPT_POST, 1);
                curl_setopt($oCurl, CURLOPT_POSTFIELDS, $envelope);
                curl_setopt($oCurl, CURLOPT_HTTPHEADER, $parameters);
            }
			
            $response = curl_exec($oCurl);
			$this->soaperror = curl_error($oCurl);
			$ainfo = curl_getinfo($oCurl);            

            if (is_array($ainfo)) {
                $this->soapinfo = $ainfo;
            }
            $headsize = curl_getinfo($oCurl, CURLINFO_HEADER_SIZE);
            $httpcode = curl_getinfo($oCurl, CURLINFO_HTTP_CODE);
            curl_close($oCurl);

			print_r($response);
			exit;

            $this->responseHead = trim(substr($response, 0, $headsize));
            $this->responseBody = trim(substr($response, $headsize));
            $this->saveDebugFiles(
                $operation,
                $this->requestHead . "\n" . $this->requestBody,
                $this->responseHead . "\n" . $this->responseBody
            );
			
			
        } catch (\Exception $e) {
            throw SoapException::unableToLoadCurl($e->getMessage());
        }
        if ($this->soaperror != '') {
            throw SoapException::soapFault($this->soaperror . " [$url]");
        }
        if ($httpcode != 200) {
            throw SoapException::soapFault(" [$url]" . $this->responseHead);
        }
        return $this->responseBody;
    }
    
    /**
     * Set proxy into cURL parameters
     * @param resource $oCurl
     */
    private function setCurlProxy(&$oCurl)
    {
        if ($this->proxyIP != '') {
            curl_setopt($oCurl, CURLOPT_HTTPPROXYTUNNEL, 1);
            curl_setopt($oCurl, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
            curl_setopt($oCurl, CURLOPT_PROXY, $this->proxyIP . ':' . $this->proxyPort);
            if ($this->proxyUser != '') {
                curl_setopt($oCurl, CURLOPT_PROXYUSERPWD, $this->proxyUser . ':' . $this->proxyPass);
                curl_setopt($oCurl, CURLOPT_PROXYAUTH, CURLAUTH_BASIC);
            }
        }
    }
	
	function addChildWithCDATA($name, $value = NULL) {
		$this->_xmlItem = $this->_xmlRoot->addChild($name);

		$node = dom_import_simplexml($this->_xmlItem);
		$no   = $node->ownerDocument;
		$node->appendChild($no->createCDATASection($value));

	}
}
