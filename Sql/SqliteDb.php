<?php

namespace qck\Sql;

/**
 *
 * @author muellerm
 */
class SqliteDb extends Abstracts\Db implements Interfaces\Dbms
{

  function __construct( $SqliteFile = ":memory:" )
  {
    $this->SqliteFile = $SqliteFile;
  }

  public function closeConnection()
  {
    $this->Pdo = null;
  }

  /**
   * @return \PDO
   */
  protected function getPdo()
  {
    if ( !$this->Pdo )
      $this->Pdo = new \PDO( 'sqlite:' . $this->SqliteFile, null, null, [ \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION ] );

    return $this->Pdo;
  }

  public function getIntDatatype()
  {
    return "INTEGER";
  }

  public function getStringDatatype( $MinLength = 0, $MaxLength = 255, $IsBlob = false )
  {
    return $IsBlob ? "BLOB" : "TEXT";
  }

  public function getRegExpOperator()
  {
    if ( $this->RegexpInstalled )
    {
      $this->getPdo()->sqliteCreateFunction( 'REGEXP', function($y, $x)
      {
        return preg_match( '/' . addcslashes( $y, '/' ) . '/', $x );
      }, 2 );
      $this->RegexpInstalled = true;
    }
    return "REGEXP";
  }

  public function getStrlenFunctionName()
  {
    return "LENGTH";
  }

  public function getPrimaryKeyAttribute()
  {
    return "PRIMARY KEY";
  }

  public function getAutoincrementAttribute()
  {
    return "AUTOINCREMENT";
  }

  public function getBoolDatatype()
  {
    return $this->getIntDatatype();
  }

  public function getFloatDatatype()
  {
    return "REAL";
  }

  public function createDatabase( $Name )
  {
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

  public function getName()
  {
    return $this->SqliteFile;
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

  protected $SqliteFile;
  protected $SqliteCmdExe = "sqlite3";

  /**
   *
   * @var bool
   */
  protected $RegexpInstalled = false;

}
