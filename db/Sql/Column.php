<?php

namespace qck\db\Sql;

/**
 *
 * @author muellerm
 */
class Column
{

  function __construct( $Name, $DataType, $Null, $Default )
  {
    $this->Name = $Name;
    $this->DataType = $DataType;
    $this->Null = $Null;
    $this->Default = $Default;
  }

  protected $Name;
  protected $DataType;
  protected $Null;
  protected $Default;

}
