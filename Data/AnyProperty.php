<?php

namespace qck\Data;

/**
 *
 * @author muellerm
 */
class AnyProperty extends Abstracts\Property
{
  public function __construct( $Name, $Uuid )
  {
    parent::__construct( $Name, $Uuid );
  }

  public function prepare( $Value )
  {
    return serialize( $Value );
  }

  public function recover( $Value, Interfaces\ObjectDb $ObjectDb )
  {
    return unserialize( $Value );
  }

  public function toSqlColumn()
  {
    return new \qck\Sql\StringColumn( $this->getName(), 0, \qck\Sql\StringColumn::MEDIUMTEXT );
  }
  

}
