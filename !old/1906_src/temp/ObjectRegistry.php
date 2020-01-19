<?php

namespace Qck;

/**
 * An interface for exporting objects
 * 
 * @author muellerm
 */
class ObjectRegistry implements \Qck\Interfaces\ObjectRegistry
{

  function __construct( \Qck\Interfaces\ObjectIdGenerator $ObjectIdGenerator )
  {
    $this->ObjectIdGenerator = $ObjectIdGenerator;
  }

  public function getObject( $Id )
  {
    return isset( $this->IdToObject[ $Id ] ) ? $this->IdToObject[ $Id ] : null;
  }

  public function setObject( $Id, $Object )
  {
    $this->IdToObject[ $Id ] = $Object;
  }

  public function getId( $Object )
  {
    foreach ( $this->IdToObject as $Id => $CurrObject )
      if ( $CurrObject === $Object )
        return $Id;

    $NextId = $this->ObjectIdGenerator->generateNextId();
    $this->setObject( $NextId, $Object );
    return $NextId;
  }

  /**
   *
   * @var \Qck\Interfaces\ObjectIdGenerator
   */
  protected $ObjectIdGenerator;

  /**
   *
   * @var string
   */
  protected $IdToObject=[];

}
