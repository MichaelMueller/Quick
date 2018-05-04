<?php

namespace qck\exp;

/**
 *
 * @author muellerm
 */
class Identifier extends AtomicExpression
{

  function __construct( $Name )
  {
    $this->Name = $Name;
  }

  function getName()
  {
    return $this->Name;
  }

  public function evaluate( array $Data )
  {
    return $Data[ $this->Name ];
  }

  /**
   *
   * @var AtomicExpression
   */
  protected $Name;

}
