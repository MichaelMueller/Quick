<?php

namespace qck\GraphStorage;

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
