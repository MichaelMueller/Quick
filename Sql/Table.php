<?php

namespace Qck\Sql;

/**
 *
 * @author muellerm
 */
class Table implements \Qck\Interfaces\Sql\Table
{

  function __construct( $Name, PrimaryKeyCol $PrimaryKeyColumn )
  {
    $this->Name = $Name;
    $this->addColumn( $PrimaryKeyColumn );
  }

  function addColumn( \Qck\Interfaces\Sql\Column $Column )
  {
    $this->Columns[] = $Column;
  }

  function addUniqueIndex( $ColumnName )
  {
    $this->UniqueIndexes[] = $ColumnName;
  }

  function addIndex( $ColumnName )
  {
    $this->Indexes[] = $ColumnName;
  }

  public function getColumnNames( $SkipPrimaryKeyCol = true )
  {
    $ColNames = [];
    for ( $i = $SkipPrimaryKeyCol ? 1 : 0; $i < count( $this->Columns ); $i++ )
      $ColNames[] = $this->Columns[ $i ]->getName();
    return $ColNames;
  }

  public function getColumnNamesAsString( $SkipPrimaryKeyCol = true )
  {
    return implode( ", ", $this->getColumnNames( $SkipPrimaryKeyCol ) );
  }

  public function getColumnSql( \Qck\Interfaces\Sql\DbDialect $SqlDbDialect )
  {
    $ColumnSqlParts = [];
    /* @var $Column Interfaces\Column */
    foreach ( $this->Columns as $Column )
      $ColumnSqlParts[] = $Column->toSql( $SqlDbDialect );
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

  function getIndexes()
  {
    return $this->Indexes;
  }

  public function getPrimaryKeyColumn()
  {
    return $this->Columns[ 0 ];
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
  protected $Columns = [];

  /**
   *
   * @var array 
   */
  protected $UniqueIndexes = [];

  /**
   *
   * @var array 
   */
  protected $Indexes = [];

}
