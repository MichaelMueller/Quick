<?php

namespace qck\node\tests;

use \qck\node;

/**
 * @property string $Name Description
 * @property db\Node $Teachers Description
 * @author muellerm
 */
class Student extends db\Node
{

  static function create( $Name )
  {
    $Student = new Student();
    $Student->Teachers = new db\Node();
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
