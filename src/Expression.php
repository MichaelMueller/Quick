<?php

namespace Qck;

// ****************** Abstract Expression Classes
abstract class Expression implements Interfaces\Expression
{

    function __construct( Expression $parent = null )
    {
        $this->parent = $parent;
    }

    /**
     * 
     * @return Expression
     */
    function parent()
    {
        return $this->parent;
    }

    /**
     *
     * @var Expression
     */
    protected $parent;

}

abstract class ExpressionFunction extends Expression
{

    function __construct( Expression $child = null, Expression $parent = null )
    {
        parent::__construct( $parent );
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

abstract class ExpressionGroup implements Interfaces\Expression
{

    function __construct( Expression $parent = null )
    {
        parent::__construct( $parent );
    }

    function add( Expression $child )
    {
        $this->children[] = $child;
    }

    /**
     *
     * @var Expression
     */
    protected $parent;

    /**
     *
     * @var Expression[]
     */
    protected $children;

}

abstract class ExpressionComparison extends Expression
{

    function __construct( Expression $left = null, Expression $right = null, Expression $parent = null )
    {
        parent::__construct( $parent );
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

    public function __construct( $varName, Expression $parent = null )
    {
        parent::__construct( $parent );
        $this->varName = $varName;
    }

    public function eval( array $data )
    {
        return isset( $data[ $this->varName ] ) ? $data[ $this->varName ] : null;
    }

    protected $varName;

}

class ExpressionValue extends Expression
{

    public function __construct( $value, Expression $parent = null )
    {
        parent::__construct( $parent );
        $this->value = $value;
    }

    public function eval( array $data )
    {
        return $this->value;
    }

    protected $value;

}

// ****************** End Concrete Expression Classes
// ****************** Concrete ExpressionFunction Classes
class ExpressionStringLength extends ExpressionFunction
{

    public function __construct( Expression $child = null, Expression $parent = null )
    {
        parent::__construct( $child, $parent );
    }

    public function eval( $data )
    {
        return mb_strlen( strval( $this->child->eval( $data ) ) );
    }

}

class ExpressionNegate extends ExpressionFunction
{

    public function __construct( Expression $child = null, Expression $parent = null )
    {
        parent::__construct( $child, $parent );
    }

    public function eval( $data )
    {
        return !( boolval( $this->child->eval( $data ) ) );
    }

}

// ****************** End Concrete ExpressionFunction Classes
// ****************** Concrete ExpressionGroup Classes
class ExpressionAndGroup extends ExpressionGroup
{

    public function __construct( $evaluateAll = false, Expression $parent = null )
    {
        parent::__construct( $parent );
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

    /**
     *
     * @var bool 
     */
    protected $evaluateAll;

}

class ExpressionOrGroup extends ExpressionGroup
{

    public function __construct( Expression $parent = null )
    {
        parent::__construct( $parent );
    }

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

    /**
     *
     * @var bool 
     */
    protected $evaluateAll;

}

// ****************** End Concrete ExpressionGroup Classes
// ****************** Concrete ExpressionComparison Classes
class ExpressionEquals extends ExpressionComparison
{

    public function __construct( Expression $left = null, Expression $right = null, Expression $parent = null )
    {
        parent::__construct( $left, $right, $parent );
    }

    public function eval( $data )
    {
        return $this->left->eval( $data ) == $this->right->eval( $data );
    }

}

class ExpressionNotEquals extends ExpressionComparison
{

    public function __construct( Expression $left = null, Expression $right = null, Expression $parent = null )
    {
        parent::__construct( $left, $right, $parent );
    }

    public function eval( $data )
    {
        return $this->left->eval( $data ) != $this->right->eval( $data );
    }

}

class ExpressionGreater extends ExpressionComparison
{

    public function __construct( Expression $left = null, Expression $right = null, Expression $parent = null )
    {
        parent::__construct( $left, $right, $parent );
    }

    public function eval( $data )
    {
        return $this->left->eval( $data ) > $this->right->eval( $data );
    }

}

class ExpressionGreaterEquals extends ExpressionComparison
{

    public function __construct( Expression $left = null, Expression $right = null, Expression $parent = null )
    {
        parent::__construct( $left, $right, $parent );
    }

    public function eval( $data )
    {
        return $this->left->eval( $data ) >= $this->right->eval( $data );
    }

}

class ExpressionLess extends ExpressionComparison
{

    public function __construct( Expression $left = null, Expression $right = null, Expression $parent = null )
    {
        parent::__construct( $left, $right, $parent );
    }

    public function eval( $data )
    {
        return $this->left->eval( $data ) < $this->right->eval( $data );
    }

}

class ExpressionLessEquals extends ExpressionComparison
{

    public function __construct( Expression $left = null, Expression $right = null, Expression $parent = null )
    {
        parent::__construct( $left, $right, $parent );
    }

    public function eval( $data )
    {
        return $this->left->eval( $data ) <= $this->right->eval( $data );
    }

}

class ExpressionMatches extends ExpressionComparison
{

    public function __construct( $delimiter = "#", Expression $left = null, Expression $right = null, Expression $parent = null )
    {
        parent::__construct( $left, $right, $parent );
        $this->delimiter = $delimiter;
    }

    public function eval( $data )
    {
        $subject = $this->left->eval( $data );
        $pattern = $this->delimiter . $this->right->eval( $data ) . $this->delimiter;
        return preg_match( $pattern, $subject );
    }

    protected $delimiter;

}

// ****************** End Concrete ExpressionComparison Classes
// ****************** Prepared Expression Classes
class ExpressionAlphanumeric extends ExpressionMatches
{

    public function __construct( $delimiter = "#", Expression $left = null, Expression $parent = null )
    {
        parent::__construct( $delimiter, $left, new ExpressionValue( "[a-zA-z0-9]" ), $parent );
    }

}

class ExpressionEmail extends ExpressionFunction
{

    public function __construct( Expression $child = null, Expression $parent = null )
    {
        parent::__construct( $child, $parent );
    }

    public function eval( $data )
    {
        $result = filter_var( $this->child->eval( $data ), FILTER_VALIDATE_EMAIL );
        return $result !== false ? true : false;
    }

}

// ****************** End Prepared Expression Classes