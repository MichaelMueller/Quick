<?php

namespace qck\Data\Interfaces;

/**
 *
 * @author muellerm
 */
interface UuidProvider
{

  /**
   * @return string A Uuid (if none is
   */
  function getUuid();

  /**
   * @return string of the object
   */
  function getFqcn();
}
