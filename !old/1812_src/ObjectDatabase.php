<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class ObjectDatabase implements Interfaces\ObjectDatabase
{

  public function getObjectStorage( $Fqcn, $Id = null )
  {
    
  }

  /**
   *
   * @var Interfaces\ObjectStorageFactory
   */
  protected $ObjectStorageFactory;

}
