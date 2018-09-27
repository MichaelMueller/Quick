<?php

namespace Qck\Sql\Interfaces;

/**
 *
 * @author muellerm
 */
interface Schema extends Convertable
{

  /**
   * @return Table[]
   */
  function getTables();
}
