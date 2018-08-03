<?php

namespace Qck\Sql;

/**
 *
 * @author muellerm
 */
class UuidCol extends StringColumn
{

  public function __construct( $Name )
  {
    parent::__construct( $Name, 36, 36, false );
  }

  function toSql( \Qck\Interfaces\Sql\DbDialect $SqlDbDialect )
  {
    return parent::toSql( $SqlDbDialect );
  }
}
