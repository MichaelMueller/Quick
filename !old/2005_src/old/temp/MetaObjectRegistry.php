<?php

namespace Qck;

/**
 * An interface for exporting objects
 * 
 * @author muellerm
 */
class MetaObjectRegistry implements \Qck\Interfaces\ReferentialInfos, \Qck\Interfaces\ObjectArrayMappers
{

  function __construct( array $MetaObjects )
  {
    $this->MetaObjects = $MetaObjects;
  }

  /**
   *
   * @var \Qck\Interfaces\Property[]
   */
  protected $MetaObjects = [];

  public function getObjectArrayMapper( $Fqcn )
  {
    return $this->MetaObjects[ $Fqcn ];
  }

  public function getReferentialInfo( $Fqcn )
  {
    return $this->MetaObjects[ $Fqcn ];
  }
}
