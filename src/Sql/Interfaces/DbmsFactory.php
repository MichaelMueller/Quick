<?php

namespace Qck\Sql\Interfaces;

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
