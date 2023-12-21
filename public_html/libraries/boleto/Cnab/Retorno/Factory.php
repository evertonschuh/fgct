<?php
/*
namespace Eduardokum\LaravelBoleto\Cnab\Retorno;

use Eduardokum\LaravelBoleto\Contracts\Boleto\Boleto as BoletoContract;
use Eduardokum\LaravelBoleto\Contracts\Cnab\Retorno;
use Eduardokum\LaravelBoleto\Util;
*/

//require_once(JPATH_LIBRARIES . DS . 'boleto' . DS . 'CalculoDV.php');

//require_once(JPATH_LIBRARIES . DS . 'boleto' . DS . 'Cnab' . DS . 'Remessa' . DS . 'Cnab240'. DS . 'AbstractRemessa.php');

//jimport('joomla.image.image');

require_once(JPATH_LIBRARIES . DS . 'boleto' . DS . 'Contracts' . DS . 'Boleto'. DS . 'Boleto.php');
require_once(JPATH_LIBRARIES . DS . 'boleto' . DS . 'Contracts' . DS . 'Cnab' . DS . 'Retorno.php');

require_once(JPATH_LIBRARIES . DS . 'boleto' . DS . 'Util.php');

class Factory
{

    /**
     * @param $file
     *
     * @return Retorno
     * @throws \Exception
     */
    public static function make($file)
    {
		
        if (!$file_content = Util::file2array($file)) {
            throw new \Exception("Arquivo: não existe");
        }

        if (!Util::isHeaderRetorno($file_content[0])) {
            throw new \Exception("Arquivo: $file, não é um arquivo de retorno");
        }

        $instancia = self::getBancoClass($file_content);

        return $instancia->processar();
    }

    /**
     * @param $file_content
     *
     * @return mixed
     * @throws \Exception
     */
    private static function getBancoClass($file_content)
    {
        $banco = '';
        $namespace = '';
	
		
        if (Util::isCnab400($file_content)) {
            $banco = mb_substr($file_content[0], 76, 3);
            $namespace = JPATH_LIBRARIES . DS . 'boleto' . DS . 'Cnab' . DS . 'Retorno' . DS . 'Cnab400'. DS;
        } elseif (Util::isCnab240($file_content)) {
            $banco = mb_substr($file_content[0], 0, 3);
            $namespace = JPATH_LIBRARIES . DS . 'boleto' . DS . 'Cnab' . DS . 'Retorno' . DS . 'Cnab240'. DS;
        }
		
        $aBancos = array(
            BoletoContract::COD_BANCO_BB => 'Banco'. DS . 'Bb',
            BoletoContract::COD_BANCO_SANTANDER => 'Banco'. DS . 'Santander',
            BoletoContract::COD_BANCO_CEF => 'Banco'. DS . 'Caixa',
            BoletoContract::COD_BANCO_BRADESCO => 'Banco'. DS . 'Bradesco',
            BoletoContract::COD_BANCO_ITAU => 'Banco'. DS . 'Itau',
            BoletoContract::COD_BANCO_HSBC => 'Banco'. DS . 'Hsbc',
            BoletoContract::COD_BANCO_SICREDI => 'Banco'. DS . 'Sicredi',
            BoletoContract::COD_BANCO_BANRISUL => 'Banco'. DS . 'Banrisul',
            BoletoContract::COD_BANCO_BANCOOB => 'Banco'. DS . 'Bancoob',
            BoletoContract::COD_BANCO_BNB => 'Banco'. DS . 'Bnb',
        );

        if (array_key_exists($banco, $aBancos)) {
            //$bancoClass = $namespace . Util::getBancoClass($banco);
			require_once($namespace .  Util::getBancoClass($banco) . '.php');
			$bancoClass = str_replace('Banco'. DS , '', Util::getBancoClass($banco));
            return new $bancoClass($file_content);
        }

        throw new \Exception("Banco: $banco, inválido");
    }
	
}
