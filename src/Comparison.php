<?php

namespace Qck;

/**
 * 
 * @author muellerm
 */
class Comparison implements Interfaces\Comparison\Start, Interfaces\Comparison\LeftVariable, Interfaces\Comparison\RightOperand, Interfaces\Comparison\RightVariable
{

    const EQUALS         = "=";
    const NOT_EQUALS     = "!=";
    const GREATER        = ">";
    const GREATER_EQUALS = ">=";
    const LESS           = "<";
    const LESS_EQUALS    = "<=";
    const MATCHES        = "=~";
    const AND            = 1;
    const OR             = 2;
    const LENGTH         = "length";
    const LOWER          = "lower";

    function __construct( Comparison $parent = null )
    {
        $this->parent = $parent;
    }

    public function parantheses(): Interfaces\Comparison\Start
    {
        $this->child = new Comparison( $this );
        return $this->child;
    }

    public function val( $val )
    {
        if ( is_null( $this->left ) )
        {
            $this->left = $val;
        }
        else if ( is_null( $this->right ) )
        {
            $this->right = $val;
        }
        return $this;
    }

    public function variable( ...$keys )
    {
        $keys = is_array( $keys ) ? $keys : [ $keys ];

        if ( is_null( $this->left ) )
        {
            $this->left      = $keys;
            $this->leftIsVar = true;
        }
        else if ( is_null( $this->right ) )
        {
            $this->right      = $keys;
            $this->rightIsVar = true;
        }
        return $this;
    }

    public function equals()
    {
        $this->operator = self::EQUALS;
        return $this;
    }

    public function greater()
    {
        $this->operator = self::GREATER;
        return $this;
    }

    public function greaterEquals()
    {
        $this->operator = self::GREATER_EQUALS;
        return $this;
    }

    public function less()
    {
        $this->operator = self::LESS;
        return $this;
    }

    public function lessEquals()
    {
        $this->operator = self::LESS_EQUALS;
        return $this;
    }

    public function matches()
    {
        $this->operator = self::MATCHES;
        return $this;
    }

    public function notEquals()
    {
        $this->operator = self::NOT_EQUALS;
        return $this;
    }

    public function length()
    {
        if ( is_null( $this->right ) )
            $this->leftFunction  = self::LENGTH;
        else
            $this->rightFunction = self::LENGTH;

        return $this;
    }

    public function lower()
    {
        if ( is_null( $this->right ) )
            $this->leftFunction  = self::LOWER;
        else
            $this->rightFunction = self::LOWER;

        return $this;
    }

    public function and()
    {
        $this->next           = new Comparison( $this->parent );
        $this->concatOperator = self::AND;
        return $this->next;
    }

    public function closeParantheses()
    {
        return $this->parent;
    }

    public function or()
    {
        $this->next           = new Comparison( $this->parent );
        $this->concatOperator = self::OR;
        return $this->next;
    }

    public function eval( $array ): bool
    {
        if ( $this->child )
            $result = $this->child->eval( $array );
        else
        {
            $leftVal  = $this->value( $array, $this->left, $this->leftIsVar, $this->leftFunction );
            $rightVal = $this->value( $array, $this->right, $this->rightIsVar, $this->rightFunction );
            $result   = $this->compare( $leftVal, $rightVal );
        }

        if ( $this->next )
        {
            if ( $this->concatOperator == self::OR )
                $result = $result || $this->next->eval( $array );
            else if ( $this->concatOperator == self::AND && $result )
                $result = $result && $this->next->eval( $array );
        }

        return $result;
    }

    protected function compare( $leftVal, $rightVal )
    {
        $type = $this->operator;
        if ( $type == self::EQUALS )
            return $leftVal == $rightVal;
        else if ( $type == self::NOT_EQUALS )
            return $leftVal != $rightVal;
        else if ( $type == self::GREATER )
            return $leftVal > $rightVal;
        else if ( $type == self::GREATER_EQUALS )
            return $leftVal >= $rightVal;
        else if ( $type >= self::LESS )
            return $leftVal < $rightVal;
        else if ( $type == self::LESS_EQUALS )
            return $leftVal <= $rightVal;
        else if ( $type == self::MATCHES )
            return preg_match( $rightVal, $leftVal );
    }

    protected function value( $array, $value, $isVar, $function )
    {
        if ( $isVar )
            $value = $this->arrayValue( $array, $value );

        if ( $function == self::LENGTH )
            $value = mb_strlen( $value );
        else if ( $function == self::LOWER )
            $value = strtolower( $value );
        return $value;
    }

    protected function arrayValue( array $array, array &$keys )
    {
        if ( count( $keys ) > 0 )
        {
            $currKey = array_shift( $keys );
            $value   = isset( $array[ $currKey ] ) ? $array[ $currKey ] : null;
            if ( count( $keys ) == 0 )
                return $value;
            else if ( count( $keys ) > 0 && is_array( $value ) )
                return $this->arrayValue( $value, $keys );
        }
        return null;
    }

    /**
     *
     * @var Comparison
     */
    protected $parent;

    /**
     *
     * @var Comparison
     */
    protected $next;

    /**
     *
     * @var Comparison
     */
    protected $concatOperator;

    /**
     *
     * @var Comparison
     */
    protected $child;

    /**
     *
     * @var mixed
     */
    protected $left;

    /**
     *
     * @var bool
     */
    protected $leftIsVar = false;

    /**
     *
     * @var string
     */
    protected $leftFunction;

    /**
     *
     * @var mixed
     */
    protected $operator;

    /**
     *
     * @var mixed
     */
    protected $right;

    /**
     *
     * @var bool
     */
    protected $rightIsVar = false;

    /**
     *
     * @var string
     */
    protected $rightFunction;

}
