<?php

namespace qck\db\expressions;

/**
 *
 * @author muellerm
 */
abstract class SingleParamFunction extends ValueExpression
{

  function __construct( ValueExpression $Param )
  {
    $this->Param = $Param;
  }

  function getParam()
  {
    return $this->Param;
  }

  /**
   *
   * @var ValueExpression
   */
  protected $Param;

}
