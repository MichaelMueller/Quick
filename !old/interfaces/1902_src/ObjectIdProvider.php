<?php

namespace Qck\Interfaces;

/**
 * An interface for providing existing or new ids for arbitrary objects
 * @author muellerm
 */
interface ObjectIdProvider
{
  
  /**
   * garantuees to generate a unique (!) id for this object
   * 
   * @return mixed An Id for this object
   */
  function getId( $Object );
}
