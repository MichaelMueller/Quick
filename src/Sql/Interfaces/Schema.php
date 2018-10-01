<?php

namespace Qck\Sql\Interfaces;

/**
 *
 * @author muellerm
 */
interface Schema extends Convertable
{

  /**
   * 
   * @param \Qck\Sql\Interfaces\Table $Table
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
