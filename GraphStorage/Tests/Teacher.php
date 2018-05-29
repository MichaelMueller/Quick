<?php

namespace qck\GraphStorage\Tests;

use \qck\GraphStorage;

/**
 * @property string $Name Description
 * @property GraphStorage\Node $Students Description
 * @author muellerm
 */
class Teacher extends GraphStorage\Node
{

  static function create( $Name )
  {
    $Teacher = new Teacher();
    $Teacher->Students = new GraphStorage\Node();
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
