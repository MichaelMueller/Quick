<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
class SchemaElementAdded extends GraphSchemaChange
{

  public function __construct( SchemaElement $SchemaElement )
  {
    parent::__construct( $SchemaElement );
  }

  public function applyTo( Sql\DatabaseSchemaInterface $DatabaseSchemaInterface )
  {
    $this->SchemaElement->getSqlMapper()->handleAdd( $DatabaseSchemaInterface );
  }
}
