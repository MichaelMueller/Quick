<?php

namespace qck\Expressions;

/**
 *
 * @author muellerm
 */
class UuidEquals extends Equals
{

  function __construct( $TargetId, $IdPropName = "Uuid" )
  {
    parent::__construct( new Identifier( $IdPropName ), new Value( $TargetId ) );
  }
}
