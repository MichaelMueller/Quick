<?php

namespace qck\GraphStorage\Sql;

/**
 *
 * @author muellerm
 */
class Table
{

  public function __construct( $Name )
  {
    $this->Name = $Name;
  }

  function addColumn( Column $Col )
  {
    $this->Columns[] = $Col;
  }

  function getColumns()
  {
    return $this->Columns;
  }

  function setUniqueColumns( $UniqueColumns )
  {
    $this->UniqueColumns = $UniqueColumns;
  }

  function getUniqueColumns()
  {
    return $this->UniqueColumns;
  }

  function getPrimaryKeyColumn()
  {
    return $this->PrimaryKeyColumn;
  }

  function addUniqueColumn( $ColName )
  {
    $this->UniqueColumns[] = $ColName;
  }

  function setPrimaryKeyColumn( $ColName )
  {
    $this->PrimaryKeyColumn = $ColName;
  }

  protected $Name;
  protected $Columns = [];
  protected $UniqueColumns = [];
  protected $PrimaryKeyColumn = [];

}
