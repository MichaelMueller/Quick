<?php

namespace qck\db\tests;

use \qck\db;

/**
 * @property string $Name Description
 * @property db\Node $Teachers Description
 * @author muellerm
 */
class Student extends db\Node
{

  public function __construct( db\interfaces\Backend $Backend, $Uuid = null, array $Data=[] )
  {
    parent::__construct($Backend, $Uuid, $Data);
    if(!$Uuid)
    {
      $this->Teachers = new db\Node($Backend);
    }
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
