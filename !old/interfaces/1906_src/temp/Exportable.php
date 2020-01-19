<?php

namespace Qck\Interfaces;

/**
 * An interface for conversion of objects to an array of scalars. Implementing classes must "know" these
 * classes.
 *  
 * @author muellerm
 */
interface Exportable
{

  /**
   * @return object The parent of this object or null
   */
  function toArray( ObjectIdProvider $IdProvider );

  /**
   * @return object[]
   */
  function getObjects();

  /**
   * @return array
   */
  function getScalars();
}
