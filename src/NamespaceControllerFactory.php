<?php

namespace Qck;

/**
 * Description of AppConfigFactory
 *
 * @author micha
 */
class NamespaceControllerFactory implements Interfaces\ControllerFactory
{

  function __construct($DefaultControllerClassName)
  {
    $this->DefaultControllerClassName = $DefaultControllerClassName;
  }

  function addControllerNamespace($ControllerNamespace)
  {
    $this->ControllerNamespaces[] = $ControllerNamespace;
  }

  public function create($Route)
  {
    $CurrentRoute = $Route ? $Route : $this->DefaultControllerClassName;
    $CurrentRoute = $this->MakeFirstCharacterUpperCase ? ucfirst($CurrentRoute) : $CurrentRoute;
    foreach ($this->ControllerNamespaces as $ControllerNamespace)
    {
      $Fqcn = $ControllerNamespace . "\\" . $CurrentRoute;
      if (class_exists($Fqcn, true))
        return new $Fqcn;
    }
    return null;
  }

  function setMakeFirstCharacterUpperCase($MakeFirstCharacterUpperCase)
  {
    $this->MakeFirstCharacterUpperCase = $MakeFirstCharacterUpperCase;
  }

  protected $MakeFirstCharacterUpperCase = false;
  protected $ControllerNamespaces        = [];
  protected $DefaultControllerClassName;

}
