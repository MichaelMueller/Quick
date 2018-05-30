<?php

namespace qck\Data;

/**
 *
 * @author muellerm
 */
class ObjectProperty extends IntProperty
{

  public function __construct( $Name, $Fqcn )
  {
    parent::__construct( $Name );
    $this->Fqcn = $Fqcn;
  }

  public function prepare( $Value )
  {
    return $Value instanceof Interfaces\IdProvider ? $Value->getId() : null;
  }

  public function recover( $Value, Interfaces\ObjectDb $ObjectDb )
  {
    if ( $Value !== null )
      return new LazyLoader( $this->Fqcn, $Value, $ObjectDb );
    return null;
  }

  protected $Fqcn;

}
