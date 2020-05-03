<?php

namespace Qck\Expressions;

/**
 *
 * @author muellerm
 */
class And_ implements \Qck\Interfaces\Expressions\BooleanExpression
{

    function __construct( array $Expressions = array (), $EvaluateAll = false )
    {
        $this->Expressions = $Expressions;
        $this->EvaluateAll = $EvaluateAll;
    }

    public function __toString()
    {

        $Str      = "( ";
        $ExpCount = count( $this->Expressions );
        for ( $i = 0; $i < $ExpCount; $i++ )
        {
            $Exp = $this->Expressions[ $i ];
            $Str .= strval( $Exp );
            if ( $i + 1 < $ExpCount )
                $Str .= " and ";
        }
        return $Str . " )";
    }

    public function add( \Qck\Interfaces\Expressions\BooleanExpression $Expression )
    {
        $this->Expressions[] = $Expression;
    }

    public function evaluate( array $Data, &$FilteredArray = array (), &$FailedExpressions = array () )
    {
        $eval = true;
        foreach ( $this->Expressions as $Expression )
        {
            $eval = $eval && $Expression->evaluate( $Data, $FilteredArray, $FailedExpressions );
            if ( !$eval && $this->EvaluateAll == false )
                break;
        }

        if ( !$eval )
            $FailedExpressions[] = $this;

        return $eval;
    }

    public function toSql( \Qck\Interfaces\SqlDialect $SqlDbDialect, array &$Params = array () )
    {
        $Sql      = "( ";
        $ExpCount = count( $this->Expressions );
        for ( $i = 0; $i < $ExpCount; $i++ )
        {
            $Exp = $this->Expressions[ $i ];
            $Sql .= $Exp->toSql( $SqlDbDialect, $Params );
            if ( $i + 1 < $ExpCount )
                $Sql .= " " . $SqlDbDialect->getAndOperator() . " ";
        }
        return $Sql . " )";
    }

    /**
     *
     * @var \Qck\Interfaces\Expressions\BooleanExpression[]
     */
    protected $Expressions;

    /**
     *
     * @var bool
     */
    protected $EvaluateAll;

}
