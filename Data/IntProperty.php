<?php

namespace qck\Data;

/**
 *
 * @author muellerm
 */
class IntProperty extends Abstracts\Property
{

  public function __construct( $Name )
  {
    parent::__construct( $Name );
  }

  public function prepare( $Value )
  {
    return intval( $Value );
  }

  public function recover( $Value, Interfaces\ObjectDb $ObjectDb )
  {
    return intval( $Value );
  }

  public function toSqlColumn()
  {
    return new \qck\Sql\IntColumn( $this->getName() );
  }
}
