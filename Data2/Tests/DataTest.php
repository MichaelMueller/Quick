<?php

namespace qck\Data2\Tests;

/**
 *
 * @author muellerm
 */
class DataTest extends \qck\core\abstracts\Test
{

  public function run( \qck\core\interfaces\AppConfig $config, array &$FilesToDelete = [] )
  {
    // create Sqlite instance
    $SqliteFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . str_replace( "\\", "_", StructuredDataTest::class ) . ".sqlite";
    if ( file_exists( $SqliteFile ) )
      unlink( $SqliteFile );
    $SqliteDb = new \qck\Sql\SqliteDb( $SqliteFile );

    // create schema for Test Object
    $TestObjectSchema = new \qck\Data2\ObjectSchema( TestObject::class );
    $TestObjectSchema->addProperty( new \qck\Data2\StringProperty( "Name" ) );

    // create DbSchema for ObjectDb
    $DbSchema = new \qck\Data2\DbSchema();
    $DbSchema->add( $TestObjectSchema );

    // create Db
    $Db = new \qck\Data2\Db( $SqliteDb, $DbSchema );

    // create TestObject
    $TestObject = new TestObject();
    $TestObject->setName( "Michael" );

    // run
    $DbSchema->applyTo( $SqliteDb );
    $Db->register( $TestObject );
    $Db->commit();

    // load again
    $Db2 = new \qck\Data2\Db( $SqliteDb, $DbSchema );
    $LoadedObject = $Db2->load( TestObject::class, $Db->getId() );
    $this->assert( $TestObject == $LoadedObject );
  }

  public function getRequiredTests()
  {
    return [];
  }
}
