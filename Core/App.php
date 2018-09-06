<?php

namespace Qck\Core;

/**
 * App class is essentially the class to start. It is the basic error handler. No code besides the require statement and initialization should be called in any app before.
 * 
 * @author muellerm
 */
class App
{

  function __construct( \Qck\Interfaces\AppConfig $Config )
  {
    $this->Config = $Config;
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
    {
      $err = "Controller " . $Router->getCurrentControllerFqcn() . " was not found";
      $exitCode = \Qck\Interfaces\Response::EXIT_CODE_NOT_FOUND;
      throw new \InvalidArgumentException( $err, $exitCode );
    }
    $this->handleController( $Controller, $Config );
  }

  function dispatchErrorAsException( $errno, $errstr, $errfile, $errline )
  {
    throw new \ErrorException( $errstr, 0, $errno, $errfile, $errline );
  }

  protected function handleController( \Qck\Interfaces\Controller $Controller,
                                       \Qck\Interfaces\AppConfig $Config )
  {
    /* @var $Response Qck\Interfaces\Response */
    $Response = $Controller->run( $Config );
    $Output = $Response->getOutput();
    if ( $Output !== null )
    {
      if ( $Config->isCli() == false )
      {
        http_response_code( $Response->getExitCode() );
        header( sprintf( "Content-Type: %s; charset=%s", $Output->getContentType(), $Output->getCharset() ) );
        foreach ( $Output->getAdditionalHeaders() as $header )
          header( $header );
      }
      echo $Output->render();
    }
    exit( $Response->getExitCode() );
  }

  function handle( $e )
  {
    /* @var $e \Exception */
    $ErrText = strval( $e );
    try
    {
      $Config = $this->getAppConfig();

      // First step to handle the error: Mail it (if configured)
      if ( $Config->getAdminMailer() )
        $Config->getAdminMailer()->sendToAdmin( "Error on " . $Config->getHostInfo(), $ErrText );

      // Next step: Let the programmer take over error Handling if he wants to
      // If there is no custom Error Handler or another exception occurs, we finally get out here immediately
      $ErrorController = $Config->getErrorController();
      if ( $ErrorController )
      {
        $ErrorController->setErrorCode( $e->getCode() );
        $this->handleController( $ErrorController, $Config );
      }
      if ( ini_get( "display_errors" ) == 1 )
      {
        if ( $Config->isCli() )
          echo $ErrText;
        else
          echo "<pre>" . $ErrText . "</pre>";
      }
      else
      {
        error_log( $ErrText );
        die( "An application error occured. Please come back later. If the problem persists, please contact the Administrator. Thank you for your patience." );
      }
    }
    catch ( \Exception $ex )
    {
      error_log( strval( $ex ) );
      die( "An application error occured. Please come back later. If the problem persists, please contact the Administrator. Thank you for your patience." );
    }
  }

  function fatal( $ex )
  {
    // OOPS THIS IS FATAL NOW, EVEN THE ERROR CONTROLLER PRODUCED SOMETHING WRONG
    // LOG THE ERROR AND DIE, BYE BYE
    error_log( strval( $ex ) );
  }

  /**
   * 
   * @return Qck\Interfaces\AppConfig
   */
  public function getAppConfig()
  {
    return $this->Config;
  }

  /**
   *
   * @var Qck\Interfaces\AppConfig
   */
  protected $Config;

}
