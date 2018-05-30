<?php

namespace qck\Data2\Interfaces;

/**
 *
 * @author muellerm
 */
interface UnloadedObject extends IdProvider
{

  /**
   * @return PersistableNode A persistable Node or null
   */
  function load();
}
