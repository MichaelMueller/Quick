<?php

namespace qck\Sql;

/**
 *
 * @author muellerm
 */
class SqliteDbms implements Interfaces\Dbms
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

  public function dropDatabase( Interfaces\Db $Db )
  {
    $Db->closeConnection();
    unlink( $Db->getName() );
  }

  public function dumpDatabase( $Name, $File, $Zip = false )
  {
    $Cmd = $this->SqliteCmdExe . " " . $Name . " .dump > " . $File;
    system( $Cmd );
    if ( $Zip )
    {
      $zipArchive = new ZipArchive();
      $filename = $File . ".zip";

      $zipArchive->open( $filename, ZipArchive::CREATE );
      $zipArchive->addFile( $File, pathinfo( $File, PATHINFO_FILENAME ) );
      $zipArchive->close();
      unlink( $File );
    }
  }

  public function renameDatabase( Interfaces\Db $Db, $NewName )
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
