<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class DataNode implements Interfaces\Node
{

  
  
  public function hasChanged()
  {
    return $this->Changed;
  }

  public function setUnchanged()
  {
    $this->Changed = false;    
  }

  function getData()
  {
    return $this->Data;
  }

  function setData($Data)
  {
    $this->Data    = $Data;
    $this->Changed = true;
  }

  /**
   *
   * @var array the actual data
   */
  protected $Data    = [];
  protected $Changed = false;

}
