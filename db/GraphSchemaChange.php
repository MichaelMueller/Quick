<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
abstract class GraphSchemaChange
{

  abstract function applyTo( Sql\DatabaseSchemaInterface $DatabaseSchemaInterface );

  function __construct( SchemaElement $SchemaElement )
  {
    $this->SchemaElement = $SchemaElement;
  }

  /**
   * @var SchemaElement 
   */
  protected $SchemaElement;

}
