<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
class DatabaseSchema implements interfaces\DatabaseSchema
{

  function install( interfaces\SqlDb $SqlDb )
  {
    $Pdo = $SqlDb->getPdo();
    $Pdo->beginTransaction();
    /* @var $Schema interfaces\ObjectSchema */
    foreach ( $this->ObjectSchemas as $Schema )
      $Pdo->exec( $Schema->toSql( $SqlDb->getSqlDialect() ) );
    
    $Pdo->commit();
  }

  function add( $Fqcn, interfaces\ObjectSchema $Schema )
  {
    $this->ObjectSchemas[ $Fqcn ] = $Schema;
  }

  public function getObjectSchema( $Fqcn )
  {
    return $this->ObjectSchemas[ $Fqcn ];
  }

  /**
   *
   * @var \PDO
   */
  protected $Pdo;
  protected $ObjectSchemas = [];

}
