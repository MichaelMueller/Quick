<?php

namespace Qck\Sql;

/**
 *
 * @author muellerm
 */
class PrimaryKeyCol extends IntColumn
{

  public function __construct( $Name, $AutoIncrement = true )
  {
    parent::__construct( $Name );
    $this->AutoIncrement = $AutoIncrement;
  }

  function toSql( \Qck\Interfaces\Sql\DbDialect $SqlDbDialect )
  {
    return parent::toSql( $SqlDbDialect ) . " " . $SqlDbDialect->getPrimaryKeyAttribute() . ($this->AutoIncrement ? " " . $SqlDbDialect->getAutoincrementAttribute() : "");
  }

  protected $AutoIncrement;

}
