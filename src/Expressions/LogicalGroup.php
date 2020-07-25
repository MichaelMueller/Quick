<?php

namespace Qck\Expressions;

class LogicalGroup implements BooleanExpression
{

    function __construct( string $operator, BooleanExpression $booleanExpressions )
    {
        $this->operator           = $operator;
        $this->booleanExpressions = $booleanExpressions;
    }

    const AND = "and";
    const OR  = "or";
    const XOR = "xor";

    public function eval( array $array )
    {
        $result = null;
        foreach ( $this->booleanExpressions as $booleanExpression )
        {
            if ( is_null( $result ) )
                $result = $booleanExpression->eval( $array );
            if ( $this->operator == self::AND && $result !== false )
                $result = $result && $booleanExpression->eval( $array );

            else if ( $this->operator == self::OR )
                $result = $result || $booleanExpression->eval( $array );

            else if ( $this->operator == self::XOR )
                $result = $result xor $booleanExpression->eval( $array );
            else
                throw new \Exception( "operator '" . $this->operator . "' unknown" );
        }
        return $result;
    }

    /**
     *
     * @var string
     */
    protected $operator;

    /**
     *
     * @var BooleanExpression
     */
    protected $booleanExpressions;

}