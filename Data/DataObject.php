<?php

namespace qck\Data;

/**
 *
 * @author muellerm
 */
class DataObject extends Abstracts\Object implements interfaces\DataObject
{

  public function get( $Key )
  {
    return isset( $this->Data[ $Key ] ) ? $this->Data[ $Key ] : null;
  }

  public function set( $Key, $Value )
  {
    $this->Data[ $Key ] = $Value;

    $this->Version++;
  }
}
