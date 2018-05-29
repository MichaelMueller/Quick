<?php

namespace qck\StructuredData\Interfaces;

/**
 *
 * @author muellerm
 */
interface NodeSelect
{

  /**
   * @return string
   */
  function getFqcn();

  /**
   * @return \qck\Expressions\Interfaces\Expression
   */
  function getExpression();

  /**
   * @return int
   */
  function getOffset();

  /**
   * @return int
   */
  function getLimit();

  /**
   * @return string
   */
  function getOrderProp();

  /**
   * @return bool
   */
  function isDescendingOrder();
}
