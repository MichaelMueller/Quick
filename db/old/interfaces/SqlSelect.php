<?php

namespace qck\db\interfaces;

/**
 *
 * @author muellerm
 */
interface SqlSelect
{

  const ASC = 0;
  const DESC = 1;

  function getTableName();
  function getExpression();
  function getOffset();
  function getLimit();
  function getOrderByColName();
  function getOrder();
  function shouldReturnFirst();
  
}
