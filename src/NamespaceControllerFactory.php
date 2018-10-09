<?php

namespace Qck;

/**
 * Description of AppConfigFactory
 *
 * @author micha
 */
class NamespaceControllerFactory implements Interfaces\ControllerFactory
{

  function addControllerNamespace( $ControllerNamespace )
  {
    $this->ControllerNamespaces[] = $ControllerNamespace;
  }

  public function create( $Route )
  {
    $Route = $this->MakeFirstCharacterUpperCase ? ucfirst( $Route ) : $Route;
    foreach ( $this->ControllerNamespaces as $ControllerNamespace )
    {
      $Fqcn = $ControllerNamespace . "\\" . $Route;
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
  protected $ControllerNamespaces = [];

}
