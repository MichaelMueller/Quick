<?php

namespace qck\StructuredData\Interfaces;

/**
 *
 * @author muellerm
 */
interface SqlTable
{

  /**
   * @return array of strings
   */
  function getColumnNames();

  /**
   * @return SqlColumn SqlColumn or null
   */
  function getColumn( $Name );

  /**
   * @return array of strings
   */
  function getUniqueColumnNames();

  /**
   * @return string name of the primary column
   */
  function getPrimaryKeyColumnName();
}
