<?php

namespace qck\Data;

/**
 *
 * @author muellerm
 */
class StringProperty extends Abstracts\Property
{

  public function __construct( $Name, $Uuid, $MinLength = 0, $MaxLength = 255 )
  {
    parent::__construct( $Name, $Uuid );
    $this->MinLength = $MinLength;
    $this->MaxLength = $MaxLength;
  }

  public function prepare( $Value )
  {
    return strval( $Value );
  }

  public function recover( $Value, Interfaces\ObjectDb $ObjectDb )
  {
    return strval( $Value );
  }

  public function toSqlColumn()
  {
    return new \qck\Sql\StringColumn( $this->getName(), $this->MinLength, $this->MaxLength );
  }

  protected $MinLength;
  protected $MaxLength;

}
