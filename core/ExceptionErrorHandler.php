<?php

namespace qck\core;

/**
 * Custom error / exception handling and error handling setup
 *
 * @author muellerm
 */
class ExceptionErrorHandler
{

  function setFatalErrorText( $FatalErrorText )
  {
    $this->FatalErrorText = $FatalErrorText;
  }

  function setAppConfig( \qck\core\interfaces\AppConfig $AppConfig )
  {
    $this->AppConfig = $AppConfig;
  }

  function install()
  {
    set_error_handler( array ( $this, "dispatchErrorAsException" ) );
    set_exception_handler( array ( $this, "handle" ) );
  }

  function dispatchErrorAsException( $errno, $errstr, $errfile, $errline )
  {
    throw new \ErrorException( $errstr, 0, $errno, $errfile, $errline );
  }

  function handle( \Throwable $e )
  {
    $errTxt = $this->FatalErrorText;
    try
    {
      $text = strval( $e );
      //$code = $e->getCode();
      if ( $this->AppConfig->getAdminMailer() )
        $this->AppConfig->getAdminMailer()->sendToAdmin( "Error on " . $this->AppConfig->getHostInfo(), $text );

      // decide if we are on cli or not      
      if($this->AppConfig->getControllerFactory()->usesCli())
        die( PHP_EOL.$text );
      else
      {
        // otherwise log error and consult error handler
        error_log( $text );
        $errDialog = $this->AppConfig->getErrorController();
        $code = $e->getCode() ? $e->getCode() : 500;
        $errDialog->setErrorCode( $code );
        
        $Response = $errDialog->run( $this->AppConfig );
        if($Response)
          $Response->send();
      }
    }
    catch ( \Exception $ex )
    {
      error_log( strval( $ex ) );
      http_response_code( 500 );
      die( $errTxt );
    }
  }

  /**
   *
   * @var \qck\core\interfaces\AppConfig
   */
  protected $AppConfig;
  protected $FatalErrorText = "An application error occured. Please come back later. If the problem persists, please contact the administrator. Thank you for your patience.";

}
