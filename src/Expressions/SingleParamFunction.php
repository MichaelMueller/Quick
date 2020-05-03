<?php

namespace Qck\Expressions;

/**
 *
 * @author muellerm
 */
class SingleParamFunction implements \Qck\Interfaces\Expressions\ValueExpression
{

    const STRLEN = "strlen";

    static function createStrlen( \Qck\Interfaces\Expressions\ValueExpression $Param )
    {
        return new SingleParamFunction( self::STRLEN, $Param );
    }

    protected function __construct( $FunctionName, \Qck\Interfaces\Expressions\ValueExpression $Param )
    {
        $this->FunctionName = $FunctionName;
        $this->Param        = $Param;
    }

    function getValue( array $Data = [], array &$FilteredData = [] )
    {
        $Value = null;
        if ( $this->FunctionName == self::STRLEN )
            $Value = mb_strlen( $this->Param->getValue( $Data, $FilteredData ) );

        return $Value;
    }

    public function toSql( \Qck\Interfaces\SqlDialect $Dictionary,
                           array &$Params = array () )
    {
        if ( $this->FunctionName == self::STRLEN )
            $SqlFunctionName = $Dictionary->getStrlenFunctionName();
        return $SqlFunctionName . " ( " . $this->Param->toSql( $Dictionary, $Params ) . " ) ";
    }

    function __toString()
    {
        return $this->FunctionName . " ( " . $this->Param->__toString() . " )";
    }

    /**
     *
     * @var string
     */
    protected $FunctionName;

    /**
     *
     * @var \Qck\Interfaces\Expressions\ValueExpression
     */
    protected $Param;

}
