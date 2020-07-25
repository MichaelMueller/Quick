<?php

namespace Qck\Expressions;

class Regex implements \Qck\Interfaces\Expressions\ValueExpression
{

    function __construct( ValueExpression $val, $pattern )
    {
        $this->val     = $val;
        $this->pattern = $pattern;
    }

    public function get( array $array )
    {
        return preg_match( $this->pattern, $this->val->get( $array ) );
    }

    /**
     *
     * @var ValueExpression 
     */
    protected $val;

    /**
     *
     * @var string 
     */
    protected $pattern;

}
