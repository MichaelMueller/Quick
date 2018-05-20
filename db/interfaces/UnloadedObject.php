<?php

namespace qck\db\interfaces;

/**
 *
 * @author muellerm
 */
interface UnloadedObject
{

  /**
   * @return Object or null if it could not be loaded
   */
  function load();
}
