<?php

//namespace NFePHP\NFSe\Models\Issnet\Factories;

//use NFePHP\NFSe\Models\Issnet\Factories\Header;
//use NFePHP\NFSe\Models\Issnet\Factories\Factory;
//use NFePHP\NFSe\Models\Issnet\RenderRPS;


require_once(JPATH_LIBRARIES . DS . 'NFe'. DS . 'NFSe' . DS . 'Models' . DS . 'Issnet' . DS . 'Factories' . DS . 'Header.php');
require_once(JPATH_LIBRARIES . DS . 'NFe'. DS . 'NFSe' . DS . 'Models' . DS . 'Issnet' . DS . 'Factories' . DS . 'Factory.php');
require_once(JPATH_LIBRARIES . DS . 'NFe'. DS . 'NFSe' . DS . 'Models' . DS . 'Issnet' . DS . 'RenderRPS.php');

class GerarNfse extends Factory
{
    public function render(
        $versao,
        $remetenteTipoDoc,
        $remetenteCNPJCPF,
        $inscricaoMunicipal,
        $lote,
        $rpss
    ) {
        $method = 'GerarNfse';
        $xsd = 'nfse_v20_08_2015';
        $qtdRps = count($rpss);
		//$idLote = '2014111893123';
		
		
		//$content = $this->requestFirstPart($method, $xsd);
        //$content .= Header::render($remetenteTipoDoc, $remetenteCNPJCPF, $inscricaoMunicipal);
		
        $content = "<GerarNfseEnvio xmlns=\"http://www.abrasf.org.br/nfse.xsd\">";
		
        $content .= "<LoteRps Id=\"Lote" . $lote ."\" versao=\"1.00\">";
        $content .= "<NumeroLote>$lote</NumeroLote>";
        if ($remetenteTipoDoc == '2') {
            $content .= "<Cnpj>$remetenteCNPJCPF</Cnpj>";
        } else {
            $content .= "<Cpf>$remetenteCNPJCPF</Cpf>";
        }
        $content .= "<InscricaoMunicipal>$inscricaoMunicipal</InscricaoMunicipal>";
        $content .= "<QuantidadeRps>$qtdRps</QuantidadeRps>";
        $content .= "<ListaRps>";
        foreach ($rpss as $rps) {
            $content .= RenderRPS::toXml($rps, $this->timezone, $this->algorithm, $this->certificate, $lote);
        }
        $content .= "</ListaRps>";
        $content .= "</LoteRps>";
        $content .= "</GerarNfseEnvio>";
 
        $body = Signer::sign(
            $this->certificate,
            $content,
            'LoteRps',
            'Id',
            $this->algorithm,
            array(false,false,null,null) ,
			false,
			true
        );
        $body = $this->clear($body);

 		//header("Content-type: text/xml");
		//echo $body;	
		//exit;	


        $this->validar($versao, $body, 'Issnet', $xsd, '');
        return $body;
    }
}
