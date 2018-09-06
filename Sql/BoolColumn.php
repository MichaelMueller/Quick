<?php

namespace Qck\Sql;

/**
 *
 * @author muellerm
 */
class BoolColumn extends Column
{

  function __construct( $name )
  {
    parent::__construct($name);
  }

  public function getDatatype( \Qck\Interfaces\Sql\DbDialect $SqlDbDialect )
  {
    return $SqlDbDialect->getBoolDatatype();
  }

  public function createExpression()
  {
    return new \Qck\Expressions\True_();
  }
}
