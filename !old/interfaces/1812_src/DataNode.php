<?php

namespace Qck\Interfaces;

/**
 *
 * Basic and simple interface for a DataNode
 * @author muellerm
 */
interface DataNode
{

  /**
   * @return string
   */
  function getUuid();

  /**
   * @return void
   */
  function getData();

  /**
   * @return array
   */
  function setData( array $Data );

  /**
   * @return array
   */
  function hasChanged();

  /**
   * @return array
   */
  function setUnchanged();
}
