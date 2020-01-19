<?php

namespace Qck\Interfaces\Sql;

/**
 * Represents a Column
 * 
 * @author muellerm
 */
interface SchemaFactory
{

  /**
   * @param Table[] $Tables
   * @return Schema
   */
  function createSchema(array $Tables);
}
