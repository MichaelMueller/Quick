<?php

namespace qck\abstracts;

/**
 * Description of MbitsPhpMailer
 *
 * @author muellerm
 */
abstract class Controller implements \qck\interfaces\Controller
{

  abstract protected function proxyRun();

  function run( \qck\interfaces\AppConfig $config )
  {
    $this->AppConfig = $config;
    return $this->proxyRun();
  }

  /**
   * @return \qck\interfaces\AppConfig
   */
  function getAppConfig()
  {
    return $this->AppConfig;
  }

  /**
   * @var \qck\interfaces\AppConfig
   */
  protected $AppConfig;

}
