<?php

namespace Qck;

/**
 * App class is essentially the class to start. It is the basic error handler. No code besides the require statement and initialization should be called in any app before.
 * 
 * @author muellerm
 */
class App implements \Qck\Interfaces\App
{

  function __construct( \Qck\Interfaces\ServiceRepo $ServiceRepo )
  {
    $this->ServiceRepo = $ServiceRepo;
  }

  function exec()
  {
    try
    {
      /* @var $Request \Qck\Interfaces\Request */
      $Request = $this->ServiceRepo->get( \Qck\Interfaces\Request::class );
      if ( $Request->isCli() )
      {
        ini_set( 'display_errors', 1 );
        ini_set( 'log_errors', 0 );
      }
      /* @var $Router \Qck\Interfaces\Router */
      $Router = $this->ServiceRepo->get( \Qck\Interfaces\Router::class );

      $CurrentRoute = $Router->getCurrentRoute();
      $Fqcn = $Router->getFqcn( $CurrentRoute );
      if ( !class_exists( $Fqcn, true ) )
        throw new \Exception( sprintf( "Route %s or class %s not found. Please check route definitions.", $CurrentRoute, $Fqcn ), Interfaces\Response::EXIT_CODE_NOT_FOUND );
      // Authentication
      if ( $Router->isProtected( $CurrentRoute ) )
      {
        /* @var $UserDb \Qck\Interfaces\UserDb */
        $UserDb = $this->ServiceRepo->get( \Qck\Interfaces\UserDb::class );
        /* @var $Session \Qck\Interfaces\Session */
        $Session = $this->ServiceRepo->get( \Qck\Interfaces\Session::class );
        $User = $UserDb->getUser( $Session->getUserName() );
        if ( !$User || !$User->hasPermissionFor( $CurrentRoute ) )
          throw new \Exception( "User cannot access Route " . $CurrentRoute, Interfaces\Response::EXIT_CODE_UNAUTHORIZED );
      }

      $Controller = new $Fqcn;
      $this->handleController( $Controller );
    }
    catch ( \Exception $exc )
    {
      /* @var $e \Exception */
      $ErrText = strval( $exc );

      /* @var $Request Interfaces\Mail\AdminMailer */
      $AdminMailer = $this->ServiceRepo->getOptional( Interfaces\Mail\AdminMailer::class );
      /* @var $Config \Qck\Interfaces\Config */
      $Config = $this->ServiceRepo->getOptional( \Qck\Interfaces\Config::class );
      // First step to handle the error: Mail it (if configured)
      if ( $AdminMailer )
        $AdminMailer->sendToAdmin( "Error for App " . $Config->getAppName() . " on " . $Config->getHostName(), $ErrText );
      /* @var $ErrorController \Qck\Interfaces\Controller */
      $ErrorController = $this->ServiceRepo->getOptional( \Qck\Interfaces\ErrorController::class );
      if ( $ErrorController )
      {
        $ErrorController->setErrorCode( $exc->getCode() );
        $this->handleController( $ErrorController );
      }
      else
      {
        throw $exc;
      }
    }
  }

  protected function handleController( \Qck\Interfaces\Controller $Controller )
  {
    $Response = $Controller->run( $this->ServiceRepo );
    $Output = $Response->getOutput();
    if ( $Output !== null )
    {
      /* @var $Request \Qck\Interfaces\Request */
      $Request = $this->ServiceRepo->get( \Qck\Interfaces\Request::class );
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

  public function run( Interfaces\AppConfig $AppConfig )
  {
    
  }

  /**
   *
   * @var \Qck\Interfaces\ServiceRepo
   */
  protected $ServiceRepo;

}
