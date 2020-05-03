<?php

namespace Qck\Interfaces\Sql;

/**
 * Implementing Trigger for Databases
 * @author muellerm
 */
interface Trigger
{

  /**
   * 
   * @param \Qck\Interfaces\Sql\Db $Db
   * @param \Qck\Interfaces\Sql\Query $Query
   */
  function beforeQuery( Db $Db, WriteQuery $Query );

  /**
   * 
   * @param \Qck\Interfaces\Sql\Db $Db
   * @param \Qck\Interfaces\Sql\Query $Query
   * @param mixed $Results
   */
  function afterQuery( Db $Db, WriteQuery $Query, $Results );
}
