<?php

namespace Qck\Expression\Interfaces;

/**
 * base class for expressions (which is in turn a tree again)
 * @author muellerm
 */
interface Expression extends \Qck\Sql\Interfaces\Convertable
{

  /**
   * @return string
   */
  function __toString();
}
