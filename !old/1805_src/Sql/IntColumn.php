<?php

namespace qck\Sql;

/**
 *
 * @author muellerm
 */
class IntColumn extends Abstracts\Column
{

  public function __construct( $Name )
  {
    parent::__construct( $Name );
  }

  public function getDatatype( Interfaces\DbDictionary $DbDictionary )
  {
    return $DbDictionary->getIntDatatype();
  }
}
