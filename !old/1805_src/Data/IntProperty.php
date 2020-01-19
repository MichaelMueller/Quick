<?php

namespace qck\Data;

/**
 *
 * @author muellerm
 */
class IntProperty extends Abstracts\Property
{
  public function __construct( $Name, $Uuid )
  {
    parent::__construct( $Name, $Uuid );
  }

  public function prepare( $Value )
  {
    return intval( $Value );
  }

  public function recover( $Value, Interfaces\Db $ObjectDb )
  {
    return intval( $Value );
  }

  public function toSqlColumn()
  {
    return new \qck\Sql\IntColumn( $this->getName() );
  }
}
