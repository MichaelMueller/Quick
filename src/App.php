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
   * the default method has to be implemented
   */
  abstract public function index();

  /**
   * @return Inputs
   */
  abstract protected function getInputs();

  /**
   * @return Interfaces\ErrorHandler
   */
  protected function getErrorHandler()
  {
    return null;
  }

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

  /**
   * 
   * @return bool
   */
  protected function getLink( $MethodName, $args = array () )
  {
    $link = "?" . $this->MethodParamName . "=" . $MethodName;

    if ( is_array( $args ) )
    {
      foreach ( $args as $key => $value )
        $link .= "&" . $key . "=" . (urlencode( $value ));
    }
    return $link;
  }

  protected function isMethodAllowed( $MethodName )
  {
    if ( $MethodName == "run" || $MethodName == "wasInvokedFromCli" )
      return false;
    $reflection = new \ReflectionMethod( $this, $MethodName );
    if ( ! $reflection->isPublic() )
      return false;

    return true;
  }

  function run()
  {
    $ErrorHandler = $this->getErrorHandler();
    if ( $ErrorHandler )
      $ErrorHandler->install();

    // get controller for current route
    $RequestedMethodName = $this->getInputs()->get( $this->MethodParamName, "index" );

    // throw a good error message if controller is not found
    if ( method_exists( $this, $RequestedMethodName ) === false )
    {
      $Error = sprintf( "Method %s not implemented.", $RequestedMethodName );
      throw new \Exception( $Error, Interfaces\Response::EXIT_CODE_NOT_IMPLEMENTED );
    }    // throw a good error message if controller is not found
    else if ( $this->isMethodAllowed( $RequestedMethodName ) === false )
    {
      $Error = sprintf( "Method %s is not allowed to be called.", $RequestedMethodName );
      throw new \Exception( $Error, Interfaces\Response::EXIT_CODE_UNAUTHORIZED );
    }
    else
      call_user_func( array ( $this, $RequestedMethodName ) );
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
