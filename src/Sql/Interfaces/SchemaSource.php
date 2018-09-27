<?php

namespace Qck\Sql\Interfaces;

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
