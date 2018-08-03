<?php

namespace Qck\Apps\HelloWorld;

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
      $var = new \Qck\Core\Router( "\\Qck\\Apps\\HelloWorld\\Controller", $this->getInputs() );
    return $var;
  }

  public function getAppName()
  {
    return "Qck.Apps.HelloWorld";
  }
}
