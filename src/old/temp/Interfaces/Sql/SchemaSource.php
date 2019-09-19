<?php

namespace Qck\Interfaces\Sql;

/**
 * Represents a Column
 * 
 * @author muellerm
 */
interface SchemaSource
{

  /**
   * 
   * @return Schema
   */
  function get();
}
