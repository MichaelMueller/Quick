<?php

namespace qck\db\interfaces;

/**
 * @author muellerm
 */
interface Matcher
{
  /**
   * @param type $value
   * @return bool Description
   */
  function matches( $value );
}
