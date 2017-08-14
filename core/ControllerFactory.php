<?php

namespace qck\core;

/**
 * The ControllerFactory gets the currently selected controller of the application
 *
 * @author muellerm
 */
class ControllerFactory implements \qck\interfaces\ControllerFactory
{

  public function __construct( $controllerNamespace )
  {
    $this->controllerNamespace = $controllerNamespace;
  }

  function setQueryKey( $queryKey )
  {
    $this->queryKey = $queryKey;
  }

  function setStartControllerQueryId( $startControllerQueryId )
  {
    $this->startControllerQueryId = $startControllerQueryId;
  }

  /**
   * @return \qck\interfaces\Controller or null
   */
  public function getController()
  {
    // find requested query ( = the controller class to be instatiated and called )
    $CurrentControllerClassName = $this->getCurrentControllerClassName();

    // validate class name
    if ( $CurrentControllerClassName )
    {
      $fqClassName = $this->controllerNamespace . "\\" . $CurrentControllerClassName;
      $subController = null;
      if ( class_exists( $fqClassName, true ) )
        $subController = new $fqClassName();
      if ( $subController instanceof \qck\interfaces\Controller )
        return $subController;
    }
    return null;
  }
  
  public function getCurrentControllerClassName()
  {
    static $CurrentControllerClassName = null;
    if ( !$CurrentControllerClassName )
    {
      // find requested query ( = the controller class to be instatiated and called )
      $queryKey = $this->queryKey;
      $query = isset( $_GET[ $queryKey ] ) ? $_GET[ $queryKey ] : $this->startControllerQueryId;

      // validate class name
      if ( preg_match( "/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/", $query ) )
      {
        $CurrentControllerClassName = $query;
      }
    }
    return $CurrentControllerClassName;
  }

  public function getQueryKey()
  {
    return $this->queryKey;
  }

  function getStartControllerQueryId()
  {
    return $this->startControllerQueryId;
  }

  protected $controllerNamespace;
  protected $queryKey = "q";
  protected $startControllerQueryId = "Start";

}
