<?php

namespace qck\db\interfaces;

/**
 * A Node is a special Object that is persistable within a Graph structure.
 * It basically provides a UUID for identification and for persisting references.
 * Internally an Object using the Node interface is like an array (key/value pairs).
 * An Object must only expose this interface to be used with NodeDb Objects.
 * 
 * @author muellerm
 */
interface Node extends UuidProvider
{

  /**
   * Will add an Observer to this Node. @see NodeObserver
   * @param \qck\db\interfaces\NodeObserver $Observer
   */
  function addObserver( NodeObserver $Observer );

  /**
   * Sets Data on this Node. The Node should notify observers when updating internal Data using this array.
   * Caution: Array could have NodeRef objects. Use NodeRef::getNode() when requested to lazy load these references.
   * @param array $Data
   */
  function setData( array $Data );

  /**
   * @return array the Data array (key/value pairs) of this node
   */
  function getData();

  /**
   * @return int The timestamp of last modificaton of the internal data
   */
  function getModifiedTime();
}
