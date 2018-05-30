<?php

namespace qck\Data;

/**
 *
 * @author muellerm
 */
class Vector extends Abstracts\Object
{
  const ELEMENTS_NAME = "Elements";
  
  function __construct()
  {
    $this->Data[ self::ELEMENTS_NAME ] = [];
  }

  public function add( $Element )
  {
    $this->Data[ self::ELEMENTS_NAME ][] = $Element;
    $this->Version++;
  }

  public function at( $Index )
  {
    return $this->Data[ self::ELEMENTS_NAME ][ $Index ];
  }

  public function removeAt( $Index )
  {
    unset( $this->Data[ self::ELEMENTS_NAME ][ $Index ] );
    $this->Version++;
  }

  public function size()
  {
    return count( $this->Data[ self::ELEMENTS_NAME ] );
  }
}
