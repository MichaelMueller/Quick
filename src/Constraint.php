<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class Constraint implements Interfaces\Constraint
{

    // Special Types
    const AND          = 0;
    const OR           = 1;
    const CONSTRAINT   = 3;
    // Implemented Checks
    const MIN_LENGTH   = 10;
    const ALPHANUMERIC = 11;
    const EQUALS_VAR   = 12;

    function __construct( string $varName, Constraint $parent = null )
    {
        $this->varName = $varName;
        $this->parent  = $parent;
    }

    public function eval( $array )
    {
        $eval = null;
        if ( !isset( $array[ $this->varName ] ) )
            return $eval;

        $val = $array[ $this->varName ];
        foreach ( $this->items as $item )
        {
            $currentEval = null;
            $concatType  = $item[ 0 ];
            $type        = $item[ 1 ];
            $param       = $item[ 2 ];

            if ( $concatType == self::AND && $eval === false )
                continue;
            // implement check types here
            else if ( $type == self::CONSTRAINT )
                $currentEval = $param->eval( $array );
            else if ( $type == self::EQUALS_VAR )
                $currentEval = isset( $array[ $param ] ) ? $array[ $param ] == $val : false;
            else if ( $type == self::ALPHANUMERIC )
                $currentEval = ctype_alnum( $val );
            else if ( $type == self::MIN_LENGTH )
                $currentEval = mb_strlen( $val ) > $param;

            $eval = $concatType == self::AND ? $eval && $currentEval : $eval || $currentEval;
        }
        return $eval;
    }

    public function equalsVar( $otherVarName )
    {
        $this->items[] = [ $this->currentConcatenation(), self::EQUALS_VAR, $otherVarName ];
        return $this;
    }

    public function minLength( $minLength )
    {
        $this->items[] = [ $this->currentConcatenation(), self::MIN_LENGTH, $minLength ];
        return $this;
    }

    public function alphaNumeric()
    {
        $this->items[] = [ $this->currentConcatenation(), self::ONLY_ALPHANUMERIC, null ];
        return $this;
    }

    public function group()
    {
        $constraint    = new Constraint( $this->varName, $this );
        $this->items[] = [ $this->currentConcatenation(), self::CONSTRAINT, $constraint ];
        return $this;
    }

    public function endGroup()
    {
        return $this->parent;
    }

    public function and()
    {
        $this->currentConcatenation = self::AND;
        return $this;
    }

    public function or()
    {
        $this->currentConcatenation = self::OR;
        return $this;
    }

    protected function currentConcatenation()
    {
        if ( count( $this->items ) > 0 )
            return is_null( $this->currentConcatenation ) ? self::AND : $this->currentConcatenation;
        else
            return null;
    }

    /**
     *
     * @var string
     */
    protected $varName;

    /**
     *
     * @var Constraint
     */
    protected $parent;

    // state

    /**
     *
     * @var array of strings and Constraints
     */
    protected $items;

    /**
     *
     * @var int
     */
    protected $currentConcatenation;

}
