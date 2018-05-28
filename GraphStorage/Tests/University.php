<?php

namespace qck\GraphStorage\Tests;

use \qck\GraphStorage;

/**
 * @property string $Name Description
 * @property Teacher $Decane
 * @property GraphStorage\Node $Teachers
 * @property GraphStorage\Node $Students
 * @property string $DateFounded
 * @author muellerm
 */
class University extends GraphStorage\Node
{

  const UUID = "2e6a4315-7c94-4086-9c33-c477adac32e7";

  static function create( $Name )
  {
    $University = new University();
    $University->Teachers = new GraphStorage\Node();
    $University->Students = new GraphStorage\Node();
    $University->Name = $Name;
    $University->DateFounded = '15-Feb-2009';
    return $University;
  }

  public function __construct()
  {
    parent::__construct( self::UUID );
  }
}
