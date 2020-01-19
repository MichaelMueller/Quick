<?php

namespace Qck\Interfaces\Serialization;

/**
 * An interface for conversion of objects to an array of scalar. Implementing classes must "know" these
 * classes.
 *  
 * @author muellerm
 */
interface Serializable
{

  /**
   * 
   * @param \Qck\Interfaces\Data\ObjectIdProvider $ObjectIdProvider
   * @return array of scalars!
   */
  function toScalarArray( ObjectIdProvider $ObjectIdProvider );

  /**
   * 
   * @param array $ScalarArray
   * @param \Qck\Interfaces\Serialization\Source $Source
   */
  function fromScalarArray( array $ScalarArray, Source $Source, $Reload = false );

  /**
   * 
   * @param object $Object
   * @return Serializable[]
   */
  function getOwnedObjects();
}
