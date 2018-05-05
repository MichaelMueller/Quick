<?php

namespace qck\node\tests;

use \qck\node;

/**
 * @property string $Name Description
 * @property node\Node $Teachers Description
 * @author muellerm
 */
class Student extends node\Node
{

  static function create( $Name )
  {
    $Student = new Student();
    $Student->Teachers = new node\Node();
    $Student->Name = $Name;
    return $Student;
  }

  public function __construct( $Uuid = null )
  {
    parent::__construct( $Uuid );
  }

  function addTeacher( Teacher $NewTeacher )
  {
    if ( $this->Teachers->findFirst( $NewTeacher ) === null )
    {
      $this->Teachers->add( $NewTeacher );
      $NewTeacher->addStudent( $this );
    }
  }
}
