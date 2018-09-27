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
}
