<?php

namespace Qck\Apps\HelloWorld;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class AppConfig extends \Qck\Core\AppConfig
{

  function __construct( $Argv )
  {
    parent::__construct( $Argv );
  }

  public function getAppName()
  {
    return "HelloWorld";
  }

  public function getRouter()
  {
    return $this->getSingleton( "Router", function()
        {
          return new \Qck\Core\Router( "\\Qck\\Apps\\HelloWorld\\Controller", $this->getArgv() );
        } );
  }
}
