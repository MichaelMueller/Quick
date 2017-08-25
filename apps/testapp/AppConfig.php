<?php

namespace qck\apps\testapp;

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
      $var = new \qck\core\ControllerFactory( "\\qck\\apps\\testapp\\controller" );
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
    return "testapp";
  }
  
  public function getTests()
  {
    return array(tests\DailyLogTest::class);
  }
}
