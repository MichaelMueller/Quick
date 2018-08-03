<?php

namespace Qck\Sql;

/**
 *
 * @author muellerm
 */
class FloatColumn extends Column
{

  function __construct( $Name )
  {
    parent::__construct( $Name );
  }

  public function getDatatype( \Qck\Interfaces\Sql\DbDialect $SqlDbDialect )
  {
    return $SqlDbDialect->getFloatDatatype();
  }

  protected $MinLength;
  protected $MaxLength;

}
