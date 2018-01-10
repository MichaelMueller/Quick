<?php

namespace qck\apps\cmd;

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
    return $this->getSingleton( "ControllerFactory", function()
            {
              return new \qck\core\ControllerFactory( "\\qck\\apps\\cmd\\controller", $this->Argv );
            } );
  }

  public function getAppName()
  {
    return "cli test app";
  }

  private $Argv;

}
