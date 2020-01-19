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
   * @return mixed if something was found
   */
  function load( $Id, $Reload = false );
  
}
