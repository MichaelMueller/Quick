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
   * 
   * @return Schema
   */
  function createSchema();
}
