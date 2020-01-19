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
   * @param bool $UpdateModifiedTime
   */
  function setData( array $Data );

  /**
   * get the Data
   * @return array
   */
  function getData();

  /**
   * get the ModifiedTime
   * @return int
   */
  function getModifiedTime();
}
