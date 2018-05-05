<?php

namespace qck\node\tests;

use \qck\node;

/**
 * @property string $Name Description
 * @property node\Node $Students Description
 * @author muellerm
 */
class Teacher extends node\Node
{

  static function create( $Name )
  {
    $Teacher = new Teacher();
    $Teacher->Students = new node\Node();
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
