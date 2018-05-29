<?php

namespace qck\StructuredData\Abstracts;

/**
 *
 * @author muellerm
 */
abstract class Select
{

  function setExpression( \qck\Expressions\Interfaces\Expression $Expression )
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
   * @var \qck\Expressions\Interfaces\Expression
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
