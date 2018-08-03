<?php

namespace Qck\Expressions;

/**
 *
 * @author muellerm
 */
abstract class BooleanChain extends BooleanExpression
{

  abstract function getOperator( \Qck\Interfaces\Sql\DbDialect $Dictionary );

  function __construct( array $Expressions = [] )
  {
    foreach ( $Expressions as $Expression )
      $this->add( $Expression );
  }

  function add( BooleanExpression $Expression )
  {
    $this->Expressions[] = $Expression;
  }

  public function toSql( \Qck\Interfaces\Sql\DbDialect $Dictionary,
                         array &$Params = array () )
  {
    $Sql = "(";

    $ExpCount = count( $this->Expressions );
    for ( $i = 0; $i < $ExpCount; $i++ )
    {
      $Xpression = $this->Expressions[ $i ];
      $Sql .= $Xpression->toSql( $Dictionary, $Params );
      if ( $i + 1 < $ExpCount )
        $Sql .= " " . $this->getOperator( $Dictionary ) . " ";
    }

    $Sql .= ")";
    return $Sql;
  }

  protected $Expressions;

}
