<?php

namespace qck\Data;

/**
 *
 * @author muellerm
 */
class VectorSchema extends ObjectSchema
{

  public function __construct()
  {
    parent::__construct( Vector::class, "69c63419-30b4-47ee-bea1-fdab6cfc3ec5" );
    $this->addProperty( new AnyProperty( Vector::ELEMENTS_NAME, "c1d957ff-8486-418c-9a5c-5af36cbc14f0" ) );
  }
}
