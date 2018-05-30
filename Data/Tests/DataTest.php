<?php

namespace qck\Data\Tests;

/**
 *
 * @author muellerm
 */
class DataTest extends \qck\core\abstracts\Test
{

  public function run( \qck\core\interfaces\AppConfig $config, array &$FilesToDelete = [] )
  {
    // create Sqlite instance
    $SqliteFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . str_replace( "\\", "_", self::class ) . ".sqlite";
    if ( file_exists( $SqliteFile ) )
      unlink( $SqliteFile );
    $SqliteDb = new \qck\Sql\SqliteDb( $SqliteFile );

    // create schema for Test Object
    $TestObjectSchema = new \qck\Data\ObjectSchema( TestObject::class );
    $TestObjectSchema->addProperty( new \qck\Data\StringProperty( "Name" ) );

    // create ObjectDbSchema for ObjectDb
    $ObjectDbSchema = new \qck\Data\ObjectDbSchema();
    $ObjectDbSchema->add( $TestObjectSchema );

    // create ObjectDb
    $ObjectDb = new \qck\Data\ObjectDb( $SqliteDb, $ObjectDbSchema );

    // create TestObject
    $TestObject = new TestObject();
    $TestObject->setName( "Michael" );

    // run
    $ObjectDbSchema->applyTo( $SqliteDb );
    $ObjectDb->register( $TestObject );
    $ObjectDb->commit();
    $TestObject->setName( "Michael Air" );
    $ObjectDb->commit();

    // load again
    $Db2 = new \qck\Data\ObjectDb( $SqliteDb, $ObjectDbSchema );
    $LoadedObject = $Db2->load( TestObject::class, $TestObject->getId() );
    $this->assert( $TestObject == $LoadedObject );
    
    $TestObject->setName( "Michael Air2" );
    $ObjectDb->commit();
    $LoadedObject = $Db2->load( TestObject::class, $TestObject->getId() );
    $this->assert( $TestObject == $LoadedObject );
  }

  public function getRequiredTests()
  {
    return [];
  }
}
