<?php

namespace qck\db\Sql;

/**
 *
 * @author muellerm
 */
interface DatabaseManager
{

  function createDatabase( $Name );
  
  function removeDatabase( $Name );

  function dumpSql( $File );
}
