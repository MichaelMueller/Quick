<?php

namespace Qck\Core;

/**
 * The Router gets the currently selected controller of the application
 *
 * @author muellerm
 */
class Router implements \Qck\Interfaces\Router
{

  function __construct( $ControllerNamespace, \Qck\Interfaces\Inputs $Inputs,
                        $QueryKey = "q", $StartControllerClassName = "Start" )
  {
    $this->ControllerNamespace = $ControllerNamespace;
    $this->Inputs = $Inputs;
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
    $Query = $this->Inputs->get( $this->QueryKey, $this->StartControllerClassName );

    // validate class name
    if ( preg_match( "/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/", $Query ) )
      $this->CurrentControllerClassName = $Query;

    return $this->CurrentControllerClassName;
  }

  public function getLink( $ControllerFqcn, $args = array () )
  {
    $Link = "?" . $this->QueryKey . "=" . str_replace( $this->ControllerNamespace . "\\\\", "", $ControllerFqcn );

    if ( is_array( $args ) )
    {
      foreach ( $args as $key => $value )
        $Link .= "&" . $key . "=" . (urlencode( $value ));
    }
    return $Link;
  }

  /**
   *
   * @var string
   */
  protected $ControllerNamespace;

  /**
   *
   * @var \Qck\Interfaces\Inputs
   */
  protected $Inputs;

  /**
   *
   * @var string
   */
  protected $QueryKey = "q";

  /**
   *
   * @var string
   */
  protected $StartControllerClassName = "Start";

  /**
   * state var
   * @var \Qck\Interfaces\Controller
   */
  protected $Controller;

  /**
   * state var
   * @var string
   */
  protected $CurrentControllerClassName;

}
