<?php

namespace qck\db\interfaces;

/**
 *
 * @author muellerm
 */
interface Storage
{
  /**
   * will recusrively store all nodes
   * @param \qck\db\interfaces\Node $Node
   * @return array The original array of data or null if it could not be loaded.
   */
  function save(Node $Node);
}
