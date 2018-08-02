<?php

namespace Qck\Core;

/**
 * App class is essentially the class to start. It is the basic error handler. No code besides the require statement and initialization should be called in any app before.
 * 
 * @author muellerm
 */
class App
{

  const FATAL_MSG = "An application error occured. Please come back later. If the problem persists, please contact the Administrator. Thank you for your patience.";

  function __construct( \Qck\Interfaces\AppConfigFactory $ConfigFactory )
  {
    $this->ConfigFactory = $ConfigFactory;
  }

  function run()
  {
    set_error_handler( array ( $this, "dispatchErrorAsException" ) );
    set_exception_handler( array ( $this, "handle" ) );

    // now load appConfigig local values
    $Config = $this->getAppConfig();

    // let the frontcontroller handle the rest
    $Router = $Config->getRouter();
    /* @var $Controller Qck\Interfaces\Controller */
    $Controller = $Router->getController();

    // handle error if no controller is found
    if ( is_null( $Controller ) )
      throw new \InvalidArgumentException( "Controller " . $Router->getControllerNamespace() . "\\" . $Router->getCurrentControllerClassName() . " was not found", \Qck\Interfaces\Response::CODE_NOT_FOUND );

    /* @var $Response Qck\Interfaces\Response */
    $Response = $Controller->run( $Config );

    // If there is a null Response, the Controller has sent Everything himself
    $ExitCode = 0;
    if ( !is_null( $Response ) )
    {
      $Response->send();
      $ExitCode = $Response->getExitCode();
    }

    exit( $ExitCode );
  }

  function dispatchErrorAsException( $errno, $errstr, $errfile, $errline )
  {
    throw new \ErrorException( $errstr, 0, $errno, $errfile, $errline );
  }

  function handle( $e )
  {
    $FatalMsg = self::FATAL_MSG;
    /* @var $e \Exception */
    $ErrText = strval( $e );
    try
    {
      $AppConfig = $this->getAppConfig();

      // First step to handle the error: Mail it (if configured)
      if ( $AppConfig->getAdminMailer() )
        $AppConfig->getAdminMailer()->sendToAdmin( "Error on " . $AppConfig->getHostInfo(), $ErrText );

      // Next step: Let the programmer take over error Handling if he wants to
      // If there is no custom Error Handler or another exception occurs, we finally get out here immediately
      $ErrorController = $AppConfig->getErrorController();
      if ( $ErrorController )
      {
        $ErrorController->setErrorCode( $e->getCode() );
        $Response = $ErrorController->run( $AppConfig );
        if ( $Response )
          $Response->send();
      }

      if ( !$AppConfig->isCli() )
      {
        http_response_code( $e->getCode() );
        print "<pre>";
      }

      if ( $AppConfig->shouldPrintErrors() == false )
        print $ErrText;
      else
      {
        error_log( $ErrText );
        print $FatalMsg;
      }

      if ( !$AppConfig->isCli() )
        print "</pre>";

      exit( $e->getCode() );
    }
    catch ( \Exception $ex )
    {
      error_log( $ErrText );
      die( $FatalMsg );
    }
  }

  /**
   * 
   * @return Qck\Interfaces\AppConfig
   */
  public function getAppConfig()
  {
    if ( is_null( $this->Config ) )
    {
      $this->Config = $this->ConfigFactory->create();
    }
    return $this->Config;
  }

  /**
   *
   * @var Qck\Interfaces\AppConfigFactory
   */
  protected $ConfigFactory;
  protected $Config;

}
