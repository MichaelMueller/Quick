<?php

namespace qck\db\interfaces;

/**
 *
 * @author muellerm
 */
interface NodeSerializer
{

  /**
   * convert a array to a string. All referenced Nodes will be replaced to NodeRefs before serialization
   * 
   * @param \qck\db\interfaces\Node $Node
   * @return string A string representation of the Node
   */
  function toString( Node $Node );

  /**
   * create a array from a string
   * 
   * @param string $String
   * @param NodeDb $NodeDb for creating NodeRef objects a NodeDb instance is needed
   * @return Node or null if unserialization did not work
   */
  function fromString( $String, NodeDb $NodeDb );

  /**
   * @return string the file extension
   */
  function getFileExtensionWithoutDot();
}
