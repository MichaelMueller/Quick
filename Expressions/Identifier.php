<?php

namespace qck\Expressions;

/**
 *
 * @author muellerm
 */
class Identifier extends ValueExpression
{

  function __construct( $Name, $UseForFilteredArray = true )
  {
    $this->Name = $Name;
    $this->UseForFilteredArray = $UseForFilteredArray;
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
    if ( $this->UseForFilteredArray )
      $FilteredArray[ $this->Name ] = $Data[ $this->Name ];
    return $Data[ $this->Name ];
  }

  public function toSql( \qck\Sql\Interfaces\DbDictionary $Dictionary,
                         array &$Params = array () )
  {
    return $this->Name;
  }

  /**
   *
   * @var ValueExpression
   */
  protected $Name;
  protected $UseForFilteredArray;

}
