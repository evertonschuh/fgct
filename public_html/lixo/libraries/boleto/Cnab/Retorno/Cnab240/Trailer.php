<?php

/*
namespace Eduardokum\LaravelBoleto\Cnab\Retorno\Cnab240;

use \Eduardokum\LaravelBoleto\Contracts\Cnab\Retorno\Cnab240\Trailer as TrailerContract;
use Eduardokum\LaravelBoleto\MagicTrait;
*/


require_once(JPATH_LIBRARIES . DS . 'boleto'. DS . 'Carbon' . DS . 'Carbon.php');
require_once(JPATH_LIBRARIES . DS . 'boleto' . DS . 'Contracts' . DS . 'Cnab' . DS . 'Retorno' . DS . 'Cnab240' . DS . 'Trailer.php');
require_once(JPATH_LIBRARIES . DS . 'boleto' . DS . 'MagicTrait.php');

class Trailer implements Trailer240Contract
{
  //  use MagicTrait;
    /**
     * @var integer
     */
    protected $numeroLote;

    /**
     * @var integer
     */
    protected $tipoRegistro;

    /**
     * @var integer
     */
    protected $qtdLotesArquivo;

    /**
     * @var integer
     */
    protected $qtdRegistroArquivo;

    /**
     * @return mixed
     */
    public function getTipoRegistro()
    {
        return $this->tipoRegistro;
    }

    /**
     * @param mixed $numeroLote
     *
     * @return $this
     */
    public function setNumeroLote($numeroLote)
    {
        $this->numeroLote = $numeroLote;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNumeroLoteRemessa()
    {
        return $this->numeroLote;
    }

    /**
     * @param mixed $qtdLotesArquivo
     *
     * @return $this
     */
    public function setQtdLotesArquivo($qtdLotesArquivo)
    {
        $this->qtdLotesArquivo = $qtdLotesArquivo;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getQtdLotesArquivo()
    {
        return $this->qtdLotesArquivo;
    }

    /**
     * @param mixed $qtdRegistroArquivo
     *
     * @return $this
     */
    public function setQtdRegistroArquivo($qtdRegistroArquivo)
    {
        $this->qtdRegistroArquivo = $qtdRegistroArquivo;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getQtdRegistroArquivo()
    {
        return $this->qtdRegistroArquivo;
    }

    /**
     * @param mixed $tipoRegistro
     *
     * @return $this
     */
    public function setTipoRegistro($tipoRegistro)
    {
        $this->tipoRegistro = $tipoRegistro;

        return $this;
    }
}
