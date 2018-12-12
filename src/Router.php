<?php

namespace Qck;

/**
 * The Router gets the currently selected controller of the application
 *
 * @author muellerm
 */
class Router implements \Qck\Interfaces\Router
{

  const DEFAULT_QUERY_KEY = "q";

  static function getClassName( $Fqcn )
  {
    $path = explode( '\\', $Fqcn );
    return array_pop( $path );
  }

  static function createSingleRoute( $Fqcn )
  {
    $ClassName = self::getClassName( $Fqcn );
    $Router    = new Router();
    $Router->addRoute( $ClassName, $Fqcn );
    return $Router;
  }

  function __construct( \Qck\Interfaces\Inputs $Inputs = null )
  {
    $this->Inputs   = $Inputs;
    $this->QueryKey = self::DEFAULT_QUERY_KEY;
  }

  function addFqcn( $Fqcn )
  {
    $ClassName = self::getClassName( $Fqcn );
    $this->addRoute( $ClassName, $Fqcn );
  }

  function addRoute( $Route, $Fqcn )
  {
    $this->RouteFqcnMap[ $Route ] = $Fqcn;
    if ( ! $this->DefaultRoute )
      $this->DefaultRoute           = $Route;
  }

  function setQueryKey( $QueryKey )
  {
    $this->QueryKey = $QueryKey;
  }

  public function getLink( $Route, $args = array () )
  {

    $link = "?" . $this->QueryKey . "=" . $Route;

    if ( is_array( $args ) )
    {
      foreach ( $args as $key => $value )
        $link .= "&" . $key . "=" . (urlencode( $value ));
    }
    return $link;
  }

  public function redirect( $Route, $args = array () )
  {
    $Link = $this->getLink( $Route, $args );
    $this->redirectToUrl( $Link );
  }

  public function getCurrentRoute()
  {
    static $CurrentRoute = null;
    if ( ! $CurrentRoute )
      $CurrentRoute        = $this->Inputs ? $this->Inputs->get( $this->QueryKey, $this->DefaultRoute ) : $this->DefaultRoute;
    return $CurrentRoute;
  }

  function setDefaultRoute( $DefaultRoute )
  {
    $this->DefaultRoute = $DefaultRoute;
  }

  public function getCurrentController()
  {
    $CurrentRoute = $this->getCurrentRoute();
    return isset( $this->RouteFqcnMap[ $CurrentRoute ] ) ? new $this->RouteFqcnMap[ $CurrentRoute ]() : null;
  }

  public function redirectToUrl( $Url )
  {
    header( "Location: " . $Url );
  }

  /**
   *
   * @var \Qck\Interfaces\Inputs
   */
  protected $Inputs;

  /**
   *
   * @var array
   */
  protected $RouteFqcnMap = [];

  /**
   *
   * @var string
   */
  protected $QueryKey = "q";

  /**
   *
   * @var string
   */
  protected $DefaultRoute;

}
