<?php

namespace Qck\Interfaces;

/**
 * An interface for conversion of objects to an array of scalars. Implementing classes must "know" these
 * classes.
 *  
 * @author muellerm
 */
interface Importable
{

  /**
   * @return bool
   */
  function isRoot();

  /**
   * @return object[]
   */
  function getObjects();
  
  /**
   * @return object The parent of this object or null
   */
  function canBeDeleted();
  
  /**
   * @return array
   */
  function getScalars();
}
