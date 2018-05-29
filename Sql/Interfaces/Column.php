<?php

namespace qck\Sql\Interfaces;

/**
 *
 * @author muellerm
 */
interface Column
{

  /**
   * @return string
   */
  function toSql(Interfaces\DbDictionary $DbDictionary);
}
