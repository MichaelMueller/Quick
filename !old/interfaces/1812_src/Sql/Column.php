<?php

namespace Qck\Interfaces\Sql;

/**
 * Represents a Column
 * 
 * @author muellerm
 */
interface Column extends Convertable
{

  /**
   * @return string
   */
  function getName();
  
  /**
   * @return string
   */
  function getDatatype( DbDialect $DbDialect );
}
