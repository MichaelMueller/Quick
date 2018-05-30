<?php

namespace qck\Sql\Interfaces;

/**
 *
 * @author muellerm
 */
interface Table
{

  /**
   * 
   * @param \qck\Sql\Interfaces\Interfaces\Column $Column
   */
  function addColumn( Interfaces\Column $Column );

  /**
   * 
   * @param type $ColumnName
   */
  function addUniqueIndex( $ColumnName );

  /**
   * 
   * @param type $ColumnName
   */
  function addIndex( $ColumnName );

  /**
   * @return string
   */
  function getName();

  /**
   * @return string
   */
  function getColumnSql( DbDictionary $DbDictionary );

  /**
   * @return array of strings
   */
  function getIndexes();

  /**
   * @return array of strings
   */
  function getUniqueIndexes();
}
