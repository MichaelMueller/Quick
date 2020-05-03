<?php

namespace Qck\Expressions;

use \Qck\Interfaces\Expressions\ValueExpression as IValueExpression;

/**
 *
 * @author muellerm
 */
class ClassExists implements \Qck\Interfaces\Expressions\BooleanExpression
{

    static function create( IValueExpression $ClassNameValue, $Namespace )
    {
        return new ClassExists( $ClassNameValue, $Namespace );
    }

    function __construct( IValueExpression $ClassNameValue, $Namespace )
    {
        $this->ClassNameValue = $ClassNameValue;
        $this->Namespace      = $Namespace;
    }

    public function __toString()
    {
        return "class_exists(" . $this->Namespace . "\\" . strval( $this->ClassNameValue ) . ", true)";
    }

    public function evaluate( array $Data, &$FilteredArray = array (), &$FailedExpressions = array () )
    {
        $Exists              = class_exists( $this->Namespace . "\\" . $this->ClassNameValue->getValue( $Data, $FilteredArray ), true );
        if ( !$Exists )
            $FailedExpressions[] = $this;
        return $Exists;
    }

    public function toSql( \Qck\Interfaces\SqlDialect $SqlDbDialect, array &$Params = array () )
    {
        throw new \RuntimeException( "This expression is not convertable to SQL" );
    }

    /**
     *
     * @var IValueExpression
     */
    protected $ClassNameValue;

    /**
     *
     * @var string
     */
    protected $Namespace;

}
