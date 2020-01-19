<?php

namespace Qck\Interfaces;

/**
 *
 * Basic and simple interface for a controller
 * @author muellerm
 */
interface ControllerFactory
{

  /**
   * @param string $Route The current route
   * @return Controller or null if no controller was found
   */
  public function create( $Route );
}
