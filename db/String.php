<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
class String extends Property
{

  public function __construct( $Name )
  {
    parent::__construct( $Name );
  }

  function getName()
  {
    return $this->Name;
  }

  protected $Name;

}
