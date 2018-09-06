<?php

namespace Qck\Sql;

use Qck\Expressions\Expression as x;

/**
 *
 * @author muellerm
 */
class StringColumn extends Column
{

  const TINYTEXT = 255;
  const TEXT = 65535;
  const MEDIUMTEXT = 16777215;
  const LONGTEXT = 4294967295;

  function __construct( $Name, $MinLength = 0, $MaxLength = self::TINYTEXT, $Blob = false,
                        $regex = null )
  {
    parent::__construct( $Name );
    $this->MinLength = $MinLength;
    $this->MaxLength = $MaxLength;
    $this->regex = $regex;
  }

  public function getDatatype( \Qck\Interfaces\Sql\DbDialect $SqlDbDialect )
  {
    return $SqlDbDialect->getStringDatatype( $this->MinLength, $this->MaxLength, $this->Blob );
  }

  public function createInputElement( \Qck\Interfaces\Html\Page $Page )
  {
    return $Page->createElement( "input", [ "name" => $this->getName(), "type" => "text" ] );
  }

  public function createExpression()
  {
    $leThanMax = x::le( x::strlen( x::id( $this->getName() ) ), x::val( $this->MaxLength ) );
    $geThanMin = x::ge( x::strlen( x::id( $this->getName() ) ), x::val( $this->MinLength ) );
    $and = x::and_( $leThanMax, $geThanMin, true );
    if ( $this->regex )
    {
      $regexMatch = x::regexp( x::id( $this->getName() ), x::val( $this->regex ) );
      $and->add( $regexMatch );
    }

    return $and;
  }

  protected $MinLength;
  protected $MaxLength;
  protected $Blob;
  protected $regex;

}
