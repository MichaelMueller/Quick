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

  function setAppConfig( \qck\interfaces\AppConfig $AppConfig )
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

  function handle( \Exception $e )
  {
    $errTxt = $this->FatalErrorText;
    try
    {
      $text = strval( $e );
      //$code = $e->getCode();
      error_log( $text );
      if ( $this->AppConfig->sendMailOnException() )
        $this->AppConfig->getAdminMailer()->sendToAdmin( "Error on " . $this->AppConfig->getHostInfo(), $text );

      $errDialog = $this->AppConfig->getErrorHandler();
      $errDialog->setErrorCode( $e->getCode() );
      $errDialog->run( $this->AppConfig )->send();
    }
    catch ( Exception $ex )
    {
      error_log( strval( $ex ) );
      http_response_code( 500 );
      die( $errTxt );
    }
  }

  /**
   *
   * @var \qck\interfaces\AppConfig
   */
  protected $AppConfig;
  protected $FatalErrorText = "An application error occured. Please come back later. If the problem persists, please contact the administrator. Thank you for your patience.";

}
