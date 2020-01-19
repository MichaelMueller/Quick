<?php

namespace Qck\Interfaces;

/**
 *
 * Basic and simple interface for a DataNode
 * @author muellerm
 */
interface ObjectStore
{

  /**
   * @return PersistableObject or null
   */
  function create( $Fqcn, $Id = null );

  /**
   * @return PersistableObject or null
   */
  function get( $Fqcn, $Id, $Create = false );

  /**
   * @return bool
   */
  function exists( $Fqcn, $Id );

  /**
   * @return bool
   */
  function getAll( $Fqcn );
  
  /**
   * @return bool
   */
  function removeAll( $Fqcn );

  /**
   * @return bool
   */
  function remove( $Fqcn, $Id );

  /**
   * @return PersistableObject or null
   */
  function getFirst( $Fqcn );

  /**
   * @return int the number of stored objects of that kind
   */
  function count( $Fqcn );

  /**
   * persist all changed objects
   */
  function commit();
}
