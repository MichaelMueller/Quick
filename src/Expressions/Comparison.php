<?php

namespace Qck\Expressions;


class Comparison implements \Qck\Interfaces\Expressions\BooleanExpression
{

    const EQUALS_STRICT     = "===";
    const NOT_EQUALS_STRICT = "!==";
    const EQUALS            = "==";
    const NOT_EQUALS        = "!=";
    const GREATER           = ">";
    const GREATER_EQUALS    = ">=";
    const LESS              = "<";
    const LESS_EQUALS       = "<=";

    function __construct( ValueExpression $leftVal, string $operator, ValueExpression $rightVal )
    {
        $this->leftVal  = $leftVal;
        $this->operator = $operator;
        $this->rightVal = $rightVal;
    }

    function check( $leftVal, $rightVal )
    {
        if ( $this->operator == self::EQUALS )
            return $leftVal == $rightVal;
        elseif ( $this->operator == self::EQUALS_STRICT )
            return $leftVal === $rightVal;
        elseif ( $this->operator == self::NOT_EQUALS )
            return $leftVal != $rightVal;
        elseif ( $this->operator == self::NOT_EQUALS_STRICT )
            return $leftVal !== $rightVal;
        elseif ( $this->operator == self::GREATER )
            return $leftVal > $rightVal;
        elseif ( $this->operator == self::GREATER_EQUALS )
            return $leftVal >= $rightVal;
        elseif ( $this->operator == self::LESS )
            return $leftVal < $rightVal;
        elseif ( $this->operator == self::LESS_EQUALS )
            return $leftVal <= $rightVal;
        else
            throw new \Exception( "operator '" . $this->operator . "' unknown" );
    }

    public function eval( array $array )
    {
        return $this->check( $this->leftVal->get( $array ), $this->rightVal->get( $array ) );
    }

    /**
     *
     * @var ValueExpression
     */
    protected $leftVal;

    /**
     *
     * @var string
     */
    protected $operator;

    /**
     *
     * @var ValueExpression
     */
    protected $rightVal;

}