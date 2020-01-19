<?php

namespace Qck\Interfaces;

/**
 * Abstraction for a Source of arbitrary Objects based on Ids
 * 
 * @author muellerm
 */
interface ObjectSource
{

  /**
   * 
   * @param mixed $Id an id
   * @param bool $Reload
   * @return Serializable or null if nothing was found
   */
  function load( $Id, $Reload = false );
  
  /**
   * 
   * @param mixed $Id
   * @param bool $Reload
   * @return callable a lazy loader
   */
  function createLazyLoader( $Id, $Reload = false );
}
