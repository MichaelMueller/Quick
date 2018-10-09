<?php

namespace Qck;

/**
 * App class is essentially the class to start. It is the basic error handler. No code besides the require statement and initialization should be called in any app before.
 * 
 * @author muellerm
 */
class App implements \Qck\Interfaces\App
{

  function run( \Qck\Interfaces\AppConfig $Config )
  {
    error_reporting( E_ALL );
    ini_set( 'log_errors', 1 );
    ini_set( 'display_errors', 0 );

    try
    {
      $Request = $Config->getRequest();

      if ( $Request->isCli() )
      {
        ini_set( 'display_errors', 1 );
        ini_set( 'log_errors', 0 );
      }
      $Router = $Config->getRouter();
      $CurrentRoute = $Router->getCurrentRoute();

      $ControllerFactory = $Config->getControllerFactory();
      $Controller = $ControllerFactory->create( $CurrentRoute );
      if ( !$Controller )
        throw new \Exception( sprintf( "Controller for Route %s not found. Please check route definitions.", $CurrentRoute ), Interfaces\Response::EXIT_CODE_NOT_FOUND );

      $this->handleController( $Controller, $Config );
    }
    catch ( \Exception $exc )
    {
      /* @var $e \Exception */
      $ErrText = strval( $exc );

      $AdminMailer = $Config->getAdminMailer();
      // First step to handle the error: Mail it (if configured)
      if ( $AdminMailer )
        $AdminMailer->sendToAdmin( "Error for App " . $Config->getAppName() . " on " . $Config->getHostName(), $ErrText );
      /* @var $ErrorController \Qck\Interfaces\Controller */
      $ErrorController = $Config->getErrorController();
      if ( $ErrorController )
      {
        $ErrorController->setErrorCode( $exc->getCode() );
        $this->handleController( $ErrorController, $Config );
      }
      else
      {
        throw $exc;
      }
    }
  }

  protected function handleController( \Qck\Interfaces\Controller $Controller,
                                       Interfaces\AppConfig $Config )
  {
    $Response = $Controller->run( $Config );
    $Output = $Response->getOutput();
    if ( $Output !== null )
    {
      $Request = $Config->getRequest();
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
}
