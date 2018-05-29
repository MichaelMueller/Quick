<?php

namespace qck\Data\Interfaces;

/**
 *
 * @author muellerm
 */
interface UnloadedNode extends IdProvider
{

  /**
   * @return PersistableNode A persistable Node or null
   */
  function load();
}
