<?php

namespace qck\Sql;

/**
 *
 * @author muellerm
 */
class StringColumn extends Abstracts\Column
{

  function __construct( $Name, $MinLength = 0, $MaxLength = 255, $Blob = false )
  {
    parent::__construct( $Name );
    $this->MinLength = $MinLength;
    $this->MaxLength = $MaxLength;
    $this->Blob = $Blob;
  }

  public function getDatatype( Interfaces\DbDictionary $DbDictionary )
  {
    return $DbDictionary->getStringDatatype( $this->MinLength, $this->MaxLength, $this->Blob );
  }

  protected $MinLength;
  protected $MaxLength;
  protected $Blob;

}
