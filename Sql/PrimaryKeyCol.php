<?php

namespace qck\Sql;

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

  function toSql( Interfaces\DbDictionary $DbDictionary )
  {
    return parent::toSql( $DbDictionary ) . " " . $DbDictionary->getPrimaryKeyAttribute() . ($this->AutoIncrement ? " " . $DbDictionary->getAutoincrementAttribute() : "");
  }

  protected $AutoIncrement;

}
