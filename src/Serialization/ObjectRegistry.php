<?php

namespace Qck\Serialization;

/**
 * An interface for exporting objects
 * 
 * @author muellerm
 */
class ObjectRegistry implements \Qck\Interfaces\Serialization\ObjectRegistry
{

  function __construct( \Qck\Interfaces\Serialization\ObjectIdGenerator $ObjectIdGenerator )
  {
    $this->ObjectIdGenerator = $ObjectIdGenerator;
  }

  public function getObject( $Id )
  {
    return isset( $this->IdToObject[ $Id ] ) ? $this->IdToObject[ $Id ] : null;
  }

  public function setObject( $Id, \Qck\Interfaces\Serialization\Serializable $Object )
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
   * @var \Qck\Interfaces\Serialization\ObjectIdGenerator
   */
  protected $ObjectIdGenerator;

  /**
   *
   * @var string
   */
  protected $IdToObject=[];

}
