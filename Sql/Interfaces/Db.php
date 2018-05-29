<?php

namespace qck\Sql\Interfaces;

/**
 *
 * @author muellerm
 */
interface Db
{

  /**
   * @return DbSchema
   */
  function getDbSchema();

  /**
   * 
   * @param string $TableName
   * @param array $ColumnNames
   * @param array $Values
   */
  function insert( $TableName, array $ColumnNames, array $Values );

  /**
   * 
   * @param string $TableName
   * @param array $ColumnNames
   * @param array $Values
   * @param \qck\Sql\Interfaces\Expression $Expression
   */
  function update( $TableName, array $ColumnNames, array $Values,
                   \qck\Expressions\Interfaces\Expression $Expression );

  /**
   * 
   * @param string $TableName
   * @param \qck\Sql\Interfaces\Expression $Expression
   */
  function delete( $TableName, \qck\Expressions\Interfaces\Expression $Expression );

  /**
   * 
   */
  function beginTransaction();

  /**
   * 
   */
  function commit();
}
