<?php

namespace qck\Data\Interfaces;

/**
 *
 * @author muellerm
 */
interface UnloadedObject extends UuidProvider
{

  /**
   * @return PersistableNode A persistable Node or null
   */
  function load();
}
