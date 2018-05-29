<?php

namespace qck\GraphStorage;

/**
 *
 * @author muellerm
 */
class NodeLazyLoader implements UnloadedNode
{

  function __construct( $Fqcn, $Id, \NodeDb $NodeDb = null )
  {
    $this->Fqcn = $Fqcn;
    $this->Id = $Id;
    $this->NodeDb = $NodeDb;
  }

  function setNodeDb( NodeDb $NodeDb )
  {
    $this->NodeDb = $NodeDb;
  }

  function unsetNodeDb()
  {
    $this->NodeDb = null;
  }

  function getId()
  {
    return $this->Id;
  }

  public function load()
  {
    return $this->NodeDb->load( $this->Fqcn, $this->Id );
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
   * @var NodeDb 
   */
  protected $NodeDb;

}
