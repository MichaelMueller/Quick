<?php

namespace Qck\Interfaces\Serialization;

/**
 * A central interface for addressing objects using an id
 * @author muellerm
 */
interface ObjectIdProvider
{
  
  /**
   * @return mixed An Id for this object
   */
  function getId( Serializable $Object );
}
