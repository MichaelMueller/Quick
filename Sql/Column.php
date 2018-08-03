<?php

namespace Qck\Sql;

/**
 *
 * @author muellerm
 */
abstract class Column implements \Qck\Interfaces\Sql\Column
{

  abstract function getDatatype( \Qck\Interfaces\Sql\DbDialect $SqlDbDialect );

  function __construct( $Name )
  {
    $this->Name = $Name;
  }

  function toSql( \Qck\Interfaces\Sql\DbDialect $SqlDbDialect )
  {
    $Elements = [ $this->Name, $this->getDatatype( $SqlDbDialect ) ];
    return implode( " ", $Elements );
  }

  function getName()
  {
    return $this->Name;
  }

  protected $Name;

}
