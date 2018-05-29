<?php

namespace qck\GraphStorage\Sql\Expressions;

/**
 *
 * @author muellerm
 */
abstract class BooleanChain extends BooleanExpression
{
  abstract function getOperator( \qck\GraphStorage\Sql\Dictionary $Dictionary );
  
  function __construct( array $Expressions = [] )
  {
    foreach ( $Expressions as $Expression )
      $this->add( $Expression );
  }

  function add( \qck\GraphStorage\Sql\Expressions $Expression )
  {
    $this->Expressions[] = $Expression;
  }

  public function toSql( \qck\GraphStorage\Sql\Dictionary $Dictionary, array &$Params = array () )
  {
    $Sql = "(";

    $ExpCount = count( $this->Expressions );
    for ( $i = 0; $i < $ExpCount; $i++ )
    {
      $Xpression = $this->Expressions[ $i ];
      $Sql .= $Xpression->toSql( $Dictionary, $Params );
      if ( $i + 1 < $ExpCount )
        $Sql .= " ".$this->getOperator( $Dictionary )." ";
    }

    $Sql .= ")";
    return $Sql;
  }

  protected $Expressions;

}
