<?php

//namespace Eduardokum\LaravelBoleto\Contracts\Cnab\Retorno\Cnab240;

interface Trailer240Contract
{
    /**
     * @return mixed
     */
    public function getTipoRegistro();

    /**
     * @return mixed
     */
    public function getNumeroLoteRemessa();

    /**
     * @return mixed
     */
    public function getQtdLotesArquivo();

    /**
     * @return mixed
     */
    public function getQtdRegistroArquivo();

    /**
     * @return array
     */
  //  public function toArray();
}
