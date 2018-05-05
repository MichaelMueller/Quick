<?php

namespace qck\node\tests;

use \qck\node;

/**
 * @property string $Name Description
 * @property Teacher $Decane
 * @property db\Node $Teachers
 * @property db\Node $Students
 * @property \DateTime $DateFounded
 * @author muellerm
 */
class University extends db\Node
{

  const UUID = "2e6a4315-7c94-4086-9c33-c477adac32e7";

  static function create( $Name, Teacher $Decane )
  {
    $University = new University(self::UUID );
    $University->Teachers = new db\Node();
    $University->Students = new db\Node();
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
