<?php

namespace Qck\Interfaces\Serialization;

/**
 * An interface for exporting objects
 * 
 * @author muellerm
 */
interface Sink
{

  /**
   * recursively save 
   * 
   * @param Serializable $Object an id
   * @return void
   */
  function save( Serializable $Object );
}
