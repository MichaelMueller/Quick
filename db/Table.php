<?php

namespace qck\db;

/**
 * Description of TableDefinition
 *
 * @author muellerm
 */
class Table extends SchemaElement implements SqlTransformable
{

  public function __construct( $Uid, $Name, $PrimaryKeyColName )
  {
    parent::__construct( $Uid );
    $this->Name = $Name;
    $this->PrimaryKeyColName = $PrimaryKeyColName;
  }

  function addColumn( Column $Column )
  {
    $this->Columns[] = $Column;
  }

  function getName()
  {
    return $this->Name;
  }

  function getPrimaryKeyColName()
  {
    return $this->PrimaryKeyColName;
  }

  function getColumns()
  {
    return $this->Columns;
  }

  public function toSql( SqlDialect $SqlDialect )
  {
    
  }

  protected $Name;
  protected $PrimaryKeyColName;
  protected $Columns;

}
