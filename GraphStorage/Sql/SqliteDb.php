<?php

namespace qck\GraphStorage\Sql;

/**
 *
 * @author muellerm
 */
class SqliteDb extends \qck\GraphStorage\GraphDb
{

  function __construct( $SqliteFile )
  {
    $this->SqliteFile = $SqliteFile;
  }

  public function commit()
  {    
  }

  function delete( $Fqcn, Expression $Expression )
  {
    
  }

  function select( Select $SqlSelect )
  {
    
  }

  /**
   * 
   * @return \PDO
   */
  protected function getPdo()
  {
    if ( $this->Pdo == null )
    {
      $this->Pdo = new \PDO( "sqlite:" . $this->SqliteFile );
      $this->Pdo->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
    }
    return $this->Pdo;
  }

  protected $Pdo;
  protected $SqliteFile;

}
