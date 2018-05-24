<?php

namespace qck\GraphStorage\Tests;

use \qck\GraphStorage;

/**
 * @property string $Name Description
 * @property GraphStorage\Node $Teachers Description
 * @author muellerm
 */
class Student extends Node
{

  static function create( $Name )
  {
    $Student = new Student();
    $Student->Teachers = new GraphStorage\Node();
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
