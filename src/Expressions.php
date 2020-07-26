<?php

namespace Qck;

// ****************** Abstract Expression Classes
interface ExpressionDataFilter extends Interfaces\Expression
{

    /**
     * 
     * @param array $data
     * @return array [evalResult, filteredData, errors]
     */
    function filterData( array $data );
}

abstract class ExpressionFunction implements Interfaces\Expression
{

    function __construct( Interfaces\Expression $child = null )
    {
        $this->child = $child;
    }

    function setChild( Interfaces\Expression $child )
    {
        $this->child = $child;
    }

    /**
     *
     * @var Interfaces\Expression
     */
    protected $child;

}

abstract class ExpressionGroup implements Interfaces\Expression
{

    function add( Interfaces\Expression $child )
    {
        $this->children[] = $child;
    }

    /**
     *
     * @var Expression[]
     */
    protected $children;

}

abstract class ExpressionComparison implements Interfaces\Expression
{

    function __construct( Expression $left = null, Expression $right = null )
    {
        $this->left  = $left;
        $this->right = $right;
    }

    function setLeft( Interfaces\Expression $left )
    {
        $this->left = $left;
    }

    function setRight( Interfaces\Expression $right )
    {
        $this->right = $right;
    }

    /**
     *
     * @var Interfaces\Expression
     */
    protected $left;

    /**
     *
     * @var Interfaces\Expression
     */
    protected $right;

}

// ****************** End of abstract Expression Classes
// ****************** Concrete Expression Classes
class ExpressionVariable implements Interfaces\Expression
{

    public function __construct( $varName, $filter = false )
    {
        $this->varName = $varName;
        $this->filter  = $filter;
    }

    public function eval( array $data, array &$filteredData = [], array &$errors = [] )
    {
        if ( isset( $data[ $this->varName ] ) )
        {
            if ( $this->filter )
                $filteredData[ $this->varName ] = $data[ $this->varName ];
            return $data[ $this->varName ];
        }
        return null;
    }

    public function __toString()
    {
        return strval( $this->varName );
    }

    protected $varName;
    protected $filter;

}

class ExpressionValue implements Interfaces\Expression
{

    public function __construct( $value )
    {
        $this->value = $value;
    }

    public function eval( array $data, array &$filteredData = [], array &$errors = [] )
    {
        return $this->value;
    }

    public function __toString()
    {
        return strval( is_bool( $this->value ) ? intval( $this->value ) : $this->value );
    }

    protected $value;

}

// ****************** End Concrete Expression Classes
// ****************** Concrete ExpressionFunction Classes
class ExpressionStringLength extends ExpressionFunction
{

    public function __construct( Interfaces\Expression $child = null )
    {
        parent::__construct( $child );
    }

    public function eval( $data, array &$filteredData = [], array &$errors = [] )
    {
        return mb_strlen( strval( $this->child->eval( $data, $filteredData, $errors ) ) );
    }

    public function __toString()
    {
        return sprintf( "strlen(%s)", $this->child );
    }

}

class ExpressionNegate extends ExpressionFunction
{

    public function __construct( Expression $child = null )
    {
        parent::__construct( $child );
    }

    public function eval( array $data, array &$filteredData = [], array &$errors = [] )
    {
        return !( boolval( $this->child->eval( $data, $filteredData, $errors ) ) );
    }

    public function __toString()
    {
        return sprintf( "!%s", $this->child );
    }

}

// ****************** End Concrete ExpressionFunction Classes
// ****************** Concrete ExpressionComparison Classes
class ExpressionEquals extends ExpressionComparison
{

    public function __construct( Expression $left = null, Expression $right = null )
    {
        parent::__construct( $left, $right );
    }

    public function eval( array $data, array &$filteredData = [], array &$errors = [] )
    {
        return $this->left->eval( $data, $filteredData, $errors ) == $this->right->eval( $data, $filteredData, $errors );
    }

    public function __toString()
    {
        return sprintf( "%s == %s", $this->left, $this->right );
    }

}

class ExpressionNotEquals extends ExpressionComparison
{

    public function __construct( Expression $left = null, Expression $right = null )
    {
        parent::__construct( $left, $right );
    }

