<?php

namespace Qck\Sql\Interfaces;

/**
 *
 * @author muellerm
 */
interface Table extends \Qck\Sql\Convertable
{
  /**
   * @return string
   */
  function getName();
  /**
   * @return array
   */
  function getForeignKeyColumns();
}
