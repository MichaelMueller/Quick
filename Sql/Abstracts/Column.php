<?php

namespace qck\Sql\Abstracts;

/**
 *
 * @author muellerm
 */
abstract class Column implements \qck\Sql\Interfaces\Column
{

  abstract function getDatatype( \Qck\Interfaces\DbDictionary $DbDictionary );

  function __construct( $Name )
  {
    $this->Name = $Name;
  }

  function toSql( \Qck\Interfaces\DbDictionary $DbDictionary )
  {
    $Elements = [ $this->Name, $this->getDatatype( $DbDictionary ) ];
    return implode( " ", $Elements );
  }

  function getName()
  {
    return $this->Name;
  }

  protected $Name;

}
