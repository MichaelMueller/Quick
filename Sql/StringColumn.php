<?php

namespace qck\Sql;

/**
 *
 * @author muellerm
 */
class StringColumn extends Abstracts\Column
{

  const TINYTEXT = 255;
  const TEXT = 65535;
  const MEDIUMTEXT = 16777215;
  const LONGTEXT = 4294967295;

  function __construct( $Name, $MinLength = 0, $MaxLength = self::TINYTEXT, $Blob = false )
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
