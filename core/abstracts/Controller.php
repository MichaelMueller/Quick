<?php

namespace qck\core\abstracts;

/**
 * Description of QckPhpMailer
 *
 * @author muellerm
 */
abstract class Controller implements \qck\core\interfaces\Controller
{

  abstract protected function proxyRun();

  function run( \qck\core\interfaces\AppConfig $config )
  {
    $this->AppConfig = $config;
    return $this->proxyRun();
  }

  function redirect($ControllerName, $args = array ())
  {
    header("Location: ".$this->getAppConfig()->mkLink( $ControllerName, $args ));
    return null;
  }
  
  /**
   * @return \qck\core\interfaces\AppConfig
   */
  function getAppConfig()
  {
    return $this->AppConfig;
  }

  /**
   * @var \qck\core\interfaces\AppConfig
   */
  protected $AppConfig;

}
