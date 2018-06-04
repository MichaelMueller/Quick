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

  function setData( array $Data )
  {
    $this->Data = $Data;
    $this->setModified();
  }

  protected function setModified()
  {
    $this->ModifiedTime = microtime();
    foreach ( $this->Observer as $Observer )
      $Observer->onModified( $this );
  }

  /**
   * 
   * @param \qck\Data\Interfaces\ObjectObserver $Observer
   */
  function addObserver( \qck\Data\Interfaces\ObjectObserver $Observer )
  {
    $this->Observer[] = $Observer;
  }

  public function removeObserver( \qck\Data\Interfaces\ObjectObserver $Observer )
  {
    $Index = array_search( $Observer, $this->Observer );
    if ( $Index !== false )
      unset( $this->Observer[ $Index ] );
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

  function equals( \qck\Data\Interfaces\Object $Other )
  {
    if ( $this->getUuid() != $Other->getUuid() || $this->getModifiedTime() != $Other->getModifiedTime() )
      return false;

    $MyData = $this->getData();
    $OtherData = $Other->getData();
    foreach ( $MyData as $Key => $Value )
    {
      $ElementCompare = true;
      if ( !isset( $OtherData[ $Key ] ) )
        $ElementCompare = false;
      else if ( $Value instanceof \qck\Data\Interfaces\Object && $OtherData[$Key] instanceof \qck\Data\Interfaces\Object )
        $ElementCompare = $Value->equals( $OtherData[ $Key ] );
      else
        $ElementCompare = $Value == $OtherData[ $Key ];
      if ( !$ElementCompare )
        return false;
    }
    return true;
  }

  function isNewerThan( \qck\Data\Interfaces\Object $Other )
  {
    $time1 = $this->getModifiedTime();
    $time2 = $Other->getModifiedTime();
    list($time1_usec, $time1_sec) = explode( " ", $time1 );
    list($time2_usec, $time2_sec) = explode( " ", $time2 );
    $sec1 = intval( $time1_sec );
    $sec2 = intval( $time2_sec );
    if ( $sec1 == $sec2 )
    {
      $msec1 = floatval( $time1_usec );
      $msec2 = floatval( $time2_usec );
      if ( $msec1 == $msec2 )
        return false;
      return $msec1 > $msec2;
    }
    else
      return $sec1 > $sec2;
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

  /**
   *
   * @var array
   */
  protected $Observer = [];

}
