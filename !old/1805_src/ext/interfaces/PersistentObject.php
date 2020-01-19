<?php

namespace qck\ext\interfaces;

/**
 * represents a user of the system (which gets authenticated in a sense)
 */
interface PersistentObject extends DataObject
{

  /**
   * @return string the username
   */
  function save();
}
