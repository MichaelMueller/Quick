<?php

namespace qck\Sql;

/**
 *
 * @author muellerm
 */
class PrimaryKeyCol extends IntColumn
{

  public function __construct( $Name )
  {
    parent::__construct( $Name );
  }

  function toSql( Interfaces\DbDictionary $DbDictionary )
  {
    return parent::toSql( $DbDictionary ) . " " . $DbDictionary->getPrimaryKeyAutoincrementAttribute();
  }
}
