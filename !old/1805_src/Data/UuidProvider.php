<?php

namespace qck\Data;

/**
 *
 * @author muellerm
 */
class UuidProvider implements Interfaces\UuidProvider
{

  function __construct( $Fqcn, $Uuid )
  {
    $this->Fqcn = $Fqcn;
    $this->Uuid = $Uuid;
  }
  
  function getUuid()
  {
    return $this->Uuid;
  }
  
  public function getFqcn()
  {
    return $this->Fqcn;
  }

  /**
   *
   * @var string 
   */
  protected $Fqcn;

  /**
   *
   * @var int 
   */
  protected $Uuid;

}
