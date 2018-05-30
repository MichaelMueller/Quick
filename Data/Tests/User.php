<?php

namespace qck\Data\Tests;

/**
 *
 * @author muellerm
 */
class User extends \qck\Data\Abstracts\Object
{

  public function __construct( $Uuid = null )
  {
    parent::__construct( $Uuid );

    $this->Data[ "Organisations" ] = new \qck\Data\Vector();
  }

  function setName( $Name )
  {
    $this->Data[ "Name" ] = $Name;
    $this->Version++;
  }

  /**
   * 
   * @return \qck\Data\Vector
   */
  function getOrganisations()
  {
    print spl_object_hash( $this->Data[ "Organisations" ] ) . PHP_EOL;
    return $this->Data[ "Organisations" ];
  }
}
