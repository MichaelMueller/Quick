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

  public function __construct( db\interfaces\Backend $Backend, $Uuid = null, array $Data=[] )
  {
    parent::__construct($Backend, $Uuid, $Data);
    if(!$Uuid)
    {      
      $this->Teachers = new db\Node($Backend);
      $this->Students = new db\Node($Backend);
    }
  }
}
