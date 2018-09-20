<?php

namespace Qck\Sql;

/**
 *
 * @author muellerm
 */
abstract class Column implements \Qck\Interfaces\Sql\Column
{

  /**
   * @return \Qck\Expressions\Expression
   */
  abstract function createExpression();

  /**
   * @return string
   */
  abstract function getDatatype( \Qck\Interfaces\Sql\DbDialect $SqlDbDialect );

  function __construct( $name )
  {
    $this->name = $name;
  }

  function toSql( \Qck\Interfaces\Sql\DbDialect $SqlDbDialect, array &$Params = [] )
  {
    $Elements = [ $this->name, $this->getDatatype( $SqlDbDialect ) ];
    return implode( " ", $Elements );
  }

  function getName()
  {
    return $this->name;
  }

  function evaluate( array $Data, &$FilteredArray = [], &$FailedExpressions = [] )
  {
    if ( !$this->expression )
      $this->expression = $this->createExpression();
    return $this->expression->evaluate( $Data, $FilteredArray, $FailedExpressions );
  }

  function getId()
  {
    return $this->name;
  }

  /**
   * @return String
   */
  function getLabel()
  {
    return $this->label ? $this->label : $this->name;
  }

  function setLabel( $label )
  {
    $this->label = $label;
  }

  protected $name;
  protected $label;

  /**
   *
   * @var \Qck\Expressions\Expression
   */
  protected $expression;

}
