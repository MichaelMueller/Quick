<?php

namespace Qck;

/**
 * Basic App
 *
 * @author muellerm
 */
abstract class Controller implements \Qck\Interfaces\Controller
{

  abstract protected function proxyRun();

  function run(\Qck\Interfaces\App $App)
  {
    $this->App = $App;
    $this->proxyRun();
  }

  /**
   * 
   * @return Interfaces\App
   */
  function getApp()
  {
    return $this->App;
  }

  function redirect($Location)
  {
    header("Location: " . $Location);
  }
  
  /**
   *
   * @var Interfaces\App 
   */
  protected $App;

}
