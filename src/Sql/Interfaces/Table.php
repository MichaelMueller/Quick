<?php

namespace Qck\Sql\Interfaces;

/**
 *
 * @author muellerm
 */
interface Table extends Convertable
{

  /**
   * @return string
   */
  function addColumn( Column $Column );

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
}
