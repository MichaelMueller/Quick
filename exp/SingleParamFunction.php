<?php

namespace qck\exp;

/**
 *
 * @author muellerm
 */
abstract class SingleParamFunction extends AtomicExpression
{

  function __construct( AtomicExpression $Param )
  {
    $this->Param = $Param;
  }

  function getParam()
  {
    return $this->Param;
  }

  /**
   *
   * @var AtomicExpression
   */
  protected $Param;

}
