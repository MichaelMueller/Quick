<?php

namespace Qck\Core;

/**
 * Description of QckPhpMailer
 *
 * @author muellerm
 */
abstract class CoreController implements \Qck\Interfaces\Controller
{

  abstract protected function proxyRun();

  function run( \Qck\Interfaces\AppConfig $config )
  {
    $this->AppConfig = $config;
    return $this->proxyRun();
  }
  
  function redirect( $ControllerName, $args = array () )
  {
    header( "Location: " . $this->getAppConfig()->getRouter()->getLink( $ControllerName, $args ) );
    return null;
  }

  /**
   * @return \Qck\Interfaces\AppConfig
   */
  function getAppConfig()
  {
    return $this->AppConfig;
  }

  /**
   * @var \Qck\Interfaces\AppConfig
   */
  protected $AppConfig;

}
