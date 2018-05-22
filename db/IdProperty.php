<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
class IdProperty extends Integer
{

  public function __construct()
  {
    parent::__construct( "208f3e6d-cbc9-4e85-8f20-607c59c78911", "Id" );
  }

  function getName()
  {
    return $this->Name;
  }

  public function toSqlDatatype( Sql\DatabaseDictionary $Dict )
  {
    
  }

  protected $Name;

}
