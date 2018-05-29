<?php

namespace qck\Data\Interfaces;

/**
 *
 * @author muellerm
 */
interface NodeDb
{

  /**
   * 
   * @param \qck\Data\Interfaces\PersistableNode $Node
   */
  function register( PersistableNode $Node );

  /**
   * 
   * @param string $Fqcn
   * @param int $Id
   * @return PersistableNode
   */
  function load( $Fqcn, $Id );

  /**
   * 
   * @param string $Fqcn
   * @param int $Id
   */
  function delete( $Fqcn, $Id );

  /**
   * 
   */
  function commit();
}
