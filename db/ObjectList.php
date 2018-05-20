<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
class ObjectList extends abstracts\Object implements interfaces\ObjectList
{

  function __construct( array $Objects = [] )
  {
    foreach ( $Objects as $Object )
      $this->add( $Object );
  }

  public function add( interfaces\Object $Object )
  {
    $this->Objects[] = $Object;
    /* @var $ObjectListObserver qck\db\interfaces\ObjectListObserver */
    foreach ( $this->ObjectListObserver as $ObjectListObserver )
      $ObjectListObserver->onObjectAdded( $this, $Object );
  }

  public function addObjectListObserver( interfaces\ObjectListObserver $ObjectListObserver )
  {
    $this->ObjectListObserver = $ObjectListObserver;
  }

  public function at( $index )
  {
    return $this->Objects[ $index ];
  }

  public function remove( interfaces\Object $Object )
  {
    $index = array_search( $Object, $this->Objects, true );
    if ( $index !== false )
    {
      $Object = $this->Objects[ $index ];
      unset( $this->Objects[ $index ] );
      /* @var $ObjectListObserver qck\db\interfaces\ObjectListObserver */
      foreach ( $this->ObjectListObserver as $ObjectListObserver )
        $ObjectListObserver->onObjectRemoved( $this, $Object );
    }
  }

  public function size()
  {
    return count( $this->Objects );
  }

  public function contains( interfaces\Object $Object )
  {
    return in_array( $Object, $this->Objects, true );
  }

  public function getFqcn()
  {
    return get_class( $this );
  }

  /**
   *
   * @var array
   */
  protected $Objects;

  /**
   *
   * @var array
   */
  protected $ObjectListObserver;

}
