<?php

namespace qck\Sql;

/**
 *
 * @author muellerm
 */
class FloatColumn extends Abstracts\Column
{

  function __construct( $Name )
  {
    parent::__construct( $Name );
  }

  public function getDatatype( Interfaces\DbDictionary $DbDictionary )
  {
    return $DbDictionary->getFloatDatatype();
  }

  protected $MinLength;
  protected $MaxLength;

}
