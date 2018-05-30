<?php

namespace qck\Data2;

/**
 *
 * @author muellerm
 */
class DbSchema implements Interfaces\DbSchema
{

  function __construct( array $Schemas = [] )
  {
    $this->Schemas = $Schemas;
  }

  function add( Interfaces\ObjectSchema $Schema )
  {
    $this->Schemas[ $Schema->getFqcn() ] = $Schema;
  }

  public function getObjectSchema( $Fqcn )
  {
    return $this->Schemas[ $Fqcn ];
  }

  public function applyTo( \qck\Sql\Interfaces\DbSchema $DbSchema )
  {
    foreach ( $this->Schemas as $Schema )
      $Schema->applyTo( $DbSchema );
  }

  protected $Schemas;

}
