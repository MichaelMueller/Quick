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
   * 
   * @param \qck\db\interfaces\Node $Node
   */
  function commit();
  
  /**
   * 
   * @param type $Uuid
   * @return Node a Node or null
   */
  function getNode($Uuid);
}
