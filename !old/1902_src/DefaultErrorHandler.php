<?php

namespace Qck;

/**
 * App class is essentially the class to start.
 * It is the basic error handler. No code besides the require statement and initialization should be called in any app before.
 * 
 * @author muellerm
 */
class DefaultErrorHandler implements Interfaces\ErrorHandler
{

  function __construct( $CliMode = false, Interfaces\AdminMailer $AdminMailer = null )
  {
    $this->CliMode     = $CliMode;
    $this->AdminMailer = $AdminMailer;
  }

  function install()
  {
    // basic error setup
    error_reporting( E_ALL );
    ini_set( 'log_errors', intval(  ! $this->CliMode ) );
    ini_set( 'display_errors', intval( $this->CliMode ) );
    ini_set( 'html_errors', intval(  ! $this->CliMode ) );

    set_error_handler( array ( $this, "errorHandler" ) );
    set_exception_handler( array ( $this, "exceptionHandler" ) );
  }

  function errorHandler( $errno, $errstr, $errfile, $errline )
  {
    throw new \ErrorException( $errstr, 0, $errno, $errfile, $errline );
  }

  function exceptionHandler( $Exception )
  {
    if ( $this->AdminMailer )
      $this->AdminMailer->sendToAdmin( "Exception occured", strval( $Exception ) );
    throw $Exception;
  }

  protected $CliMode = false;

  /**
   *
   * @var Interfaces\AdminMailer
   */
  protected $AdminMailer = false;

}