    public function eval( array $data, array &$filteredData = [], array &$errors = [] )
    {
        return $this->left->eval( $data, $filteredData, $errors ) != $this->right->eval( $data, $filteredData, $errors );
    }

    public function __toString()
    {
        return sprintf( "%s != %s", $this->left, $this->right );
    }

}

class ExpressionGreater extends ExpressionComparison
{

    public function __construct( Expression $left = null, Expression $right = null )
    {
        parent::__construct( $left, $right );
    }

    public function eval( array $data, array &$filteredData = [], array &$errors = [] )
    {
        return $this->left->eval( $data, $filteredData, $errors ) > $this->right->eval( $data, $filteredData, $errors );
    }

    public function __toString()
    {
        return sprintf( "%s > %s", $this->left, $this->right );
    }

}

class ExpressionGreaterEquals extends ExpressionComparison
{

    public function __construct( Expression $left = null, Expression $right = null )
    {
        parent::__construct( $left, $right );
    }

    public function eval( array $data, array &$filteredData = [], array &$errors = [] )
    {
        return $this->left->eval( $data, $filteredData, $errors ) >= $this->right->eval( $data, $filteredData, $errors );
    }

    public function __toString()
    {
        return sprintf( "%s >= %s", $this->left, $this->right );
    }

}

class ExpressionLess extends ExpressionComparison
{

    public function __construct( Expression $left = null, Expression $right = null )
    {
        parent::__construct( $left, $right );
    }

    public function eval( array $data, array &$filteredData = [], array &$errors = [] )
    {
        return $this->left->eval( $data, $filteredData, $errors ) < $this->right->eval( $data, $filteredData, $errors );
    }

    public function __toString()
    {
        return sprintf( "%s < %s", $this->left, $this->right );
    }

}

class ExpressionLessEquals extends ExpressionComparison
{

    public function __construct( Expression $left = null, Expression $right = null )
    {
        parent::__construct( $left, $right );
    }

    public function eval( array $data, array &$filteredData = [], array &$errors = [] )
    {
        return $this->left->eval( $data, $filteredData, $errors ) <= $this->right->eval( $data, $filteredData, $errors );
    }

    public function __toString()
    {
        return sprintf( "%s <= %s", $this->left, $this->right );
    }

}

class ExpressionMatches extends ExpressionComparison
{

    public function __construct( Expression $left = null, Expression $right = null )
    {
        parent::__construct( $left, $right );
    }

    public function eval( array $data, array &$filteredData = [], array &$errors = [] )
    {
        $subject = $this->left->eval( $data, $filteredData, $errors );
        $pattern = $this->right->eval( $data, $filteredData, $errors );
        return preg_match( $pattern, $subject );
    }

    public function __toString()
    {
        return sprintf( "%s matches %s", $this->left, $this->right );
    }

}

// ****************** End Concrete ExpressionComparison Classes
// ****************** Prepared Expression Classes
class ExpressionAlphanumeric extends ExpressionMatches
{

    public function __construct( Expression $left = null )
    {
        parent::__construct( $left, new ExpressionValue( "/[a-zA-z0-9]/" ) );
    }

}

class ExpressionEmail extends ExpressionFunction
{

    public function __construct( Expression $child = null )
    {
        parent::__construct( $child );
    }

    public function eval( array $data, array &$filteredData = [], array &$errors = [] )
    {
        $result = filter_var( $this->child->eval( $data, $filteredData, $errors ), FILTER_VALIDATE_EMAIL );
        return $result !== false ? true : false;
    }

    public function __toString()
    {
        return sprintf( "isEmail(%s)", $this->child );
    }

}

// ****************** End Prepared Expression Classes
class Expressions implements Interfaces\Expressions
{

    const AND = 1;
    const OR  = 2;

    function __construct( Expressions $parent = null, $error = null, $evaluateAll = false )
    {
        $this->parent      = $parent;
        $this->error       = $error;
        $this->evaluateAll = $evaluateAll;
    }

