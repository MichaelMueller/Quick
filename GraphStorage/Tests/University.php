<?php

namespace qck\GraphStorage\Tests;

use \qck\GraphStorage;

/**
 * @property string $Name Description
 * @property Teacher $Decane
 * @property GraphStorage\Node $Teachers
 * @property GraphStorage\Node $Students
 * @property \DateTime $DateFounded
 * @author muellerm
 */
class University extends GraphStorage\Node
{

  const UUID = "University";

  static function create( $Name, Teacher $Decane )
  {
    $University = new University(self::UUID );
    $University->Teachers = new GraphStorage\Node();
    $University->Students = new GraphStorage\Node();
    $University->Decane = $Decane;
    $University->Name = $Name;
    $University->DateFounded = \DateTime::createFromFormat( 'j-M-Y', '15-Feb-2009' );
    return $University;
  }

  public function __construct( $Uuid = null )
  {
    parent::__construct( $Uuid );
  }
}
