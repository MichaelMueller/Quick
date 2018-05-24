<?php

namespace qck\GraphStorage;

/**
 *
 * @author muellerm
 */
class Uuid
{

  function __construct( $Uuid )
  {
    $this->Uuid = $Uuid;
  }

  function getUuid()
  {
    return $this->Uuid;
  }

  protected $Uuid;

}
