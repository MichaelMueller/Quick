<?php

namespace qck\GraphStorage\Sql;

/**
 *
 * @author muellerm
 */
class Column
{

  function __construct( $Name, $DataType, $Null=true, $Default=false )
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
