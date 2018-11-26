<?php

namespace Qck;

/**
 * App class is essentially the class to start. It is the basic error handler. No code besides the require statement and initialization should be called in any app before.
 * 
 * @author muellerm
 */
class App
{

  function run(Interfaces\AppConfig $AppConfig)
  {
    try
    {
      if ($AppConfig->getRequest()->wasRunFromCommandLine())
      {
        ini_set('display_errors', 1);
        ini_set('log_errors', 0);
      }
      // get route
      $Router            = $AppConfig->getRouter();
      $CurrentRoute      = $Router->getCurrentRoute();
      // get controller
      $ControllerFactory = $AppConfig->getControllerFactory();
      $Controller        = $ControllerFactory->create($CurrentRoute);

      if (!$Controller)
      {
        $Error = sprintf("Controller for Route %s not found. Please check route definitions.", $CurrentRoute);
        throw new \Exception($Error, Interfaces\Response::EXIT_CODE_NOT_FOUND);
      }

      $this->handleController($Controller, $AppConfig);
    }
    catch (\Exception $exc)
    {
      /* @var $exc \Exception */
      $ErrText = strval($exc);

      // First step to handle the error: Mail it (if configured)
      $AdminMailer = $AppConfig->getAdminMailer();
      if ($AdminMailer)
      {
        $AdminMailer->sendToAdmin("Error for App " . $AppConfig->getAppName() . " on " . $AppConfig->getHostName(), $ErrText);
      }
      // second: try use the error controller
      /* @var $ErrorController \Qck\Interfaces\Controller */
      $ErrorController = $AppConfig->getErrorController();
      if ($ErrorController)
      {
        $ErrorController->setErrorCode($exc->getCode());
        $this->handleController($ErrorController, $AppConfig);
      }
      // third: let php decide
      else
      {
        throw $exc;
      }
    }
  }

  protected function handleController(\Qck\Interfaces\Controller $Controller, \Qck\Interfaces\AppConfig $AppConfig)
  {
    $Response = $Controller->run($AppConfig);
    $Output   = $Response->getOutput();
    if ($Output !== null)
    {
      $Request = $AppConfig->getRequest();
      if ($Request->wasRunFromCommandLine() == false)
      {
        http_response_code($Response->getExitCode());
        header(sprintf("Content-Type: %s; charset=%s", $Output->getContentType(), $Output->getCharset()));
        foreach ($Output->getAdditionalHeaders() as $header)
        {
          header($header);
        }
      }
      echo $Output->render();
    }
    exit($Response->getExitCode());
  }

}
