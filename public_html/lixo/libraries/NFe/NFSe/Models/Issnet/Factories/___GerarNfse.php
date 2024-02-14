<?php


//namespace NFePHP\NFSe\Models\Abrasf\Factories;

//use NFePHP\NFSe\Models\Abrasf\Factories\Header;
//use NFePHP\NFSe\Models\Abrasf\Factories\Factory;

require_once(JPATH_LIBRARIES . DS . 'NFe'. DS . 'NFSe' . DS . 'Models' . DS . 'Issnet' . DS . 'Factories' . DS . 'Header.php');
require_once(JPATH_LIBRARIES . DS . 'NFe'. DS . 'NFSe' . DS . 'Models' . DS . 'Issnet' . DS . 'Factories' . DS . 'Factory.php');



class GerarNfse extends Factory
{
	    public function render(
        $versao,
        $remetenteTipoDoc,
        $remetenteCNPJCPF,
        $inscricaoMunicipal,
        $numeroNFse = '',
        $dtInicio = '',
        $dtFim = '',
        $tomador = array(),
        $intermediario = array()
    ) {
        $method = "ConsultarNfseEnvio";
        $xsd = 'nfse_v20_08_2015';
        $content = $this->requestFirstPart($method, $xsd);
		
		
        $content .= Header::render($remetenteTipoDoc, $remetenteCNPJCPF, $inscricaoMunicipal);

        if (!empty(trim($numeroNFse))) {
            $content .= "<NumeroNfse>$numeroNFse</NumeroNfse>";
        }
		
		
        if (!empty($dtInicio) && !empty($dtFim)) {
            $content .= "<PeriodoEmissao>";
            $content .= "<DataInicial>$dtInicio</DataInicial>";
            $content .= "<DataFinal>$dtFim</DataFinal>";
            $content .= "</PeriodoEmissao>";
        }
		
		
        if (!empty($tomador)) {
            $content .= "<Tomador>";
            $content .= "<tc:CpfCnpj>";
            if ($tomador['tipo'] == 2) {
                $content .= "<tc:Cnpj>".$tomador['doc']."</tc:Cnpj>";
            } else {
                $content .= "<tc:Cpf>".$tomador['doc']."</tc:Cpf>";
            }
            $content .= "</tc:CpfCnpj>";
            if (!empty($tomador['im'])) {
                $content .= "<tc:InscricaoMunicipal>".$tomador['im']."</tc:InscricaoMunicipal>";
            }
            $content .= "</Tomador>";
        }
        if (!empty($intermediario)) {
            $content .= "<IntermediarioServico>";
            $content .= "<tc:CpfCnpj>";
            if ($intermediario['tipo'] == 2) {
                $content .= "<tc:Cnpj>".$intermediario['doc']."</tc:Cnpj>";
            } else {
                $content .= "<tc:Cpf>".$intermediario['doc']."</tc:Cpf>";
            }
            $content .= "</tc:CpfCnpj>";
            if (!empty($intermediario['razao'])) {
                $content .= "<tc:RazaoSocial>".$intermediario['razao']."</tc:RazaoSocial>";
            }
            if (!empty($intermediario['im'])) {
                $content .= "<tc:InscricaoMunicipal>".$intermediario['im']."</tc:InscricaoMunicipal>";
            }
            $content .= "</IntermediarioServico>";
        }
        $content .= "</$method>";
		
        //acredito que nessa consulta não exista assinatura
        //$body = $this->signer($content, $method, '', array(false,false,null,null));
		//JÁ TESTEI COM A ASSINATURA E DIZ QUE A ASSINATURA NAO É ESPOERADA NESTE ELEMENTO COMFORME PREVE O XSD. É ESPERADO OU O Tomador OU O IntermediarioServico 
		
		
		//

		
      	 $body = $this->clear($content);
		//header("Content-type: text/xml");
		//echo $body;
		//exit;			
        //comandos para testes apenas depois remover
     //  header("Content-type: text/xml");
     //   echo $body;
      	
		//die;
        //file_put_contents('/tmp/issnet_ConsultarNfseEnvio.xml', $body);
        
        $this->validar($versao, $body, 'Issnet', $xsd, '');
        return $body;
    }
 
}
