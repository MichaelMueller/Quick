<?php

namespace qck\core;

/**
 * The ControllerFactory gets the currently selected controller of the application
 *
 * @author muellerm
 */
class ControllerFactory implements \qck\core\interfaces\ControllerFactory
{

  public function __construct( $controllerNamespace, $Argv = null )
  {
    $this->controllerNamespace = $controllerNamespace;
    $this->Argv = $Argv;
  }

  function setQueryKey( $queryKey )
  {
    $this->queryKey = $queryKey;
  }

  function setStartControllerClassName( $startControllerClassName )
  {
    $this->startControllerClassName = $startControllerClassName;
  }

  /**
   * @return \qck\core\interfaces\Controller or null
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
      if ( $subController instanceof \qck\core\interfaces\Controller )
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
      $query = $this->startControllerClassName;
      if ( $this->Argv )
      {
        // get first positional argument
        for ( $i = 1; $i < count( $this->Argv ); $i++ )
        {
          if ( isset( $this->Argv[ $i ][ 0 ] ) && $this->Argv[ $i ][ 0 ] == "-" )
            $i = $i + 1;
          else
          {
            $query = $this->Argv[ $i ];
            break;
          }
        }
      }
      else
        $query = isset( $_GET[ $queryKey ] ) ? $_GET[ $queryKey ] : $query;

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

  function getStartControllerClassName()
  {
    return $this->startControllerClassName;
  }

  public function usesCli()
  {
    return $this->Argv !== null;
  }

  public function getArgv()
  {
    return $this->Argv;
  }

  protected $controllerNamespace;
  protected $queryKey = "q";
  protected $startControllerClassName = "Start";
  protected $Argv;

}
