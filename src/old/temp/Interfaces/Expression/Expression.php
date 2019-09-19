<?php

namespace Qck\Interfaces\Expressions;

/**
 * base class for expressions (which is in turn a tree again)
 * @author muellerm
 */
interface Expression extends \Qck\Interfaces\Sql\Convertable
{

  /**
   * @return string
   */
  function __toString();
}
