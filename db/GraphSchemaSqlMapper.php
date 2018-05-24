<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
class GraphSchemaSqlMapper
{

  function __construct( $SchemaCacheFile, GraphSchema $Schema )
  {
    $this->SchemaCacheFile = $SchemaCacheFile;
    $this->Schema = $Schema;
  }

  /**
   * @return Sql\Schema
   */
  function installOrUpdateOn( Sql\Database $Database )
  {
    /* @var $PrevSchema GraphSchema */
    $PrevSchema = file_exists( $PrevGraphSchemaFile ) ? unserialize( file_get_contents( $PrevGraphSchemaFile ) ) : null;
    $PrevMetaObjects = $PrevSchema ? $PrevSchema->getMetaObjects() : null;

    /* @var $MetaObject MetaObject */
    foreach ( $PrevMetaObjects as $Uuid => $MetaObject )
    {
      $MetaObject->removeFromSqlDatabase( $Database->getDatabaseSchemaInterface() );      
    }
    
    foreach ( $this->Schema->getMetaObjects() as $Uuid => $MetaObject )
    {
      $MetaObject->addToSqlDatabase( $SchemaInterface );
    }

    file_put_contents( $PrevGraphSchemaFile, serialize( $this ) );
  }

  protected $SchemaCacheFile;

  /**
   *
   * @var GraphSchema
   */
  protected $Schema;

}
