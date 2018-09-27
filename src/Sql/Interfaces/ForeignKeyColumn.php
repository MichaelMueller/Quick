<?php

namespace Qck\Sql\Interfaces;

/**
 * Represents a Column
 * 
 * @author muellerm
 */
interface ForeignKeyColumn extends Column
{
  /**
   * @return StandardTable
   */
  function getRefTable();
}
