<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
class MetaObjectSqlMapper implements SqlMapper
{

  function __construct( MetaObject $MetaObject )
  {
    $this->MetaObject = $MetaObject;
  }

  public function handleAdd( Sql\DatabaseSchemaInterface $SchemaInterface )
  {
    $SchemaInterface->createTable( $this->MetaObject->getName() );
  }

  public function handleChange( Sql\DatabaseSchemaInterface $SchemaInterface,
                                SchemaElement $PrevSchemaElement )
  {
    // TODO?
  }

  public function handleDrop( Sql\DatabaseSchemaInterface $SchemaInterface )
  {
    $SchemaInterface->dropTable( $this->MetaObject->getName() );
  }

  public function handleRename( Sql\DatabaseSchemaInterface $SchemaInterface,
                                SchemaElement $PrevSchemaElement )
  {
    $SchemaInterface->renameTable( $this->MetaObject->getName(), $PrevSchemaElement->getName() );
  }

  /**
   *
   * @var MetaObject 
   */
  protected $MetaObject;

}
