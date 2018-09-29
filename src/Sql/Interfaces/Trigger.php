<?php

namespace Qck\Sql\Interfaces;

/**
 * Implementing Trigger for Databases
 * @author muellerm
 */
interface Trigger
{

  /**
   * 
   * @param \Qck\Sql\Interfaces\Db $Db
   * @param \Qck\Sql\Interfaces\Query $Query
   */
  function beforeQuery( Db $Db, WriteQuery $Query );

  /**
   * 
   * @param \Qck\Sql\Interfaces\Db $Db
   * @param \Qck\Sql\Interfaces\Query $Query
   * @param mixed $Results
   */
  function afterQuery( Db $Db, WriteQuery $Query, $Results );
}
