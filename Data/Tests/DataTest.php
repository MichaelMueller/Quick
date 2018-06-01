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
    $UserSchema = new \qck\Data\ObjectSchema( User::class );
    $UserSchema->addProperty( new \qck\Data\StringProperty( "Name" ) );
    $UserSchema->addProperty( new \qck\Data\ObjectProperty( "Organisations", \qck\Data\Vector::class ) );

    // create ObjectDbSchema for ObjectDb
    $ObjectDbSchema = new \qck\Data\ObjectDbSchema();
    $ObjectDbSchema->add( $UserSchema );
    $ObjectDbSchema->applyTo( $SqliteDb );

    // create ObjectDb
    $ObjectDb = new \qck\Data\ObjectDb( $SqliteDb, $ObjectDbSchema );

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
    $ObjectDb2 = new \qck\Data\ObjectDb( $SqliteDb, $ObjectDbSchema );
    $UserLoaded = $ObjectDb2->load( User::class, $User->getUuid() );
    $this->assert( $Orgs == $UserLoaded->Organisations );
    $this->assert( $User == $UserLoaded, "Created and Loaded User are different: " . print_r( $User, true ) . " vs " . print_r( $UserLoaded, true ) );
    $LoadedVector = $ObjectDb2->load( \qck\Data\Vector::class, $TestVector->getUuid() );
    $this->assert( $TestVector == $LoadedVector );

    $User->setName( "Michael Air2" );
    $ObjectDb->commit();
    $UserLoaded = $ObjectDb2->load( User::class, $User->getUuid() );
    $this->assert( $Orgs == $UserLoaded->Organisations, "Objects differ: " . print_r( $Orgs, true ) . " vs " . print_r( $UserLoaded->Organisations, true ) );
    $this->assert( $User == $UserLoaded, "Created and Loaded User are different: " . print_r( $User, true ) . " vs " . print_r( $UserLoaded, true ) );

    $ObjectDb->delete( User::class, $User->getUuid() );
    $UserLoaded = $ObjectDb->load( User::class, $User->getUuid() );
    $this->assert( $UserLoaded == null );
    $ObjectDb->commit();
    $UserLoaded = $ObjectDb2->load( User::class, $User->getUuid() );
    $this->assert( $UserLoaded == null );
  }

  public function getRequiredTests()
  {
    return [];
  }
}
