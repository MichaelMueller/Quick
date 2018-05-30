<?php

namespace qck\Data;

/**
 *
 * @author muellerm
 */
class UuidProperty extends IntProperty
{

  public function __construct()
  {
    parent::__construct( "Uuid" );
    $this->setUnique();
  }

  public function toSqlColumn()
  {
    return new \qck\Sql\PrimaryKeyUuidCol( $this->getName() );
  }
}
