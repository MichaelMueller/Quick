<?php

namespace qck\GraphStorage\Sql;

/**
 *
 * @author muellerm
 */
interface Dictionary
{


  // DICTIONARY INFO
  function getIntDatatype();

  function getStringDatatype( $MinLength = 0, $MaxLength = 255 );

  function getRegExpOperator();

  function getStrlenFunctionName();
}
