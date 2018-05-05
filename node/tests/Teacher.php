<?php

namespace qck\node\tests;

use \qck\node;

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

  public function __construct( $Uuid = null )
  {
    parent::__construct( $Uuid );
  }

  function addStudent( Student $NewStudent )
  {
    if ( $this->Students->findFirst( $NewStudent ) === null )
    {
      $this->Students->add( $NewStudent );
      $NewStudent->addTeacher( $this );
    }
  }
}
