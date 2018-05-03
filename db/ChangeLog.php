<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
class ChangeLog implements interfaces\NodeObserver
{

  const PROP_ADDED = 0;
  const PROP_MODIFIED = 1;
  const REMOVE_INVOKED = 2;

  function __construct( interfaces\Node $Node )
  {
    $this->Node = $Node;
    $this->Node->addObserver( $this );
  }

  function clear()
  {
    $this->Events = [];
  }
  
  function applyTo( interfaces\Node $OtherNode )
  {
    while ( count( $this->Events ) > 0 )
    {
      $Event = array_shift( $this->Events );
      $Type = $Event[ 0 ];

      if ( $Type == self::PROP_ADDED )
      {
        $Key = $Event[ 1 ];
        $OtherNode->add( $this->Node->get( $Key, false ) );
      }
      else if ( $Type == self::PROP_MODIFIED )
      {
        $Key = $Event[ 1 ];
        $OtherNode->set( $Key, $this->Node->get( $Key, false ) );
      }
      else
      {
        $Matcher = $Event[ 1 ];
        $OtherNode->remove( $Matcher );
      }
    }
  }

  public function propertyAdded( interfaces\Node $Node, $key )
  {
    $this->Events[] = [ self::PROP_ADDED, $key ];
  }

  public function propertyModified( interfaces\Node $Node, $key, $prevVal )
  {
    $this->Events[] = [ self::PROP_MODIFIED, $key ];
  }

  public function removeInvoked( interfaces\Node $Node, interfaces\Matcher $Matcher )
  {
    $this->Events[] = [ self::REMOVE_INVOKED, $Matcher ];
  }

  /**
   *
   * @var interfaces\Node
   */
  protected $Node;

  /**
   *
   * @var array
   */
  protected $Events = [];

}
