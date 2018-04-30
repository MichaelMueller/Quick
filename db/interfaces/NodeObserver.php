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
  function added( Node $Node, $key, $val );
  /**
   * called whenever a value is modified
   * @param \qck\db\interfaces\Node $Node
   * @param type $key
   * @param type $newVal
   * @param type $prevVal
   */
  function modified( Node $Node, $key, $newVal, $prevVal );
  /**
   * called whenever a key is removed
   * @param \qck\db\interfaces\Node $Node
   * @param type $key
   * @param type $val
   */
  function deleted( Node $Node, $key, $val );
}