    public function eval( $data, &$filteredData = array (), &$errors = array () )
    {
        $cache = [];
        // first of all the ands
        for ( $i = 0; $i < count( $this->children ); $i++ )
        {
            $exp        = $this->children[ $i ];
            $concatType = $this->concatTypes[ $i ];

            if ( $concatType === self::AND )
            {
                $lastIdx           = count( $cache ) - 1;
                $lastVal           = is_bool( $cache[ $lastIdx ] ) ? $cache[ $lastIdx ] : $cache[ $lastIdx ]->eval( $data, $filteredData, $errors );
                if ( $lastVal === false && $this->evaluateAll === false )
                    $cache[ $lastIdx ] = false;
                else
                {
                    $currEval = boolval( $exp->eval( $data, $filteredData, $errors ) );
                    $cache[ $lastIdx ] = $lastVal && $currEval;
                }
            }
            else
                $cache[] = $exp;
        }

        $eval = null;
        foreach ( $cache as $cacheItem )
        {
            $currEval = is_bool( $cacheItem ) ? $cacheItem : $cacheItem->eval( $data, $filteredData, $errors );
            $eval     = is_null( $eval ) ? $currEval : $eval || $currEval;
        }

        if ( $this->error && $eval === false )
            $errors[] = $this->error;
        return $eval;
    }

    public function __toString()
    {
        $string = "";
        for ( $i = 0; $i < count( $this->children ); $i++ )
        {
            if ( $this->concatTypes[ $i ] !== null )
                $string .= $this->concatTypes[ $i ] == self::AND ? " and " : " or ";
            $string .= $this->children[ $i ];
        }

        $prefix = $this->parent ? "( " : null;
        $suffix = $this->parent ? " )" : null;
        return $prefix . $string . $suffix;
    }

    function var( $varName, $filter = false )
    {
        $this->handleNewExpression( new ExpressionVariable( $varName, $filter ) );
        return $this;
    }

    function val( $value )
    {
        $this->handleNewExpression( new ExpressionValue( $value ) );
        return $this;
    }

    function length( $varName, $filter = false )
    {
        $this->handleNewExpression( new ExpressionStringLength( new ExpressionVariable( $varName, $filter ) ) );
        return $this;
    }

    function eq()
    {
        $this->comparison = new ExpressionEquals();
        return $this;
    }

    function ne()
    {
        $this->comparison = new ExpressionNotEquals();
        return $this;
    }

    function gt()
    {
        $this->comparison = new ExpressionGreater();
        return $this;
    }

    function ge()
    {
        $this->comparison = new ExpressionGreaterEquals();
        return $this;
    }

    function lt()
    {
        $this->comparison = new ExpressionLess();
        return $this;
    }

    function le()
    {
        $this->comparison = new ExpressionLessEquals();
        return $this;
    }

    function matches()
    {
        $this->comparison = new ExpressionMatches();
        return $this;
    }

    protected function handleNewExpression( Interfaces\Expression $exp )
    {
        if ( is_null( $this->left ) )
            $this->left = $exp;
        else
        {
            $this->comparison->setLeft( $this->left );
            $this->comparison->setRight( $exp );
            $this->add( $this->comparison );
            // clear cache
            $this->left       = null;
            $this->comparison = null;
        }
    }

    protected function currentConcatType()
    {
        if ( $this->currentConcatType === null && count( $this->children ) > 1 )
            return self::AND;
        else
            return $this->currentConcatType;
    }

    public function and()
    {
        $this->currentConcatType = self::AND;
        return $this;
    }

    public function or()
    {
        $this->currentConcatType = self::OR;
        return $this;
    }

    public function group( $error = null, $evaluateAll = false )
    {
        $group = new Expressions( $this, $error, $evaluateAll );
        $this->add( $group );
        return $group;
    }

    public function closeGroup()
    {
        return $this->parent;
    }

    protected function add( Interfaces\Expression $Expression )
    {
        $this->children[]        = $Expression;
        $this->concatTypes[]     = $this->currentConcatType();
        $this->currentConcatType = null;
    }

    /**
     *
     * @var Expressions
     */
    protected $parent;

    /**
     *
     * @var mixed
     */
    protected $error;

    /**
     *
     * @var bool 
     */
    protected $evaluateAll;

    /**
     *
     * @var Interfaces\Expression[]
     */
    protected $children = [];

    /**
     *
     * @var int[]
     */
    protected $concatTypes = [];

    // CACHE

    /**
     *
     * @var Expression
     */
    protected $left;

    /**
     *
     * @var ExpressionComparison
     */
    protected $comparison;

    /**
     *
     * @var int
     */
    protected $currentConcatType;

}
