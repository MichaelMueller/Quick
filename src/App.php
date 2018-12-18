<?php

namespace Qck;

/**
 * App class is essentially the class to start.
 * It is the basic error handler. No code besides the require statement and initialization should be called in any app before.
 * 
 * @author muellerm
 */
class App implements Interfaces\Controller
{

  function createAppConfigAndRun( Interfaces\DirectoryConfig $DirectoryConfig )
  {
    $this->setDefaultErrorSettings();

    $this->run( $this->loadConfig( $DirectoryConfig ) );
  }

  protected function setDefaultErrorSettings( $DisplayErrors = false, $LogErrors = true )
  {
    // basic error setup
    error_reporting( E_ALL );
    ini_set( 'log_errors', intval( $LogErrors ) );
    ini_set( 'display_errors', intval( $DisplayErrors ) );
  }

  function run( Interfaces\AppConfig $AppConfig )
  {
    $this->setDefaultErrorSettings();

    try
    {
      // reset if issued from cli
      if ( $AppConfig->showErrors() || $AppConfig->wasInvokedFromCli() )
      {
        $this->setDefaultErrorSettings( true, false );
      }

      set_error_handler( array ( $this, "exceptionErrorHandler" ) );
      // get controller for current route
      $Controller = $AppConfig->getRouter()->getCurrentController();

      // throw a good error message if controller is not found
      if ( ! $Controller instanceof Interfaces\Controller )
      {
        $Error = sprintf( "Controller for Route %s not found. Please check route definitions.", $AppConfig->getRouter()->getCurrentRoute() );
        throw new \Exception( $Error, Interfaces\Response::EXIT_CODE_NOT_FOUND );
      }
      $Controller->run( $AppConfig );
    }
    catch ( \Exception $exc )
    {
      /* @var $exc \Exception */
      // First step to handle the error: Mail it (if configured)
      $AdminMailer = $AppConfig->getAdminMailer();
      if ( $AdminMailer )
        $AdminMailer->sendToAdmin( "Error for App " . $AppConfig->getAppName() . " on " . $AppConfig->getDirectoryConfig()->getHostName(), strval( $exc ) );

      // second: try use the error controller
      /* @var $ErrorController \Qck\Interfaces\Controller */
      $ErrorController = $AppConfig->getErrorController();
      if ( $ErrorController )
      {
        $ErrorController->setErrorCode( $exc->getCode() );
        $ErrorController->run( $AppConfig );
      }
      // third: let php decide
      else
      {
        throw $exc;
      }
    }
  }

  /**
   * 
   * @param \Qck\Interfaces\DirectoryConfig $DirectoryConfig
   * @throws \Exception
   * @return Interfaces\AppConfig AppConfig instance
   */
  protected function loadConfig( Interfaces\DirectoryConfig $DirectoryConfig )
  {
    $Dir           = $DirectoryConfig->getLocalDataDir();
    $CreateAppFile = $Dir . "/" . $this->CreateAppConfigFileName;

    if ( ! file_exists( $CreateAppFile ) )
    {
      $DirectoryConfig->createDirIfNotExists( $Dir );
      touch( $CreateAppFile );
      $Error = sprintf( "Cannot find host specific AppConfig Creator file. " .
              "Please create %s and return a valid \\Qck\\Interfaces\\App instance", $CreateAppFile );
      throw new \Exception( $Error, Interfaces\Response::EXIT_CODE_INTERNAL_ERROR );
    }

    /* @var $AppConfig Interfaces\AppConfig */
    $AppConfig = require_once( $CreateAppFile );
    $AppConfig->setDirectoryConfig( $DirectoryConfig );
    return $AppConfig;
  }

  function exceptionErrorHandler( $errno, $errstr, $errfile, $errline )
  {
    throw new \ErrorException( $errstr, 0, $errno, $errfile, $errline );
  }

  protected $CreateAppConfigFileName = "createAppConfig.php";

}
