<?php

namespace qck\Data;

/**
 *
 * @author muellerm
 */
class ObjectSetSchema extends ObjectSchema implements Interfaces\ObjectSetSchema
{

  public function __construct()
  {
    parent::__construct( ObjectSet::class, "a5c90b18-5bf5-4281-a7c4-1fe253a53c80", [ new StringProperty( ObjectSet::OBJECTS_FQCN_KEY, "cc6fce3a-96e8-4751-ab45-c514d43a283f", 0, 1024 ) ] );
  }

  public function getItemsSqlTableName()
  {
    return parent::getSqlTableName() . "_Objects";
  }

  public function getObjectUuidPropertyName()
  {
    return "ObjectUuid";
  }

  public function applyTo( \qck\Sql\Interfaces\DbSchema $ObjectDbSchema )
  {
    parent::applyTo( $ObjectDbSchema );

    $Table = new \qck\Sql\Table( $this->getItemsSqlTableName() );
    $UuidCol = new \qck\Sql\StringColumn( UuidProperty::NAME, 36, 36 );
    $Table->addColumn( $UuidCol );
    $ObjectUuidCol = new \qck\Sql\StringColumn( $this->getObjectUuidPropertyName(), 36, 36 );
    $Table->addColumn( $ObjectUuidCol );
    $Table->addIndex( UuidProperty::NAME );
    $Table->addIndex( $this->getObjectUuidPropertyName() );

    $ObjectDbSchema->createTable( $Table );
  }
}
