<?php

namespace qck\Data\Tests;

/**
 *
 * @author muellerm
 */
class TestObject extends \qck\Data\Abstracts\Object
{

  function setName( $Name )
  {
    $this->Data[ "Name" ] = $Name;
    $this->Version++;
  }

}
