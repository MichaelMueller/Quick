<?php

namespace qck\db\interfaces;

/**
 *
 * @author muellerm
 */
interface NodeDb
{

  /**
   * Register this Node (and children recursively)
   * @param \qck\db\interfaces\Node $Node
   */
  function add( Node $Node );

  /**
   * Sync the InMemory Objects with the persistent Objects
   * Will first write new objects
   * Will then acquire a global lock
   * Will then update InMemory Nodes that have changed on the persistent side
   * Will then apply changes to persistent Nodes
   * @param \qck\db\interfaces\Node $Node
   */
  function sync();

  /**
   * get an already loaded node or load a node from persistent storage
   * @param type $Uuid
   * @return Node a Node or null
   */
  function get( $Uuid );

  /**
   * The Node gets unloaded, i.e. the next call to getNode() will reload it from disk
   * A complete reload can be done using unload() and get() or just by using sync()
   * @param Node $Node
   */
  function unload( Node $Node );
}
