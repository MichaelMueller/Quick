<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
class SchemaElementChanged extends GraphSchemaChange
{

  public function __construct( SchemaElement $SchemaElement,
                               SchemaElement $PrevSchemaElement )
  {
    parent::__construct( $SchemaElement );
    $this->PrevSchemaElement = $PrevSchemaElement;
  }

  public function applyTo( Sql\DatabaseSchemaInterface $DatabaseSchemaInterface )
  {
    $this->SchemaElement->getSqlMapper()->handleChange( $DatabaseSchemaInterface, $this->PrevSchemaElement );
  }
  
  /**
   *
   * @var SchemaElement 
   */
  protected $PrevSchemaElement;

}
