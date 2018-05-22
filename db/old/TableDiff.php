<?php

namespace qck\db;

/**
 * Description of TableDefinition
 *
 * @author muellerm
 */
class TableDiff
{
  function getAddedColumns()
  {
    return $this->AddedColumns;
  }

  function getModifiedColumns()
  {
    return $this->ModifiedColumns;
  }

  function getDroppedColumns()
  {
    return $this->DroppedColumns;
  }

  function getRenamedColumns()
  {
    return $this->RenamedColumns;
  }
  
  public $AddedColumns=[];
  public $ModifiedColumns=[];
  public $DroppedColumns=[];
  public $RenamedColumns=[];
}
