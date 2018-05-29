<?php

namespace qck\Sql;

/**
 *
 * @author muellerm
 */
class Table implements Interfaces\Table
{

  function __construct( $Name, array $Columns, array $UniqueIndexes = [] )
  {
    $this->Name = $Name;
    $this->Columns = $Columns;
    $this->UniqueIndexes = $UniqueIndexes;
  }

  public function getColumnNames()
  {
    $ColNames = [];
    foreach ( $this->Columns as $Column )
      $ColNames[] = $Column->getName();
    return $ColNames;
  }

  public function getColumnSql( Interfaces\DbDictionary $DbDictionary )
  {
    $ColumnSqlParts = [];
    /* @var $Column Interfaces\Column */
    foreach ( $this->Columns as $Column )
      $ColumnSqlParts[] = $Column->toSql( $DbDictionary );
    return implode( ", ", $ColumnSqlParts );
  }

  public function getName()
  {
    return $this->Name;
  }

  public function getUniqueIndexes()
  {
    return $this->UniqueIndexes;
  }

  /**
   *
   * @var string 
   */
  protected $Name;

  /**
   *
   * @var array 
   */
  protected $Columns;

  /**
   *
   * @var array 
   */
  protected $UniqueIndexes;

}
