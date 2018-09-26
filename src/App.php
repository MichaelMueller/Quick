<?php

namespace Qck;

/**
 * App class is essentially the class to start. It is the basic error handler. No code besides the require statement and initialization should be called in any app before.
 * 
 * @author muellerm
 */
class App implements Interfaces\App
{

  function __construct( Qck\Interfaces\ServiceRepo $ServiceRepo )
  {
    $this->ServiceRepo = $ServiceRepo;
  }

  function run()
  {
    // basic error handling
    set_error_handler( array ( $this, "dispatchErrorAsException" ) );
    set_exception_handler( array ( $this, "handle" ) );

    /* @var $Router Interfaces\Router */
    $Router = $this->ServiceRepo->get( Interfaces\Router::class );
    $Controller = $Router->getController();

    $this->handleController( $Controller );
  }

  function dispatchErrorAsException( $errno, $errstr, $errfile, $errline )
  {
    throw new \ErrorException( $errstr, 0, $errno, $errfile, $errline );
  }

  protected function handleController( Interfaces\Controller $Controller )
  {
    $Response = $Controller->run( $this->ServiceRepo );
    $Output = $Response->getOutput();
    if ( $Output !== null )
    {
      /* @var $Request Interfaces\Request */
      $Request = $this->ServiceRepo->get( Interfaces\Request::class );
      if ( $Request->isCli() == false )
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

    /* @var $Request Interfaces\Mail\AdminMailer */
    $AdminMailer = $this->ServiceRepo->getOptional( Interfaces\Mail\AdminMailer::class );
    /* @var $AppConfig Interfaces\AppConfig */
    $AppConfig = $this->ServiceRepo->getOptional( Interfaces\AppConfig::class );
    // First step to handle the error: Mail it (if configured)
    if ( $AdminMailer )
      $AdminMailer->sendToAdmin( "Error for App " . $AppConfig->getAppName() . " on " . $AppConfig->getHostName(), $ErrText );

    // Next step: Let the programmer take over error Handling if he wants to
    // If there is no custom Error Handler or another exception occurs, we finally get out here immediately
    /* @var $ErrorController Interfaces\Controller */
    $ErrorController = $this->ServiceRepo->getOptional( Interfaces\Mail\AdminMailer::class );
    if ( $ErrorController )
    {
      $ErrorController->setErrorCode( $e->getCode() );
      $this->handleController( $ErrorController );
    }
    // since there is exit() in controller we do not end here
    if ( ini_get( "display_errors" ) == 1 )
    {
      /* @var $Request Interfaces\Request */
      $Request = $this->ServiceRepo->get( Interfaces\Request::class );
      if ( $Request->isCli() )
        echo $ErrText;
      else
        echo "<html><head><title>Errors occured</head><body><pre>" . $ErrText . "</pre></body></html>";
    }
    else
    {
      error_log( $ErrText );
      die( "An application error occured. Please come back later. If the problem persists, please contact the Administrator. Thank you for your patience." );
    }
  }

  /**
   *
   * @var Qck\Interfaces\ServiceRepo
   */
  protected $ServiceRepo;

}
