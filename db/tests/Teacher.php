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

  public function __construct( db\interfaces\Backend $Backend, $Uuid = null, array $Data=[] )
  {
    parent::__construct($Backend, $Uuid, $Data);
    if(!$Uuid)
    {
      $this->Students = new db\Node($Backend);
    }
  }

  function addStudent( Student $NewStudent )
  {
    if ( !$this->Students->contains( $NewStudent ) )
    {
      $this->Students->add( $NewStudent );
      $NewStudent->addTeacher( $this );
    }
  }
}
