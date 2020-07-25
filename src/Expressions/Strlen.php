<?php

namespace Qck\Expressions;

class Strlen implements \Qck\Interfaces\Expressions\ValueExpression
{

    function __construct( ValueExpression $val )
    {
        $this->val = $val;
    }

    public function get( array $array )
    {
        return mb_strlen( $this->val->get( $array ) );
    }

    /**
     *
     * @var ValueExpression 
     */
    protected $val;

}
