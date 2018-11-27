<?php

namespace Qck;

/**
 * Description of AppConfigFactory
 *
 * @author micha
 */
class NamespaceControllerFactory implements Interfaces\ControllerFactory
{

  function __construct( array $ControllerNamespaces = [] )
  {
    $this->ControllerNamespaces = $ControllerNamespaces;
  }

  function addControllerNamespace( $ControllerNamespace )
  {
    $this->ControllerNamespaces[] = $ControllerNamespace;
  }

  public function create( $Route )
  {    
    $CurrentRoute = $this->MakeFirstCharacterUpperCase ? ucfirst( $Route ) : $Route;
    foreach ( $this->ControllerNamespaces as $ControllerNamespace )
    {
      $Fqcn = $ControllerNamespace . "\\" . $CurrentRoute;
      if ( class_exists( $Fqcn, true ) )
        return new $Fqcn;
    }
    return null;
  }

  function setMakeFirstCharacterUpperCase( $MakeFirstCharacterUpperCase )
  {
    $this->MakeFirstCharacterUpperCase = $MakeFirstCharacterUpperCase;
  }

  protected $MakeFirstCharacterUpperCase = false;
  protected $ControllerNamespaces        = [];

}
