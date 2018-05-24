<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
abstract class Property
{

  /**
   * @return Sql\Column
   */
  abstract function toSqlColumn( Sql\DatabaseDictionary $Dict );

  function __construct( $Name )
  {
    $this->Name = $Name;
  }

  function getName()
  {
    return $this->Name;
  }

  protected $Name;

}
