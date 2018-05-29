<?php

namespace qck\Sql\Abstracts;

/**
 *
 * @author muellerm
 */
abstract class Column implements \qck\Sql\Interfaces\Column
{

  abstract function getDatatype( \qck\Sql\Interfaces\DbDictionary $DbDictionary );

  function __construct( $Name )
  {
    $this->Name = $Name;
  }

  function toSql( \qck\Sql\Interfaces\DbDictionary $DbDictionary )
  {
    $Elements = [ $this->Name, $this->getDatatype( $DbDictionary ) ];
    return implode( " ", $Elements );
  }

  protected $Name;
}
