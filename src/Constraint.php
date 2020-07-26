<?php

namespace Qck;

class Constraint implements Interfaces\Constraint
{

    const AND = 0;
    const OR  = 1;
    
    function __construct( string $varName, Constraint $parent = null, string $error = null )
    {
        $this->key = $varName;
        $this->parent   = $parent;
        $this->error    = $error;
    }

    // tree navigation
    public function and( $varName = null, $error = null )
    {
        $this->next = new Constraint($varName, $this->parent, $error);
        $this->nextConcatType = self::AND;
        return $this->next;
    }

    public function or( $varName = null, $error = null ): Interfaces\Constraint
    {
        
        $this->next = new Constraint($varName, $this->parent, $error);
        $this->nextConcatType = self::OR;
        return $this->next;
    }

    public function child( $varName = null, $error = null ): Interfaces\Constraint
    {        
        $this->child = new Constraint($varName, $this, $error);
        return $this->child;
    }

    public function parent()
    {
        return $this->parent;
    }

    public function eq(): Interfaces\Constraint
    {
        
    }

    public function eval( $data ): array
    {
    }

    public function length(): Interfaces\Constraint
    {
        
    }

    public function matches(): Interfaces\Constraint
    {
        
    }

    public function neq(): Interfaces\Constraint
    {
        
    }

    public function val( $value ): Interfaces\Constraint
    {
        
    }

    public function var( $varName ): Interfaces\Constraint
    {
        
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

    /**
     *
     * @var string
     */
    protected $error;

    /**
     *
     * @var Constraint
     */
    protected $next;

    /**
     *
     * @var int
     */
    protected $nextConcatType;

    /**
     *
     * @var Constraint
     */
    protected $child;
    
    // state
    /**
     *
     * @var ConstraintComparator
     */
    protected $comparator;

}

abstract class ConstraintComparator
{

    protected $varName;
    public $otherVarName;
}


abstract class ComparisonsItem implements Interfaces\BoolExpression
{

    // Special Types
    const AND = 0;
    const OR  = 1;

    function __construct( Constraint $parent, string $error, int $concatType, bool $negate = false )
    {
        $this->parent     = $parent;
        $this->error      = $error;
        $this->concatType = $concatType;
        $this->negate     = $negate;
    }

    function evalWithPrevious( $previousEval )
    {

        if ( $this->concatType == self::AND && $previousEval === false )
            return false;

        if ( is_null( $previousEval ) )
            $eval = $currentEval;
        else
            $eval = $concatType == self::AND ? $eval && $currentEval : $eval || $currentEval;
    }

    /**
     *
     * @var Constraint
     */
    protected $parent;

    /**
     *
     * @var string
     */
    protected $error;

    /**
     *
     * @var int
     */
    protected $concatType;

    /**
     *
     * @var bool
     */
    protected $negate;

}

/**
 * not/ values
 * @author muellerm
 */
class Comparisons extends ComparisonsItem implements Interfaces\Comparisons
{

    public function __construct( Constraint $parent = null, $error = null, $concatType = null, $negate = false )
    {
        parent::__construct( $parent, $error, $concatType, $negate );
    }

    public function eval( $array )
    {
        $eval = null;
        /*
          if ( !isset( $array[ $this->error ] ) )
          return $eval;

          $val = $array[ $this->error ];
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

          if ( is_null( $eval ) )
          $eval = $currentEval;
          else
          $eval = $concatType == self::AND ? $eval && $currentEval : $eval || $currentEval;
          } */
        return $eval;
    }

    public function parantheses( $error = null )
    {
        $this->addItem( new Comparisons( $this, $error, $this->concatTypeForNext(), $this->negate ) );
        return $this;
    }

    public function close()
    {
        return $this->parent;
    }

    public function not()
    {
        $this->negate = true;
        return $this;
    }

    public function and()
    {
        $this->concatTypeForNext = self::AND;
        return $this;
    }

    public function or()
    {
        $this->concatTypeForNext = self::OR;
        return $this;
    }

    public function compare( $varName, $error = null )
    {
        
    }

    protected function addItem( ComparisonsItem $item )
    {
        $this->items[]           = $item;
        $this->concatTypeForNext = null;
        $this->negate            = false;
    }

    protected function concatTypeForNext()
    {
        if ( count( $this->items ) > 0 )
            return is_null( $this->concatTypeForNext ) ? self::AND : $this->concatTypeForNext;
        else
            return null;
    }

    /**
     *
     * @var Constraint
     */
    protected $parent;

    /**
     *
     * @var string
     */
    protected $error;

    // state

    /**
     *
     * @var ComparisonsItem[]
     */
    protected $items = [];

    /**
     *
     * @var int
     */
    protected $concatTypeForNext;

    /**
     *
     * @var bool
     */
    protected $negateNext = false;

}
