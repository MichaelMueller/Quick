<?php

namespace qck\Expressions\Interfaces;

/**
 *
 * @author muellerm
 */
interface Expression
{

  function evaluate( array $Data, &$FilteredArray = [], &$FailedExpressions = [] );

  function toSql( \qck\Sql\Interfaces\DbDictionary $Dictionary, array &$Params = [] );
}
