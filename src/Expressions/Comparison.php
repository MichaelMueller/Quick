<?php

namespace Qck\Expressions;

use \Qck\Interfaces\Expressions\ValueExpression as IValueExpression;

/**
 *
 * @author muellerm
 */
class Comparison implements \Qck\Interfaces\Expressions\BooleanExpression
{

    const EQUALS         = "==";
    const NOT_EQUALS     = "!=";
    const GREATER        = ">";
    const GREATER_EQUALS = ">=";
    const LESS           = "<";
    const LESS_EQUALS    = "<=";
    const REGEXP         = "regexp";

// TODO
    static function createEquals( IValueExpression $Left, IValueExpression $Right )
    {
        return new Comparison( self::EQUALS, $Left, $Right );
    }

    static function createNotEquals( IValueExpression $Left, IValueExpression $Right )
    {
        return new Comparison( self::NOT_EQUALS, $Left, $Right );
    }

    static function createGreater( IValueExpression $Left, IValueExpression $Right )
    {
        return new Comparison( self::GREATER, $Left, $Right );
    }

    static function createGreaterEquals( IValueExpression $Left, IValueExpression $Right )
    {
        return new Comparison( self::GREATER_EQUALS, $Left, $Right );
    }

    static function createLess( Expression $Left, IValueExpression $Right )
    {
        return new Comparison( self::LESS, $Left, $Right );
    }

    static function createLessEquals( IValueExpression $Left, IValueExpression $Right )
    {
        return new Comparison( self::LESS_EQUALS, $Left, $Right );
    }

    static function createRegexp( IValueExpression $Left, IValueExpression $Right, $Delimiter = "#" )
    {
        $Cmp = new Comparison( self::REGEXP, $Left, $Right );
        $Cmp->setRegexpDelimiter( $Delimiter );
        return $Cmp;
    }

    protected function __construct( $Operator, IValueExpression $Left, IValueExpression $Right )
    {
        $this->Operator = $Operator;
        $this->Left     = $Left;
        $this->Right    = $Right;
    }

    function setRegexpDelimiter( $RegexpDelimiter )
    {
        $this->RegexpDelimiter = $RegexpDelimiter;
    }

    function getLeft()
    {
        return $this->Left;
    }

    function getRight()
    {
        return $this->Right;
    }

    public function toSql( \Qck\Interfaces\SqlDialect $SqlDbDialect,
                           array &$Params = array () )
    {
        $SqlOperator = $this->Operator;
        if ( $this->Operator == self::REGEXP )
            $SqlOperator = $SqlDbDialect->getRegExpOperator();
        return $this->Left->toSql( $SqlDbDialect, $Params ) . " " . $SqlOperator . " " . $this->Right->toSql( $SqlDbDialect, $Params );
    }

    function __toString()
    {
        return $this->Left->__toString() . " " . $this->Operator . " " . $this->Right->__toString();
    }

    public function evaluate( array $Data, &$FilteredArray = array (), &$FailedExpressions = array () )
    {
        $Eval = false;

        if ( $this->Operator == self::EQUALS )
            $Eval = $this->Left->getValue( $Data, $FilteredArray ) == $this->Right->getValue( $Data, $FilteredArray );
        elseif ( $this->Operator == self::NOT_EQUALS )
            $Eval = $this->Left->getValue( $Data, $FilteredArray ) != $this->Right->getValue( $Data, $FilteredArray );
        elseif ( $this->Operator == self::GREATER )
            $Eval = $this->Left->getValue( $Data, $FilteredArray ) > $this->Right->getValue( $Data, $FilteredArray );
        elseif ( $this->Operator == self::GREATER_EQUALS )
            $Eval = $this->Left->getValue( $Data, $FilteredArray ) >= $this->Right->getValue( $Data, $FilteredArray );
        elseif ( $this->Operator == self::LESS )
            $Eval = $this->Left->getValue( $Data, $FilteredArray ) < $this->Right->getValue( $Data, $FilteredArray );
        elseif ( $this->Operator == self::LESS_EQUALS )
            $Eval = $this->Left->getValue( $Data, $FilteredArray ) <= $this->Right->getValue( $Data, $FilteredArray );
        elseif ( $this->Operator == self::REGEXP )
            $Eval = preg_match( $this->RegexpDelimiter . $this->Right->getValue( $Data, $FilteredArray ) . $this->RegexpDelimiter, $this->Left->getValue( $Data, $FilteredArray ) );

        if ( !$Eval )
            $FailedExpressions[] = $this;
        return $Eval;
    }

    /**
     *
     * @var string
     */
    protected $Operator;

    /**
     *
     * @var IValueExpression
     */
    protected $Left;

    /**
     *
     * @var IValueExpression
     */
    protected $Right;

    /**
     *
     * @var string
     */
    protected $RegexpDelimiter = "#";

}
