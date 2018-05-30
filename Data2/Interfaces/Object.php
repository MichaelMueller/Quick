<?php

namespace qck\Data2\Interfaces;

/**
 *
 * @author muellerm
 */
interface Object extends IdProvider
{

  /**
   * 
   * @param int $Id
   */
  function setId( $Id );

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
   * @param int $Version
   */
  function setVersion( $Version );

  /**
   * get the Version
   * @return int
   */
  function getVersion();
}
