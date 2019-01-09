<?php

namespace Qck\Serialization;

/**
 * An interface for exporting objects
 * 
 * @author muellerm
 */
class ObjectRegistry implements \Qck\Interfaces\Serialization\ObjectRegistry
{

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
   * @var string
   */
  protected $IdToObject;

}
