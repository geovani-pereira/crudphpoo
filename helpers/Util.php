<?php
namespace Helpers;

class Util
{
  public static function trataValor($valor) // metodo static
  {
    return "R$". number_format($valor,2,',','.');
  }