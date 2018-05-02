<?php

namespace qck\db\interfaces;

/**
 *
 * @author muellerm
 */
interface NodeDb
{
  /**
   * tries to register nodes recursivley to this database
   * if a node is already registered nothing will happen
   * @param \qck\db\interfaces\Node $Node
   */
  function add(Node $Node);
  
  /**
   * store new and update changed Nodes
   * @param \qck\db\interfaces\Node $Node
   */
  function commit();
  
  /**
   * get an already loaded node or load a node from persistent storage
   * @param type $Uuid
   * @return Node a Node or null
   */
  function getNode($Uuid);
  
  /**
   * The Node gets unloaded, i.e. the next call to getNode() will reload it from disk
   */
  function unloadNode(Node $Node);
}
