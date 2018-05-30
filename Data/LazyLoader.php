<?php

namespace qck\Data;

/**
 *
 * @author muellerm
 */
class LazyLoader implements Interfaces\UnloadedObject
{

  function __construct( $Fqcn, $Id, Interfaces\ObjectDb $ObjectDb = null )
  {
    $this->Fqcn = $Fqcn;
    $this->Id = $Id;
    $this->ObjectDb = $ObjectDb;
  }

  function setDb( ObjectDb $ObjectDb )
  {
    $this->ObjectDb = $ObjectDb;
  }

  function getId()
  {
    return $this->Id;
  }

  public function load()
  {
    return $this->ObjectDb->load( $this->Fqcn, $this->Id );
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
  protected $Id;

  /**
   *
   * @var Interfaces\ObjectDb
   */
  protected $ObjectDb;

}
