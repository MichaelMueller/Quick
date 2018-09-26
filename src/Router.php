<?php

namespace Qck;

/**
 * The Router gets the currently selected controller of the application
 *
 * @author muellerm
 */
class Router implements \Qck\Interfaces\Router
{

  const DEFAULT_QUERY = "Start";
  const DEFAULT_QUERY_KEY = "q";

  function __construct( \Qck\Interfaces\ServiceRepo $ServiceRepo )
  {
    $this->ServiceRepo = $ServiceRepo;
    $this->DefaultQuery = self::DEFAULT_QUERY;
    $this->QueryKey = self::DEFAULT_QUERY_KEY;
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
      $Request = $this->ServiceRepo->get( Interfaces\Request::class );
      $className = $Request->get( $this->QueryKey, $this->DefaultQuery );
      $controllerFqcn = isset( $this->ControllerFqcns[ $className ] ) ? $this->ControllerFqcns[ $className ] : null;
    }
    return $controllerFqcn;
  }

  public function getLink( $controllerFqcn, $args = array () )
  {
    $query = array_search( $controllerFqcn, $this->ControllerFqcns );

    $link = "?" . $this->QueryKey . "=" . $query;

    if ( is_array( $args ) )
    {
      foreach ( $args as $key => $value )
        $link .= "&" . $key . "=" . (urlencode( $value ));
    }
    return $link;
  }

  /**
   *
   * @var \Qck\Interfaces\ServiceRepo
   */
  protected $ServiceRepo;

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
