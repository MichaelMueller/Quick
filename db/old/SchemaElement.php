<?php

namespace qck\db;

/**
 * Description of TableDefinition
 *
 * @author muellerm
 */
class SchemaElement
{

  function __construct( $Uid )
  {
    $this->Uid = $Uid;
  }

  function getUid()
  {
    return $this->Uid;
  }

  protected $Uid;

}
