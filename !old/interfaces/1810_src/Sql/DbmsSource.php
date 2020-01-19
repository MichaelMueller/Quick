<?php

namespace Qck\Interfaces\Sql;

/**
 * Represents a Column
 * 
 * @author muellerm
 */
interface DbmsSource
{

  /**
   * 
   * @return Dbms
   */
  function get();
}
