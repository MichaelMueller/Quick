<?php

namespace qck\Sql;

/**
 *
 * @author muellerm
 */
class Table implements Interfaces\Table
{

  function __construct( $Name, array $Columns = [], array $UniqueIndexes = [],
                        array $Indexes = [] )
  {
    $this->Name = $Name;
    $this->Columns = $Columns;
    $this->UniqueIndexes = $UniqueIndexes;
    $this->Indexes = $Indexes;
  }

  function addColumn( Interfaces\Column $Column )
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
    foreach ( $this->Columns as $Column )
      if ( !$Column instanceof PrimaryKeyCol || $SkipPrimaryKeyCol == false )
        $ColNames[] = $Column->getName();
    return $ColNames;
  }

  public function getColumnNamesAsString( $SkipPrimaryKeyCol = true )
  {
    return implode( ", ", $this->getColumnNames( $SkipPrimaryKeyCol ) );
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

  function getIndexes()
  {
    return $this->Indexes;
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

  /**
   *
   * @var array 
   */
  protected $Indexes;

}
