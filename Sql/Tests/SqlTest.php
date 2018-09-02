<?php

namespace Qck\Sql\Tests;

use \Qck\Expressions\Expression as x;

/**
 *
 * @author muellerm
 */
class SqlTest extends \Qck\Core\Test
{

  public function run( \Qck\Interfaces\AppConfig $config, array &$FilesToDelete = [] )
  {
    // Create Schema
    $PersonTable = new \Qck\Sql\Table( "Person", new \Qck\Sql\PrimaryKeyCol( "Id" ) );
    $PersonTable->addColumn( new \Qck\Sql\StringColumn( "Name", 0, 255 ) );
    $PersonTable->addColumn( new \Qck\Sql\StringColumn( "Address", 0, 2048 ) );

    $UsersTable = new \Qck\Sql\Table( "Users", new \Qck\Sql\PrimaryKeyCol( "Id" ) );
    $UsersTable->addColumn( new \Qck\Sql\StringColumn( "Name", 0, 255 ) );
    $UsersTable->addColumn( new \Qck\Sql\BoolColumn( "Admin" ) );
    $UsersTable->addColumn( new \Qck\Sql\FloatColumn( "LastLogin" ) );
    $UsersTable->addColumn( new \Qck\Sql\StringColumn( "Picture", 0, 16777215, true ) );
    $UsersTable->addColumn( new \Qck\Sql\ForeignKeyCol( $PersonTable ) );
    $UsersTable->addUniqueIndex( "Name" );

    $Schema = new \Qck\Sql\Schema( [ $PersonTable, $UsersTable ] );

    // Sqlite
    $SqliteFile = sys_get_temp_dir() . "/" . crc32( SqlTest::class ) . ".sqlite";
    $this->delete( $SqliteFile );
    $SqliteDbms = new \Qck\Sql\SqliteDbms();
    $Sqlite = $SqliteDbms->connectToDatabase( $SqliteFile );
    $Sqlite->install( $Schema, true );
    $PersonId = $Sqlite->insert( $PersonTable->getName(), $PersonTable->getColumnNames( true ), [ "Michael Müller", "NoWhere Street 5, 67089 Heidelberg" ] );
    $Id = $Sqlite->insert( $UsersTable->getName(), $UsersTable->getColumnNames( true ), [ "Michael", true, 0.2312, null, $PersonId ] );

    // Read
    $ReadByName = x::eq( x::id( "Name" ), x::val( "Michael" ) );
    $Select = new \Qck\Sql\Select( $UsersTable->getName(), $ReadByName );
    $Select->setColumns( [ "Id" ] );
    $Results = $Sqlite->select( $Select )->fetchAll();
    $this->assert( $Results[ 0 ][ 0 ] == $Id );

    // Update
    $NewName = "Michael Jordan";
    $Sqlite->update( $UsersTable->getName(), [ "Name" ], [ $NewName ], new \Qck\Expressions\IdEquals( $Id, "Id" ) );

    // Read again
    $ReadById = x::eq( x::id( "Id" ), x::val( $Id ) );
    $Select = new \Qck\Sql\Select( $UsersTable->getName(), $ReadById );
    $Select->setColumns( [ "Name" ] );
    $Results = $Sqlite->select( $Select )->fetchAll();
    $this->assert( $Results[ 0 ][ 0 ] == $NewName );

    // Delete
    $Person2Id = $Sqlite->insert( $PersonTable->getName(), $PersonTable->getColumnNames( true ), [ "Ingmar Gergel", "NoWhere Street 5, 67089 Heidelberg" ] );
    $User2Id = $Sqlite->insert( $UsersTable->getName(), $UsersTable->getColumnNames( true ), [ "Ingmar", false, 0.33, null, $Person2Id ] );
    $this->assert( $Sqlite->delete( $UsersTable->getName(), new \Qck\Expressions\IdEquals( $User2Id ) ) == 1 );
    $this->assert( $Sqlite->delete( $PersonTable->getName(), new \Qck\Expressions\IdEquals( $Person2Id ) ) == 1 );

    // Read again
    $Results = $Sqlite->select( $Select )->fetchAll();
    $this->assert( count( $Results ) == 1 );
  }

  public function getRequiredTests()
  {
    return [];
  }
}
