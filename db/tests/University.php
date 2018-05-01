<?php

namespace qck\db\tests;

use \qck\db;

/**
 * @property string $Name Description
 * @property Teacher $Decane
 * @property db\Node $Teachers
 * @property db\Node $Students
 * @author muellerm
 */
class University extends db\Node
{
  const UUID = "2e6a4315-7c94-4086-9c33-c477adac32e7";

  static function create( $Name, Teacher $Decane )
  {
    $University = new University( array (), self::UUID );
    $University->Teachers = new db\Node();
    $University->Students = new db\Node();
    $University->Decane = $Decane;
    $University->Name = $Name;
    return $University;
  }

  public function __construct( array $Data = [], $Uuid = null )
  {
    parent::__construct( $Data, $Uuid );
  }
}
