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

  function getData()
  {
    return $this->Data;
  }

  function setData( array $Data )
  {
    $this->Data = $Data;
    $this->Version++;
  }

  public function getFqcn()
  {
    return get_class( $this );
  }

  function getVersion()
  {
    return $this->Version;
  }

  function setVersion( $Version )
  {
    $this->Version = $Version;
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
  protected $Version = 0;

  /**
   *
   * @var array the actual data
   */
  protected $Data = [];

}
