<?php

namespace Qck;

/**
 * App class is essentially the class to start.
 * It is the basic error handler. No code besides the require statement and initialization should be called in any app before.
 * 
 * @author muellerm
 */
abstract class App implements Interfaces\App
{

  /**
   * 
   * @return bool
   */
  function wasInvokedFromCli()
  {
    if ( ! $this->InvokedFromCli )
      $this->InvokedFromCli = ! isset( $_SERVER[ 'SERVER_SOFTWARE' ] ) && (php_sapi_name() == 'cli' || is_numeric( $_SERVER[ 'argc' ] ) && $_SERVER[ 'argc' ] > 0);
    return $this->InvokedFromCli;
  }

  protected function disableMethodAuthForCli()
  {
    return true;
  }

  protected function setupErrorHandling()
  {
    error_reporting( E_ALL );
    $CliMode = $this->wasInvokedFromCli();
    ini_set( 'log_errors', intval(  ! $CliMode ) );
    ini_set( 'display_errors', intval( $CliMode ) );
    ini_set( 'html_errors', intval(  ! $CliMode ) );
    set_error_handler( array ( $this, "errorHandler" ) );
  }

  function errorHandler( $errno, $errstr, $errfile, $errline )
  {
    throw new \ErrorException( $errstr, 0, $errno, $errfile, $errline );
  }

  function buildUrl( $MethodName, array $QueryData = [] )
  {
    $CompleteQueryData = array_merge( [ $this->MethodParamName => $MethodName ], $QueryData );
    return "?" . http_build_query( $CompleteQueryData );
  }

  function run()
  {
    $this->setupErrorHandling();

    // find method and run
    $ShellMethods        = $this->getShellMethods();
    $RequestedMethodName = $this->getInputs()->get( $this->MethodParamName, $ShellMethods[ 0 ] );
    if ( in_array( $RequestedMethodName, $ShellMethods ) === false )
    {
      $Error = sprintf( "Method %s is not declared as Shell Method.", $RequestedMethodName );
      throw new \Exception( $Error, Interfaces\HttpResponder::EXIT_CODE_INTERNAL_ERROR );
    }

    $Method             = new \ReflectionMethod( $this, $RequestedMethodName );
    $MethodAuthDisabled = $this->wasInvokedFromCli() && $this->disableMethodAuthForCli();
    if ( $MethodAuthDisabled == false && $Method->isPublic() == false )
    {
      $MethodAllowed = false;
      $User          = $this->getUserDb()->getUser( $this->getSession()->getUsername() );
      if ( $User )
        $MethodAllowed = $Method->isProtected() || ($Method->isPrivate() && $User->isAdmin());

      if ( $MethodAllowed === false )
      {
        $Error = sprintf( "Method %s is not allowed to be called.", $RequestedMethodName );
        throw new \Exception( $Error, Interfaces\HttpResponder::EXIT_CODE_UNAUTHORIZED );
      }
    }
    $RequestedParams = $Method->getParameters();
    $FoundParams     = [];
    foreach ( $RequestedParams as $RequestedParam )
      $FoundParams[]   = $this->getInputs()->get( $RequestedParam->getName(), null );

    $Method->setAccessible( true );
    $Method->invokeArgs( $this, $FoundParams );
  }

  /**
   *
   * @var bool 
   */
  protected $InvokedFromCli;

  /**
   *
   * @var string
   */
  protected $MethodParamName = "q";

}
