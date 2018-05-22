<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
class SchemaElementRenamed extends GraphSchemaChange
{

  public function __construct( SchemaElement $SchemaElement,
                               SchemaElement $PrevSchemaElement )
  {
    parent::__construct( $SchemaElement );
    $this->PrevSchemaElement = $PrevSchemaElement;
  }

  public function applyTo( Sql\DatabaseSchemaInterface $DatabaseSchemaInterface )
  {
    $this->SchemaElement->getSqlMapper()->handleRename( $DatabaseSchemaInterface, $this->PrevSchemaElement );
  }
  
  /**
   *
   * @var SchemaElement 
   */
  protected $PrevSchemaElement;

}
