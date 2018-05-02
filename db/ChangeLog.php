<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
class ChangeLog implements interfaces\NodeObserver
{

  const KEY_ADDED = 0;
  const KEY_MODIFIED = 1;
  const KEY_DELETED = 2;

  function __construct( interfaces\Node $Node )
  {
    $this->Node = $Node;
    $this->Node->addObserver( $this );
  }

  /**
   * 
   * @return interfaces\Node
   */
  function getNode()
  {
    return $this->Node;
  }

  public function keyAdded( interfaces\Node $Node, $key, $val )
  {
    $this->Events[] = [ $key, self::KEY_ADDED ];
  }

  public function keyDeleted( interfaces\Node $Node, $key, $val )
  {
    $this->Events[] = [ $key, self::KEY_DELETED ];
  }

  public function keyModified( interfaces\Node $Node, $key, $newVal, $prevVal )
  {
    $this->Events[] = [ $key, self::KEY_MODIFIED ];
  }

  function isAddedEvent( $index )
  {
    return $this->getEvent( $index ) == self::KEY_ADDED;
  }

  function isModifiedEvent( $index )
  {
    return $this->getEvent( $index ) == self::KEY_MODIFIED;
  }

  function isDeletedEvent( $index )
  {
    return $this->getEvent( $index ) == self::KEY_DELETED;
  }

  function getEvent( $index )
  {
    $this->CurrentIndex = $index;
    return $this->Events[ $index ][ 1 ];
  }

  function getKey( $index )
  {
    $this->CurrentIndex = $index;
    return $this->Events[ $index ][ 0 ];
  }

  function nodeWasModified()
  {
    return $this->getNextIndex() < $this->getSize();
  }

  function getNextIndex()
  {
    return $this->CurrentIndex + 1;
  }

  function getSize()
  {
    return count( $this->Events );
  }

  /**
   *
   * @var interfaces\Node
   */
  protected $Node;
  protected $Events = [];
  protected $CurrentIndex = -1;

}
