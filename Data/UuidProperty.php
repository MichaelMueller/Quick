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
    parent::__construct( "Uuid", "7e08b1ca-1fe6-4519-ad39-0501d6d267db", 36, 36 );
    $this->setUnique();
  }

  public function toSqlColumn()
  {
    return new \qck\Sql\PrimaryKeyUuidCol( $this->getName() );
  }
}
