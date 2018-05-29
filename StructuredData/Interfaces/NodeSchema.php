<?php

namespace qck\StructuredData\Interfaces;

/**
 *
 * @author muellerm
 */
interface NodeSchema
{  
  /**
   * 
   * @param \qck\StructuredData\Interfaces\SqlDbSchema $Schema
   */
  function applyTo(SqlDbSchema $Schema);
}
