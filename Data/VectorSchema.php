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
    parent::__construct( Vector::class );
    $this->addProperty( new AnyProperty( Vector::ELEMENTS_NAME ) );
  }
}
