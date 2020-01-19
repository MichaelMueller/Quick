<?php

namespace Qck\Expression;

/**
 *
 * @author muellerm
 */
class Regexp extends Comparison
{

  function __construct( Interfaces\ValueExpression $Left,
                        Interfaces\ValueExpression $Right )
  {
    parent::__construct( $Left, $Right );
  }

  public function getOperator( \Qck\Interfaces\Sql\DbDialect $Dictionary )
  {
    return $Dictionary->getRegExpOperator();
  }

  public function evaluateProxy( array $Data, &$FilteredArray = array () )
  {
    return preg_match( $this->Left->getValue( $Data, $FilteredArray ), $this->Right->getValue( $Data, $FilteredArray ) ) == true;
  }
  
  public function getOperatorString()
  {
    return "regexp";
  }
}
