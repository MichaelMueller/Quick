<?php

namespace qck\Data\Tests;

/**
 *
 * @author muellerm
 */
class User extends \qck\Data\Abstracts\Object
{

  function setName( $Name )
  {
    $this->Data[ "Name" ] = $Name;
    $this->Data[ "Organisations" ] = new \qck\Data\Vector();
    $this->Version++;
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
