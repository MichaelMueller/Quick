<?php

namespace Qck;

/**
 * App class is essentially the class to start.
 * It is the basic error handler. No code besides the require statement and initialization should be called in any app before.
 * 
 * @author muellerm
 */
abstract class App
{

  /**
   * @return Interfaces\Inputs
   */
  abstract function getInputs();

  /**
   * @return string[]
   */
  abstract function getShellMethods();

  function __construct( $DevMode = false, $MethodParamName = "m" )
  {
    $this->DevMode         = $DevMode;
    $this->MethodParamName = $MethodParamName;
  }

  function isCli()
  {
    if ( defined( 'STDIN' ) )
    {
      return true;
    }

    if ( empty( $_SERVER[ 'REMOTE_ADDR' ] ) and ! isset( $_SERVER[ 'HTTP_USER_AGENT' ] ) and count( $_SERVER[ 'argv' ] ) > 0 )
    {
      return true;
    }

    return false;
  }

  protected function setupErrorHandling()
  {
    error_reporting( E_ALL );
    ini_set( 'log_errors', intval(  ! $this->DevMode ) );
    ini_set( 'display_errors', intval( $this->DevMode ) );
    ini_set( 'html_errors', intval(  ! $this->isCli() ) );
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
      throw new \InvalidArgumentException( $Error, Interfaces\HttpResponder::EXIT_CODE_INTERNAL_ERROR );
    }

    $Method          = new \ReflectionMethod( $this, $RequestedMethodName );
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
  protected $DevMode;

  /**
   *
   * @var string
   */
  protected $MethodParamName;

}
