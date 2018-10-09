<?php

namespace Qck;

/**
 * Description of AppConfigFactory
 *
 * @author micha
 */
class AppConfig implements Interfaces\AppConfig
{

  function __construct( $AppName, Interfaces\Request $Request, Interfaces\Router $Router,
                        Interfaces\ControllerFactory $ControllerFactory )
  {
    $this->AppName = $AppName;
    $this->Request = $Request;
    $this->Router = $Router;
    $this->ControllerFactory = $ControllerFactory;
  }

  function getAppName()
  {
    return $this->AppName;
  }

  function getRequest()
  {
    return $this->Request;
  }

  function getRouter()
  {
    return $this->Router;
  }

  function getControllerFactory()
  {
    return $this->ControllerFactory;
  }

  function getAdminMailer()
  {
    return $this->AdminMailer;
  }

  function getErrorController()
  {
    return $this->ErrorController;
  }

  function setErrorController( Interfaces\ErrorController $ErrorController )
  {
    $this->ErrorController = $ErrorController;
  }

  function setAdminMailer( Interfaces\AdminMailer $AdminMailer )
  {
    $this->AdminMailer = $AdminMailer;
  }

  public function getHostName()
  {
    if ( !$this->HostName )
      $this->HostName = gethostname();
    return $this->HostName;
  }

  function setHostName( $HostName )
  {
    $this->HostName = $HostName;
  }

  /**
   *
   * @var string
   */
  protected $AppName;

  /**
   *
   * @var Interfaces\Request
   */
  protected $Request;

  /**
   *
   * @var Interfaces\Router
   */
  protected $Router;

  /**
   *
   * @var Interfaces\ControllerFactory
   */
  protected $ControllerFactory;

  /**
   *
   * @var Interfaces\AdminMailer
   */
  protected $AdminMailer;

  /**
   *
   * @var Interfaces\ErrorController
   */
  protected $ErrorController;

  /**
   *
   * @var string
   */
  protected $HostName;

}
