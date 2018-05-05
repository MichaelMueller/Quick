<?php

namespace qck\node\interfaces;

/**
 *
 * @author muellerm
 */
interface NodeRef extends UuidProvider
{

  /**
   * @return Node or null if it does not exist anymore
   */
  function getNode();
}
