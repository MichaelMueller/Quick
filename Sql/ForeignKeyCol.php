<?php

namespace Qck\Sql;

/**
 *
 * @author muellerm
 */
class ForeignKeyCol extends IntColumn implements \Qck\Interfaces\Sql\ForeignKeyColumn
{

  public function __construct( \Qck\Interfaces\Sql\StandardTable $RefTable )
  {
    parent::__construct( null, 1, PHP_INT_MAX );
    $this->RefTable = $RefTable;
  }

  function toSql( \Qck\Interfaces\Sql\DbDialect $SqlDbDialect, array &$Params = [] )
  {
    return parent::toSql( $SqlDbDialect );
  }

  function getRefTable()
  {
    return $this->RefTable;
  }

  function getName()
  {
    if ( is_null( $this->name ) )
      $name = $this->RefTable->getName() . $this->RefTable->getPrimaryKeyColumnName();
    return $name;
  }

  /**
   *
   * @var \Qck\Interfaces\Sql\Table 
   */
  protected $RefTable;

}
