<?php

namespace Qck\Sql\Interfaces;

/**
 *
 * @author muellerm
 */
interface DbFactory
{

  /**
   * 
   * @param string $DbName
   * @param \Qck\Sql\Interfaces\DbDialect $DbDialect
   * @return Db
   */
  function create( $DbName, DbDialect $DbDialect );
}
