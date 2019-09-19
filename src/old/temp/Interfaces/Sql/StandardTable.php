<?php

namespace Qck\Interfaces\Sql;

/**
 *
 * @author muellerm
 */
interface StandardTable extends Table
{

  /**
   * @return Column
   */
  function getPrimaryKeyColumn();

  /**
   * @return Column
   */
  function getPrimaryKey( $FullName = false );

  /**
   * @return string
   */
  function getPrimaryKeyFullName();

  /**
   * 
   * @param bool $HiddenTable
   */
  function setHiddenTable( $HiddenTable );
}
