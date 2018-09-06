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
    parent::__construct( $Name, 1, PHP_INT_MAX );
    $this->AutoIncrement = $AutoIncrement;
  }

  function toSql( \Qck\Interfaces\Sql\DbDialect $SqlDbDialect, array &$Params = [] )
  {
    return parent::toSql( $SqlDbDialect ) . " " . $SqlDbDialect->getPrimaryKeyAttribute() . ($this->AutoIncrement ? " " . $SqlDbDialect->getAutoincrementAttribute() : "");
  }

  protected $AutoIncrement;

}
