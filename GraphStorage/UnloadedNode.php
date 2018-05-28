<?php

namespace qck\GraphStorage;

/**
 *
 * @author muellerm
 */
interface UnloadedNode extends UuidProvider
{

  /**
   * @return PersistableNode A persistable Node or null
   */
  function load();
}
