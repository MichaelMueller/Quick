<?php

namespace Qck\Interfaces;

/**
 *
 * Basic and simple interface for a DataNode
 * @author muellerm
 */
interface ObjectLoader
{

  /**
   * set a scalar value
   * @param string $Key
   * @param mixed $Value
   */
  function setScalar( $Key, $Value );

  /**
   * 
   * @param type $Key
   * @param \Qck\Interfaces\PersistableObject $PersistableObject
   * @param type $WeakReference
   */
  function setObject( $Key, PersistableObject $PersistableObject, $WeakReference = false );

  /**
   * persist changes
   */
  function save();
}
