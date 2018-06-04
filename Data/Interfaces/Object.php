<?php

namespace qck\Data\Interfaces;

/**
 *
 * @author muellerm
 */
interface Object extends UuidProvider
{

  /**
   * set data with arbitrary key values
   * @param array $Data
   */
  function setData( array $Data );

  /**
   * get the Data
   * @return array
   */
  function getData();

  /**
   * @param int $ModifiedTime
   */
  function setModifiedTime( $ModifiedTime );

  /**
   * get the ModifiedTime
   * @return int
   */
  function getModifiedTime();

  /**
   * 
   * @param \qck\Data\Interfaces\ObjectObserver $Observer
   */
  function addObserver( ObjectObserver $Observer );

  /**
   * 
   * @param \qck\Data\Interfaces\ObjectObserver $Observer
   */
  function removeObserver( ObjectObserver $Observer );
  
  /**
   * 
   * @param \qck\Data\Interfaces\Object $Other
   * @return bool
   */
  function equals( Object $Other );
}
