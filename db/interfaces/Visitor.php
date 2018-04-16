<?php

namespace qck\db\interfaces;

/**
 *
 * @author muellerm
 */
interface Visitor
{
  /**
   * 
   * @param \qck\db\interfaces\Node $Node
   */
  function handle(Node $Node);
}
