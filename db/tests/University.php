<?php

namespace qck\db\tests;

use \qck\db;

/**
 * @property string $Name Description
 * @property Teacher $Decane
 * @property db\Node $Teachers Description
 * @property db\Node $Students Description
 * @author muellerm
 */
class University extends db\Node
{

  public function __construct( db\interfaces\Backend $Backend, $Name, Teacher $Decane = null )
  {
    parent::__construct( $Backend, [      
      "Name" => $Name,
      "Decane" => $Decane,
      "Teachers" => new db\Node($Backend),
      "Students" => new db\Node($Backend) ] );
  }
}
