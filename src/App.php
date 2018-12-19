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

  function buildUrl( $MethodName, array $QueryData = [] )
  {
    $CompleteQueryData = array_merge( [ $this->MethodParamName => $MethodName ], $QueryData );
    return "?" . http_build_query( $CompleteQueryData );
  }

  function run()
  {
    $ShellMethods        = $this->getShellMethods();
    $RequestedMethodName = $this->getInputs()->get( $this->MethodParamName, $ShellMethods[ 0 ] );

    // throw a good error message if controller is not found
    if ( method_exists( $this, $RequestedMethodName ) === false )
    {
      $Error = sprintf( "Method %s not implemented.", $RequestedMethodName );
      throw new \Exception( $Error, Interfaces\HttpResponder::EXIT_CODE_NOT_IMPLEMENTED );
    }
    else if ( in_array( $RequestedMethodName, $ShellMethods ) == false )
    {
      $Error = sprintf( "Method %s is not declared as Shell Method.", $RequestedMethodName );
      throw new \Exception( $Error, Interfaces\HttpResponder::EXIT_CODE_INTERNAL_ERROR );
    }
    else
    {
      $Method = new \ReflectionMethod( $this, $RequestedMethodName );
      if ( $Method->isPublic() == false && $this->getSession()->getUsername() === null )
      {
        $Error = sprintf( "Method %s is not allowed to be called.", $RequestedMethodName );
        throw new \Exception( $Error, Interfaces\HttpResponder::EXIT_CODE_UNAUTHORIZED );
      }
      $RequestedParams = $Method->getParameters();
      $FoundParams     = [];
      foreach ( $RequestedParams as $RequestedParam )
        $FoundParams[]   = $this->getInputs()->get( $RequestedParam->getName(), null );

      $Method->setAccessible( true );
      $Method->invokeArgs( $this, $FoundParams );
    }
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
