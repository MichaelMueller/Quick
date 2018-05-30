<?php

namespace qck\Data;

/**
 *
 * @author muellerm
 */
class UuidProperty extends StringProperty
{

  public function __construct()
  {
    parent::__construct( "Uuid", 36, 36 );
    $this->setUnique();
  }

  public function toSqlColumn()
  {
    return new \qck\Sql\PrimaryKeyUuidCol( $this->getName() );
  }
}
