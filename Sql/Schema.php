<?php

namespace Qck\Sql;

/**
 *
 * @author muellerm
 */
class Schema implements \Qck\Interfaces\Sql\Schema
{

  function __construct( array $Tables = [] )
  {
    $this->Tables = $Tables;
  }

  function addTable( Qck\Interfaces\Sql\Table $Table )
  {
    $this->Tables[] = $Table;
  }

  function getTables()
  {
    return $this->Tables;
  }

  protected $Tables;

}
