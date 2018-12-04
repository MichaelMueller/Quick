<?php

namespace Qck;

/**
 * App class is essentially the class to start. It is the basic error handler. No code besides the require statement and initialization should be called in any app before.
 * 
 * @author muellerm
 */
abstract class App implements Interfaces\App, Interfaces\Functor
{

  /**
   * @return Interfaces\Router
   */
  abstract function getRouter();

  /**
   * @return Interfaces\ControllerFactory
   */
  abstract function getControllerFactory();

  /**
   * @return Interfaces\Inputs
   */
  abstract function getInputs();

  /**
   * @return string
   */
  abstract function getAppName();

  /**
   * @return Interfaces\Mail\AdminMailer
   */
  function getAdminMailer()
  {
    return null;
  }

  /**
   * @return Interfaces\ErrorController
   */
  function getErrorController()
  {
    return null;
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
    if ( !$this->InvokedFromCli )
      $this->InvokedFromCli = isset( $_SERVER[ 'argc' ] );
    return $this->InvokedFromCli;
  }

  /**
   * @return Interfaces\Mail\AdminMailer
   */
  function getHostName()
  {
    if ( !$this->HostName )
      $this->HostName = gethostname();
    return $this->HostName;
  }

  function getShowErrors()
  {
    return $this->ShowErrors;
  }

  function setShowErrors( $ShowErrors )
  {
    $this->ShowErrors = $ShowErrors;
  }

  function run()
  {
    try
    {
      // basic error reporting
      error_reporting( E_ALL );

      // reset if issued from cli
      if ( $this->ShowErrors || $this->wasInvokedFromCli() )
      {
        ini_set( 'display_errors', 1 );
        ini_set( 'log_errors', 0 );
      }
      else
      {
        ini_set( 'log_errors', 1 );
        ini_set( 'display_errors', 1 );
      }

      set_error_handler( array ( $this, "exceptionErrorHandler" ) );
      // get controller for current route
      $CurrentRoute = $this->getRouter()->getCurrentRoute();

      $Controller = $this->getControllerFactory()->create( $CurrentRoute );

      // throw a good error message if controller is not found
      if ( !$Controller )
      {
        $Error = sprintf( "Controller for Route %s not found. Please check route definitions.", $CurrentRoute );
        throw new \Exception( $Error, Interfaces\Response::EXIT_CODE_NOT_FOUND );
      }
      $Controller->run($this);
    }
    catch ( \Exception $exc )
    {
      /* @var $exc \Exception */
      // First step to handle the error: Mail it (if configured)
      $AdminMailer = $this->getAdminMailer();
      if ( $AdminMailer )
        $AdminMailer->sendToAdmin( "Error for App " . $this->getAppName() . " on " . $this->getHostName(), strval( $exc ) );

      // second: try use the error controller
      /* @var $ErrorController \Qck\Interfaces\Controller */
      $ErrorController = $this->getErrorController();
      if ( $ErrorController )
      {
        $ErrorController->setErrorCode( $exc->getCode() );
        $ErrorController->run($this);
      }
      // third: let php decide
      else
      {
        throw $exc;
      }
    }
  }

  function exceptionErrorHandler( $errno, $errstr, $errfile, $errline )
  {
    throw new \ErrorException( $errstr, 0, $errno, $errfile, $errline );
  }

  protected $HostName;
  protected $InvokedFromCli;
  protected $ShowErrors = false;

  /**
   *
   * @var Interfaces\DirectoryConfig
   */
  protected $DirectoryConfig;

}
