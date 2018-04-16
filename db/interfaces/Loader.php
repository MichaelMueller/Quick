<?php

namespace qck\db\interfaces;

/**
 *
 * @author muellerm
 */
interface Loader
{
  /**
   * 
   * @param \qck\db\interfaces\Node $Node
   * @return array The original array of data or null if it could not be loaded.
   */
  function loadData(Node $Node);
}
