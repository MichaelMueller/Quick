<?php

namespace qck\db\tests;

use \qck\db;

/**
 * @property string $Name Description
 * @property db\Node $Teachers Description
 * @property db\Node $Students Description
 * @author muellerm
 */
class Student extends db\Node
{

  public function __construct( $Name )
  {
    parent::__construct( [ "Name" => $Name, "Teachers" => new db\Node( ) ] );
  }

  function addTeacher( Teacher $NewTeacher )
  {

    if ( !$this->Teachers->has( $NewTeacher ) )
    {
      $this->Teachers->add( $NewTeacher );
      $NewTeacher->addStudent( $this );
    }
  }
}
