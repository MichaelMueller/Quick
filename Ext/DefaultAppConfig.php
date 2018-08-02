<?php

namespace Qck\Ext;

/**
 * Description of QckPhpMailer
 *
 * @author muellerm
 */
class DefaultAppConfig extends abstracts\AppConfig
{

  function __construct( $AppName, $ControllerNameSpace = "", $Argv = null )
  {
    $this->AppName = $AppName;
    $this->ControllerNameSpace = $ControllerNameSpace;
    $this->Argv = $Argv;
  }

  public function getAppName()
  {
    return $this->AppName;
  }

  public function getRouter()
  {
    return $this->getSingleton( "Router", function()
        {
          return new Router( $this->ControllerNameSpace, $this->Argv );
        } );
  }

  private $AppName = array ();
  private $ControllerNameSpace = array ();
  private $Argv = array ();

}
