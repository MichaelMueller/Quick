<?php

namespace Qck\Sql;

/**
 *
 * @author muellerm
 */
class IntColumn extends Column
{

  public function __construct( $Name )
  {
    parent::__construct( $Name );
  }

  public function getDatatype( \Qck\Interfaces\Sql\DbDialect $SqlDbDialect )
  {
    return $SqlDbDialect->getIntDatatype();
  }
}
