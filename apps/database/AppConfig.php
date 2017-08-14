<?php

namespace qck\apps\database;

/**
 * Description of MbitsPhpMailer
 *
 * @author muellerm
 */
class AppConfig extends \qck\abstracts\AppConfig
{

  public function getAppName()
  {
    return "database";
  }

  public function getControllerFactory()
  {
    static $var = null;
    if ( !$var )
      $var = new \qck\core\ControllerFactory( "\\qck\\apps\\database\\controller" );
    return $var;
  }

  public function getErrorHandler()
  {
    static $var = null;
    if ( !$var )
      $var = new ErrorController( );
    return $var;
  }
}
