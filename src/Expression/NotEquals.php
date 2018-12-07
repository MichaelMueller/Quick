<?php

namespace Qck\Expression;

/**
 *
 * @author muellerm
 */
class NotEquals extends Comparison
{

  function __construct( Interfaces\ValueExpression $Left,
                        Interfaces\ValueExpression $Right )
  {
    parent::__construct( $Left, $Right );
  }

  public function evaluateProxy( array $Data, &$FilteredArray = [],
                                 &$FailedExpressions = [] )
  {
    return $this->Left->getValue( $Data, $FilteredArray ) != $this->Right->getValue( $Data, $FilteredArray );
  }

  public function getOperator( \Qck\Interfaces\Sql\DbDialect $Dictionary )
  {
    return "!=";
  }
  public function getOperatorString()
  {
    return "!=";
  }
}
