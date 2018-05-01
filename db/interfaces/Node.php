<?php

namespace qck\db\interfaces;

/**
 * basically an array of data and a UUID
 * @author muellerm
 */
interface Node extends UuidProvider
{

  /**
   * @param \qck\db\interfaces\Observer $Observer
   */
  function addObserver( NodeObserver $Observer );

  /**
   * @return array the data array of this node
   */
  function keys();

  /**
   * @return array the data array of this node
   */
  function getData();

  /**
   * @return int The timestamp of last modificaiton of the data
   */
  function getModifiedTime();
}
