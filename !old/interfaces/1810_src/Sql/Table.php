<?php

namespace Qck\Interfaces\Sql;

/**
 *
 * @author muellerm
 */
interface Table extends Convertable
{

  /**
   * @return string
   */
  function addUniqueIndex( $ColumnName );

  /**
   * @return string
   */
  function addIndex( $ColumnName );

  /**
   * @return string
   */
  function getName();

  /**
   * @return array
   */
  function addKeys( $ValueArray, $SkipPrimaryKeys = true );
  
  /**
   * @return array
   */
  function getColumnNames( $SkipPrimaryKeys = true, $FullyQualified=false );
  
  /**
   * @return \Qck\Sql\ForeignKeyCol[]
   */
  function getForeignKeyCols();
  
  
  /**
   * @return bool whether this table is actually hidden (e.g. helper tables for logging etc.) or if it is a regular data table
   */
  function isHiddenTable();
}
