<?php

namespace Qck\Interfaces\Sql;

/**
 *
 * @author muellerm
 */
interface Select extends Query
{

  /**
   * 
   * @param \Qck\Interfaces\Expressions\BooleanExpression $Expression
   */
  function setExpression( \Qck\Interfaces\Expressions\BooleanExpression $Expression );

  /**
   * 
   * @param string $OrderCol
   * @param bool $Descending
   */
  function setOrderParams( $OrderCol, $Descending = true );

  /**
   * 
   * @param Columns[]
   */
  function setColumns( $Columns );

  /**
   * 
   * @param int $Offset
   */
  function setOffset( $Offset );

  /**
   * 
   * @param int $Limit
   */
  function setLimit( $Limit );

  /**
   * @return Table[]
   */
  function getTables();
}
