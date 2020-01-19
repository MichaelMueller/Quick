<?php

namespace Qck\Expression;

use Qck\Interfaces\Expressions\ValueExpression;

/**
 *
 * @author muellerm
 */
abstract class Comparison extends BooleanExpression implements Interfaces\Comparison
{

  abstract function getOperatorString();

  abstract function getOperator( \Qck\Interfaces\Sql\DbDialect $Dictionary );

  function __construct( ValueExpression $Left = null, ValueExpression $Right = null )
  {
    $this->Left = $Left;
    $this->Right = $Right;
  }

  function getLeft()
  {
    return $this->Left;
  }

  function getRight()
  {
    return $this->Right;
  }

  public function toSql( \Qck\Interfaces\Sql\DbDialect $SqlDbDialect,
                         array &$Params = array () )
  {
    return $this->Left->toSql( $SqlDbDialect, $Params ) . " " . $this->getOperator( $SqlDbDialect ) . " " . $this->Right->toSql( $SqlDbDialect, $Params );
  }

  function __toString()
  {
    return $this->Left->__toString() . " " . $this->getOperatorString() . " " . $this->Right->__toString();
  }

  /**
   *
   * @var ValueExpression
   */
  protected $Left;

  /**
   *
   * @var ValueExpression 
   */
  protected $Right;

}
