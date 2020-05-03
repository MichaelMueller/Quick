<?php

namespace Qck\Expressions;

/**
 *
 * @author muellerm
 */
class Strlen extends SingleParamFunction
{

    function __construct( Interfaces\ValueExpression $Param = null )
    {
        parent::__construct( $Param );
    }

    public function toSql( \Qck\Interfaces\SqlDialect $Dictionary,
                           array &$Params = array () )
    {
        return $Dictionary->getStrlenFunctionName() . " ( " . $this->Param->toSql( $Dictionary, $Params ) . " ) ";
    }

    public function runFunction( $Value )
    {
        return mb_strlen( $Value );
    }

    function __toString()
    {
        return "strlen ( " . $this->Param->__toString() . " )";
    }

}
