<?php

namespace Qck\Interfaces\Sql;

/**
 *
 * @author muellerm
 */
interface WriteQuery extends Query
{

  const INSERT = "INSERT";
  const UPDATE = "UPDATE";
  const DELETE = "DELETE";
  const SELECT = "SELECT";
  const CREATE_TABLE = "CREATE TABLE";
  const DROP_TABLE = "DROP TABLE";
  
  /**
   * @return Table
   */
  function getTable();
  
  /**
   * @return String
   */
  function getType();
  
}
