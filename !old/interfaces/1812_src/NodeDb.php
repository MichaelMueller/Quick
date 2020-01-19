<?php

namespace Qck\Interfaces;

/**
 *
 * Basic and simple interface for a controller
 * @author muellerm
 */
interface NodeDb extends NodeStorage
{

  /**
   * @return array
   */
  function commit();
}
