<?php

namespace qck\Data\Tests;

use \qck\Data;

/**
 * @property string $Name Description
 * @property Data\Node $Students Description
 * @author muellerm
 */
class Teacher extends Data\Node
{

  static function create( $Name )
  {
    $Teacher = new Teacher();
    $Teacher->Students = new Data\Node();
    $Teacher->Name = $Name;
    return $Teacher;
  }

  public function __construct( $Id = null )
  {
    parent::__construct( $Id );
  }

  function addStudent( Student $NewStudent )
  {
    if ( !$this->Students->contains($NewStudent) )
    {
      $this->Students->add( $NewStudent );
      $NewStudent->addTeacher( $this );
    }
  }
}
