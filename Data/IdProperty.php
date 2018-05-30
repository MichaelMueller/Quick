<?php

namespace qck\Data;

/**
 *
 * @author muellerm
 */
class IdProperty extends IntProperty
{

  public function __construct()
  {
    parent::__construct( "Id" );
    $this->setUnique();
  }

  public function toSqlColumn()
  {
    return new \qck\Sql\PrimaryKeyCol( $this->getName() );
  }
}
