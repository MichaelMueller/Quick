<?php

namespace Qck\Interfaces\Sql;

/**
 *
 * @author muellerm
 */
interface Schema extends Convertable
{

  /**
   * 
   * @param \Qck\Interfaces\Sql\Table $Table
   */
  function addTable( Table $Table );

  /**
   * @return Table[]
   */
  function getTables();

  /**
   * @return Query[]
   */
  function toQueries();
}
