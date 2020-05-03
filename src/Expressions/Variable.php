<?php

namespace Qck\Expressions;

/**
 *
 * @author muellerm
 */
class Variable implements \Qck\Interfaces\Expressions\ValueExpression
{

    static function create( $Name, $FilterOut = false )
    {
        return new Variable( $Name, $FilterOut );
    }
    function __construct( $Name, $FilterOut = false )
    {
        $this->Name      = $Name;
        $this->FilterOut = $FilterOut;
    }

    function getName()
    {
        return $this->Name;
    }

    public function toSql( \Qck\Interfaces\SqlDialect $Dictionary,
                           array &$Params = array () )
    {
        return $this->Name;
    }

    function getValue( array $Data = [], array &$FilteredData = [] )
    {
        $Value                       = isset( $Data[ $this->Name ] ) ? $Data[ $this->Name ] : null;
        if ( $this->FilterOut == false )
            $FilteredData[ $this->Name ] = $Value;
        return $Value;
    }

    function __toString()
    {
        return $this->Name;
    }

    /**
     *
     * @var string
     */
    protected $Name;

    /**
     *
     * @var bool
     */
    protected $FilterOut;

}
