<?php

namespace qck\Sql\Tests;

use Qck\Expressions\Expression as x;

/**
 *
 * @author muellerm
 */
class SqlTest extends Qck\Interfaces\Test
{

  public function run(  \Qck\Interfaces\AppConfig $config, array &$FilesToDelete = [] )
  {

    // create user table
    $UserIdCol = new \qck\Sql\PrimaryKeyCol( "Id" );
    $UserNameCol = new \qck\Sql\StringColumn( "Name", 0, 255 );
    $UserAdminCol = new \qck\Sql\BoolColumn( "Admin" );
    $UserLastLogin = new \qck\Sql\FloatColumn( "LastLogin" );
    $UserPicture = new \qck\Sql\StringColumn( "Picture", 0, 16777215, true );
    $Table = new \qck\Sql\Table( "Users", [ $UserIdCol, $UserNameCol, $UserAdminCol, $UserLastLogin, $UserPicture ], [ "Name" ] );
    $Schema = new \qck\Sql\Schema( [ $Table ] );

    // Sqlite
    $SqliteFile = $this->getTempFile( $FilesToDelete );
    $Sqlite = new \qck\Sql\SqliteDb( $SqliteFile );
    $Sqlite->install( $Schema, true );
    $Id = $Sqlite->insert( $Table->getName(), $Table->getColumnNames( true ), [ "Michael", true, 0.2312, null ] );

    // Read
    $ReadByName = x::eq( x::id( "Name" ), x::val( "Michael" ) );
    $Select = new \qck\Sql\Select( $Table->getName(), $ReadByName );
    $Select->setColumns( [ "Id" ] );
    $Results = $Sqlite->select( $Select )->fetchAll();
    $this->assert( $Results[ 0 ][ 0 ] == $Id );

    // Update
    $NewName = "Michael Jordan";
    $Sqlite->update( $Table->getName(), [ "Name" ], [ $NewName ], new \Qck\Expressions\IdEquals( $Id, "Id" ) );

    // Read again
    $ReadById = x::eq( x::id( "Id" ), x::val( $Id ) );
    $Select = new \qck\Sql\Select( $Table->getName(), $ReadById );
    $Select->setColumns( [ "Name" ] );
    $Results = $Sqlite->select( $Select )->fetchAll();
    $this->assert( $Results[ 0 ][ 0 ] == $NewName );

    // Delete
    $this->assert( $Sqlite->delete( $Table->getName(), new \Qck\Expressions\IdEquals( $Id ) ) == 1 );

    // Read again
    $Results = $Sqlite->select( $Select )->fetchAll();
    $this->assert( count( $Results ) == 0 );
  }

  public function getRequiredTests()
  {
    return [];
  }
}
