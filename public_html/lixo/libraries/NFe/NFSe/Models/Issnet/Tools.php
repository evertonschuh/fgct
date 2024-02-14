<?php

//namespace NFePHP\NFSe\Models\Issnet;

/**
 * Classe para a comunicação com os webservices da
 * conforme o modelo ISSNET
 *
 * @category  NFePHP
 * @package   NFePHP\NFSe\Models\Issnet\Tools
 * @copyright NFePHP Copyright (c) 2016
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfse for the canonical source repository
 */

//use NFePHP\NFSe\Models\Issnet\Rps;
//use NFePHP\NFSe\Models\Issnet\Factories;
//use NFePHP\NFSe\Common\Tools as ToolsBase;


require_once(JPATH_LIBRARIES . DS . 'NFe'. DS . 'NFSe' . DS . 'Models' . DS . 'Issnet' . DS . 'Rps.php');
//require_once(JPATH_LIBRARIES . DS . 'NFe'. DS . 'NFSe' . DS . 'Models' . DS . 'Issnet' . DS . 'Factories.php');
require_once(JPATH_LIBRARIES . DS . 'NFe'. DS . 'NFSe' . DS . 'Common'  . DS . 'Tools.php');

class ToolsModel extends ToolsBase
{
    public function cancelarNfse($numero, $codigoCancelamento)
    {
        $this->method = 'CancelarNfse';
        $fact = new Factories\CancelarNfse($this->certificate);
        $fact->setSignAlgorithm($this->algorithm);
        $cmun = $this->config->cmun;
        if ($this->config->tpAmb == 2) {
            $cmun = '999';
        }
        $message = $fact->render(
            $this->config->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            $this->remetenteIM,
            $cmun,
            $numero,
            $codigoCancelamento
        );
        return $this->sendRequest('', $message);
    }
    
    public function consultarUrlVisualizacaoNfse($numero, $codigoTributacao)
    {
        $this->method = 'ConsultarUrlVisualizacaoNfse';
        $fact = new Factories\ConsultarUrlVisualizacaoNfse($this->certificate);
        $fact->setSignAlgorithm($this->algorithm);
        $message = $fact->render(
            $this->config->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            $this->remetenteIM,
            $numero,
            $codigoTributacao
        );
        return $this->sendRequest('', $message);
    }
    
    public function consultarUrlVisualizacaoNfseSerie($numero, $codigoTributacao, $serie)
    {
        $this->method = 'ConsultarUrlVisualizacaoNfseSerie';
        $fact = new Factories\ConsultarUrlVisualizacaoNfse($this->certificate);
        $fact->setSignAlgorithm($this->algorithm);
        $message = $fact->render(
            $this->config->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            $this->remetenteIM,
            $numero,
            $codigoTributacao,
            $serie
        );
        return $this->sendRequest('', $message);
    }
    
    public function recepcionarLoteRps($lote, $rpss)
    {
        $this->method = 'RecepcionarLoteRps';
        $fact = new Factories\EnviarLoteRps($this->certificate);
        $fact->setSignAlgorithm($this->algorithm);
        $fact->setTimezone($this->timezone);
        $message = $fact->render(
            $this->config->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            $this->remetenteIM,
            $lote,
            $rpss
        );
        return $this->sendRequest('', $message);
    }

    public function consultarNfse(
        $numeroNFSe = '',
        $dtInicio = '',
        $dtFim = '',
        $tomador = array(),
        $intermediario = array()
    ) {
        $this->method = 'ConsultarNfse';
		
		require_once(JPATH_LIBRARIES . DS . 'NFe'. DS . 'NFSe' . DS . 'Models' . DS . 'Issnet' . DS . 'Factories' . DS . 'ConsultarNfse.php');

        $fact = new ConsultarNfse($this->certificate);

       // $fact = new Factories\ConsultarNfse($this->certificate);
        $fact->setSignAlgorithm($this->algorithm);
		
	
        $message = $fact->render(
            $this->config->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            $this->remetenteIM,
            $numeroNFSe,
            $dtInicio,
            $dtFim,
            $tomador,
            $intermediario
        );
        return $this->sendRequest('', $message);
    }
    
