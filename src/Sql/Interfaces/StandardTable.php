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
   * @return String
   */
  function getPrimaryKeyColumnName();
}
