<?php

require_once(JPATH_LIBRARIES . DS . 'boleto' . DS . 'Contracts' . DS .  'Cnab' . DS .'Cnab.php');

interface RemessaContract extends Cnab
{
    public function gerar();
}
