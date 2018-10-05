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

  function __construct( \Qck\Interfaces\Request $Request )
  {
    $this->Request = $Request;

    $this->DefaultQuery = \Qck\Interfaces\Router::DEFAULT_ROUTE;
    $this->QueryKey = self::DEFAULT_QUERY_KEY;
  }
  function addRoute( $Route, $ControllerFqcn )
  {
    $this->ControllerFqcns[ $Route ] = $ControllerFqcn;
  }
  function addProtectedRoute( $Route )
  {
    $this->ProtectedRoutes[ $Route ] = $Route;
  }
  
  
  function addController( $Query, $ControllerFqcn )
  {
    $this->ControllerFqcns[ $Query ] = $ControllerFqcn;
  }

  function setQueryKey( $QueryKey )
  {
    $this->QueryKey = $QueryKey;
  }

  function getDefaultQuery()
  {
    return $this->DefaultQuery;
  }

  function setDefaultQuery( $DefaultQuery )
  {
    $this->DefaultQuery = $DefaultQuery;
  }

  public function getController()
  {
    static $controller = null;
    if ( is_null( $controller ) )
    {
      $fqcn = $this->getCurrentControllerFqcn();
      if ( $fqcn )
        $controller = new $fqcn;
    }
    return $controller;
  }

  public function getCurrentControllerFqcn()
  {
    static $controllerFqcn = null;
    if ( is_null( $controllerFqcn ) )
    {
      /* @var $Request Interfaces\Request */
      $Request = $this->Request;
      $className = $Request->get( $this->QueryKey, $this->DefaultQuery );
      $controllerFqcn = isset( $this->ControllerFqcns[ $className ] ) ? $this->ControllerFqcns[ $className ] : null;
    }
    return $controllerFqcn;
  }

  public function getLink( $ControllerFqcn, $args = array () )
  {
    $query = $this->getRoute( $ControllerFqcn );

    $link = "?" . $this->QueryKey . "=" . $query;

    if ( is_array( $args ) )
    {
      foreach ( $args as $key => $value )
        $link .= "&" . $key . "=" . (urlencode( $value ));
    }
    return $link;
  }

  public function redirect( $ControllerFqcn, $args = array () )
  {
    $Link = $this->getLink( $ControllerFqcn, $args );
    header( "Location: " . $Link );
  }

  public function getCurrentRoute()
  {
    static $CurrentRoute = null;
    if ( !$CurrentRoute )
      $CurrentRoute = $this->Request->get( $this->QueryKey, $this->DefaultQuery );
    return $CurrentRoute;
  }

  public function setDefaultRoute( $DefaultRoute = self::DEFAULT_ROUTE )
  {
    $this->DefaultQuery = $DefaultRoute;
  }

  public function setRouteKey( $RouteKey )
  {
    $this->QueryKey = $RouteKey;
  }

  public function getFqcn( $Route )
  {
    return isset( $this->Routes[ $Route ] ) ? $this->Routes[ $Route ] : null;
  }

  public function isProtected( $Route )
  {
    return in_array( $Route, $this->ProtectedRoutes );
  }

  public function getRoute( $Fqcn )
  {
    $key = array_search( $Fqcn, $this->Routes );
    return $key !== false ? $key : null;
  }

  public function getDefaultRoute()
  {
    return $this->DefaultQuery;
  }

  /**
   *
   * @var \Qck\Interfaces\Request
   */
  protected $Request;

  /**
   *
   * @var array 
   */
  protected $Routes=[];

  /**
   *
   * @var array
   */
  protected $ProtectedRoutes=[];

  /**
   *
   * @var array 
   */
  protected $ControllerFqcns;

  /**
   *
   * @var string
   */
  protected $QueryKey = "q";

  /**
   *
   * @var string
   */
  protected $DefaultQuery = "Start";

}
