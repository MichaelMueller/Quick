<?php

namespace qck\Sql\Interfaces;

/**
 *
 * @author muellerm
 */
interface Table
{

  /**
   * @return string
   */
  function getName();  

  /**
   * @return string
   */
  function getColumnSql(DbDictionary $DbDictionary);
  
  /**
   * @return array of strings
   */
  function getUniqueIndexes();
}
