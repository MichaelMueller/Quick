<?php

namespace Qck\Interfaces\Sql;

/**
 *
 * @author muellerm
 */
interface DbFactory
{

  /**
   * 
   * @param string $DbName
   * @param \Qck\Interfaces\Sql\DbDialect $DbDialect
   * @return Db
   */
  function create( $DbName, DbDialect $DbDialect );
}
