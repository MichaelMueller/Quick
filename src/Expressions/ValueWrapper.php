<?php

namespace Qck\Expressions;

class ValueWrapper implements \Qck\Interfaces\Expressions\ValueExpression
{

    function __construct( $value )
    {
        $this->value = $value;
    }

    public function get( array $array )
    {
        return $this->value;
    }

    /**
     *
     * @var int|float|bool|string
     */
    public $value;

}