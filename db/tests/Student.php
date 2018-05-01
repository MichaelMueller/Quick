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

  static function create( $Name )
  {
    $Student = new Student();
    $Student->Teachers = new db\Node();
    $Student->Name = $Name;
    return $Student;
  }

  public function __construct( array $Data = [], $Uuid = null )
  {
    parent::__construct( $Data, $Uuid );
  }

  function addTeacher( Teacher $NewTeacher )
  {
    if ( $this->Teachers->addIfNotExists( $NewTeacher ) )
      $NewTeacher->addStudent( $this );
  }
}
