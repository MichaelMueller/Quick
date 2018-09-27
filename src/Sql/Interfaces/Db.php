<?php

namespace Qck\Sql\Interfaces;

/**
 *
 * @author muellerm
 */
interface Db
{

  /**
   * @return string
   */
  function getName();
  
  /**
   * 
   */
  function closeConnection();

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
   * @return int Number of affected rows
   */
  function update( $TableName, array $ColumnNames, array $Values,
                   \qck\Expressions\Interfaces\Expression $Expression );

  /**
   * 
   * @param string $TableName
   * @param \qck\Sql\Interfaces\Expression $Expression
   * @return int Number of affected rows
   */
  function delete( $TableName, \qck\Expressions\Interfaces\Expression $Expression );

  /**
   * 
   * @param \qck\Sql\Interfaces\Select $Select
   * @return \PDOStatement
   */
  function select( Select $Select );

  /**
   * 
   */
  function beginTransaction();

  /**
   * 
   */
  function isInTransaction();

  /**
   * 
   */
  function commit();
}
