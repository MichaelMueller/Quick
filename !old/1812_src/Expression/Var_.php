<?php

namespace Qck\Expression;

/**
 *
 * @author muellerm
 */
class Var_ implements Interfaces\Var_
{

  function __construct( $Name, $FilterOut = false )
  {
    $this->Name = $Name;
    $this->FilterOut = $FilterOut;
  }

  function getName()
  {
    return $this->Name;
  }

  public function toSql( \Qck\Interfaces\Sql\DbDialect $Dictionary,
                         array &$Params = array () )
  {
    return $this->Name;
  }

  function getValue( array $Data = [], array &$FilteredData = [] )
  {
    $Value = isset( $Data[ $this->Name ] ) ? $Data[ $this->Name ] : null;
    if ( $this->FilterOut == false )
      $FilteredData[ $this->Name ] = $Value;
    return $Value;
  }

  function __toString()
  {
    return $this->Name;
  }

  /**
   *
   * @var string
   */
  protected $Name;

  /**
   *
   * @var bool
   */
  protected $FilterOut;

}
