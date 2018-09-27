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
  function getLeftForeignKeyColumn();
  /**
   * @return Column
   */
  function getRightForeignKeyColumn();
}
