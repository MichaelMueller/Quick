<?php

namespace Qck\Interfaces\Sql;

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

  /**
   * @return string
   */
  function getLeftFullName();

  /**
   * @return string
   */
  function getRightFullName();

}
