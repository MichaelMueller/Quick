<?php

namespace Qck;

/**
 * Basic AppConfig
 *
 * @author muellerm
 */
abstract class Controller implements \Qck\Interfaces\Controller
{

  /**
   * @return Interfaces\Session
   */
  abstract protected function getSession();

  abstract protected function proxyRun();

  function run( \Qck\Interfaces\AppConfig $AppConfig )
  {
    $this->AppConfig = $AppConfig;
    $this->proxyRun();
  }

  function getCurrentUsername()
  {
    $Username = $this->getSession()->getUsername();
  }

  /**
   * 
   * @return Interfaces\AppConfig
   */
  function getAppConfig()
  {
    return $this->AppConfig;
  }

  function redirect( $Location )
  {
    header( "Location: " . $Location );
  }

  /**
   *
   * @var Interfaces\AppConfig 
   */
  protected $AppConfig;

}
