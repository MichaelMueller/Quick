<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
class Identifier extends abstracts\ValueExpression
{

  function __construct( $Name )
  {
    $this->Name = $Name;
  }

  function getName()
  {
    return $this->Name;
  }

  public function evaluate( array $Data, &$FilteredArray = [], &$FailedExpressions = [] )
  {
    if ( !isset( $Data[ $this->Name ] ) )
    {
      $FailedExpressions[] = $this;
      return null;
    }
    $FilteredArray[ $this->Name ] = $Data[ $this->Name ];
    return $Data[ $this->Name ];
  }

  /**
   *
   * @var abstracts\ValueExpression
   */
  protected $Name;

}
