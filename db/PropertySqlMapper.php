<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
class PropertySqlMapper implements SqlMapper
{

  function __construct( Property $Property )
  {
    $this->Property = $Property;
  }

  public function handleAdd( Sql\DatabaseSchemaInterface $SchemaInterface )
  {
    $Table = $this->Property->getMetaObject()->getName();
    $ColName = $this->Property->getName();
    $DataType = $this->Property->toSqlDatatype( $SchemaInterface->getDatabaseDictionary() );
    $SchemaInterface->createColumn( $Table, $ColName, $DataType );
  }

  public function handleChange( Sql\DatabaseSchemaInterface $SchemaInterface,
                                SchemaElement $PrevSchemaElement )
  {
    $Table = $this->Property->getMetaObject()->getName();
    $ColName = $this->Property->getName();
    $DataType = $this->Property->toSqlDatatype( $SchemaInterface->getDatabaseDictionary() );
    $SchemaInterface->modifyColumn( $Table, $ColName, $DataType );
  }

  public function handleDrop( Sql\DatabaseSchemaInterface $SchemaInterface )
  {
    $Table = $this->Property->getMetaObject()->getName();
    $ColName = $this->Property->getName();
    $SchemaInterface->dropColumn( $Table, $ColName );
  }

  public function handleRename( Sql\DatabaseSchemaInterface $SchemaInterface,
                                SchemaElement $PrevSchemaElement )
  {
    $Table = $this->Property->getMetaObject()->getName();
    $ColName = $this->Property->getName();
    $SchemaInterface->dropColumn( $Table, $PrevSchemaElement->getName(), $ColName );
  }

  /**
   *
   * @var Property 
   */
  protected $Property;

}
