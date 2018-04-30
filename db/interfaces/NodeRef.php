<?php

namespace qck\db\interfaces;

/**
 *
 * @author muellerm
 */
interface NodeRef
{

  /**
   * @return string the uuid of a node
   */
  function getUuid();
  
  /**
   * @return Node or null if it does not exist anymore
   */
  function getNode();
}
