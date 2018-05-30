<?php

namespace qck\Data2\Abstracts;

/**
 *
 * @author muellerm
 */
abstract class Object implements \qck\Data2\Interfaces\Object
{

  function getId()
  {
    return $this->Id;
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

  function setId( $Id )
  {
    $this->Id = $Id;
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
  protected $Id;

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
