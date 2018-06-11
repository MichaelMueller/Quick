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
    $this->add( new ObjectSetSchema() );
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

  public function getFqcns()
  {
    return array_keys( $this->Schemas );
  }

  public function getObjectSchemaByUuid( $Uuid )
  {
    foreach ( $this->Schemas as $ObjectSchema )
      if ( $ObjectSchema->getUuid() == $Uuid )
        return $ObjectSchema;

    return null;
  }

  protected $Schemas;

}
