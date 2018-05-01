<?php

namespace qck\db\tests;

use \qck\db;

/**
 * @property string $Name Description
 * @property db\Node $Students Description
 * @author muellerm
 */
class Teacher extends db\Node
{

  static function create( $Name )
  {
    $Teacher = new Teacher();
    $Teacher->Students = new db\Node();
    $Teacher->Name = $Name;
    return $Teacher;
  }

  public function __construct( array $Data = [], $Uuid = null )
  {
    parent::__construct( $Data, $Uuid );
  }

  function addStudent( Student $NewStudent )
  {
    if ( $this->Students->addIfNotExists( $NewStudent ) )
      $NewStudent->addTeacher( $this );
  }
}
