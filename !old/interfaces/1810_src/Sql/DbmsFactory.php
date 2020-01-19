<?php

namespace Qck\Interfaces\Sql;

/**
 * Represents a Column
 * 
 * @author muellerm
 */
interface DbmsFactory
{

  /**
   * 
   * @return Dbms
   */
  function createSqliteDbms();
}
