<?php

namespace qck\Data;

/**
 *
 * @author muellerm
 */
class ObjectDbFactory
{

  function __construct( $SchemaFile, Interfaces\ObjectDbSchema $ObjectDbSchema,
                        \qck\Sql\Interfaces\Dbms $Dbms, $DbName, $TempDbName = null,
                        $DumpFile = null )
  {
    $this->SchemaFile = $SchemaFile;
    $this->ObjectDbSchema = $ObjectDbSchema;
    $this->Dbms = $Dbms;
    $this->DbName = $DbName;
    $this->TempDbName = $TempDbName;
    $this->DumpFile = $DumpFile;
  }

  function setZipDumpFile( $ZipDumpFile )
  {
    $this->ZipDumpFile = $ZipDumpFile;
  }

  /**
   * 
   * @return \qck\Data\ObjectDb
   */
  function create()
  {
    // load prior schema as serialized string    
    $OldObjectDbSchemaSerialized = file_exists( $this->SchemaFile ) ? file_get_contents( $this->SchemaFile ) : null;
    // CASE I fresh and new install
    if ( !$OldObjectDbSchemaSerialized && $this->ObjectDbSchema )
    {
      $SqlDb = $this->Dbms->createDatabase( $this->DbName );
      $this->ObjectDbSchema->applyTo( $SqlDb );
      file_put_contents( $this->SchemaFile, serialize( $this->ObjectDbSchema ) );
      return new ObjectDb( $SqlDb, $this->ObjectDbSchema );
    }

    // CASE II we have an old schema -> compare them and take action (if necessary)
    $ObjectDbSchemaSerialized = serialize( $this->ObjectDbSchema );
    if ( crc32( $OldObjectDbSchemaSerialized ) == crc32( $ObjectDbSchemaSerialized ) )
    {
      // nothing changed -> connect in standard way
      $SqlDb = $this->Dbms->connectToDatabase( $this->DbName );
      return new ObjectDb( $SqlDb, $this->ObjectDbSchema );
    }

    // CASE III schema changed -> MIGRATE
    // create temporary database for the new data and connect to the old db 
    $TempDbName = $this->TempDbName ? $this->TempDbName : \Ramsey\Uuid\Uuid::uuid4()->toString();
    $NewSqlDb = $this->Dbms->createDatabase( $TempDbName );
    $this->ObjectDbSchema->applyTo( $NewSqlDb );
    $NewObjectDb = new ObjectDb( $NewSqlDb, $this->ObjectDbSchema );
    $OldObjectDbSchema = unserialize( $OldObjectDbSchemaSerialized );
    $OldSqlDb = $this->Dbms->connectToDatabase( $this->DbName );
    $OldObjectDb = new ObjectDb( $OldSqlDb, $OldObjectDbSchema );

    // load every object from old schema and convert keys -> uuids -> keys and save to new database
    /* @var $OldObjectDbSchema qck\Data\Interfaces\ObjectDbSchema */
    foreach ( $OldObjectDbSchema->getFqcns() as $Fqcn )
    {
      foreach ( $OldObjectDb->select( $Fqcn ) as $LazyLoader )
      {
        $NewObj = $this->migrate( $LazyLoader->load(), $OldObjectDbSchema, $this->ObjectDbSchema );
        if ( $NewObj )
          $NewObjectDb->register( $NewObj );
      }
    }

    // close all backkup old if needed
    $NewObjectDb->commit();
    if ( $this->DumpFile )
      $this->Dbms->dumpDatabase( $OldObjectDb->getSqlDb()->getName(), $this->DumpFile, $this->ZipDumpFile );
    $this->Dbms->dropDatabase( $OldObjectDb->getSqlDb() );
    $this->Dbms->renameDatabase( $NewSqlDb, $OldObjectDb->getSqlDb()->getName() );
    file_put_contents( $this->SchemaFile, serialize( $this->ObjectDbSchema ) );
    return $NewObjectDb;
  }

  protected function migrate( Interfaces\Object $OldObject,
                              \qck\Data\ObjectDbSchema $OldObjectDbSchema,
                              \qck\Data\ObjectDbSchema $ObjectDbSchema )
  {

    $PriorObjSchema = $OldObjectDbSchema->getObjectSchema( $OldObject->getFqcn() );
    $NewObjectSchema = $ObjectDbSchema->getObjectSchemaByUuid( $PriorObjSchema->getUuid() );

    if ( !$NewObjectSchema )
      return null;

    $Data = $OldObject->getData();
    $Data = $PriorObjSchema->convertKeysToUuids( $Data );
    $Data = $NewObjectSchema->convertUuidsToKeys( $Data );
    $Data = $NewObjectSchema->filterArray( $Data );
    $OldObject->setData( $Data );
    return $OldObject;
  }

  /**
   *
   * @var string
   */
  protected $SchemaFile;

  /**
   *
   * @var Interfaces\ObjectDbSchema 
   */
  protected $ObjectDbSchema;

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

  /**
   *
   * @var string
   */
  protected $TempDbName;

  /**
   *
   * @var string
   */
  protected $DumpFile;

  /**
   *
   * @var string
   */
  protected $ZipDumpFile = true;

}
