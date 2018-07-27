<?php

namespace qck\Data;

/**
 *
 * @author muellerm
 */
class BoolProperty extends Abstracts\Property
{
  public function __construct( $Name, $Uuid )
  {
    parent::__construct( $Name, $Uuid );
  }

  public function prepare( $Value )
  {
    return boolval( $Value );
  }

  public function recover( $Value, Interfaces\Db $ObjectDb )
  {
    return boolval( $Value );
  }

  public function toSqlColumn()
  {
    return new \qck\Sql\BoolColumn( $this->getName() );
  }
}
