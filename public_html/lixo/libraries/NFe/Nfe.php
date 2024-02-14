<?php 
defined('_JEXEC') or die('Restricted access');

//require_once(JPATH_LIBRARIES . DS . 'boleto'. DS . 'Carbon' . DS . 'Carbon.php');
require_once(JPATH_LIBRARIES . DS . 'NFe'. DS . 'Common' . DS . 'Certificate.php');
require_once(JPATH_LIBRARIES . DS . 'NFe'. DS . 'Common' . DS . 'Soap' . DS . 'SoapCurl.php');

class nfe
{
    public function __construct()
	{
		

	}
	
    public function setNfe()
	{
			
			require_once(JPATH_LIBRARIES . DS . 'NFe'. DS . 'Core' . DS . 'Tools.php');
			require_once(JPATH_LIBRARIES . DS . 'NFe'. DS . 'DA' . DS . 'NFe' . DS . 'Danfe.php');
			require_once(JPATH_LIBRARIES . DS . 'NFe'. DS . 'DA' . DS . 'Legacy' . DS . 'FilesFolders.php');
			
		$json = file_get_contents(JPATH_LIBRARIES . DS . 'NFe'. DS . 'storage'. DS . 'txtstructure310.json');
		$lines = json_decode($json, true);
		foreach($lines as $lin) {
		//	echo $lin.'<br>';
		}	
		
		
		error_reporting(E_ALL);
		ini_set('display_errors', 'On');
		//tanto o config.json como o certificado.pfx podem estar
		//armazenados em uma base de dados, então não é necessário 
		///trabalhar com arquivos, este script abaixo serve apenas como 
		//exemplo durante a fase de desenvolvimento e testes.
		$arr = array(
			"atualizacao" => "2016-11-03 18:01:21",
			"tpAmb" => 2,
			"razaosocial" => "SUA RAZAO SOCIAL LTDA",
			"cnpj" => "99999999999999",
			"siglaUF" => "SP",
			"schemes" => "PL008i2",
			"versao" => '3.10',
			"tokenIBPT" => "AAAAAAA",
			"CSC" => "GPB0JBWLUR6HWFTVEAS6RJ69GPCROFPBBB8G",
			"CSCid" => "000001",
			"proxyConf" => array(
				"proxyIp" => "",
				"proxyPort" => "",
				"proxyUser" => "",
				"proxyPass" => "",
			),  
		);
		$configJson = json_encode($arr);
		$pfxcontent = file_get_contents(JPATH_LIBRARIES . DS . 'NFe'. DS . 'storage'. DS . 'expired_certificate.pfx');
		$tools = new Tools($configJson, Certificate::readPfx($pfxcontent, 'associacao'));
		$tools->model('55');
		//sempre que ativar a contingência pela primeira vez essa informação deverá ser 
		//gravada na base de dados ou em um arquivo para uso posterior, até que a mesma seja 
		//desativada pelo usuário, essa informação não é persistida automaticamente e depende 
		//de ser gravada pelo ERP
		$contingencia = $tools->contingency->deactivate();
		//e se necessário carregada novamente quando a classe for instanciada
		$tools->contingency->load($contingencia);
		//executa a busca por documentos
		$response = $tools->sefazDistDFe(
			'AN',
			$arr['cnpj'],
			0,
			0
		);
		echo "<pre>";
		print_r($response);
		echo "</pre>";
		
		
	}	
		
	function getDanfe()	
	{
		error_reporting(E_ALL);
		ini_set('display_errors', 'On');
		//require_once '../bootstrap.php';

		$xml = JPATH_LIBRARIES . DS . 'NFe'. DS . 'DA' . DS . 'xml' . DS .'mod55-nfe.xml';
		$docxml = FilesFolders::readFile($xml);
		
		$img = JPATH_BASE .DS. 'views' .DS. 'system' .DS. 'images' .DS. 'logo_email_fbt.jpg';
		try {
			$danfe = new Danfe($docxml, 'P', 'A4', $img, 'I', '');
			$id = $danfe->montaDANFE();
			$pdf = $danfe->render();
			return $pdf;
			//o pdf porde ser exibido como view no browser
			//salvo em arquivo
			//ou setado para download forçado no browser 
			//ou ainda gravado na base de dados
			//header('Content-Type: application/pdf');
			//echo $pdf;
		} catch (InvalidArgumentException $e) {
    		echo "Ocorreu um erro durante o processamento :" . $e->getMessage();
		} 
	} 
	
	
	function getNFSe()	
	{

		require_once(JPATH_LIBRARIES . DS . 'NFe'. DS . 'NFSe' . DS . 'NFSe.php');
		require_once(JPATH_LIBRARIES . DS . 'NFe'. DS . 'Common' . DS . 'Soap' . DS . 'SoapNative.php');
		
		$arr = array(
			"atualizacao" => "2016-08-03 18:01:21",
			"tpAmb" => 1,
			"versao" => '20_08_2015',
			"razaosocial" => "INST. NAC. DE ESTUDOS JURIDICOS E EMPRESARIAS LTDA",
			"cnpj" => "02600321000152",
			"cpf" => "",
			"im" => "16957822", //INSCRIÇÃO MUNICIPAL
			"cmun" => "4314902", //Porto Alegre
			"siglaUF" => "RS",
			"pathNFSeFiles" => "media/files/nfse",
			"proxyConf" => array(
				"proxyIp" => "",
				"proxyPort" => "",
				"proxyUser" => "",
				"proxyPass" => ""
			) ,   
		);
		$configJson = json_encode($arr);
		
		$certificate = JPATH_LIBRARIES . DS . 'NFe'. DS . 'storage' . DS . 'certs' . DS . 'cert_fbt.pfx';
		
		$contentpfx = file_get_contents($certificate);
		

			//$configJson = json_encode($arr);
			//$contentpfx = file_get_contents('/var/www/sped/sped-nfse/certs/certificado.pfx');
			try {
				//com os dados do config e do certificado já obtidos e desconvertidos
				//a sua forma original e só passa-los para a classe 
				$nfse = new NFSe($configJson, Certificate::readPfx($contentpfx, 'mostard88'));
				//Por ora apenas o SoapCurl funciona com IssNet
				$nfse->tools->loadSoapClass(new SoapCurl());
				//caso o mode debug seja ativado serão salvos em arquivos 
				//a requisicção SOAP e a resposta do webservice na pasta de 
				//arquivos temporarios do SO em sub pasta denominada "soap"
				$nfse->tools->setDebugSoapMode(true);
					
				$numeroNFSe = '';    
				$dtInicio = '2018-08-01';
				$dtFim = '2018-08-07';
				$tomador = array(); //['tipo' => 2, 'doc' => '11111111111111', 'im' => '55555555'];
				$intermediario = array(); //['tipo' => 2, 'doc' => '11111111111111', 'im' => '44444444', 'razao' => 'SEI LA LTDA-ME'];
				$content = $nfse->tools->consultarNfse($numeroNFSe, $dtInicio, $dtFim, $tomador, $intermediario);    
				
				header("Content-type: text/xml");
				echo $content;
				
				//echo "<pre>";
				//print_r($response);
				//echo "</pre>";
				
			} catch (\NFePHP\Common\Exception\SoapException $e) {
				echo $e->getMessage();
			} catch (NFePHP\Common\Exception\CertificateException $e) {
				echo $e->getMessage();
			} catch (Exception $e) {
				echo $e->getMessage();
			}
		
		
		
		
	} 		
		
}
?>