<?php

namespace qck\Sql;

/**
 *
 * @author muellerm
 */
class PrimaryKeyUuidCol extends StringColumn
{

  public function __construct( $Name )
  {
    parent::__construct( $Name, 36, 36, false );
  }

  function toSql( Interfaces\DbDictionary $DbDictionary )
  {
    return parent::toSql( $DbDictionary ) . " " . $DbDictionary->getPrimaryKeyAttribute();
  }
}
