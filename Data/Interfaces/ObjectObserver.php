<?php

namespace qck\Data\Interfaces;

/**
 *
 * @author muellerm
 */
interface ObjectObserver
{

  /**
   * 
   */
  function onModified(Object $Object);
}
