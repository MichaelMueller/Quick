<?php

namespace Qck\Interfaces;

/**
 * A central interface for addressing objects using an id
 * @author muellerm
 */
interface ObjectRegistry extends ObjectIdProvider
{

  /**
   * 
   * @param type $Id
   * @param \Qck\Interfaces\Serialization\Serializable $Object
   */
  function setObject( $Id, $Object );

  /**
   * @return Serializable An object or null
   */
  function getObject( $Id );
}
