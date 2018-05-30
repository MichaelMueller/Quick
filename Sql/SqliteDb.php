<?php

namespace qck\Sql;

/**
 *
 * @author muellerm
 */
class SqliteDb extends Abstracts\Db
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

  public function getPrimaryKeyAutoincrementAttribute()
  {
    return "PRIMARY KEY AUTOINCREMENT";
  }

  public function getBoolDatatype()
  {
    return $this->getIntDatatype();
  }

  public function getFloatDatatype()
  {
    return "REAL";
  }

  protected $SqliteFile;

  /**
   *
   * @var bool
   */
  protected $RegexpInstalled = false;

}
