<?php

namespace qck\db\interfaces;

/**
 *
 * @author muellerm
 */
interface NodeObserver
{
  /**
   * called whenever a value is added
   * @param \qck\db\interfaces\Node $Node
   * @param type $key
   * @param type $val
   */
  function propertyAdded( Node $Node, $key );
  /**
   * called whenever a value is modified
   * @param \qck\db\interfaces\Node $Node
   * @param type $key
   * @param type $newVal
   * @param type $prevVal
   */
  function propertyModified( Node $Node, $key, $prevVal );
  /**
   * called whenever a key is removed
   * @param \qck\db\interfaces\Node $Node
   * @param type $key
   * @param type $val
   */
  function removeInvoked( Node $Node, Matcher $Matcher );
}
