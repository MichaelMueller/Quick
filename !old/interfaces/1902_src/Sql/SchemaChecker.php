<?php

namespace Qck\Interfaces\Sql;

/**
 *
 * @author muellerm
 */
interface SchemaChecker
{

  /**
   * installs the schema on this Db
   * @param \Qck\Interfaces\Sql\Db $Db
   * @param \Qck\Interfaces\Sql\Schema $CurrentSchema
   */
  function install( Db $Db, Schema $CurrentSchema );

  /**
   * 
   * @param \Qck\Interfaces\Sql\Db $Db
   * @param \Qck\Interfaces\Sql\Schema $CurrentSchema
   * @throws \InvalidArgumentException if Schema is not valid anymore
   */
  function validate( Db $Db, Schema $CurrentSchema );
}
