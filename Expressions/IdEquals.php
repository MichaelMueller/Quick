<?php

namespace qck\Expressions;

/**
 *
 * @author muellerm
 */
class IdEquals extends Equals
{

  function __construct( $TargetId, $IdPropName = "Id" )
  {
    parent::__construct( new Identifier( $IdPropName ), new Value( $TargetId ) );
  }
}
