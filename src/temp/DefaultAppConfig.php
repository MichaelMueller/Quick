<?php

namespace Qck;

/**
 * App class is essentially the class to start. It is the basic error handler. No code besides the require statement and initialization should be called in any app before.
 * 
 * @author muellerm
 */
abstract class DefaultAppConfig implements Interfaces\AppConfig
{

  function setErrorController( Interfaces\ErrorController $ErrorController )
  {
    $this->ErrorController = $ErrorController;
  }

  function setAdminMailer( Interfaces\Mail\AdminMailer $AdminMailer )
  {
    $this->AdminMailer = $AdminMailer;
  }

  /**
   * @return Interfaces\Mail\AdminMailer
   */
  function getAdminMailer()
  {
    return $this->AdminMailer;
  }

  /**
   * @return Interfaces\ErrorController
   */
  function getErrorController()
  {
    return $this->ErrorController;
  }

  /**
   * @return Interfaces\DirectoryConfig
   */
  function getDirectoryConfig()
  {
    return $this->DirectoryConfig;
  }

  function setDirectoryConfig( Interfaces\DirectoryConfig $DirectoryConfig )
  {
    $this->DirectoryConfig = $DirectoryConfig;
  }

  function wasInvokedFromCli()
  {
    if ( ! $this->InvokedFromCli )
      $this->InvokedFromCli = isset( $_SERVER[ 'argc' ] );
    return $this->InvokedFromCli;
  }

  /**
   * @return Interfaces\Mail\AdminMailer
   */
  function getHostName()
  {
    return $this->getDirectoryConfig()->getHostName();
  }

  function showErrors()
  {
    return $this->ShowErrors;
  }

  function setShowErrors( $ShowErrors )
  {
    $this->ShowErrors = $ShowErrors;
  }

  /**
   *
   * @var string
   */
  protected $AppName;

  /**
   *
   * @var Interfaces\Router
   */
  protected $Router;

  /**
   *
   * @var Interfaces\Arguments
   */
  protected $Inputs;

  /**
   *
   * @var bool
   */
  protected $InvokedFromCli;

  /**
   *
   * @var bool
   */
  protected $ShowErrors = false;

  /**
   *
   * @var Interfaces\DirectoryConfig
   */
  protected $DirectoryConfig;

  /**
   *
   * @var Interfaces\ErrorController
   */
  protected $ErrorController;

  /**
   *
   * @var Interfaces\Mail\AdminMailer
   */
  protected $AdminMailer;

}
