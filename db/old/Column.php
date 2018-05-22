<?php

namespace qck\db;

/**
 * Description of TableDefinition
 *
 * @author muellerm
 */
abstract class Column extends SchemaElement
{
  abstract function toSql( SqlDialect $SqlDialect );
  
  public function __construct( $Uid, $Name )
  {
    parent::__construct( $Uid );
    $this->Name = $Name;
  }
 
  protected $Name;

}
