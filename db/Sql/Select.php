<?php

namespace qck\db\Sql;

/**
 *
 * @author muellerm
 */
interface Select
{

  function getTableName();
  function getExpression();
  function getOffset();
  function getLimit();
  function getOrderByColName();
  function isDescendingOrder();
  function shouldReturnFirst();
  
}
