<?php

namespace qck\apps\testapp;

/**
 * Description of QckPhpMailer
 *
 * @author muellerm
 */
class AppConfig extends \qck\ext\abstracts\AppConfig
{

  function __construct( $Argv = null )
  {
    $this->Argv = $Argv;
  }

  public function getControllerFactory()
  {
    static $var = null;
    if ( !$var )
      $var = new \qck\core\ControllerFactory( "\\qck\\apps\\testapp\\controller", $this->Argv );
    return $var;
  }

  public function getErrorController()
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
    return array ( \qck\ext\tests\DailyLogTest::class, \qck\node\tests\NodeTest::class, \qck\db\tests\ExpressionTest::class );
  }

  private $Argv;

}
