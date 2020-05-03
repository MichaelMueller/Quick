<?php

namespace Qck\Interfaces\Sql;

/**
 *
 * @author muellerm
 */
interface Convertable
{

  /**
   * 
   * @param \Qck\Interfaces\Sql\DbDialect $Dictionary
   * @param array $Params optional output array of possible parameters. whereever variables are used this statement will create ? placeholders for prepared statements. the array will therefore be filled with values in the correct order.
   * @return string An Sql String
   */
  public function toSql( \Qck\Interfaces\Sql\DbDialect $Dictionary,
                         array &$Params = array () );
}
