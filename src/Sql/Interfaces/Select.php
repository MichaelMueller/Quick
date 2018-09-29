<?php

namespace Qck\Sql\Interfaces;

/**
 *
 * @author muellerm
 */
interface Select extends Query
{

  /**
   * 
   * @param \Qck\Expression\Interfaces\BooleanExpression $Expression
   */
  function setExpression( \Qck\Expression\Interfaces\BooleanExpression $Expression );

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
