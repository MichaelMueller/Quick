<?php

namespace qck\Data;

/**
 *
 * @author muellerm
 */
class LazyLoader implements Interfaces\UnloadedObject
{

  function __construct( $Fqcn, $Uuid, Interfaces\ObjectDb $ObjectDb = null )
  {
    $this->Fqcn = $Fqcn;
    $this->Uuid = $Uuid;
    $this->ObjectDb = $ObjectDb;
  }

  function setDb( ObjectDb $ObjectDb )
  {
    $this->ObjectDb = $ObjectDb;
  }

  function getUuid()
  {
    return $this->Uuid;
  }

  public function load()
  {
    return $this->ObjectDb->load( $this->Fqcn, $this->Uuid );
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

  /**
   *
   * @var Interfaces\ObjectDb
   */
  protected $ObjectDb;

}
