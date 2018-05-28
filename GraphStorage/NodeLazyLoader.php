<?php

namespace qck\GraphStorage;

/**
 *
 * @author muellerm
 */
class NodeLazyLoader implements UnloadedNode
{

  function __construct( $Uuid, \NodeLoader $NodeLoader = null )
  {
    $this->Uuid = $Uuid;
    $this->NodeLoader = $NodeLoader;
  }

  function setNodeLoader( NodeLoader $NodeLoader )
  {
    $this->NodeLoader = $NodeLoader;
  }

  function unsetNodeLoader()
  {
    $this->NodeLoader = null;
  }

  function getUuid()
  {
    return $this->Uuid;
  }

  public function load()
  {
    return $this->NodeLoader->load( $this->Uuid );
  }

  protected $Uuid;

  /**
   *
   * @var NodeLoader 
   */
  protected $NodeLoader;

}
