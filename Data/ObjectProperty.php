<?php

namespace qck\Data;

/**
 *
 * @author muellerm
 */
class ObjectProperty extends StringProperty
{

  public function __construct( $Name, $Fqcn )
  {
    parent::__construct( $Name, 36, 36 );
    $this->Fqcn = $Fqcn;
  }

  public function prepare( $Value )
  {
    $Uuid = $Value->getUuid();
    return $Uuid;
  }

  public function recover( $Value, Interfaces\ObjectDb $ObjectDb )
  {
    if ( $Value !== null )
      return new LazyLoader( $this->Fqcn, $Value, $ObjectDb );
    return null;
  }

  protected $Fqcn;

}
