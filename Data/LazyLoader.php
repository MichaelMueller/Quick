<?php

namespace qck\Data;

/**
 *
 * @author muellerm
 */
class LazyLoader extends UuidProvider implements Interfaces\UnloadedObject
{

  function __construct( $Fqcn, $Uuid, Interfaces\ObjectDb $ObjectDb = null )
  {
    parent::__construct( $Fqcn, $Uuid );
    $this->ObjectDb = $ObjectDb;
  }

  function setDb( Interfaces\ObjectDb $ObjectDb )
  {
    $this->ObjectDb = $ObjectDb;
  }

  public function load()
  {
    return $this->ObjectDb->load( $this->Fqcn, $this->Uuid );
  }

  /**
   *
   * @var Interfaces\ObjectDb
   */
  protected $ObjectDb;

}
