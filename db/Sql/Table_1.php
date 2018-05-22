<?php

namespace qck\db\Sql;

/**
 *
 * @author muellerm
 */
class Schema
{

  function addTable( Table $Table )
  {
    $this->Tables[] = $Table;
  }

  function getTables()
  {
    return $this->Tables;
  }

  protected $Tables = [];

}
