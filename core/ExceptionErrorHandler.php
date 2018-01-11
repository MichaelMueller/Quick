<?php

namespace qck\core;

/**
 * Custom error / exception handling and error handling setup
 *
 * @author muellerm
 */
class ExceptionErrorHandler
{

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

  function handle( $e )
  {
    $FatalMsg = "An application error occured. Please come back later. If the problem persists, please contact the administrator. Thank you for your patience.";
    try
    {
      $errText = strval( $e );

      // first step: if errors should get logged, then log it
      if ( boolval( ini_get( "log_errors" ) ) )
        error_log( $errText );

      // third step to handle the error: mail it if it is necessary
      if ( $this->AppConfig->getAdminMailer() )
        $this->AppConfig->getAdminMailer()->sendToAdmin( "Error on " . $this->AppConfig->getHostInfo(), $errText );

      // third step: if we have an error controller, then use this one to do the error handling      
      $errDialog = $this->AppConfig->getErrorController();
      if ( $errDialog )
      {
        $code = $e->getCode() ? $e->getCode() : 500;
        $errDialog->setErrorCode( $code );
        $Response = $errDialog->run( $this->AppConfig );
        if ( $Response )
          $Response->send();
      }
      // else: if errors should get printed= throw the error ant let the whole thing die from there on
      else if ( boolval( ini_get( "display_errors" ) ) )
        throw $e;
      // ultimatively: inform at least something to the user
      else
      {
        if ( !$this->AppConfig->getControllerFactory()->usesCli() )
          http_response_code( 500 );
        die( $FatalMsg );
      }
    }
    catch ( \Exception $ex )
    {
      die( $FatalMsg );
    }
  }

  /**
   *
   * @var \qck\core\interfaces\AppConfig
   */
  protected $AppConfig;

}
