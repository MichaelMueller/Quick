<?php

namespace qck\Sql\Tests;

/**
 *
 * @author muellerm
 */
class SqlTest extends \qck\core\abstracts\Test
{

  public function run( \qck\core\interfaces\AppConfig $config, array &$FilesToDelete = [] )
  {

    // create user table
    $UserIdCol = new \qck\Sql\PrimaryKeyCol( "Id" );
    $UserNameCol = new \qck\Sql\StringColumn( "Name", 0, 255 );
    $UserAdminCol = new \qck\Sql\BoolColumn( "Admin" );
    $UserLastLogin = new \qck\Sql\FloatColumn( "LastLogin" );
    $UserPicture = new \qck\Sql\StringColumn( "Picture", 0, 16777215, true );
    $Table = new \qck\Sql\Table( "Users", [ $UserIdCol, $UserNameCol, $UserAdminCol, $UserLastLogin, $UserPicture ], [ "Name" ] );

    // Sqlite
    $SqliteFile = $this->getTempFile( $FilesToDelete );
    $Sqlite = new \qck\Sql\SqliteDb( $SqliteFile );
    $Sqlite->createTable( $Table );
    $Id = $Sqlite->insert( $Table->getName(), [ "Name", "Admin", "LastLogin", "Picture" ], [ "Michael", true, 0.2312, null ] );

    //$Sqlite->update( $Table->getName(), [ "Name" ], [ "Michael Jordan" ], new \qck\Expressions\IdEquals( $Id, "Id" ) );
  }

  public function getRequiredTests()
  {
    return [];
  }
}
