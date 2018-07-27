<?php

namespace qck\Data;

/**
 *
 * @author muellerm
 */
class LazyLoader extends UuidProvider implements Interfaces\UnloadedObject
{

  function __construct( $Fqcn, $Uuid, Interfaces\Db $ObjectDb = null )
  {
    parent::__construct( $Fqcn, $Uuid );
    $this->ObjectDb = $ObjectDb;
  }

  function setDb( Interfaces\Db $ObjectDb )
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
