<?php

namespace qck\Sql;

/**
 *
 * @author muellerm
 */
class Schema implements Interfaces\Schema
{

  function __construct( array $Tables = [] )
  {
    $this->Tables = $Tables;
  }

  function addTable( Interfaces\Table $Table )
  {
    $this->Tables[] = $Table;
  }

  function getTables()
  {
    return $this->Tables;
  }

  protected $Tables;

}
