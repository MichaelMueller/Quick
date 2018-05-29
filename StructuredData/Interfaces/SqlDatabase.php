<?php

namespace qck\StructuredData\Interfaces;

/**
 *
 * @author muellerm
 */
interface SqlDb
{

  /**
   * @return SqlDbSchema
   */
  function getSqlDbSchema();

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
   * @param \qck\StructuredData\Interfaces\Expression $Expression
   */
  function update( $TableName, array $ColumnNames, array $Values, Expression $Expression );

  /**
   * 
   * @param string $TableName
   * @param \qck\StructuredData\Interfaces\Expression $Expression
   */
  function delete( $TableName, Expression $Expression );
  
  /**
   * 
   */
  function beginTransaction();

  /**
   * 
   */
  function commit();
}
