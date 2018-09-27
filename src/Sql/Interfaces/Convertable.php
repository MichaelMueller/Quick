<?php

namespace Qck\Sql\Interfaces;

/**
 *
 * @author muellerm
 */
interface Convertable
{

  /**
   * 
   * @param \Qck\Sql\Interfaces\DbDialect $Dictionary
   * @param array $Params optional output array of possible parameters. whereever variables are used this statement will create ? placeholders for prepared statements. the array will therefore be filled with values in the correct order.
   * @return string An Sql String
   */
  public function toSql( \Qck\Sql\Interfaces\DbDialect $Dictionary,
                         array &$Params = array () );
}
