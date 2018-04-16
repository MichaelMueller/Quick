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

  public function __construct( db\interfaces\Backend $Backend, $Name )
  {
    parent::__construct( $Backend, [ "Name" => $Name, "Students" => new db\Node( $Backend ) ] );
  }

  function addStudent( Student $NewStudent )
  {
    if ( !$this->Students->has( $NewStudent ) )
    {
      $this->Students->add( $NewStudent );
      $NewStudent->addTeacher( $this );
    }
  }
}
