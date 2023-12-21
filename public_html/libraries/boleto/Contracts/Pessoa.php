<?php

interface PessoaContract
{
    public function getNome();
    public function getNomeDocumento();
    public function getDocumento();
    public function getBairro();
    public function getEndereco();
    public function getCepCidadeUf();
    public function getCep();
    public function getCidade();
    public function getUf();
    public function toArray();
}
