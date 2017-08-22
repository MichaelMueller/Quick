<?php

namespace qck\apps\helloworld;

/**
 * Description of MbitsPhpMailer
 *
 * @author muellerm
 */
class AppConfig extends \qck\abstracts\AppConfig
{

  public function getControllerFactory()
  {
    static $var = null;
    if ( !$var )
      $var = new \qck\core\ControllerFactory( "\\qck\\apps\\helloworld\\controller" );
    return $var;
  }

  public function getErrorHandler()
  {
    static $var = null;
    if ( !$var )
      $var = new ErrorController( );
    return $var;
  }

  public function getAppName()
  {
    return "helloworld";
  }
}
