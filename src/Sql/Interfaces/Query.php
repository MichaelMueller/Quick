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

  function getType();
}
