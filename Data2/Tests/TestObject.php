<?php

namespace qck\Data2\Tests;

/**
 *
 * @author muellerm
 */
class TestObject extends \qck\Data2\Abstracts\Object
{

  function setName( $Name )
  {
    $this->Data[ "Name" ] = $Name;
    $this->Version++;
  }

  protected $Name;

}
