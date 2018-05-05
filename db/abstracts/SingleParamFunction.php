<?php

namespace qck\db\abstracts;

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
