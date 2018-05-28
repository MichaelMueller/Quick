<?php

namespace qck\GraphStorage;

/**
 *
 * @author muellerm
 */
interface PersistableNode extends UuidProvider
{
  /**
   * set data with arbitrary key values
   * @param array $Data
   */
  function setData( array $Data );

  /**
   * get the Data
   */
  function getData();

  /**
   * 
   * @param int $ModifiedTime
   */
  function setModifiedTime( $ModifiedTime );

  /**
   * @return int The time of the last data modification
   */
  function getModifiedTime();
}
