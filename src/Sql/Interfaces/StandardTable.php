<?php

namespace Qck\Sql\Interfaces;

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
   * @return string
   */
  function getPrimaryKeyFullName();


  /**
   * 
   * @param bool $HiddenTable
   */
  function setHiddenTable( $HiddenTable );
}
