<?php

namespace Qck\Sql\Interfaces;

/**
 *
 * @author muellerm
 */
interface Query extends Convertable
{

  const INSERT = "INSERT";
  const UPDATE = "UPDATE";
  const DELETE = "DELETE";
  const SELECT = "SELECT";
  const CREATE_TABLE = "CREATE TABLE";

  function getType();

  function getTableName();
}
