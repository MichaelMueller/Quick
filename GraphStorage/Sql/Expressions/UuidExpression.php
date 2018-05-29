<?php

namespace qck\GraphStorage\Sql\Expressions;

/**
 *
 * @author muellerm
 */
class UuidExpression extends Equals
{

  function __construct( $TargetUuid, $UuidId = "Uuid" )
  {
    parent::__construct( new Identifier( $UuidId ), new Value( $TargetUuid ) );
  }
}
