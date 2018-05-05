<?php

namespace qck\node\interfaces;

/**
 *
 * @author muellerm
 */
interface NodeObserver
{
  /**
   * called whenever a value is added
   * @param \qck\node\interfaces\Node $Node
   * @param type $key
   * @param type $val
   */
  function propertyAdded( Node $Node, $key );
  /**
   * called whenever a value is modified
   * @param \qck\node\interfaces\Node $Node
   * @param type $key
   * @param type $newVal
   * @param type $prevVal
   */
  function propertyModified( Node $Node, $key, $prevVal );
  /**
   * called whenever a key is removed
   * @param \qck\node\interfaces\Node $Node
   * @param type $key
   * @param type $val
   */
  function removeInvoked( Node $Node, Matcher $Matcher );
}
