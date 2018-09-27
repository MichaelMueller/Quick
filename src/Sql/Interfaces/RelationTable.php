<?php

namespace Qck\Sql\Interfaces;

/**
 *
 * @author muellerm
 */
interface RelationTable extends Table
{

  /**
   * @return ForeignKeyColumn
   */
  function getLeft();

  /**
   * @return ForeignKeyColumn
   */
  function getRight();
}
