<?php

namespace qck\node\interfaces;

/**
 *
 * @author muellerm
 */
interface UuidProvider
{

  /**
   * @return string the uuid of a node
   */
  function getUuid();
}
