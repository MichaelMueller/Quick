<?php

namespace Qck\Expressions;

class ArrayElement implements \Qck\Interfaces\Expressions\ValueExpression 
{
    
    function __construct( $keys )
    {
        $this->keys = $keys;
    }

    public function get( array $array )
    {
        return $this->getRecursive( $this->keys, $array );
    }

    function getRecursive( $subKeys, array $subArray )
    {
        $currKey = array_shift( $subKeys );
        if ( isset( $subArray[ $currKey ] ) )
        {
            if ( count( $subKeys ) > 0 && is_array( $subArray[ $currKey ] ) )
                return $this->getRecursive( $subKeys, $subArray[ $currKey ] );
            else
                return $subArray[ $currKey ];
        }
        else
            return null;
    }

    /**
     *
     * @var string[]
     */
    protected $keys;

}