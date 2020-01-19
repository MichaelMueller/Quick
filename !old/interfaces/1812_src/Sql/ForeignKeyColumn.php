<?php

namespace Qck\Interfaces\Sql;

/**
 * Represents a ForeignKeyColumn
 * 
 * @author muellerm
 */
interface ForeignKeyColumn extends Column
{

  const CASCADE = "CASCADE";
  const SET_NULL = "SET_NULL";

  /**
   * @return StandardTable
   */
  function getRefTable();

  /**
   * @return string
   */
  function getActionOnUpdate();

  /**
   * @return string
   */
  function getActionOnDelete();
}
