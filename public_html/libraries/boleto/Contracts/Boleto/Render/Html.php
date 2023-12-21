<?php

interface HtmlContract
{
    public function getImagemCodigoDeBarras($codigo_barras);

    public function gerarBoleto();
}
