<?php

namespace qck\Data;

/**
 *
 * @author muellerm
 */
class Object implements \qck\Data\Interfaces\Object
{

  function setData( array $Data )
  {
    $this->Data = $Data;
    if ( !isset( $this->Data[ ModifiedTimeProperty::NAME ] ) )
      $this->setModified();
  }

  function __set( $Key, $Value )
  {
    $this->$Key = $Value;
  }

  function set( $Key, $Value )
  {
    $this->Data[ $Key ] = $Value;
    if ( $Key != ModifiedTimeProperty::NAME )
      $this->setModified();
  }

  protected function setModified()
  {
    $this->Data[ ModifiedTimeProperty::NAME ] = microtime();
  }

  function getUuid()
  {
    if ( is_null( $this->get( UuidProperty::NAME ) ) )
      $this->set( UuidProperty::NAME, \Ramsey\Uuid\Uuid::uuid4()->toString() );
    return $this->get( UuidProperty::NAME );
  }

  function __get( $Key )
  {
    return $this->get( $Key );
  }

  function get( $Key )
  {
    if ( isset( $this->Data[ $Key ] ) )
    {
      $Value = $this->Data[ $Key ];
      if ( $Value instanceof \qck\Data\Interfaces\UnloadedObject )
      {
        $Value = $Value->load();
        $this->Data[ $Key ] = $Value;
      }
      return $Value;
    }
    return null;
  }

  function getData()
  {
    return $this->Data;
  }

  function getModifiedTime()
  {
    return $this->Data[ ModifiedTimeProperty::NAME ];
  }

  public function getFqcn()
  {
    return get_class( $this );
  }

  /**
   *
   * @var array the actual data
   */
  protected $Data = [];

}
