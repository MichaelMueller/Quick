<?php

namespace Qck\Ext;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class DefaultLanguageConfig implements interfaces\LanguageConfig
{

  public function getDefaultLanguage()
  {
    return "en";
  }

  public function isSupported( $lang )
  {
    return $lang == "en";
  }
}
