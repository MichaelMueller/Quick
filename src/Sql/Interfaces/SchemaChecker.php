<?php

namespace Qck\Sql\Interfaces;

/**
 *
 * @author muellerm
 */
interface SchemaChecker
{

  /**
   * 
   * @param \Qck\Sql\Interfaces\Db $Db
   * @param \Qck\Sql\Interfaces\Schema $CurrentSchema
   * @throws \InvalidArgumentException if Schema is not valid anymore
   */
  function validate( Db $Db, Schema $CurrentSchema );
}
