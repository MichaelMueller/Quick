<?php

namespace qck\ext;

/**
 * Description of QckPhpMailer
 *
 * @author muellerm
 */
class DefaultAppConfig extends abstracts\AppConfig
{

  function __construct($AppName, $ControllerNameSpace = "", $Argv = null)
  {
    $this->AppName = $AppName;
    $this->ControllerNameSpace = $ControllerNameSpace;
    $this->Argv = $Argv;
  }

  public function getAppName()
  {
    return $this->AppName;
  }

  public function getControllerFactory()
  {
    return $this->getSingleton("ControllerFactory", function()
            {
              return new ControllerFactory($this->ControllerNameSpace, $this->Argv);
            });
  }

  private $AppName = array();
  private $ControllerNameSpace = array();
  private $Argv = array();

}
