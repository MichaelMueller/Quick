<?php

namespace qck\node\interfaces;

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
