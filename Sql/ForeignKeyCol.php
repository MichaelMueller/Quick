<?php

namespace Qck\Sql;

/**
 *
 * @author muellerm
 */
class ForeignKeyCol extends IntColumn implements \Qck\Interfaces\Sql\ForeignKeyColumn
{

  public function __construct( \Qck\Interfaces\Sql\Table $ReferencedTable )
  {
    parent::__construct( $ReferencedTable->getName() . $ReferencedTable->getPrimaryKeyColumn()->getName() );
    $this->ReferencedTable = $ReferencedTable;
  }

  function toSql( \Qck\Interfaces\Sql\DbDialect $SqlDbDialect )
  {
    return parent::toSql( $SqlDbDialect );
  }

  function getReferencedTable()
  {
    return $this->ReferencedTable;
  }

  /**
   *
   * @var \Qck\Interfaces\Sql\Table 
   */
  protected $ReferencedTable;


}
