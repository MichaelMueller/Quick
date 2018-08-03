<?php

namespace qck\Data\Tests;

/**
 *
 * @author muellerm
 */
class DataTest extends Qck\Interfaces\Test
{

  public function run(  \Qck\Interfaces\AppConfig $config, array &$FilesToDelete = [] )
  {
    // create Sqlite instance
    $SqliteFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . str_replace( "\\", "_", self::class ) . ".sqlite";
    $SchemaFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . str_replace( "\\", "_", self::class ) . ".schema";
    foreach ( [ $SqliteFile, $SchemaFile ] as $File )
      if ( file_exists( $File ) )
        unlink( $File );
    //$SqliteDb = new \qck\Sql\SqliteDb( $SqliteFile );
    // create schema for Test Object
    $UserSchema = new \qck\Data\ObjectSchema( User::class, "48eb75e0-3b1c-4f2d-8a32-b68fdac9bdfc" );
    $UserSchema->addProperty( new \qck\Data\StringProperty( "Name", "48eb75e0-3b1c-4f2d-8a32-b68fdac9bdfc" ) );
    $UserSchema->addProperty( new \qck\Data\ObjectProperty( "Organisations", "4cb6978c-40a2-4ebb-8be6-3f6f18c87429", \qck\Data\Vector::class ) );

    // create ObjectDbSchema for ObjectDb
    $ObjectDbSchema = new \qck\Data\ObjectDbSchema();
    $ObjectDbSchema->add( $UserSchema );
    //$ObjectDbSchema->applyTo( $SqliteDb );
    // objectDb factory
    $ObjectDbFactory = new \qck\Data\SqlObjectDbFactory( $SchemaFile, $ObjectDbSchema, new \qck\Sql\SqliteDbms(), $SqliteFile );

    // create ObjectDb
    $ObjectDb = $ObjectDbFactory->create();

    // create User
    $User = new User();
    $User->setName( "Michael" );
    $Orgs = $User->getOrganisations();
    $Orgs->add( "GE" );
    $Orgs->add( "Siemens" );

    // create List
    $TestVector = new \qck\Data\Vector();
    $TestVector->add( "First" );
    $TestVector->add( 2 );
    $TestVector->add( true );

    // run
    $ObjectDb->register( $User );
    $ObjectDb->register( $TestVector );
    $ObjectDb->commit();
    $User->setName( "Michael Air" );
    $ObjectDb->commit();

    // load again
    $ObjectDb2 = $ObjectDbFactory->create();
    $UserLoaded = $ObjectDb2->load( User::class, $User->getUuid() );

    $this->assertCompareObjects( $Orgs, $UserLoaded->Organisations );
    $this->assertCompareObjects( $User, $UserLoaded );

    $LoadedVector = $ObjectDb2->load( \qck\Data\Vector::class, $TestVector->getUuid() );
    $this->assertCompareObjects( $TestVector, $LoadedVector );

    $User->setName( "Michael Air2" );
    $ObjectDb->commit();

    $UserLoaded = $ObjectDb2->load( User::class, $User->getUuid() );
    $this->assertCompareObjects( $Orgs, $UserLoaded->Organisations );
    $this->assertCompareObjects( $User, $UserLoaded );

    $ObjectDb->deleteOnCommit( User::class, $User->getUuid() );
    $ObjectDb->commit();
    $UserLoaded = $ObjectDb2->load( User::class, $User->getUuid() );
    $this->assert( $UserLoaded == null, "User should was not deleted during test" );
  }

  public function getRequiredTests()
  {
    return [];
  }
}
