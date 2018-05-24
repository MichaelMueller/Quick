<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
class MetaObject
{

  function __construct( $Uuid, $Fqcn )
  {
    $this->Uuid = $Uuid;
    $this->Fqcn = $Fqcn;
    $this->IdProperty = new IdProperty();
    $this->addProperty( new IdProperty() );
  }

  function getUuid()
  {
    return $this->Uuid;
  }

  function addProperty( Property $Property )
  {
    $this->Properties[] = $Property;
  }

  function getProperties()
  {
    return $this->Properties;
  }

  /**
   * @return Sql\Table
   */
  function addToSqlDatabase( Sql\DatabaseSchemaInterface $SchemaInterface )
  {
    $Table = new Sql\Table( $this->getSqlTableName() );
    /* @var $Property Property */
    foreach ( $this->Properties as $Property )
      $Table->addColumn( $Property->toSqlColumn( $SchemaInterface->getDatabaseDictionary() ) );
    $Table->setPrimaryKeyColumn( $this->IdProperty->getName() );
    $Table->setUniqueColumns( $this->UniqueProperties );
    $SchemaInterface->createTable( $Table );
  } 
  
  function removeFromSqlDatabase( Sql\DatabaseSchemaInterface $SchemaInterface )
  {
    $SchemaInterface->dropTable( $this->getSqlTableName() );
  }

  function getFqcn()
  {
    return $this->Fqcn;
  }

  protected function getSqlTableName()
  {
    return str_replace( "\\", "_", $this->Fqcn );
  }

  protected $Uuid;
  protected $Fqcn;
  protected $IdProperty;
  protected $Properties = [];
  protected $UniqueProperties = [];

}
