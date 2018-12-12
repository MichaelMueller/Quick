<?php

namespace Qck;

/**
 * The Router gets the currently selected controller of the application
 *
 * @author muellerm
 */
class ExtendedRouter implements \Qck\Interfaces\ExtendedRouter
{

  function __construct( Interfaces\Router $Router, $BaseUrl )
  {
    $this->Router  = $Router;
    $this->BaseUrl = $BaseUrl;
  }

  public function getAbsoluteLink( $Route, $args = array () )
  {
    return $this->getBaseUrl() . "/" . $this->getFrontControllerScriptName() . $this->Router->getLink( $Route, $args );
  }

  public function getBaseUrl()
  {
    return $this->BaseUrl();
  }

  public function getCurrentController()
  {
    return $this->Router->getCurrentController();
  }

  public function getCurrentRoute()
  {
    return $this->Router->getCurrentRoute();
  }

  function setFrontControllerScriptName( $FrontControllerScriptName )
  {
    $this->FrontControllerScriptName = $FrontControllerScriptName;
  }

  public function getFrontControllerScriptName()
  {
    return $this->FrontControllerScriptName();
  }

  public function getLink( $Route, $args = array () )
  {
    return $this->Router->getLink( $Route, $args );
  }

  public function redirect( $Route, $args = array () )
  {
    $this->Router->redirect( $Route, $args );
  }

  public function redirectToUrl( $Url )
  {
    $this->Router->redirectToUrl( $Url );
  }

  /**
   *
   * @var Interfaces\Router
   */
  protected $Router;

  /**
   * @return Controller or null
   */
  protected $BaseUrl;

  /**
   * @return string this will normally default to "index.php"
   */
  protected $FrontControllerScriptName = "index.php";

}
