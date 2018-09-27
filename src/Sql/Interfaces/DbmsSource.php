<?php

namespace Qck\Sql\Interfaces;

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
