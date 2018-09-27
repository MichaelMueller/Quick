<?php

namespace Qck\Sql\Interfaces;

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
