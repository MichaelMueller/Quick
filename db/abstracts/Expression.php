<?php

namespace qck\db\abstracts;

/**
 *
 * @author muellerm
 */
abstract class Expression implements \qck\db\interfaces\Expression
{

  function filterVar( array $Data, &$FailedExpressions = [] )
  {
    $FilteredArray = [];
    $IsValid = $this->evaluate( $Data, $FilteredArray, $FailedExpressions );
    return $IsValid ? $FilteredArray : false;
  }
}
