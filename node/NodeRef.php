<?php

namespace qck\node;

/**
 *
 * @author muellerm
 */
class NodeRef implements interfaces\NodeRef
{

  function __construct( $Uuid, interfaces\NodeDb $NodeDb )
  {
    $this->Uuid = $Uuid;
    $this->NodeDb = $NodeDb;
  }

  function getUuid()
  {
    return $this->Uuid;
  }

  public function getNode()
  {
    return $this->NodeDb->get($this->Uuid);
  }

  protected $Uuid;

  /**
   *
   * @var interfaces\NodeDb
   */
  protected $NodeDb;

}
