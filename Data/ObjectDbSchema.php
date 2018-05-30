<?php

namespace qck\Data;

/**
 *
 * @author muellerm
 */
class ObjectDbSchema implements Interfaces\ObjectDbSchema
{

  function __construct( array $Schemas = [] )
  {
    $this->Schemas = $Schemas;
    $this->add( new VectorSchema() );
  }

  function add( Interfaces\ObjectSchema $Schema )
  {
    $this->Schemas[ $Schema->getFqcn() ] = $Schema;
  }

  public function getObjectSchema( $Fqcn )
  {
    return $this->Schemas[ $Fqcn ];
  }

  public function applyTo( \qck\Sql\Interfaces\DbSchema $ObjectDbSchema )
  {
    foreach ( $this->Schemas as $Schema )
      $Schema->applyTo( $ObjectDbSchema );
  }

  protected $Schemas;

}
