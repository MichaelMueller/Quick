<?php

namespace qck\Data\Tests;

use \qck\Data;

/**
 * @property string $Name Description
 * @property Data\Node $Teachers Description
 * @author muellerm
 */
class Student extends Data\Node
{

  static function create( $Name )
  {
    $Student = new Student();
    $Student->Teachers = new Data\Node();
    $Student->Name = $Name;
    return $Student;
  }

  public function __construct( $Id = null )
  {
    parent::__construct( $Id );
  }

  function addTeacher( Teacher $NewTeacher )
  {
    if ( !$this->Teachers->contains( $NewTeacher ) )
    {
      $this->Teachers->add( $NewTeacher );
      $NewTeacher->addStudent( $this );
    }
  }
}
