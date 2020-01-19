<?php

namespace Qck\Interfaces;

/**
 *
 * Basic and simple interface for a controller
 * @author muellerm
 */
interface NodeStorage
{

  /**
   * @return array
   */
  function load( $Uuid, $Fqcn = null );
}
