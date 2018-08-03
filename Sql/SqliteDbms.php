<?php

namespace Qck\Sql;

/**
 *
 * @author muellerm
 */
class SqliteDbms implements \Qck\Interfaces\Sql\Dbms
{

  public function connectToDatabase( $Name )
  {
    return new SqliteDb( $Name );
  }

  public function createDatabase( $Name )
  {
    /* if ( file_exists( $Name ) )
      unlink( $Name ); */
    return new SqliteDb( $Name );
  }

  public function dropDatabase( \Qck\Interfaces\Sql\Db $Db )
  {
    $Db->closeConnection();
    unlink( $Db->getName() );
  }

  public function dumpDatabase( $Name, $File )
  {
    $Cmd = $this->SqliteCmdExe . " " . $Name . " .dump > " . $File;
    system( $Cmd );
  }

  public function renameDatabase( \Qck\Interfaces\Sql\Db $Db, $NewName )
  {
    $Db->closeConnection();
    rename( $Db->getName(), $NewName );
    return new SqliteDb( $NewName );
  }

  function setSqliteCmdExe( $SqliteCmdExe )
  {
    $this->SqliteCmdExe = $SqliteCmdExe;
  }

  protected $SqliteCmdExe = "sqlite3";

}
