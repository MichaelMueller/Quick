<?php

namespace Qck\Interfaces;

/**
 *
 * @author muellerm
 */
interface ObjectDatabase
{

  /**
   * @return ObjectStorage
   */
  function getObjectStorage( $Fqcn, $Id = null );
}
