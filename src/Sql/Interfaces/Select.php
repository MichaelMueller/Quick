<?php

namespace Qck\Sql\Interfaces;

/**
 *
 * @author muellerm
 */
interface Select extends Query
{

  function setExpression( \Qck\Expression\Interfaces\BooleanExpression $Expression );

  function setOrderParams( $OrderCol, $Descending = true );

  function setColumns( $Columns );

  function setOffset( $Offset );

  function setLimit( $Limit );
}
