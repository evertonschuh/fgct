<?php

//namespace NFePHP\NFSe\Counties\M4313409;

/**
 * Classe para a comunicação com os webservices da
 * Cidade de Novo Hamburgo RS
 * conforme o modelo ISSNET
 *
 * @category  NFePHP
 * @package   NFePHP\NFSe\Counties\M4313409\Tools
 * @copyright NFePHP Copyright (c) 2016
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfse for the canonical source repository
 */

//use NFePHP\NFSe\Models\Issnet\Tools as ToolsModel;

require_once(JPATH_LIBRARIES . DS . 'NFe'. DS . 'NFSe' . DS . 'Models' . DS . 'Issnet' . DS .'Tools.php');

class Tools extends ToolsModel
{
    /**
     * Webservices URL
     * @var array
     */
    protected $url = array(
        2 => 'https://nfe.portoalegre.rs.gov.br/bhiss-ws/nfse?wsdl',
        1 => 'https://nfse-hom.procempa.com.br/bhiss-ws/nfse?wsdl'
    );
    /**
     * County Namespace
     * @var string
     */
    protected $xmlns = 'http://ws.bhiss.pbh.gov.br';
    
    /**
     * Soap Version
     * @var int
     */
    protected $soapversion = SOAP_1_2;
    /**
     * SIAFI County Cod
     * @var int
     */
    protected $codcidade = '';
    /**
     * Indicates when use CDATA string on message
     * @var boolean
     */
    protected $withcdata = true;
    /**
     * Encription signature algorithm
     * @var string
     */
    protected $algorithm = OPENSSL_ALGO_SHA1;
    /**
     * Version of schemas
     * @var int
     */
    protected $versao = 1;
    /**
     * namespaces for soap envelope
     * @var array
     */
	 
	 //mlns:ws="http://ws.bhiss.pbh.gov.br" xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/" xmlns:xsd="http://www.w3.org/2001/XMLSchema"
	 
    protected $namespaces = array(
        1 =>  array(
            'xmlns:soapenv' => "http://schemas.xmlsoap.org/soap/envelope/",
            'xmlns' => "http://www.abrasf.org.br/nfse.xsd"
        ),
        2  =>  array(
            'xmlns:S' => "http://schemas.xmlsoap.org/soap/envelope/",
            'xmlns' => "http://www.w3.org/2001/XMLSchema"
			
			//'xmlns:soap'=>"http://schemas.xmlsoap.org/wsdl/soap/" ,
		//	'xmlns:ws'=>"http://ws.bhiss.pbh.gov.br",
		//	'xmlns:wsdl'=>"http://schemas.xmlsoap.org/wsdl/", 
		//	'xmlns:xsd'=>"http://www.w3.org/2001/XMLSchema"
			
			
        )
    );
}
