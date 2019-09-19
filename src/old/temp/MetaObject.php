<?php

namespace Qck;

/**
 * An interface for exporting objects
 * 
 * @author muellerm
 */
class MetaObject implements \Qck\Interfaces\ObjectArrayMapper, \Qck\Interfaces\ReferentialInfo
{

  function __construct( $Properties )
  {
    $this->Properties = $Properties;
  }

  public function fromArray( $Object, array $ScalarArray,
                             \Qck\Interfaces\ObjectSource $Source, $Reload = false )
  {
    for ( $i = 0; $i < count( $ScalarArray ); $i++ )
      $this->Properties[ $i ]->setScalar( $Object, $ScalarArray[ $i ], $Source, $Reload );
  }

  public function toArray( $Object, \Qck\Interfaces\ObjectIdProvider $ObjectIdProvider )
  {
    $Array   = [];
    foreach ( $this->Properties as $Property )
      $Array[] = $Property->getScalar( $Object, $ObjectIdProvider );
    return $Array;
  }

  public function getOwnedObjects( $Object )
  {
    $OwnedObjects = [];
    foreach ( $this->Properties as $Property )
    {
      $Value          = $Property->getValue( $Object );
      if ( is_object( $Value ) && $Property->isWeakReference() == false )
        $OwnedObjects[] = $Value;
    }

    return $OwnedObjects;
  }

  /**
   *
   * @var \Qck\Interfaces\Property[]
   */
  protected $Properties = [];

}
