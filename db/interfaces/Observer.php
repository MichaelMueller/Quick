<?php

namespace qck\db\interfaces;

/**
 *
 * @author muellerm
 */
interface Observer
{

  /**
   * 
   */
  function changed( Node $Node, $key, $newVal, $oldVal );
}
