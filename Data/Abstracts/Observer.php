<?php

namespace qck\Data\Abstracts;

/**
 *
 * @author muellerm
 */
abstract class Observer
{

  function __construct( \qck\Data\Interfaces\Object $Object )
  {
    $this->Object = $Object;
  }

  function getObject()
  {
    return $this->Object;
  }

  /**
   *
   * @var \qck\Data\Interfaces\Object
   */
  protected $Object;

}
