<?php

namespace qck\Data\Interfaces;

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
