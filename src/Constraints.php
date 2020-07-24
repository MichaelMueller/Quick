<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class Constraints
{

    public function evaluate( array $data )
    {
        $filteredArray = [];
        $result        = false;
        foreach ( $this->checks as $check )
            $check->evaluate( $data, $filteredArray );

        return [ $result, $filteredArray ];
    }

    /**
     * 
     * @param type $key
     * @return \Qck\Check
     */
    function check( $key )
    {
        $check          = new Check( $key );
        $this->checks[] = $check;
        return $check;
    }

    /**
     *
     * @var Check[]
     */
    protected $checks = [];

}

class Check
{

    function __construct( string $key )
    {
        $this->key = $key;
    }

    function evaluate( array $data, array &$filteredArray )
    {
        $result                      = false;
        if ( isset( $data[ $this->key ] ) )
            foreach ( $this->concatenations as $concatenation )
                $result                      = $concatenation->evaluate( $data[ $this->key ], $result );
        if ( $result === true )
            $filteredArray[ $this->key ] = $data[ $this->key ];
        return $result;
    }

    function group()
    {
        $group = new ConcatGroup($this);
    }
    
    /**
     * 
     * @param type $minLength
     * @return $this
     */
    function minLength( $minLength )
    {
        $predicate           = new Predicate( Predicate::GREATER_EQUALS, $minLength );
        $predicate->function = Predicate::FUNCTION_STRLENGTH;
        $this->addPredicate( $predicate );
        return $this;
    }

    function isOnlyAlphaNumeric()
    {
        $predicate = new Predicate( Predicate::REGEXP, "[a-zA-Z0-9]*", false );
        $this->addPredicate( $predicate );
        return $this;
    }

    function addPredicate( Predicate $predicate )
    {
        if ( $this->currentConcatentation )
        {
            $concat                      = $this->currentConcatentation;
            $this->currentConcatentation = null;
        }
        else
        {
            $concat           = new Concatenation();
            if ( count( $this->concatenations ) > 0 )
                $concat->operator = Concatenation::AND;
        }

        $concat->predicate      = $predicate;
        $this->concatenations[] = $concat;
    }

    /**
     * 
     * @return $this
     */
    function and()
    {
        $this->currentConcatentation           = new Concatenation();
        $this->currentConcatentation->operator = Concatenation::AND;
        return $this;
    }

    /**
     *
     * @var string|int
     */
    public $key;

    /**
     *
     * @var Concatenation[]
     */
    public $concatenations = [];

    // state

    /**
     *
     * @var Concatenation[]
     */
    protected $currentConcatentation;

}

class Concatenation
{

    function __construct( Predicate $predicate = null, $operator = null )
    {
        $this->predicate = $predicate;
        $this->operator  = $operator;
    }

    const AND = "and";
    const OR  = "or";
    const NOT = "!";
    const XOR = "xor";

    function evaluate( $val, &$formerEvalResult )
    {
        $result = false;

        if ( $this->operator == self::AND && $formerEvalResult === true )
            $result = $formerEvalResult && $this->predicate->evaluate( $val );
        if ( $this->operator == self::AND && $formerEvalResult === false )
            $result = false;
        else if ( $this->operator == self::OR )
            $result = $formerEvalResult || $this->predicate->evaluate( $val );
        else if ( $this->operator == self::XOR )
            $result = $formerEvalResult xor $this->predicate->evaluate( $val );
        else if ( $this->operator == self::NOT )
            $result = !$this->predicate->evaluate( $val );
        else
            $result = $this->predicate->evaluate( $val );

        return $result;
    }

    /**
     *
     * @var Predicate
     */
    public $predicate;
    public $operator;

}

class ConcatGroup
{
    function __construct( Check $check, ConcatGroup $parentGroup )
    {
        $this->check       = $check;
        $this->parentGroup = $parentGroup;
    }

        function evaluate( $val, &$formerEvalResult )
    {
        $result = $formerEvalResult;
        foreach ( $this->concatenations as $concatenation )
            $result = $concatenation->evaluate( $val, $result );
        return $result;
    }

    /**
     * 
     * @return Check
     */
    function check()
    {
        return $this->check;
    }
    
    /**
     *
     * @var Concatenation[]
     */
    public $currentConcatentation;
    
    /**
     *
     * @var Check
     */
    public $check;
    
    /**
     *
     * @var ConcatGroup
     */
    public $parentGroup;

}

class Predicate
{

    function __construct( $operator, $targetValue, $function = null, $strict = false, $regexDelim = "#" )
    {
        $this->operator    = $operator;
        $this->targetValue = $targetValue;
        $this->function    = $function;
        $this->strict      = $strict;
        $this->regexDelim  = $regexDelim;
    }

    const FUNCTION_STRLENGTH = "STRLEN";
    const EQUALS             = "==";
    const NOT_EQUALS         = "!=";
    const GREATER            = ">";
    const GREATER_EQUALS     = ">=";
    const LESS               = "<";
    const LESS_EQUALS        = "<=";
    const REGEXP             = "regexp";

    function evaluate( $val )
    {
        if ( $this->function == self::FUNCTION_STRLENGTH )
            $val = mb_strlen( $val );

        if ( $this->operator == self::EQUALS )
            return $this->strict ? $val === $this->targetValue : $val == $this->targetValue;
        elseif ( $this->operator == self::NOT_EQUALS )
            return $this->strict ? $val !== $this->targetValue : $val != $this->targetValue;
        elseif ( $this->operator == self::GREATER )
            return $val > $this->targetValue;
        elseif ( $this->operator == self::GREATER_EQUALS )
            return $val >= $this->targetValue;
        elseif ( $this->operator == self::LESS )
            return $val < $this->targetValue;
        elseif ( $this->operator == self::LESS_EQUALS )
            return $val > $this->targetValue;
        elseif ( $this->operator == self::REGEXP )
            return preg_match( $this->regexDelim . $this->targetValue . $this->regexDelim, $val );
        else
            throw \Exception( "operator '" . $this->operator . "' unknown" );
    }

    public $operator;
    public $targetValue;
    public $function;
    public $strict;
    public $regexDelim = "#";

}
