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
   * @param mixed $Id
   * @param object $Object
   */
  function setObject( $Id, $Object );

  /**
   * @return object An object or null
   */
  function getObject( $Id );
}
