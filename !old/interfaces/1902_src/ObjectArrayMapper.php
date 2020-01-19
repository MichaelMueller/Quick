<?php

namespace Qck\Interfaces;

/**
 * An interface for conversion of objects to an array of scalars. Implementing classes must "know" these
 * classes.
 *  
 * @author muellerm
 */
interface ObjectArrayMapper
{

  /**
   * 
   * @param \Qck\Interfaces\Data\ObjectIdProvider $ObjectIdProvider
   * @return array an array of scalars 
   */
  function toArray( $Object, ObjectIdProvider $ObjectIdProvider );

  /**
   * 
   * @param array $ScalarArray
   * @param \Qck\Interfaces\Serialization\Source $Source
   */
  function fromArray( $Object, array $ScalarArray, ObjectSource $Source, $Reload = false );

}