    public function consultarNfseRps($numero, $serie, $tipo)
    {
        $this->method = 'ConsultarNfseRps';
        $fact = new Factories\ConsultarNfseRps($this->certificate);
        $fact->setSignAlgorithm($this->algorithm);
        $message = $fact->render(
            $this->config->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            $this->remetenteIM,
            $numero,
            $serie,
            $tipo
        );
        return $this->sendRequest('', $message);
    }
    
    public function consultarLoteRps($protocolo)
    {
        $this->method = 'ConsultarLoteRps';
        $fact = new Factories\ConsultarLoteRps($this->certificate);
        $fact->setSignAlgorithm($this->algorithm);
        $message = $fact->render(
            $this->config->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            $this->remetenteIM,
            $protocolo
        );
        return $this->sendRequest('', $message);
    }
    
    public function consultarSituacaoLoteRps($protocolo)
    {
        $this->method = 'ConsultarSituacaoLoteRPS';
        $fact = new Factories\ConsultarSituacaoLoteRps($this->certificate);
        $fact->setSignAlgorithm($this->algorithm);
        $message = $fact->render(
            $this->config->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            $this->remetenteIM,
            $protocolo
        );
        return $this->sendRequest('', $message);
    }
    
    protected function sendRequest($url, $message)
    {
        //no caso do ISSNET o URL é unico para todas as ações
        $url = $this->url[$this->config->tpAmb];
		

        if (!is_object($this->soap)) {
			require_once(JPATH_LIBRARIES . DS . 'NFe'. DS . 'Common' . DS . 'Soap' . DS . 'SoapCurl.php');
			$this->soap = new SoapCurl($this->certificate);
            //$this->soap = new \NFePHP\NFSe\Common\SoapCurl($this->certificate);
        }
		
	
        //formata o xml da mensagem para o padão esperado pelo webservice
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($message);
        $message = str_replace('<?xml version="1.0"?>', '<?xml version="1.0" encoding="UTF-8"?>', $dom->saveXML());
		
		
        $messageText = $message;
		
       if ($this->withcdata) {
          $messageText = $this->stringTransform($message);
        }
		
        $request = "<". $this->method . " xmlns=\"".$this->xmlns."\">"
            . "<xml>$messageText</xml>"
          . "</". $this->method . ">";
        $params = array(
            'xml' => $message
        );
		
		/*
		$cabecalho = "<?xml version='1.0' encoding='UTF-8'?>
					 <cabecalho xmlns=\"http://www.abrasf.org.br/nfse.xsd\" versao=\"0.01\">
						<versaoDados>1.00</versaoDados>
					 </cabecalho>";
		$cabecalho = $this->stringTransform($cabecalho);			 
		
		$request = "<?xml version='1.0' encoding='UTF-8'?>
		<S:Envelope xmlns:S=\"http://schemas.xmlsoap.org/soap/envelope/\">
			<S:Body>
				<ns2:ConsultarNfseRequest 
					xmlns:ns2=\"http://ws.bhiss.pbh.gov.br\">
					<nfseCabecMsg>
						$cabecalho
					</nfseCabecMsg>
					<nfseDadosMsg>
						$messageText
					</nfseDadosMsg>
				</ns2:ConsultarNfseRequest >
			</S:Body>
		</S:Envelope>";
		
		*/
		//$request = $messageText;
		
		
        $action = "\"". $this->xmlns ."/". $this->method ."\"";
		
		
		//$action =  "\"http://ws.bhiss.pbh.gov.br/ConsultarNfse\"";
		
		//header("Content-type: text/xml");
		//echo $request;
		//print_r( $request);
		//application/soap+xml
		//exit;
		
		
        return $this->soap->send(
            $url,
            $this->method,
            $action,
            $this->soapversion,
            $params,
            $this->namespaces[$this->soapversion],
            $request
        );
    }
}
