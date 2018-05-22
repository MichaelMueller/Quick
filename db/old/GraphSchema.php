<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
class GraphSchema
{

  function __construct( $SchemaFile )
  {
    $this->SchemaFile = $SchemaFile;
  }

  function addObjectSchema( $Uuid, ObjectSchema $ObjectSchema )
  {
    $this->ObjectSchemas[ $Uuid ] = $ObjectSchema;
  }

  function getUuids()
  {
    return array_keys( $this->ObjectSchemas );
  }

  /**
   * 
   * @param type $Uuid
   * @return ObjectSchema
   */
  function getObjectSchema( $Uuid )
  {
    return isset( $this->ObjectSchemas[ $Uuid ] ) ? $this->ObjectSchemas[ $Uuid ] : null;
  }

  function installOnSqlDb( SqlDb $SqlDb )
  {
    $PrevSchema = file_exists( $this->SchemaFile ) ? unserialize( file_get_contents( $this->SchemaFile ) ) : null;
    $this->update( $SqlDb, $PrevSchema );
  }

  protected function update( SqlDb $SqlDb, Schema $PrevSchema = null )
  {
    $SqlDb->beginTransaction();
    
    $Uuids = $this->getUuids();
    // run update foreach object schema
    foreach ( $Uuids as $Uuid )
      $this->getObjectSchema( $Uuid )->update( $SqlDb, $PrevSchema ? $PrevSchema->getObjectSchema( $Uuid ) : null );

    // Drop other Object Schema
    $DroppedObjectSchemaUuids = array_diff( $PrevSchema ? $PrevSchema->getUuids() : [], $Uuids );
    foreach ( $DroppedObjectSchemaUuids as $DroppedObjectSchemaUuid )
      $SqlDb->dropTable( $PrevSchema->getObjectSchema( $DroppedObjectSchemaUuid )->getSqlTableName() );

    $SqlDb->commit();
    file_put_contents( $this->SchemaFile, serialize( $this ) );
  }

  protected $SchemaFile; 
  protected $ObjectSchemas = [];

}
