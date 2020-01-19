<?php

namespace Qck\Interfaces\Serialization;

/**
 * An interface for removing serialized objects.
 * 
 * @author muellerm
 */
interface SinkCleaner
{

  /**
   * 
   * @param Serializable $Object
   * @return void
   */
  function delete( Serializable $Object );
}
