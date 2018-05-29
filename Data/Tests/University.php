<?php

namespace qck\Data\Tests;

use \qck\Data;

/**
 * @property string $Name Description
 * @property Teacher $Decane
 * @property Data\Node $Teachers
 * @property Data\Node $Students
 * @property string $DateFounded
 * @author muellerm
 */
class University extends Data\Node
{

  const Id = 0;

  static function create( $Name )
  {
    $University = new University();
    $University->Teachers = new Data\Node();
    $University->Students = new Data\Node();
    $University->Name = $Name;
    $University->DateFounded = '15-Feb-2009';
    return $University;
  }

}
