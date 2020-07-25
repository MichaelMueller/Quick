<?php

namespace Qck\Expressions;

class Not implements \Qck\Interfaces\Expressions\BooleanExpression
{

    function __construct( BooleanExpression $booleanExpression )
    {
        $this->booleanExpression = $booleanExpression;
    }

    public function eval( array $array )
    {
        return !$this->booleanExpression->eval( $array );
    }

    /**
     *
     * @var BooleanExpression
     */
    protected $booleanExpression;

}
