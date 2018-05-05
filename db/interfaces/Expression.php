<?php

namespace qck\db\interfaces;

/**
 *
 * @author muellerm
 */
interface Expression
{

  function evaluate( array $Data, &$FilteredArray = [], &$FailedExpressions = [] );
}
