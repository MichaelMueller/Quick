<?php

namespace Qck\Interfaces;

/**
 *
 * Basic and simple interface for a DataNode
 * @author muellerm
 */
interface ObjectStorage
{

  /**
   * 
   * @param string $Key
   * @param mixed $DefaultValue
   */
  function get( $Key, $DefaultValue );

  /**
   * 
   * @param type $Key
   * @param type $Value
   */
  function setScalar( $Key, $Value );

  /**
   * 
   * @param type $Key
   * @param type $Value
   */
  function setObject( $Key, PersistableObject $PersistableObject, $WeakRef = false );

  /**
   * persist changes
   */
  function commit();

  /**
   * deletes the storage
   */
  function delete();

  /**
   * get 
   */
  function getId();
}
