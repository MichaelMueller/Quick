<?php

namespace qck\Data\Abstracts;

/**
 *
 * @author muellerm
 */
abstract class Object implements \qck\Data\Interfaces\Object
{

  function __construct( $Uuid = null )
  {
    $this->Uuid = $Uuid;
  }

  function getUuid()
  {
    if ( is_null( $this->Uuid ) )
      $this->Uuid = \Ramsey\Uuid\Uuid::uuid4()->toString();
    return $this->Uuid;
  }

  function __get( $Key )
  {
    if ( isset( $this->Data[ $Key ] ) )
    {
      $Value = $this->Data[ $Key ];
      if ( $Value instanceof \qck\Data\Interfaces\UnloadedObject )
        $this->Data[ $Key ] = $Value->load();
      return $this->Data[ $Key ];
    }
    return null;
  }

  function getData()
  {
    return $this->Data;
  }

  function setData( array $Data )
  {
    $this->Data = $Data;    
    $this->setModified();
  }

  protected function setModified()
  {
    $this->ModifiedTime = microtime();    
  }


  public function getFqcn()
  {
    return get_class( $this );
  }

  function getModifiedTime()
  {
    return $this->ModifiedTime;
  }

  function setModifiedTime( $ModifiedTime )
  {
    $this->ModifiedTime = $ModifiedTime;
  }

  /**
   *
   * @var int
   */
  protected $Uuid;

  /**
   *
   * @var int
   */
  protected $ModifiedTime = 0;

  /**
   *
   * @var array the actual data
   */
  protected $Data = [];

}
