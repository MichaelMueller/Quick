<?php

namespace qck\Data;

/**
 *
 * @author muellerm
 */
class BoolProperty extends Abstracts\Property
{

  public function __construct( $Name )
  {
    parent::__construct( $Name );
  }

  public function prepare( $Value )
  {
    return boolval( $Value );
  }

  public function recover( $Value, Interfaces\ObjectDb $ObjectDb )
  {
    return boolval( $Value );
  }

  public function toSqlColumn()
  {
    return new \qck\Sql\BoolColumn( $this->getName() );
  }
}
