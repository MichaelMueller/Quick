<?php

namespace Qck\Expression;

/**
 *
 * @author muellerm
 */
abstract class SingleParamFunction implements Interfaces\SingleParamFunction
{

  abstract function runFunction( $Value );

  function __construct( Interfaces\ValueExpression $Param )
  {
    $this->Param = $Param;
  }

  function getParam()
  {
    return $this->Param;
  }

  function getValue( array $Data = [], array &$FilteredData = [] )
  {
    return $this->runFunction( $this->Param->getValue( $Data, $FilteredData ));
  }

  
  /**
   *
   * @var Interfaces\ValueExpression
   */
  protected $Param;

}
