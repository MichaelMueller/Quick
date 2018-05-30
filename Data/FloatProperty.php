<?php

namespace qck\Data;

/**
 *
 * @author muellerm
 */
class FloatProperty extends Abstracts\Property
{

  public function __construct( $Name )
  {
    parent::__construct( $Name );
  }

  public function prepare( $Value )
  {
    return floatval( $Value );
  }

  public function recover( $Value, Interfaces\ObjectDb $ObjectDb )
  {
    return floatval( $Value );
  }

  public function toSqlColumn()
  {
    return new \qck\Sql\FloatColumn( $this->getName() );
  }
}
