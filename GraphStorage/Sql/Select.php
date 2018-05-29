<?php

namespace qck\GraphStorage\Sql;

/**
 *
 * @author muellerm
 */
class Select
{

  function __construct( $Fqcn, Expression $Expression=null )
  {
    $this->Fqcn = $Fqcn;
    $this->Expression = $Expression;
  }

  function setExpression( Expression $Expression )
  {
    $this->Expression = $Expression;
  }

  function setOffset( $Offset )
  {
    $this->Offset = $Offset;
  }

  function setLimit( $Limit )
  {
    $this->Limit = $Limit;
  }

  function setOrderCol( $OrderCol )
  {
    $this->OrderCol = $OrderCol;
  }

  function setDescending( $Descending )
  {
    $this->Descending = $Descending;
  }

  function getFqcn()
  {
    return $this->Fqcn;
  }

  function getExpression()
  {
    return $this->Expression;
  }

  function getOffset()
  {
    return $this->Offset;
  }

  function getLimit()
  {
    return $this->Limit;
  }

  function getOrderCol()
  {
    return $this->OrderCol;
  }

  function getDescending()
  {
    return $this->Descending;
  }

  /**
   *
   * @var string
   */
  protected $Fqcn;

  /**
   *
   * @var Expression
   */
  protected $Expression;

  /**
   *
   * @var int
   */
  protected $Offset = -1;

  /**
   *
   * @var int
   */
  protected $Limit = -1;

  /**
   *
   * @var string
   */
  protected $OrderCol;

  /**
   *
   * @var bool
   */
  protected $Descending = false;

}
