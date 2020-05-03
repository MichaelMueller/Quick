<?php

namespace Qck\Interfaces;

/**
 * A provider for ObjectArrayMapper objects based on arbitrary objects (usually it will use get_class())
 *  
 * @author muellerm
 */
interface ObjectArrayMapperProvider
{

  /**
   * 
   * @param object $Object
   * @return ObjectArrayMapper
   */
  function getObjectArrayMapper( $Fqcn );
}
