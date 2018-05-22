<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
class ObjectSchema
{
  function getPropertyUuids()
  {
    return array_keys( $this->Properties );
  }
  /**
   * 
   * @return Property
   */
  function getProperty()
  {
    return isset( $this->Properties[ $Uuid ] ) ? $this->Properties[ $Uuid ] : null;
  }
  
  function update( SqlDb $SqlDb, ObjectSchema $PrevObjectSchema = null )
  {
    $Uuids = $this->getPropertyUuids();
    // run update foreach object schema
    foreach ( $Uuids as $Uuid )
      $this->getObjectSchema( $Uuid )->update( $SqlDb, $PrevSchema ? $PrevSchema->getObjectSchema( $Uuid ) : null  );

    // Drop other Object Schema
    $DroppedObjectSchemaUuids = array_diff( $PrevSchema ? $PrevSchema->getUuids() : [], $Uuids );
    foreach ( $DroppedObjectSchemaUuids as $DroppedObjectSchemaUuid )
      $SqlDb->dropTable( $PrevSchema->getObjectSchema( $DroppedObjectSchemaUuid )->getSqlTableName() );

    // create columns
  }

  function getSqlTableName()
  {
    return str_replace( "\\", ".", $this->Fqcn );
  }

  protected $Fqcn;
  protected $Properties;

}
