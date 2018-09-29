<?php

namespace Qck\Sql\Interfaces;

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
