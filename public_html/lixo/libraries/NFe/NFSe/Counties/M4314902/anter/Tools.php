<?php

//namespace NFePHP\NFSe\Counties\M2927408;

/**
 * Classe para a comunicação com os webservices da
 * Cidade de Salvador BA
 * conforme o modelo ABRASF
 *
 * @category  NFePHP
 * @package   NFePHP\NFSe\Counties\M2927408\Tools
 * @copyright NFePHP Copyright (c) 2016
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfse for the canonical source repository
 */

//use NFePHP\NFSe\Models\Abrasf\Tools as ToolsAbrasf;
require_once(JPATH_LIBRARIES . DS . 'NFe'. DS . 'NFSe' . DS . 'Models' . DS . 'Abrasf' . DS .'Tools.php');

class Tools extends ToolsAbrasf
{
    /**
     * Webservices URL
     * @var array
     */
    protected $url = array(
        1 => array(
            'EnvioLoteRPS'=>"http://nfse-hom.procempa.com.br/nfe-ws",
            'ConsultaSituacaoLoteRPS'=>"http://nfse-hom.procempa.com.br/nfe-ws",
            'ConsultaLoteRPS'=>"http://nfse-hom.procempa.com.br/nfe-ws",
            'ConsultaNfseRPS'=>"http://nfse-hom.procempa.com.br/nfe-ws",
            'ConsultaNFse'=>"http://nfse-hom.procempa.com.br/nfe-ws"
        ),
        2 => array(
            'EnvioLoteRPS'=>"http://nfse-hom.procempa.com.br/nfe-ws",
            'ConsultaSituacaoLoteRPS'=>"http://nfse-hom.procempa.com.br/nfe-ws",
            'ConsultaLoteRPS'=>"http://nfse-hom.procempa.com.br/nfe-ws",
            'ConsultaNfseRPS'=>"http://nfse-hom.procempa.com.br/nfe-ws",
            'ConsultaNFse'=>"http://nfse-hom.procempa.com.br/nfe-ws"
        ),
    );
   
    /**
     * County Namespace
     * @var string
     */
    protected $xmlns= "http://tempuri.org/";
    /**
     * Soap Version
     * @var int
     */
    protected $soapversion = SOAP_1_2;
    /**
     * SIAFI County Cod
     * @var int
     */
    protected $codcidade = 3849;
    /**
     * Indicates when use CDATA string on message
     * @var boolean
     */
    protected $withcdata = false;
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
}
