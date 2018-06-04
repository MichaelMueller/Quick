<?php

namespace qck\Data;

/**
 *
 * @author muellerm
 */
class FloatProperty extends Abstracts\Property
{
  public function __construct( $Name, $Uuid )
  {
    parent::__construct( $Name, $Uuid );
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
