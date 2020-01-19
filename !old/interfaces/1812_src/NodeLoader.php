<?php

namespace Qck\Interfaces;

/**
 *
 * Basic and simple interface for a controller
 * @author muellerm
 */
interface NodeLoader
{

  /**
   * @return Node
   */
  public function load();
}
