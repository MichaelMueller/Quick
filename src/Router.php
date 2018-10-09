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
    $this->QueryKey = self::DEFAULT_QUERY_KEY;
  }

  function addRoute( $Route, $ControllerFqcn )
  {
    $this->ControllerFqcns[ $Route ] = $ControllerFqcn;
  }

  function addProtectedRoute( $Route )
  {
    $this->ProtectedRoutes[] = $Route;
  }

  function setQueryKey( $QueryKey )
  {
    $this->QueryKey = $QueryKey;
  }

  public function getLink( $Route, $args = array () )
  {
    $query = $this->getRoute( $Route );

    $link = "?" . $this->QueryKey . "=" . $query;

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
    header( "Location: " . $Link );
  }

  public function getCurrentRoute()
  {
    static $CurrentRoute = null;
    if ( !$CurrentRoute )
      $CurrentRoute = $this->Request->get( $this->QueryKey, null );
    return $CurrentRoute;
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
  protected $Routes = [];

  /**
   *
   * @var array
   */
  protected $ProtectedRoutes = [];

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
