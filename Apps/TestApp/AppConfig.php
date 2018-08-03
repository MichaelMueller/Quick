<?php

namespace Qck\Apps\TestApp;

/**
 * Description of QckPhpMailer
 *
 * @author muellerm
 */
class AppConfig extends \Qck\Ext\AppConfig
{

  public function getRouter()
  {
    static $var = null;
    if ( !$var )
      $var = new \Qck\Core\Router( "\\Qck\\Apps\\TestApp\\Controller", $this->getInputs(), "q", "Run" );
    return $var;
  }

  public function getAppName()
  {
    return "TestApp";
  }
}
