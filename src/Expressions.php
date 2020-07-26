<?php

namespace Qck;

// ****************** Abstract Expression Classes
abstract class Expression implements Interfaces\Expression
{
    
}

abstract class ExpressionFunction extends Expression
{

    function __construct( Expression $child = null )
    {
        $this->child = $child;
    }

    function setChild( Expression $child )
    {
        $this->child = $child;
    }

    /**
     *
     * @var Expression
     */
    protected $child;

}

abstract class ExpressionGroup extends Expression
{

    function add( Expression $child )
    {
        $this->children[] = $child;
    }

    /**
     *
     * @var Expression[]
     */
    protected $children;

}

abstract class ExpressionComparison extends Expression
{

    function __construct( Expression $left = null, Expression $right = null )
    {
        $this->left  = $left;
        $this->right = $right;
    }

    function setLeft( Expression $left )
    {
        $this->left = $left;
    }

    function setRight( Expression $right )
    {
        $this->right = $right;
    }

    /**
     *
     * @var Expression
     */
    protected $left;

    /**
     *
     * @var Expression
     */
    protected $right;

}

// ****************** End of abstract Expression Classes
// ****************** Concrete Expression Classes
class ExpressionVariable extends Expression
{

    public function __construct( $varName )
    {
        $this->varName = $varName;
    }

    public function eval( array $data )
    {
        return isset( $data[ $this->varName ] ) ? $data[ $this->varName ] : null;
    }

    public function __toString()
    {
        return strval( $this->varName );
    }

    protected $varName;

}

class ExpressionValue extends Expression
{

    public function __construct( $value )
    {
        $this->value = $value;
    }

    public function eval( array $data )
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

    public function __construct( Expression $child = null )
    {
        parent::__construct( $child );
    }

    public function eval( $data )
    {
        return mb_strlen( strval( $this->child->eval( $data ) ) );
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

    public function eval( $data )
    {
        return !( boolval( $this->child->eval( $data ) ) );
    }

    public function __toString()
    {
        return sprintf( "!%s", $this->child );
    }

}

// ****************** End Concrete ExpressionFunction Classes
// ****************** Concrete Expressions Classes
class ExpressionAndGroup extends ExpressionGroup
{

    public function __construct( $evaluateAll = false )
    {
        $this->evaluateAll = $evaluateAll;
    }

    public function eval( $data )
    {
        $eval = null;
        foreach ( $this->children as $child )
        {
            if ( $eval === false && $this->evaluateAll === false ) // early end of eval for and
                return $eval;
            $currEval = boolval( $child->eval( $data ) );
            $eval     = is_null( $eval ) ? $currEval : $eval && $currEval;
        }
        return $eval;
    }

    public function __toString()
    {
        return "( " . implode( " && ", $this->children ) . " )";
    }

    /**
     *
     * @var bool 
     */
    protected $evaluateAll;

}

class ExpressionOrGroup extends ExpressionGroup
{

    public function eval( $data )
    {
        $eval = null;
        foreach ( $this->children as $child )
        {
            $currEval = boolval( $child->eval( $data ) );
            $eval     = is_null( $eval ) ? $currEval : $eval || $currEval;
        }
        return $eval;
    }

    public function __toString()
    {
        return "( " . implode( " || ", $this->children ) . " )";
    }

}

// ****************** End Concrete Expressions Classes
// ****************** Concrete ExpressionComparison Classes
class ExpressionEquals extends ExpressionComparison
{

    public function __construct( Expression $left = null, Expression $right = null )
    {
        parent::__construct( $left, $right );
    }

    public function eval( $data )
    {
        return $this->left->eval( $data ) == $this->right->eval( $data );
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

    public function eval( $data )
    {
        return $this->left->eval( $data ) != $this->right->eval( $data );
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

    public function eval( $data )
    {
        return $this->left->eval( $data ) > $this->right->eval( $data );
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

    public function eval( $data )
    {
        return $this->left->eval( $data ) >= $this->right->eval( $data );
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

    public function eval( $data )
    {
        return $this->left->eval( $data ) < $this->right->eval( $data );
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

    public function eval( $data )
    {
        return $this->left->eval( $data ) <= $this->right->eval( $data );
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

    public function eval( $data )
    {
        $subject = $this->left->eval( $data );
        $pattern = $this->right->eval( $data );
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

    public function eval( $data )
    {
        $result = filter_var( $this->child->eval( $data ), FILTER_VALIDATE_EMAIL );
        return $result !== false ? true : false;
    }

    public function __toString()
    {
        return sprintf( "isEmail(%s)", $this->child );
    }

}

// ****************** End Prepared Expression Classes
class Expressions extends ExpressionOrGroup implements Interfaces\Expressions
{

    const EQ      = 0;
    const NE      = 1;
    const GT      = 2;
    const GE      = 3;
    const LT      = 4;
    const LE      = 5;
    const MATCHES = 6;
    const AND     = 7;
    const OR      = 8;

    function __construct( Expressions $parent = null )
    {
        $this->parent = $parent;
    }

    function var( $varName )
    {
        $this->handleNewExpression( new ExpressionVariable( $varName ) );
        return $this;
    }

    function val( $value )
    {
        $this->handleNewExpression( new ExpressionValue( $value ) );
        return $this;
    }

    function length( $varName )
    {
        $this->handleNewExpression( new ExpressionStringLength( new ExpressionVariable( $varName ) ) );
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

    protected function handleNewExpression( Expression $exp )
    {
        if ( is_null( $this->left ) )
            $this->left = $exp;
        else
        {
            $this->comparison->setLeft( $this->left );
            $this->comparison->setRight( $exp );
            $this->add( $this->comparison );
            $this->left       = null;
            $this->comparison = null;
        }
    }

    public function add( Expression $child )
    {

        if ( $this->andSubGroup )
            $this->andSubGroup->add( $child );
        else
            parent::add( $child );
    }

    public function and( $evaluateAll = false )
    {
        if ( is_null( $this->andSubGroup ) )
        {
            $this->andSubGroup = new ExpressionAndGroup( $evaluateAll );
            $this->children[]  = $this->andSubGroup;
        }
        return $this;
    }

    public function or()
    {
        if ( $this->andSubGroup )
            $this->andSubGroup = null;
        return $this;
    }

    public function group()
    {
        $group = new Expressions( $this );
        $this->add( $group );
        return $group;
    }

    public function closeGroup()
    {
        return $this->parent;
    }

    public function __toString()
    {
        $prefix = $this->parent ? "( " : null;
        $suffix = $this->parent ? " )" : null;
        return $prefix . implode( " || ", $this->children ) . $suffix;
    }

    /**
     *
     * @var Expressions
     */
    protected $parent;

    // STATE

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
     * @var ExpressionAndGroup
     */
    protected $andSubGroup;

}
