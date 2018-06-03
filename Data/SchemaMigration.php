<?php

namespace qck\Data;

/**
 *
 * @author muellerm
 */
class ObjectDbFactory
{

  function __construct( $SchemaFile, Interfaces\ObjectDbSchema $ObjectDbSchema = null,
                        \qck\Sql\Interfaces\Dbms $Dbms )
  {
    $this->SchemaFile = $SchemaFile;
    $this->NewDbSchema = $NewDbSchema;
    $this->Dbms = $Dbms;
  }

  protected function createUsingOldDbSchema( $OldDbSchemaSerialized, &$OldDbSchema = null )
  {
    $OldDbSchema = unserialize( $OldDbSchemaSerialized );
    $SqlDb = $this->Dbms->connectToDatabase( $this->DbName );
    $Db = new ObjectDb( $SqlDb, $OldDbSchema );
    return $Db;
  }

  function create()
  {
    // workflow:
    $OldDbSchemaSerialized = file_exists( $this->SchemaFile ) ? file_get_contents( $this->SchemaFile ) : null;
    // no new schema
    if ( !$this->NewDbSchema )
      return $this->createUsingOldDbSchema( $OldDbSchemaSerialized );

    $NewDbSchemaSerialized = serialize( $this->NewDbSchema );
    if ( crc32( $OldDbSchemaSerialized ) == crc32( $NewDbSchemaSerialized ) )
      return $this->createUsingOldDbSchema( $OldDbSchemaSerialized );

    // if not do the migration
    // create new db, apply new schema
    $NewDbTempName = \Ramsey\Uuid\Uuid::uuid4()->toString();
    $NewDb = $this->Dbms->createDatabase( $NewDbTempName );
    $this->NewDbSchema->applyTo( $NewDb );
    $NewObjectDb = new ObjectDb( $NewDb, $this->NewDbSchema );

    // load every object from old schema and convert keys -> uuids -> keys and save to new database
    /* @var $OldDbSchema qck\Data\Interfaces\ObjectDbSchema */
    $OldDbSchema = null;
    $OldDb = $this->createUsingOldDbSchema( $OldDbSchemaSerialized, $OldDbSchema );
    foreach ( $OldDbSchema->getFqcns() as $Fqcn )
    {
      foreach ( $OldDb->select( $Fqcn ) as $LazyLoader )
      {
        $NewObj = $this->migrate( $LazyLoader->load(), $OldDbSchema, $this->NewDbSchema );
        if ( $NewObj )
          $NewObjectDb->register( $NewObj );
      }
    }

    // close all backkup old if needed
    $NewObjectDb->commit();
    return $NewObjectDb;
  }

  protected function migrate( Interfaces\Object $OldObject,
                              \qck\Data\ObjectDbSchema $OldDbSchema,
                              \qck\Data\ObjectDbSchema $NewDbSchema )
  {

    $PriorObjSchema = $OldDbSchema->getObjectSchema( $OldObject->getFqcn() );
    $NewObjectSchema = $NewDbSchema->getObjectSchemaByUuid( $PriorObjSchema->getUuid() );

    if ( !$NewObjectSchema )
      return;

    $Data = $OldObject->getData();
    $Data = $PriorObjSchema->convertKeysToUuids( $Data );
    $Data = $NewObjectSchema->convertUuidsToKeys( $Data );
    $Data = $NewObjectSchema->filterArray( $Data );
    $OldObject->setData( $Data );
    return $OldObject;
  }

  protected $SchemaFile;

  /**
   *
   * @var Interfaces\ObjectDbSchema 
   */
  protected $NewDbSchema;

  /**
   *
   * @var \qck\Sql\Interfaces\Dbms
   */
  protected $Dbms;

  /**
   *
   * @var string
   */
  protected $DbName;

}
