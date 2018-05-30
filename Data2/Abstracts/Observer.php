<?php

namespace qck\Data2\Abstracts;

/**
 *
 * @author muellerm
 */
abstract class Observer
{

  function __construct( \qck\Data2\Interfaces\Object $Object )
  {
    $this->Object = $Object;
  }

  function getObject()
  {
    return $this->Object;
  }

  /**
   *
   * @var \qck\Data2\Interfaces\Object
   */
  protected $Object;

}
