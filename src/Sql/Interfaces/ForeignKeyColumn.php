<?php

namespace Qck\Sql\Interfaces;

/**
 * Represents a ForeignKeyColumn
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
