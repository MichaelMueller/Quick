<?php

namespace qck\Data;

/**
 *
 * @author muellerm
 */
class ObjectProperty extends StringProperty
{

  public function __construct( $Name, $Uuid, $Fqcn )
  {
    parent::__construct( $Name, $Uuid, 36, 36 );
    $this->Fqcn = $Fqcn;
  }

  public function prepare( $Value )
  {
    $Uuid = $Value->getUuid();
    return $Uuid;
  }

  public function recover( $Value, Interfaces\Db $ObjectDb )
  {
    if ( $Value !== null )
      return new LazyLoader( $this->Fqcn, $Value, $ObjectDb );
    return null;
  }

  protected $Fqcn;

}