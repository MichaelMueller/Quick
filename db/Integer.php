<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
class Integer extends Property
{

  public function __construct( $Id, $Name )
  {
    parent::__construct( $Id, $Name );
  }
  function getName()
  {
    return $this->Name;
  }

  protected $Name;

}
