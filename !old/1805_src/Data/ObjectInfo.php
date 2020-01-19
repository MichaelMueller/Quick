<?php

namespace qck\Data;

/**
 *
 * @author muellerm
 */
class ObjectInfo
{

  function __construct( Interfaces\UuidProvider $UuidProvider, $DeleteOnCommit = false )
  {
    $this->UuidProvider = $UuidProvider;
    $this->DeleteOnCommit = $DeleteOnCommit;
  }

  function updateObjectData()
  {
    $this->LastKnownModifiedTime = $this->UuidProvider->getModifiedTime();
    $this->LastKnownKeys = array_keys( $this->UuidProvider->getData() );
  }

  function setDeleteOnCommit( $DeleteOnCommit )
  {
    $this->DeleteOnCommit = $DeleteOnCommit;
  }

  function getUuidProvider()
  {
    return $this->UuidProvider;
  }

  function getLastKnownModifiedTime()
  {
    return $this->LastKnownModifiedTime;
  }

  function getLastKnownKeys()
  {
    return $this->LastKnownKeys;
  }

  function shouldBeDeletedOnCommit()
  {
    return $this->DeleteOnCommit;
  }

  /**
   *
   * @var Interfaces\UuidProvider
   */
  protected $UuidProvider;

  /**
   *
   * @var Interfaces\UuidProvider
   */
  protected $LastKnownModifiedTime;

  /**
   *
   * @var array
   */
  protected $LastKnownKeys;

  /**
   *
   * @var bool 
   */
  protected $DeleteOnCommit;

}
