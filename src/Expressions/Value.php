<?php

namespace Qck\Expressions;

/**
 *
 * @author muellerm
 */
class Value implements \Qck\Interfaces\Expressions\ValueExpression
{

    static function create( $Value )
    {
        return new Value( $Value );
    }

    function __construct( $Value )
    {
        $this->Value = $Value;
    }

    function getValue( array $Data = [], array &$FilteredData = [] )
    {
        return $this->Value;
    }

    public function toSql( \Qck\Interfaces\SqlDialect $Dictionary,
                           array &$Params = array () )
    {
        $Value = $this->Value;
        if ( is_bool( $Value ) )
            $Value == true ? $Dictionary->getTrueLiteral() : $Dictionary->getFalseLiteral();

        $Params[] = $Value;
        return "?";
    }

    function __toString()
    {
        return strval( $this->Value );
    }

    /**
     *
     * @var mixed
     */
    protected $Value;

}
