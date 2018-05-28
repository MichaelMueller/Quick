<?php

namespace qck\GraphStorage;

/**
 *
 * @author muellerm
 */
interface UuidProvider
{
  /**
   * @return string A UUID (if none is
   */
  function getUuid();
}
