<?php

namespace Qck;

/**
 * App class is essentially the class to start. It is the basic error handler. No code besides the require statement and initialization should be called in any app before.
 * 
 * @author muellerm
 */
abstract class App implements Interfaces\App, Interfaces\Functor
{

  /**
   * @return Interfaces\Router
   */
  abstract function getRouter();

  /**
   * @return Interfaces\ControllerFactory
   */
  abstract function getControllerFactory();

  /**
   * @return Interfaces\ControllerFactory
   */
  abstract function getRequest();
  
  /**
   * @return string
   */
  abstract function getAppName();

  /**
   * @return Interfaces\Mail\AdminMailer
   */
  protected function getAdminMailer()
  {
    return null;
  }

  /**
   * @return Interfaces\ErrorController
   */
  protected function getErrorController()
  {
    return null;
  }

  /**
   * @return Interfaces\Mail\AdminMailer
   */
  protected function getHostName()
  {
    if (!$this->HostName)
      $this->HostName = gethostname();
    return $this->HostName;
  }

  function run()
  {
    try
    {
      // basic error reporting
      error_reporting(E_ALL);
      ini_set('log_errors', 1);
      ini_set('display_errors', 0);
      set_error_handler(array($this, "exceptionErrorHandler"));

      // reset if issued from cli
      if ($this->getRequest()->wasRunFromCommandLine())
      {
        ini_set('display_errors', 1);
        ini_set('log_errors', 0);
      }

      // get controller for current route
      $CurrentRoute = $this->getRouter()->getCurrentRoute();
      $Controller   = $this->getControllerFactory()->create($CurrentRoute);

      // throw a good error message if controller is not found
      if (!$Controller)
      {
        $Error = sprintf("Controller for Route %s not found. Please check route definitions.", $CurrentRoute);
        throw new \Exception($Error, Interfaces\Response::EXIT_CODE_NOT_FOUND);
      }

      $this->handleController($Controller);
    }
    catch (\Exception $exc)
    {
      /* @var $exc \Exception */
      $ErrText = strval($exc);

      // First step to handle the error: Mail it (if configured)
      $AdminMailer = $this->getAdminMailer();
      if ($AdminMailer)
        $AdminMailer->sendToAdmin("Error for App " . $this->getAppName() . " on " . $this->getHostName(), $ErrText);

      // second: try use the error controller
      /* @var $ErrorController \Qck\Interfaces\Controller */
      $ErrorController = $this->getErrorController();
      if ($ErrorController)
      {
        $ErrorController->setErrorCode($exc->getCode());
        $this->handleController($ErrorController);
      }
      // third: let php decide
      else
      {
        throw $exc;
      }
    }
  }

  function exceptionErrorHandler($errno, $errstr, $errfile, $errline)
  {
    throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
  }

  protected function handleController(\Qck\Interfaces\Controller $Controller)
  {
    $Response = $Controller->run($this);
    $Output   = $Response->getOutput();
    if ($Output !== null)
    {
      $Request = $this->getRequest();
      if ($Request->wasRunFromCommandLine() == false)
      {
        http_response_code($Response->getExitCode());
        header(sprintf("Content-Type: %s; charset=%s", $Output->getContentType(), $Output->getCharset()));
        foreach ($Output->getAdditionalHeaders() as $header)
          header($header);
      }
      echo $Output->render();
    }
    exit($Response->getExitCode());
  }

  protected $HostName;

}
