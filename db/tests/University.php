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

  public function __construct( $Name, Teacher $Decane = null )
  {
    parent::__construct( [
      "Name" => $Name,
      "Decane" => $Decane,
      "Teachers" => new db\Node(),
      "Students" => new db\Node() ] );
  }
}
