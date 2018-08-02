<?php

namespace Qck\Core;

/**
 * The Router gets the currently selected controller of the application
 *
 * @author muellerm
 */
class Router implements \Qck\Interfaces\Router
{

  function __construct( $ControllerNamespace, $Argv = null, $QueryKey = "q",
                        $StartControllerClassName = "Start" )
  {
    $this->ControllerNamespace = $ControllerNamespace;
    $this->Argv = $Argv;
    $this->QueryKey = $QueryKey;
    $this->StartControllerClassName = $StartControllerClassName;
  }

  function getControllerNamespace()
  {
    return $this->ControllerNamespace;
  }

  /**
   * @return Qck\Interfaces\Controller or null
   */
  public function getController()
  {
    if ( $this->Controller )
      return $this->Controller;

    // find requested query ( = the controller class to be instatiated and called )
    $CurrentControllerClassName = $this->getCurrentControllerClassName();

    // validate class name
    if ( $CurrentControllerClassName )
    {
      $Fqcn = $this->ControllerNamespace . "\\" . $CurrentControllerClassName;
      $Controller = null;
      if ( class_exists( $Fqcn, true ) )
        $Controller = new $Fqcn();
      if ( $Controller instanceof \Qck\Interfaces\Controller )
        $this->Controller = $Controller;
    }
    return $this->Controller;
  }

  public function getCurrentControllerClassName()
  {
    if ( $this->CurrentControllerClassName )
      return $this->CurrentControllerClassName;

    // find requested query ( = the controller class to be instatiated and called )
    $QueryKey = $this->QueryKey;
    $Query = $this->StartControllerClassName;
    if ( isset( $_GET[ $QueryKey ] ) )
      $Query = $_GET[ $QueryKey ];
    else if ( !isset( $_GET ) && $this->Argv )
    {
      // get first positional argument
      for ( $i = 1; $i < count( $this->Argv ); $i++ )
      {
        if ( isset( $this->Argv[ $i ][ 0 ] ) && $this->Argv[ $i ][ 0 ] == "-" )
          $i = $i + 1;
        else
        {
          $Query = $this->Argv[ $i ];
          break;
        }
      }
    }

    // validate class name
    if ( preg_match( "/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/", $Query ) )
      $this->CurrentControllerClassName = $Query;

    return $this->CurrentControllerClassName;
  }

  public function getLink( $ControllerClassName, $args = array () )
  {
    $Link = "?" . $this->QueryKey . "=" . $ControllerClassName;

    if ( is_array( $args ) )
    {
      foreach ( $args as $key => $value )
        $Link .= "&" . $key . "=" . (urlencode( $value ));
    }
    return $Link;
  }

  protected $ControllerNamespace;
  protected $Argv;
  protected $QueryKey = "q";
  protected $StartControllerClassName = "Start";
  protected $Controller;
  protected $CurrentControllerClassName;

}
