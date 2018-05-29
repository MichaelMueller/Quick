<?php

namespace qck\GraphStorage;

/**
 *
 * @author muellerm
 */
interface PersistableNode extends IdProvider
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
   */
  function getData();

  /**
   * 
   * @param int $Version
   */
  function setVersion( $Version );

  /**
   * @return int The time of the last data modification
   */
  function getVersion();
}
