<?php

namespace qck\core;

/**
 * App class is essentially the class to start.
 * 
 * @author muellerm
 */
class App
{

  function __construct( \qck\interfaces\AppConfigFactory $ConfigFactory )
  {
    $this->ConfigFactory = $ConfigFactory;
  }

  function run()
  {
    // warnings as errors
    $exErrHandler = new ExceptionErrorHandler();
    $exErrHandler->install();

    // now load appConfigig local values
    $Config = $this->getAppConfig();
    $exErrHandler->setAppConfig( $Config );

    // run setup if necessary
    $setupController = $Config->getSetupController();
    if ( $setupController && $setupController->isUpdateNecessary() )
      $setupController->run( $Config );

    // let the frontcontroller handle the rest
    $cntrllrFctry = $Config->getControllerFactory();
    /* @var $cntrllr \qck\interfaces\Controller */
    $cntrllr = $cntrllrFctry->getController();

    // handle error if no controller is found
    if ( is_null( $cntrllr ) )
    {
      throw new \Exception( "Controller was not found", \qck\interfaces\Response::CODE_PAGE_NOT_FOUND );
    }

    /* @var $response \qck\interfaces\Response */
    $response = $cntrllr->run( $Config );
    
    // send the response
    // if there is a null response, the controller has sent everything himself
    if(!is_null($response))
      $response->send();
  }

  /**
   * 
   * @return \qck\interfaces\AppConfig
   */
  public function getAppConfig()
  {
    if ( is_null( $this->Config ) )
    {
      $this->Config = $this->ConfigFactory->create();
    }
    return $this->Config;
  }

  /**
   *
   * @var \qck\interfaces\AppConfigFactory
   */
  protected $ConfigFactory;
  protected $Config;

}
