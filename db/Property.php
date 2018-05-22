<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
abstract class Property
{

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
