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

  protected $Pdo;

}
