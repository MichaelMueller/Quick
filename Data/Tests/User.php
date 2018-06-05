<?php

namespace qck\Data\Tests;

/**
 *
 * @author muellerm
 */
class User extends \qck\Data\Object
{

  public function __construct()
  {
    $this->Data[ "Organisations" ] = new \qck\Data\Vector();
  }

  function setName( $Name )
  {
    $this->Data[ "Name" ] = $Name;
    $this->setModified();
  }

  /**
   * 
   * @return \qck\Data\Vector
   */
  function getOrganisations()
  {
    return $this->Data[ "Organisations" ];
  }
}
