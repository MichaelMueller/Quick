<?php

namespace Qck\Sql\Interfaces;

/**
 *
 * @author muellerm
 */
interface StandardTable extends Table
{

  /**
   * @return Column
   */
  function getPrimaryKeyColumn();

  /**
   * @return string
   */
  function getPrimaryKeyFullName();

  /**
   * @return bool whether this table is actually hidden (e.g. helper tables for logging etc.) or if it is a regular data table
   */
  function isHiddenTable();

  /**
   * 
   * @param bool $HiddenTable
   */
  function setHiddenTable( $HiddenTable );
}
