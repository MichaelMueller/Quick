<?php

namespace Qck\Interfaces\Serialization;

/**
 * An importer for objects from a source
 * 
 * @author muellerm
 */
interface Source
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
