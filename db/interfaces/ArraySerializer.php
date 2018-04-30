<?php

namespace qck\db\interfaces;

/**
 *
 * @author muellerm
 */
interface ArraySerializer
{

  /**
   * convert a array to a string. All referenced Nodes will be replaced to NodeRefs before serialization
   * 
   * @param \qck\db\interfaces\Node $Node
   * @return string A string representation of the Node
   */
  function toString( array $Data );

  /**
   * create a array from a string
   * 
   * @param string $String
   * @return array
   */
  function fromString( $String );

  /**
   * @return string the file extension
   */
  function getFileExtensionWithoutDot();
}
