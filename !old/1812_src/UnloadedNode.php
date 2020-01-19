<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class NodeLoader implements \Qck\Interfaces\NodeLoader
{

  function __construct($Uuid, $Fqcn, Interfaces\NodeLoader $NodeLoader)
  {
    $this->Uuid       = $Uuid;
    $this->Fqcn       = $Fqcn;
    $this->NodeLoader = $NodeLoader;
  }

  function getUuid()
  {
    return $this->Uuid;
  }

  public function load()
  {
    return $this->NodeLoader->load($this->Uuid);
  }

  public function getFqcn()
  {
    return $this->Fqcn;
  }

  public function getData()
  {
    return [];
  }

  public function setData(array $Data)
  {
    
  }

  public function setUuid($Uuid)
  {
    $this->Uuid = $Uuid;
  }

  /**
   *
   * @var string 
   */
  protected $Uuid;

  /**
   *
   * @var string 
   */
  protected $Fqcn;

  /**
   *
   * @var Interfaces\NodeLoader
   */
  protected $NodeLoader;

}
